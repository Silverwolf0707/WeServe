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
    $type = request('type'); // cswd, budget, treasury, accounting

    $analyticsViews = [
        'cswd' => [
            'permission'   => 'CSWD-ANALYTICS',
            'csv_path'     => storage_path('app/public/patient_records_year.csv'),
            'json_path'    => storage_path('app/public/age_stats_output.json'),
            'meta_path'    => storage_path('app/public/age_stats_meta.json'),
            'python_script' => base_path('python/age_statistics.py'),
            'runner'       => 'runPythonAgeStats',
        ],
        'budget' => [
            'permission'   => 'BUDGET-ANALYTICS',
            'csv_path'     => storage_path('app/public/full_patient_data.csv'),
            'json_path'    => storage_path('app/public/budget_stats_output.json'),
            'meta_path'    => storage_path('app/public/budget_stats_meta.json'),
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

    // 🔹 Always refresh CSV before hash check
    if ($type === 'budget') {
        app(TimeSeriesController::class)->exportToCsvFile();
    }

    if (isset($config['python_script'])) {
        $csvPath = $config['csv_path'];
        $jsonPath = $config['json_path'];
        $metaPath = $config['meta_path'];
        $pythonScript = $config['python_script'];
        $runnerMethod = $config['runner'];

        $shouldRunPython = false;

        if (!file_exists($jsonPath)) {
            $shouldRunPython = true;
        } else {
            $currentHash = file_exists($csvPath) ? md5_file($csvPath) : null;
            $lastHash = file_exists($metaPath) ? json_decode(file_get_contents($metaPath), true)['file_hash'] ?? null : null;

            if ($currentHash !== $lastHash) {
                $shouldRunPython = true;

                file_put_contents($metaPath, json_encode([
                    'file_hash'  => $currentHash,
                    'updated_at' => now()->toDateTimeString(),
                ]));
            }
        }

        if ($shouldRunPython && method_exists($this, $runnerMethod)) {
            $this->$runnerMethod();
        }

        return $this->getPythonJsonOutput($jsonPath);
    }

    abort(404, 'No analytics script configured for this type.');
}


    protected function runPythonBudgetStats()
    {
        $pythonPath = base_path('venv/Scripts/python.exe');
        $scriptPath = base_path('python/budget_statistics.py');

        exec("\"$pythonPath\" \"$scriptPath\"", $output, $return_var);

        if ($return_var !== 0) {
            Log::error("Python budget statistics script failed", ['output' => $output]);
        }
    }

    protected function runPythonAgeStats()
    {
        $pythonPath = base_path('venv/Scripts/python.exe');  // Adjust path to your python executable
        $scriptPath = base_path('python/age_statistics.py'); // Your Python script path

        exec("\"$pythonPath\" \"$scriptPath\"", $output, $return_var);

        if ($return_var !== 0) {
            Log::error("Python age statistics script failed", ['output' => $output]);
        }
    }

    protected function getPythonJsonOutput($jsonPath)
    {
        if (!file_exists($jsonPath)) {
            return response()->json(['error' => 'No statistics data found'], 404);
        }

        $json = file_get_contents($jsonPath);
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
