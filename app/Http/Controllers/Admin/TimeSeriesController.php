<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TimeSeriesController extends Controller
{
    public function index()
    {
        $type = request('type');

        // Always export CSV first (to private storage)
        $csvPath = $this->exportToCsvFile();

        // Always run Python script when analytics page is accessed
        if ($type === 'budget') {
            $this->runBudgetPython();
        } else {
            $this->runPythonStl();
        }

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

    public function exportToCsvFile()
    {
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

        $csv = "month,case_category,case_type,age,date_processed,budget_allocated\n";
        foreach ($data as $row) {
            $csv .= "{$row->month},{$row->case_category},{$row->case_type},{$row->age},{$row->date_processed},{$row->budget_allocated}\n";
        }

        // Store in private analytics folder
        Storage::disk('private')->put('analytics/full_patient_data.csv', $csv);
        return storage_path('app/private/analytics/full_patient_data.csv');
    }

    public function runPythonStl()
    {
        $pythonPath = base_path('venv/Scripts/python.exe');
        $scriptPath = base_path('python/stl_analysis.py');
        
        // Get the CSV file path from private storage
        $csvPath = storage_path('app/private/analytics/full_patient_data.csv');
        
        // Pass the CSV path as an argument to Python script
        exec("\"$pythonPath\" \"$scriptPath\" \"$csvPath\"", $output, $return_var);

        if ($return_var !== 0) {
            Log::error("CSWD STL Python script failed", ['output' => $output]);
        }
    }

    public function runBudgetPython()
    {
        $pythonPath = base_path('venv/Scripts/python.exe');
        $scriptPath = base_path('python/budget_stl_analysis.py');
        
        // Get the CSV file path from private storage
        $csvPath = storage_path('app/private/analytics/full_patient_data.csv');
        
        // Pass the CSV path as an argument to Python script
        exec("\"$pythonPath\" \"$scriptPath\" \"$csvPath\"", $output, $return_var);

        if ($return_var !== 0) {
            Log::error("Budget STL Python script failed", ['output' => $output]);
        }
    }

    public function getStlJson(Request $request)
    {
        $type = $request->query('type');

        // Define private file paths
        $file = $type === 'budget' 
            ? 'analytics/stl_budget_output.json' 
            : 'analytics/stl_output.json';

        // Check if file exists in private storage
        if (!Storage::disk('private')->exists($file)) {
            return response()->json(['error' => 'STL output not found'], 404);
        }

        // Read and return JSON content
        $jsonContent = Storage::disk('private')->get($file);
        return response()->json(json_decode($jsonContent, true));
    }
}