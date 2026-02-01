<?php

namespace App\Http\Controllers\Traits;

use App\Models\PatientStatusLog;
use App\Models\BudgetAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

trait CsvImportTrait
{
    private function getRequiredFields(bool $hasDisbursed, bool $forceDisburse = false): array
    {
        $fields = [
            'date_processed',
            'case_type',
            'control_number',
            'claimant_name',
            'case_category',
            'patient_name',
            'diagnosis',
            'age',
            'address',
            'contact_number',
            'case_worker',
        ];

        if ($hasDisbursed || $forceDisburse) {
            $fields[] = 'disbursed_date';
            $fields[] = 'amount';
            $fields[] = 'allocation_date';
        }

        return $fields;
    }

    /**
     * Process uploaded file (CSV, Excel, Spreadsheet)
     */
    public function processCsvImport(Request $request)
    {
        try {
            $hasDisbursed = $request->boolean('has_disbursed_date');
            $forceDisburse = $request->boolean('force_disburse', false); // New toggle
            $modelName    = $request->input('modelName');
            $modelClass   = "App\\Models\\" . $modelName;

            // Validate file
            $file = $request->file('csv_file');
            if (!$file || !$file->isValid()) {
                return redirect()
                    ->route('admin.patient-records.index')
                    ->with('toast', [
                        'type' => 'danger',
                        'title' => 'Import Failed',
                        'message' => 'Invalid file upload. Please select a valid file.',
                        'time' => now()->diffForHumans(),
                    ]);
            }

            $originalName = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension());
            
            // Define allowed extensions
            $allowedExtensions = ['csv', 'xls', 'xlsx', 'ods'];
            
            if (!in_array($extension, $allowedExtensions)) {
                return redirect()
                    ->route('admin.patient-records.index')
                    ->with('toast', [
                        'type' => 'danger',
                        'title' => 'Import Failed',
                        'message' => 'Unsupported file type. Please upload CSV, Excel (.xls, .xlsx), or OpenDocument Spreadsheet (.ods) files.',
                        'time' => now()->diffForHumans(),
                    ]);
            }

            // Read file using PhpSpreadsheet
            try {
                $spreadsheet = IOFactory::load($file->getPathname());
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();
            } catch (\Exception $e) {
                return redirect()
                    ->route('admin.patient-records.index')
                    ->with('toast', [
                        'type' => 'danger',
                        'title' => 'Import Failed',
                        'message' => 'Cannot read the uploaded file. Please ensure it\'s a valid spreadsheet file.',
                        'time' => now()->diffForHumans(),
                    ]);
            }

            if (empty($rows) || count($rows) < 2) {
                return redirect()
                    ->route('admin.patient-records.index')
                    ->with('toast', [
                        'type' => 'danger',
                        'title' => 'Import Failed',
                        'message' => 'File is empty or doesn\'t contain enough data. Please check the file content.',
                        'time' => now()->diffForHumans(),
                    ]);
            }

            // Extract headers (first row)
            $headers = array_map(function($header) {
                return is_string($header) ? strtolower(trim($header)) : '';
            }, $rows[0]);

            // Check if file actually has disbursed columns when forceDisburse is enabled
            $actualHasDisbursed = $hasDisbursed;
            if ($forceDisburse && !$hasDisbursed) {
                // Check if disbursed columns exist in file headers
                $disbursedColumns = ['disbursed_date', 'amount', 'allocation_date'];
                $actualHasDisbursed = count(array_intersect($disbursedColumns, $headers)) > 0;
            }

            // Required fields
            $requiredFields = $this->getRequiredFields($actualHasDisbursed, $forceDisburse);

            // Validate headers
            $missingColumns = [];
            foreach ($requiredFields as $field) {
                if (!in_array(strtolower($field), $headers)) {
                    // If forceDisburse is enabled and disbursed columns are missing, show warning
                    if ($forceDisburse && in_array($field, ['disbursed_date', 'amount', 'allocation_date'])) {
                        // These will be handled with default values
                        continue;
                    }
                    $missingColumns[] = $field;
                }
            }

            if (!empty($missingColumns)) {
                $columnsList = implode(', ', $missingColumns);
                return redirect()
                    ->route('admin.patient-records.index')
                    ->with('toast', [
                        'type' => 'danger',
                        'title' => 'Import Failed',
                        'message' => "Missing required columns: $columnsList. Please download the template for reference.",
                        'time' => now()->diffForHumans(),
                    ]);
            }

            // Map header → column index
            $fields = [];
            foreach ($requiredFields as $field) {
                $index = array_search(strtolower($field), $headers);
                if ($index === false) {
                    // If forceDisburse is enabled and disbursed columns are missing, use null
                    if ($forceDisburse && in_array($field, ['disbursed_date', 'amount', 'allocation_date'])) {
                        $fields[$field] = null; // Will use default values
                        continue;
                    }
                    return redirect()
                        ->route('admin.patient-records.index')
                        ->with('toast', [
                            'type' => 'danger',
                            'title' => 'Import Failed',
                            'message' => "Column '$field' not found in file. Please check the file headers.",
                            'time' => now()->diffForHumans(),
                        ]);
                }
                $fields[$field] = $index;
            }

            $rowsData = [];
            $importedCount = 0;
            $skippedCount = 0;
            $disbursedCount = 0;
            $errorRows = [];

            // Process rows starting from index 1 (skip header)
            for ($index = 1; $index < count($rows); $index++) {
                $row = $rows[$index];
                
                // Skip empty rows
                if (empty(array_filter($row, function($value) {
                    return $value !== null && $value !== '';
                }))) {
                    continue;
                }

                $tmp = [];
                $missingFields = [];

                foreach ($fields as $header => $colIndex) {
                    $value = null;
                    
                    // Get value from column if exists
                    if ($colIndex !== null && isset($row[$colIndex])) {
                        $value = $row[$colIndex];
                    }
                    
                    // Clean up the value
                    if (is_string($value)) {
                        $value = trim($value);
                    } elseif (is_object($value) && method_exists($value, '__toString')) {
                        $value = (string) $value;
                    }

                    // Handle date fields
                    if (in_array($header, ['date_processed', 'disbursed_date', 'allocation_date'])) {
                        try {
                            if (is_numeric($value)) {
                                // Convert Excel date serial number
                                $value = $this->convertExcelDate($value);
                            } elseif ($value) {
                                $value = Carbon::parse($value)->format('Y-m-d H:i:s');
                            } elseif ($forceDisburse && $header === 'disbursed_date') {
                                // Use date_processed if disbursed_date is empty and forceDisburse is enabled
                                $dateProcessedIndex = $fields['date_processed'];
                                $dateProcessedValue = $row[$dateProcessedIndex] ?? null;
                                if ($dateProcessedValue) {
                                    try {
                                        if (is_numeric($dateProcessedValue)) {
                                            $value = $this->convertExcelDate($dateProcessedValue);
                                        } else {
                                            $value = Carbon::parse($dateProcessedValue)->format('Y-m-d H:i:s');
                                        }
                                    } catch (\Exception $e) {
                                        $value = now()->format('Y-m-d H:i:s');
                                    }
                                } else {
                                    $value = now()->format('Y-m-d H:i:s');
                                }
                            } elseif ($forceDisburse && $header === 'allocation_date') {
                                $value = now()->format('Y-m-d H:i:s');
                            } else {
                                $value = null;
                            }
                        } catch (\Exception $e) {
                            if ($forceDisburse && in_array($header, ['disbursed_date', 'allocation_date'])) {
                                $value = now()->format('Y-m-d H:i:s');
                            } else {
                                $value = null;
                            }
                        }
                    }

                    // Handle numeric fields
                    if (in_array($header, ['amount', 'age'])) {
                        if (is_numeric($value)) {
                            $value = (float) $value;
                        } elseif ($value !== null && $value !== '') {
                            // Try to extract numbers from string
                            preg_match('/\d+(\.\d+)?/', (string) $value, $matches);
                            $value = isset($matches[0]) ? (float) $matches[0] : null;
                        } elseif ($forceDisburse && $header === 'amount') {
                            $value = 0.00; // Default amount for force disburse
                        }
                    }

                    // Check for required fields (allow empty for optional fields)
                    $optionalFields = $forceDisburse ? ['remarks', 'amount', 'allocation_date'] : ['remarks', 'allocation_date'];
                    if (empty($value) && $value !== '0' && $value !== 0 && !in_array($header, $optionalFields)) {
                        $missingFields[] = $header;
                    }

                    $tmp[$header] = $value;
                }

                if (!empty($missingFields)) {
                    $rowNumber = $index + 1;
                    $missingList = implode(', ', $missingFields);
                    $errorRows[] = "Row $rowNumber: Missing $missingList";
                    continue; // Skip this row but continue processing others
                }

                // Check for duplicate control number
                if (!empty($tmp['control_number'])) {
                    $existing = $modelClass::where('control_number', $tmp['control_number'])->first();
                    if ($existing) {
                        $skippedCount++;
                        $errorRows[] = "Row " . ($index + 1) . ": Duplicate control number '{$tmp['control_number']}'";
                        continue;
                    }
                }

                // Mark for disbursement if forceDisburse is enabled
                if ($forceDisburse) {
                    $tmp['_force_disburse'] = true;
                    if (empty($tmp['disbursed_date'])) {
                        $tmp['disbursed_date'] = $tmp['date_processed'] ?? now()->format('Y-m-d H:i:s');
                    }
                    if (empty($tmp['amount'])) {
                        $tmp['amount'] = 0.00;
                    }
                    if (empty($tmp['allocation_date'])) {
                        $tmp['allocation_date'] = now()->format('Y-m-d H:i:s');
                    }
                }

                $rowsData[] = $tmp;
            }

            if (empty($rowsData) && empty($errorRows)) {
                return redirect()
                    ->route('admin.patient-records.index')
                    ->with('toast', [
                        'type' => 'warning',
                        'title' => 'No Data Imported',
                        'message' => 'No valid data found in the file. Please check the file content.',
                        'time' => now()->diffForHumans(),
                    ]);
            }

            if (empty($rowsData) && !empty($errorRows)) {
                $errorCount = count($errorRows);
                $errorSummary = $errorCount > 3 ? 
                    "First 3 errors: " . implode('; ', array_slice($errorRows, 0, 3)) . "... ($errorCount total errors)" :
                    "Errors: " . implode('; ', $errorRows);
                
                return redirect()
                    ->route('admin.patient-records.index')
                    ->with('toast', [
                        'type' => 'danger',
                        'title' => 'Import Failed',
                        'message' => "All rows had errors. $errorSummary",
                        'time' => now()->diffForHumans(),
                    ]);
            }

            // --- Insert phase ---
            DB::beginTransaction();

            try {
                foreach ($rowsData as $rowData) {
                    $patient = $modelClass::create($rowData);
                    $importedCount++;

                    // Create budget allocation if amount exists or forceDisburse is enabled
                    if ((!empty($rowData['amount']) || ($forceDisburse && isset($rowData['_force_disburse']))) && 
                        ($actualHasDisbursed || $forceDisburse)) {
                        BudgetAllocation::create([
                            'patient_id'      => $patient->id,
                            'user_id'         => Auth::id(),
                            'amount'          => $rowData['amount'] ?? 0.00,
                            'remarks'         => $forceDisburse ? 'Imported and Auto-Disbursed' : 'Imported via File',
                            'budget_status'   => 'Disbursed',
                            'allocation_date' => $rowData['allocation_date'] ?? now(),
                        ]);
                        $disbursedCount++;
                    }

                    // Create status log
                    if (!empty($rowData['disbursed_date']) && ($actualHasDisbursed || $forceDisburse)) {
                        PatientStatusLog::create([
                            'patient_id'  => $patient->id,
                            'status'      => PatientStatusLog::STATUS_DISBURSED,
                            'status_date' => $rowData['disbursed_date'],
                            'user_id'     => Auth::id(),
                            'remarks'     => $forceDisburse ? 'Auto-Disbursed on Import' : 'Imported via File',
                        ]);
                    } else {
                        // Create initial status log for imported patients
                        PatientStatusLog::create([
                            'patient_id'  => $patient->id,
                            'status'      => $forceDisburse ? PatientStatusLog::STATUS_DISBURSED : PatientStatusLog::STATUS_PROCESSING,
                            'status_date' => $rowData['date_processed'] ?? now(),
                            'user_id'     => Auth::id(),
                            'remarks'     => $forceDisburse ? 'Auto-Disbursed on Import' : 'Imported via File',
                        ]);
                    }
                }

                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()
                    ->route('admin.patient-records.index')
                    ->with('toast', [
                        'type' => 'danger',
                        'title' => 'Database Error',
                        'message' => 'Failed to save data to database: ' . $e->getMessage(),
                        'time' => now()->diffForHumans(),
                    ]);
            }

            $table = Str::plural($modelName);
            $message = "Successfully imported <strong>$importedCount</strong> records into $table.";
            
            if ($disbursedCount > 0) {
                $message .= " <strong>$disbursedCount</strong> records were disbursed.";
            }
            
            if ($skippedCount > 0) {
                $message .= " <strong>$skippedCount</strong> duplicate records were skipped.";
            }
            
            if (!empty($errorRows) && count($errorRows) > 0) {
                $errorCount = count($errorRows);
                $message .= " <strong>$errorCount</strong> rows had errors and were skipped.";
                
                // Store detailed errors in session for possible download
                if ($errorCount > 0) {
                    session()->flash('import_errors', $errorRows);
                }
            }

            return redirect()
                ->route('admin.patient-records.index')
                ->with('toast', [
                    'type' => 'success',
                    'title' => 'Import Completed',
                    'message' => $message,
                    'time' => now()->diffForHumans(),
                ]);

        } catch (\Exception $ex) {
            // Clean up temp file if exists
            if (isset($path) && file_exists($path)) {
                @unlink($path);
            }

            return redirect()
                ->route('admin.patient-records.index')
                ->with('toast', [
                    'type' => 'danger',
                    'title' => 'Unexpected Error',
                    'message' => 'An unexpected error occurred: ' . $ex->getMessage(),
                    'time' => now()->diffForHumans(),
                ]);
        }
    }

    /**
     * Convert Excel serial date to PHP date
     */
    private function convertExcelDate($excelDate): ?string
    {
        if (!is_numeric($excelDate)) {
            return null;
        }

        // Excel date system (1900-based with 1900 leap year bug)
        $unixTimestamp = ($excelDate - 25569) * 86400;
        
        // Handle Excel's 1900 leap year bug
        if ($excelDate > 60) {
            $unixTimestamp -= 86400;
        }

        try {
            return Carbon::createFromTimestamp($unixTimestamp)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return null;
        }
    }
}