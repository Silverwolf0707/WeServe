<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class TimeSeriesController extends Controller
{
    public function index()
    {
        $type = request('type'); // get ?type=cswd, budget, etc.

        $this->exportToCsvFile();
        $this->runPythonStl();

        // Map type to permissions and views
        $analyticsViews = [
            'cswd'      => ['permission' => 'CSWD-ANALYTICS',     'view' => 'admin.timeseries.cswd.index'],
            'budget'    => ['permission' => 'BUDGET-ANALYTICS',   'view' => 'admin.timeseries.budget.index'],
            'treasury'  => ['permission' => 'TREASURY-ANALYTICS', 'view' => 'admin.timeseries.treasury.index'],
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
        $data = DB::table('patient_records')
            ->selectRaw('DATE_FORMAT(date_processed, "%Y-%m-01") as month, case_category, COUNT(*) as value')
            ->whereNull('deleted_at')
            ->groupBy('month', 'case_category')
            ->orderBy('month')
            ->get();

        $csv = "month,case_category,value\n";
        foreach ($data as $row) {
            $csv .= "{$row->month},\"{$row->case_category}\",{$row->value}\n";
        }

        Storage::disk('public')->put('patient_records.csv', $csv);
    }


    public function runPythonStl()
    {
        $pythonPath = base_path('venv/Scripts/python.exe');
        $scriptPath = base_path('python/stl_analysis.py');

        exec("\"$pythonPath\" \"$scriptPath\"", $output, $return_var);

        if ($return_var !== 0) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Python script failed', 'output' => $output], 500);
            }
            return;
        }

        $jsonPath = storage_path('app/public/stl_output.json');

        if (!file_exists($jsonPath)) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'STL output JSON file not found'], 500);
            }
            return;
        }

        $json = file_get_contents($jsonPath);
        $data = json_decode($json, true);

        if (request()->expectsJson()) {
            return response()->json($data);
        }
    }
}
