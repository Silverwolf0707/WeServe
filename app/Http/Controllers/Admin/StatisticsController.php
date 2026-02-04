<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StatisticsController extends Controller
{
    public function index()
    {
        $type = request('type'); 

        $analyticsViews = [
            'cswd' => [
                'permission'   => 'CSWD-ANALYTICS',
                'csv_path'     => storage_path('app/private/analytics/full_patient_data.csv'),
                'json_path'    => 'analytics/age_stats_output.json',
                'python_script' => base_path('python/age_statistics.py'),
                'runner'       => 'runPythonAgeStats',
            ],
            'budget' => [
                'permission'   => 'BUDGET-ANALYTICS',
                'csv_path'     => storage_path('app/private/analytics/full_patient_data.csv'),
                'json_path'    => 'analytics/budget_stats_output.json',
                'python_script' => base_path('python/budget_statistics.py'),
                'runner'       => 'runPythonBudgetStats',
            ],
            'treasury' => [
                'permission' => 'TREASURY-ANALYTICS',
            ],
            'accounting' => [
                'permission' => 'ACCOUNTING-ANALYTICS',
            ],
        ];

        if (!isset($analyticsViews[$type])) {
            abort(404, 'Invalid analytics type.');
        }

        $config = $analyticsViews[$type];

        if (!Gate::allows($config['permission'])) {
            abort(403, 'You do not have permission to view this statistics.');
        }

        // Always run Python script when statistics page is accessed
        if (isset($config['python_script'])) {
            // Ensure CSV is updated for budget type
            if ($type === 'budget') {
                app(TimeSeriesController::class)->exportToCsvFile();
            }
            
            $runnerMethod = $config['runner'];
            if (method_exists($this, $runnerMethod)) {
                $this->$runnerMethod();
            }

            return $this->getPythonJsonOutput($config['json_path']);
        }

        abort(404, 'No analytics script configured for this type.');
    }

    protected function runPythonBudgetStats()
    {
        $pythonPath = base_path('venv/Scripts/python.exe');
        $scriptPath = base_path('python/budget_statistics.py');
        
        // Get CSV path from private storage
        $csvPath = storage_path('app/private/analytics/full_patient_data.csv');
        
        exec("\"$pythonPath\" \"$scriptPath\" \"$csvPath\"", $output, $return_var);

        if ($return_var !== 0) {
            Log::error("Python budget statistics script failed", ['output' => $output]);
        }
    }

    protected function runPythonAgeStats()
    {
        $pythonPath = base_path('venv/Scripts/python.exe');
        $scriptPath = base_path('python/age_statistics.py');
        
        // Get CSV path from private storage
        $csvPath = storage_path('app/private/analytics/full_patient_data.csv');
        
        exec("\"$pythonPath\" \"$scriptPath\" \"$csvPath\"", $output, $return_var);

        if ($return_var !== 0) {
            Log::error("Python age statistics script failed", ['output' => $output]);
        }
    }

    protected function getPythonJsonOutput($jsonPath)
    {
        // Check if file exists in private storage
        if (!Storage::disk('private')->exists($jsonPath)) {
            return response()->json(['error' => 'No statistics data found'], 404);
        }

        // Read JSON from private storage
        $json = Storage::disk('private')->get($jsonPath);
        $data = json_decode($json, true);

        return response()->json($data);
    }

    public function getDeficiencyData()
    {
        $data = DB::table('rejection_reasons')
            ->select('reason', DB::raw('COUNT(*) as count'))
            ->groupBy('reason')
            ->orderByDesc('count')
            ->get();

        $labels = $data->pluck('reason');
        $counts = $data->pluck('count');

        $topReason = $data->first();
        $summary = "Most deficiencies are caused by <strong>{$topReason->reason}</strong>, 
                followed by " . $data->skip(1)->pluck('reason')->take(2)->implode(' and ') . ".";

        return response()->json([
            'labels' => $labels,
            'counts' => $counts,
            'summary' => $summary
        ]);
    }
}