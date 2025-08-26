<?php

namespace App\Http\Controllers\Traits;

use App\Models\PatientStatusLog;
use App\Models\BudgetAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use SpreadsheetReader;

trait CsvImportTrait
{
    public function processCsvImport(Request $request)
    {
        try {
            $filename   = $request->input('filename');
            $path       = storage_path('app/private/csv_import/' . $filename);
            $fields     = $request->input('fields', []);
            $fields     = array_flip(array_filter($fields));
            $modelName  = $request->input('modelName');
            $modelClass = "App\\Models\\" . $modelName;

            $reader = new SpreadsheetReader($path);
            $reader->rewind();
            $firstRow = $reader->current();
            $matchCount = 0;
            foreach ($firstRow as $cell) {
                if (in_array(strtolower(trim($cell)), array_map('strtolower', array_keys($fields)))) {
                    $matchCount++;
                }
            }
            $skipHeader = $matchCount > 0;

            $insert   = [];
            $rowsData = [];

            foreach ($reader as $index => $row) {
                if ($skipHeader && $index === 0) {
                    continue;
                }

                $tmp = [];
                foreach ($fields as $header => $colIndex) {
                    if (isset($row[$colIndex]) && $row[$colIndex] !== '') {
                        $value = $row[$colIndex];
                        if (in_array($header, ['date_processed', 'created_at', 'updated_at', 'disbursed_date', 'allocation_date'])) {
                            try {
                                $value = Carbon::parse($value)->format('Y-m-d H:i:s');
                            } catch (\Exception $e) {
                                $value = null;
                            }
                        }
                        $tmp[$header] = $value;
                    }
                }

                if (count($tmp) === count($fields)) {
                    $insert[]   = $tmp;
                    $rowsData[] = $tmp;
                }
            }

            // Insert records and handle disbursed + budget allocation
            foreach (array_chunk($rowsData, 100) as $batch) {
                foreach ($batch as $rowData) {
                    // create patient record
                    $patient = $modelClass::create($rowData);

                    // ---- Budget Allocation ----
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

                    // ---- Disbursed ----
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

            $rows  = count($insert);
            $table = Str::plural($modelName);

            File::delete($path);
            session()->flash('message', trans(
                'global.app_imported_rows_to_table',
                ['rows' => $rows, 'table' => $table]
            ));

            return redirect($request->input('redirect'));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function parseCsvImport(Request $request)
    {
        $file = $request->file('csv_file');
        $request->validate([
            'csv_file' => 'mimes:csv,txt',
        ]);

        $path    = $file->path();
        $reader  = new SpreadsheetReader($path);
        $headers = $reader->current();

        $lines = [];
        for ($i = 1; $i <= 5; $i++) {
            if ($reader->next() === false) {
                break;
            }
            $lines[] = $reader->current();
        }

        $filename = Str::random(10) . '.csv';
        $file->storeAs('csv_import', $filename);

        $modelName     = $request->input('model');
        $fullModelName = "App\\Models\\" . $modelName;

        $model       = new $fullModelName();
        $fillables   = $model->getFillable();

        // Exclude timestamp fields from mapping dropdown
        $exclude     = ['created_at', 'updated_at', 'deleted_at'];
        $fillables   = array_filter($fillables, function($f) use ($exclude) {
            return !in_array($f, $exclude);
        });

        if ($request->boolean('has_disbursed_date')) {
            $fillables[] = 'disbursed_date';
            $fillables[] = 'amount';
            $fillables[] = 'allocation_date';
        }

        $redirect  = url()->previous();
        $routeName = 'admin.' . strtolower(Str::plural(Str::kebab($modelName))) . '.processCsvImport';

        $hasDisbursedDate = $request->boolean('has_disbursed_date');

        return view('csvImport.parseInput', compact(
            'headers',
            'filename',
            'fillables',
            'modelName',
            'lines',
            'redirect',
            'routeName',
            'hasDisbursedDate'
        ));
    }
}
