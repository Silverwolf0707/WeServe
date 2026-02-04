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
            Log::info("Running statistics for type: {$type}", ['script' => $config['python_script']]);
            
            // Ensure CSV is updated
            app(TimeSeriesController::class)->exportToCsvFile();
            
            $runnerMethod = $config['runner'];
            if (method_exists($this, $runnerMethod)) {
                Log::info("Executing runner method: {$runnerMethod}");
                $this->$runnerMethod();
            } else {
                Log::error("Runner method not found: {$runnerMethod}");
            }

            return $this->getPythonJsonOutput($config['json_path']);
        }

        abort(404, 'No analytics script configured for this type.');
    }

    private function findPythonPath()
    {
        // Try Linux venv paths first (for production)
        $paths = [
            base_path('venv/bin/python'),
            base_path('venv/bin/python3'),
            '/usr/bin/python3',
            '/usr/bin/python',
            // Windows fallback (for local development)
            base_path('venv/Scripts/python.exe'),
            base_path('venv/Scripts/python3.exe'),
        ];
        
        foreach ($paths as $path) {
            if (file_exists($path) && is_executable($path)) {
                Log::info("Statistics Controller - Found Python at: {$path}");
                
                // Test if pandas is available
                $testCmd = escapeshellarg($path) . " -c \"import pandas; print('OK')\" 2>&1";
                exec($testCmd, $testOutput, $testReturn);
                
                if ($testReturn === 0) {
                    Log::info("Python at {$path} has pandas installed");
                    return $path;
                }
            }
        }
        
        Log::warning("No Python with pandas found, using fallback");
        return base_path('venv/bin/python');
    }

    private function executePythonScript($scriptName, $outputJsonName)
    {
        $pythonPath = $this->findPythonPath();
        $scriptPath = base_path("python/{$scriptName}");
        $csvPath = storage_path('app/private/analytics/full_patient_data.csv');
        
        // Check files
        if (!file_exists($csvPath)) {
            Log::error("CSV file not found: {$csvPath}");
            return false;
        }
        
        if (!file_exists($scriptPath)) {
            Log::error("Python script not found: {$scriptPath}");
            return false;
        }
        
        if (!file_exists($pythonPath)) {
            Log::error("Python executable not found: {$pythonPath}");
            return false;
        }
        
        // Build command with proper escaping
        $pythonPath = escapeshellarg($pythonPath);
        $scriptPath = escapeshellarg($scriptPath);
        $csvPath = escapeshellarg($csvPath);
        
        $command = "cd " . escapeshellarg(base_path()) . " && {$pythonPath} {$scriptPath} {$csvPath} 2>&1";
        
        Log::info("Executing Python script", [
            'command' => $command,
            'script' => $scriptName
        ]);
        
        set_time_limit(300);
        exec($command, $output, $return_var);
        
        $outputStr = implode("\n", $output);
        
        Log::info("Python script execution result", [
            'script' => $scriptName,
            'return_code' => $return_var,
            'output_length' => strlen($outputStr),
            'output_preview' => strlen($outputStr) > 500 ? substr($outputStr, 0, 500) . '...' : $outputStr
        ]);
        
        if ($return_var !== 0) {
            Log::error("Python script {$scriptName} failed", [
                'full_output' => $outputStr
            ]);
            return false;
        }
        
        // Check output
        $outputJsonPath = storage_path("app/private/analytics/{$outputJsonName}");
        if (file_exists($outputJsonPath)) {
            $size = filesize($outputJsonPath);
            Log::info("✅ Python script {$scriptName} SUCCESS!", [
                'output_file' => $outputJsonPath,
                'file_size' => $size
            ]);
            return true;
        }
        
        Log::warning("Python script ran but output file not created", [
            'expected_file' => $outputJsonPath
        ]);
        return false;
    }

    protected function runPythonBudgetStats()
    {
        return $this->executePythonScript('budget_statistics.py', 'budget_stats_output.json');
    }

    protected function runPythonAgeStats()
    {
        return $this->executePythonScript('age_statistics.py', 'age_stats_output.json');
    }

    protected function getPythonJsonOutput($jsonPath)
    {
        Log::info("Reading JSON output", ['path' => $jsonPath]);
        
        // Check if file exists in private storage
        if (!Storage::disk('private')->exists($jsonPath)) {
            Log::error("Statistics JSON file not found", [
                'path' => $jsonPath,
                'full_path' => storage_path('app/private/' . $jsonPath)
            ]);
            
            return response()->json([
                'error' => 'Statistics data not found.',
                'debug' => 'Check if Python scripts are running correctly.'
            ], 404);
        }

        try {
            // Read JSON from private storage
            $json = Storage::disk('private')->get($jsonPath);
            $data = json_decode($json, true);
            
            if (empty($data)) {
                Log::warning("Statistics JSON file is empty", ['path' => $jsonPath]);
                return response()->json(['error' => 'Statistics data is empty.'], 404);
            }
            
            Log::info("Successfully loaded statistics JSON", [
                'path' => $jsonPath,
                'data_structure' => array_keys($data),
                'data_size' => strlen($json)
            ]);
            
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error("Error reading statistics JSON: " . $e->getMessage(), [
                'path' => $jsonPath,
                'exception' => $e
            ]);
            return response()->json(['error' => 'Failed to read statistics data.'], 500);
        }
    }

    public function getDeficiencyData()
    {
        Log::info("Fetching deficiency data");
        
        $hasPermission = Gate::any([
            'CSWD-ANALYTICS',
            'BUDGET-ANALYTICS', 
            'TREASURY-ANALYTICS',
            'ACCOUNTING-ANALYTICS'
        ]);
        
        if (!$hasPermission) {
            abort(403, 'You do not have permission to view deficiency data.');
        }

        try {
            $data = DB::table('rejection_reasons')
                ->select('reason', DB::raw('COUNT(*) as count'))
                ->groupBy('reason')
                ->orderByDesc('count')
                ->get();

            Log::info("Deficiency data fetched", ['count' => $data->count()]);

            if ($data->isEmpty()) {
                return response()->json([
                    'labels' => [],
                    'counts' => [],
                    'summary' => 'No deficiency data available.'
                ]);
            }

            $labels = $data->pluck('reason')->toArray();
            $counts = $data->pluck('count')->toArray();

            $topReason = $data->first();
            $otherReasons = $data->slice(1)->take(2)->pluck('reason')->toArray();
            
            $summary = "Most deficiencies are caused by <strong>{$topReason->reason}</strong>";
            
            if (!empty($otherReasons)) {
                $summary .= ", followed by " . implode(' and ', $otherReasons) . ".";
            } else {
                $summary .= ".";
            }

            return response()->json([
                'labels' => $labels,
                'counts' => $counts,
                'summary' => $summary
            ]);
        } catch (\Exception $e) {
            Log::error("Error fetching deficiency data: " . $e->getMessage());
            return response()->json([
                'labels' => [],
                'counts' => [],
                'summary' => 'Error loading deficiency data.'
            ], 500);
        }
    }
}