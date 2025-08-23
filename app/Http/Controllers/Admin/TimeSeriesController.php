<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class TimeSeriesController extends Controller
{
    public function index()
    {
        $type = request('type');

        // Export CSV first (shared for all analytics)
        $csvPath = $this->exportToCsvFile();

        // Determine JSON path based on type
        $jsonFiles = [
            'cswd'   => 'stl_output.json',
            'budget' => 'budget_stl_output.json',
        ];

        $jsonPath = storage_path('app/public/' . ($jsonFiles[$type] ?? 'stl_output.json'));

        $shouldRunPython = false;

        // If JSON does not exist → must run Python
        if (!file_exists($jsonPath)) {
            $shouldRunPython = true;
        } else {
            // Compare row count of CSV vs last run
            $metaPath = str_replace('.json', '_meta.json', $jsonPath);
            $currentRowCount = $this->getCsvRowCount($csvPath);

            $lastRowCount = 0;
            if (file_exists($metaPath)) {
                $meta = json_decode(file_get_contents($metaPath), true);
                $lastRowCount = $meta['row_count'] ?? 0;
            }

            if ($currentRowCount > $lastRowCount) {
                $shouldRunPython = true;

                // Save metadata
                file_put_contents($metaPath, json_encode([
                    'row_count' => $currentRowCount,
                    'updated_at' => now()->toDateTimeString(),
                ]));
            }
        }


        if ($shouldRunPython) {
            if ($type === 'budget') {
                $this->runBudgetPython();
            } else {
                $this->runPythonStl();
            }
        }

        // --- Permissions and view mapping ---
        $analyticsViews = [
            'cswd'       => ['permission' => 'CSWD-ANALYTICS',     'view' => 'admin.timeseries.cswd.index'],
            'budget'     => ['permission' => 'BUDGET-ANALYTICS',   'view' => 'admin.timeseries.budget.index'],
            'treasury'   => ['permission' => 'TREASURY-ANALYTICS', 'view' => 'admin.timeseries.treasury.index'],
            'accounting' => ['permission' => 'ACCOUNTING-ANALYTICS', 'view' => 'admin.timeseries.accounting.index'],
        ];

        if (!isset($analyticsViews[$type])) {
            abort(404, 'Invalid analytics type.');
        }

        $permission = $analyticsViews[$type]['permission'];
        $view = $analyticsViews[$type]['view'];

        if (!Gate::allows($permission)) {
            abort(403, 'You do not have permission to view this analytics.');
        }

        return view($view);
    }

    private function getCsvRowCount($csvPath)
    {
        if (!file_exists($csvPath)) {
            return 0;
        }

        $rowCount = 0;
        if (($handle = fopen($csvPath, "r")) !== false) {
            while (fgetcsv($handle) !== false) {
                $rowCount++;
            }
            fclose($handle);
        }

        return max(0, $rowCount - 1); // exclude header row
    }

    public function exportToCsvFile()
    {
        // Fetch patient-level data
        $data = DB::table('patient_records as pr')
            ->join('patient_status_logs as psl', function ($join) {
                $join->on('psl.patient_id', '=', 'pr.id')
                    ->where('psl.status', 'Disbursed');
            })
            ->leftJoin('budget_allocations as ba', 'ba.patient_id', '=', 'pr.id')
            ->whereNull('pr.deleted_at')
            ->select(
                DB::raw('DATE_FORMAT(psl.status_date, "%Y-%m-01") as month'),
                'pr.case_category',
                'pr.case_type',
                'pr.age',
                'pr.date_processed',
                DB::raw('IFNULL(ba.amount, 0) as budget_allocated')
            )
            ->orderBy('month')
            ->get();

        // CSV header
        $csv = "month,case_category,case_type,age,date_processed,budget_allocated\n";

        foreach ($data as $row) {
            $csv .= "{$row->month},{$row->case_category},{$row->case_type},{$row->age},{$row->date_processed},{$row->budget_allocated}\n";
        }

        // Save CSV
        Storage::disk('public')->put('full_patient_data.csv', $csv);

        return storage_path('app/public/full_patient_data.csv');
    }


    public function runPythonStl()
    {
        $pythonPath = base_path('venv/Scripts/python.exe');
        $scriptPath = base_path('python/stl_analysis.py');

        exec("\"$pythonPath\" \"$scriptPath\"", $output, $return_var);

        if ($return_var !== 0) {
            \Log::error("CSWD STL Python script failed", ['output' => $output]);
        }
    }

    public function runBudgetPython()
    {
        $pythonPath = base_path('venv/Scripts/python.exe');
        $scriptPath = base_path('python/budget_stl_analysis.py');

        exec("\"$pythonPath\" \"$scriptPath\"", $output, $return_var);

        if ($return_var !== 0) {
            \Log::error("Budget STL Python script failed", ['output' => $output]);
        }
    }

    public function getStlJson(Request $request)
    {
        $type = $request->query('type');

        if ($type === 'budget') {
            $file = storage_path('app/public/stl_budget_output.json');
        } else {
            $file = storage_path('app/public/stl_output.json');
        }

        if (!file_exists($file)) {
            return response()->json(['error' => 'STL output not found'], 404);
        }

        $data = json_decode(file_get_contents($file), true);
        return response()->json($data);
    }
}
