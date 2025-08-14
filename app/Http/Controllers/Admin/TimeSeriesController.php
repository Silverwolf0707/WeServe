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
        $type = request('type');

        $this->exportToCsvFile();
        $this->runPythonStl();

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
        $data = DB::table('patient_records as pr')
            ->join('patient_status_logs as psl', function ($join) {
                $join->on('psl.patient_id', '=', 'pr.id')
                    ->where('psl.status', 'Disbursed');
            })
            ->leftJoin('budget_allocations as ba', function ($join) {
                $join->on('ba.patient_id', '=', 'pr.id');
            })
            ->leftJoin('disbursement_voucher as dv', function ($join) {
                $join->on('dv.patient_id', '=', 'pr.id');
            })
            ->whereNull('pr.deleted_at')
            ->selectRaw('
            DATE_FORMAT(pr.date_processed, "%Y-%m-01") as month,
            pr.case_type,
            pr.case_category,
            pr.control_number,
            pr.claimant_name,
            pr.patient_name,
            pr.diagnosis,
            pr.age,
            pr.address,
            pr.contact_number,
            pr.case_worker,
            psl.status as patient_status,
            ba.amount as budget_amount,
            ba.budget_status,
            dv.dv_code,
            dv.dv_date
        ')
            ->orderBy('month')
            ->get();

        // CSV Header
        $csv = "month,case_type,case_category,control_number,claimant_name,patient_name,diagnosis,age,address,contact_number,case_worker,patient_status,budget_amount,budget_status,dv_code,dv_date\n";

        // CSV Rows
        foreach ($data as $row) {
            $csv .= implode(',', [
                $row->month,
                $this->escapeCsv($row->case_type),
                $this->escapeCsv($row->case_category),
                $this->escapeCsv($row->control_number),
                $this->escapeCsv($row->claimant_name),
                $this->escapeCsv($row->patient_name),
                $this->escapeCsv($row->diagnosis),
                $row->age,
                $this->escapeCsv($row->address),
                $this->escapeCsv($row->contact_number),
                $this->escapeCsv($row->case_worker),
                $this->escapeCsv($row->patient_status),
                $row->budget_amount,
                $this->escapeCsv($row->budget_status),
                $this->escapeCsv($row->dv_code),
                $row->dv_date
            ]) . "\n";
        }

        Storage::disk('public')->put('full_patient_data.csv', $csv);
    }

    private function escapeCsv($value)
    {
        if (is_null($value)) {
            return '';
        }
        $value = str_replace('"', '""', $value); // Escape double quotes
        if (preg_match('/[",\r\n]/', $value)) {
            return '"' . $value . '"';
        }
        return $value;
    }

    public function runPythonStl()
    {
        $pythonPath = base_path('venv/Scripts/python.exe');
        $scriptPath = base_path('python/stl_analysis.py');

        exec("\"$pythonPath\" \"$scriptPath\"", $output, $return_var);

        if ($return_var !== 0) {
            \Log::error("STL Python script failed", ['output' => $output]);
        }
    }

    public function getStlJson()
    {
        $jsonPath = storage_path('app/public/stl_output.json');

        if (!file_exists($jsonPath)) {
            return response()->json(['error' => 'No STL data found'], 404);
        }

        $json = file_get_contents($jsonPath);
        $data = json_decode($json, true);

        return response()->json($data);
    }
}
