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
            Log::info("Running budget Python script");
            $this->runBudgetPython();
        } else {
            Log::info("Running CSWD Python script");
            $this->runPythonStl();
            $this->runWeeklyStl();
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
        Log::info("Starting CSV export...");

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

        Log::info("Data fetched for CSV", ['row_count' => $data->count()]);

        $csv = "month,case_category,case_type,age,date_processed,budget_allocated\n";
        foreach ($data as $row) {
            $csv .= "{$row->month},{$row->case_category},{$row->case_type},{$row->age},{$row->date_processed},{$row->budget_allocated}\n";
        }

        // Store in private analytics folder
        Storage::disk('private')->put('analytics/full_patient_data.csv', $csv);
        $csvPath = storage_path('app/private/analytics/full_patient_data.csv');

        Log::info("CSV saved to private storage", [
            'path' => $csvPath,
            'file_exists' => file_exists($csvPath),
            'csv_size' => strlen($csv)
        ]);

        return $csvPath;
    }

    private function findPythonPath()
    {
        // Try Linux venv paths first (production)
        $paths = [
            base_path('venv/bin/python'),
            base_path('venv/bin/python3'),
            '/usr/bin/python3',
            '/usr/bin/python',
            // Windows fallback (local development)
            base_path('venv/Scripts/python.exe'),
            base_path('venv/Scripts/python3.exe'),
        ];

        foreach ($paths as $path) {
            if (file_exists($path) && is_executable($path)) {
                Log::info("Found Python at: {$path}");

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

        // Check if files exist
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

        // Check if output was created
        $outputJsonPath = storage_path("app/private/analytics/{$outputJsonName}");
        if (file_exists($outputJsonPath)) {
            $size = filesize($outputJsonPath);
            Log::info("Python script {$scriptName} SUCCESS!", [
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

    public function runPythonStl()
    {
        return $this->executePythonScript('stl_analysis.py', 'stl_output.json');
    }

    public function runWeeklyStl()
    {
        return $this->executePythonScript('weekly_stl.py', 'weekly_stl_output.json');
    }

    public function runBudgetPython()
    {
        return $this->executePythonScript('budget_stl_analysis.py', 'stl_budget_output.json');
    }

    public function getStlJson(Request $request)
    {
        $type = $request->query('type');

        // Check permissions
        $permissions = [
            'cswd' => 'CSWD-ANALYTICS',
            'budget' => 'BUDGET-ANALYTICS',
            'treasury' => 'TREASURY-ANALYTICS',
            'accounting' => 'ACCOUNTING-ANALYTICS',
        ];

        if (!isset($permissions[$type])) {
            abort(404, 'Invalid analytics type.');
        }

        if (!Gate::allows($permissions[$type])) {
            abort(403, 'You do not have permission to view this analytics.');
        }

        // Define private file paths
        $file = $type === 'budget'
            ? 'analytics/stl_budget_output.json'
            : 'analytics/stl_output.json';

        Log::info("Looking for JSON file", [
            'file' => $file,
            'full_path' => storage_path('app/private/' . $file),
            'exists' => Storage::disk('private')->exists($file)
        ]);

        // Check if file exists in private storage
        if (!Storage::disk('private')->exists($file)) {
            Log::warning("JSON file not found, attempting to generate it");

            // Try to generate the file
            $success = false;
            if ($type === 'budget') {
                $success = $this->runBudgetPython();
            } else {
                $success = $this->runPythonStl();
            }

            if (!$success || !Storage::disk('private')->exists($file)) {
                Log::error("Failed to generate JSON file after attempt", [
                    'file' => $file,
                    'generation_success' => $success,
                    'now_exists' => Storage::disk('private')->exists($file)
                ]);
                return response()->json(['error' => 'Analytics data not available.'], 404);
            }
        }

        try {
            // Read and return JSON content
            $jsonContent = Storage::disk('private')->get($file);
            $data = json_decode($jsonContent, true);

            if (empty($data)) {
                Log::warning("JSON file is empty", ['file' => $file]);
                return response()->json(['error' => 'No analytics data available.'], 404);
            }

            Log::info("Successfully returned JSON data", [
                'file' => $file,
                'data_keys' => array_keys($data),
                'data_count' => count($data)
            ]);

            return response()->json($data);
        } catch (\Exception $e) {
            Log::error("Error reading STL JSON: " . $e->getMessage(), [
                'file' => $file,
                'exception' => $e
            ]);
            return response()->json(['error' => 'Failed to read analytics data.'], 500);
        }
    }
    public function getWeeklyStlJson()
    {
        // Check permission
        if (!Gate::allows('CSWD-ANALYTICS')) {
            abort(403, 'You do not have permission to view this analytics.');
        }

        $file = 'analytics/weekly_stl_output.json';

        if (!Storage::disk('private')->exists($file)) {
            // Try regenerating if missing
            $success = $this->runWeeklyStl();
            if (!$success || !Storage::disk('private')->exists($file)) {
                Log::error("Weekly STL JSON not found and failed to generate", ['file' => $file]);
                return response()->json(['error' => 'Weekly STL data not available.'], 404);
            }
        }

        try {
            $jsonContent = Storage::disk('private')->get($file);
            $data = json_decode($jsonContent, true);
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error("Failed reading weekly STL JSON: " . $e->getMessage());
            return response()->json(['error' => 'Failed to read weekly STL data.'], 500);
        }
    }
}
