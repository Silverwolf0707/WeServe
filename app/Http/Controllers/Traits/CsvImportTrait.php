<?php

namespace App\Http\Controllers\Traits;

use App\Models\PatientStatusLog;
use App\Models\BudgetAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use SpreadsheetReader;

trait CsvImportTrait
{
    private function getRequiredFields(bool $hasDisbursed): array
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

        if ($hasDisbursed) {
            $fields[] = 'disbursed_date';
            $fields[] = 'amount';
            $fields[] = 'allocation_date';
        }

        return $fields;
    }

    /**
     * Process CSV file directly (no parsing/mapping step)
     */
   public function processCsvImport(Request $request)
{
    try {
        $hasDisbursed = $request->boolean('has_disbursed_date');
        $modelName    = $request->input('modelName');
        $modelClass   = "App\\Models\\" . $modelName;

        // Store uploaded file temporarily
        $file     = $request->file('csv_file');
        $filename = uniqid('csv_') . '.' . $file->getClientOriginalExtension();
        $file->storeAs('csv_import', $filename, 'local');
        $path     = storage_path("app/private/csv_import/{$filename}");

        if (!file_exists($path) || !is_readable($path)) {
            throw new \Exception("CSV file not accessible at: {$path}");
        }

        $reader = new SpreadsheetReader($path);

        // Required fields
        $requiredFields = $this->getRequiredFields($hasDisbursed);

        // Read header row
        $reader->rewind();
        $headers = array_map('strtolower', $reader->current());

        foreach ($requiredFields as $field) {
            if (!in_array(strtolower($field), $headers)) {
                throw new \Exception("Missing required column: {$field}");
            }
        }

        // Map header → column index
        $fields = [];
        foreach ($requiredFields as $field) {
            $index = array_search(strtolower($field), $headers);
            $fields[$field] = $index;
        }

        $rowsData = [];

        foreach ($reader as $index => $row) {
            if ($index === 0) continue; // skip header row

            $tmp = [];
            $missingFields = [];

            foreach ($fields as $header => $colIndex) {
                $value = $row[$colIndex] ?? null;

                if (in_array($header, ['date_processed', 'created_at', 'updated_at', 'disbursed_date', 'allocation_date'])) {
                    try {
                        $value = $value ? Carbon::parse($value)->format('Y-m-d H:i:s') : null;
                    } catch (\Exception $e) {
                        $value = null;
                    }
                }

                if (empty($value)) {
                    $missingFields[] = $header;
                }

                $tmp[$header] = $value;
            }

            if (!empty($missingFields)) {
                throw new \Exception("Row " . ($index + 1) . " is missing required fields: " . implode(', ', $missingFields));
            }

            $rowsData[] = $tmp;
        }

        // --- Insert phase ---
        foreach (array_chunk($rowsData, 100) as $batch) {
            foreach ($batch as $rowData) {
                $patient = $modelClass::create($rowData);

                if (!empty($rowData['amount'])) {
                    BudgetAllocation::create([
                        'patient_id'      => $patient->id,
                        'user_id'         => Auth::id(),
                        'amount'          => $rowData['amount'],
                        'remarks'         => 'Imported via CSV',
                        'budget_status'   => 'Disbursed',
                        'allocation_date' => $rowData['allocation_date'] ?? now(),
                    ]);
                }

                if (!empty($rowData['disbursed_date'])) {
                    PatientStatusLog::create([
                        'patient_id'  => $patient->id,
                        'status'      => PatientStatusLog::STATUS_DISBURSED,
                        'status_date' => $rowData['disbursed_date'],
                        'user_id'     => Auth::id(),
                        'remarks'     => 'Imported via CSV',
                    ]);
                }
            }
        }

        // Delete temp file
        if (file_exists($path)) {
            unlink($path);
        }

        $rows  = count($rowsData);
        $table = Str::plural($modelName);

        return redirect()
            ->route('admin.patient-records.index')
            ->with('toast', [
                'type' => 'success',
                'title' => 'CSV Import Completed',
                'message' => "Imported {$rows} rows into {$table}.",
                'time' => now()->diffForHumans(),
            ]);

    } catch (\Exception $ex) {
        return redirect()
            ->route('admin.patient-records.index')
            ->with('toast', [
                'type' => 'danger',
                'title' => 'CSV Import Failed',
                'message' => $ex->getMessage(),
                'time' => now()->diffForHumans(),
            ]);
    }
}


}
