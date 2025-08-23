<?php

namespace App\Http\Controllers\Admin;

use App\Models\PatientRecord;
use App\Models\PatientStatusLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class HomeController
{
    public function index()
    {
        $user = Auth::user();

        if ($user->roles->contains('title', 'Admin')) {
            // Admin Dashboard Charts
            $settings1 = [
                'chart_title'        => 'Roles',
                'chart_type'         => 'bar',
                'report_type'        => 'group_by_string',
                'model'              => 'App\Models\Role',
                'group_by_field'     => 'title',
                'aggregate_function' => 'count',
                'filter_field'       => 'created_at',
                'column_class'       => 'col-md-3',
                'entries_number'     => '5',
                'translation_key'    => 'role',
            ];

            $chart1 = new LaravelChart($settings1);

            $settings2 = [
                'chart_title'        => 'Users',
                'chart_type'         => 'pie',
                'report_type'        => 'group_by_string',
                'model'              => 'App\Models\User',
                'group_by_field'     => 'name',
                'aggregate_function' => 'count',
                'filter_field'       => 'created_at',
                'column_class'       => 'col-md-3',
                'entries_number'     => '5',
                'translation_key'    => 'user',
            ];

            $chart2 = new LaravelChart($settings2);

            // Fetch departments with total and active users
            $activeUserIds = DB::table('sessions')
                ->where('last_activity', '>=', now()->subMinutes(config('session.lifetime'))->timestamp)
                ->pluck('user_id')
                ->toArray();

            $departments = Role::with('users')->get()->map(function($role) use ($activeUserIds) {
                $totalUsers = $role->users()->count();
                $activeUsers = $role->users()->whereIn('id', $activeUserIds)->count();

                // Frontend icons & colors
                $icons = [
                    'CSWD' => 'fa-users',
                    "Mayor's Office" => 'fa-building',
                    'Budget' => 'fa-wallet',
                    'Accounting' => 'fa-calculator',
                    "Treasurer's" => 'fa-coins',
                ];

                $colors = [
                    'CSWD' => ['#4e73df','#2b59cb'],
                    "Mayor's Office" => ['#1cc88a','#17a673'],
                    'Budget' => ['#f6c23e','#dda20a'],
                    'Accounting' => ['#36b9cc','#2a96a8'],
                    "Treasurer's" => ['#e74a3b','#c93020'],
                ];

                return [
                    'name' => $role->title,
                    'total_users' => $totalUsers,
                    'active_users' => $activeUsers,
                    'icon' => $icons[$role->title] ?? 'fa-user',
                    'color' => $colors[$role->title][0] ?? '#000',
                    'color-dark' => $colors[$role->title][1] ?? '#333',
                ];
            });

            return view('home', compact('chart1', 'chart2', 'departments'));

        } else {
            $year = request('year');

            // Get available years
            $availableYears = PatientRecord::selectRaw('YEAR(date_processed) as year')
                ->distinct()
                ->orderByDesc('year')
                ->pluck('year')
                ->toArray();

            $query = PatientRecord::query();
            if ($year) {
                $query->whereYear('date_processed', $year);
            }

            // Number blocks
            $totalPatients = PatientRecord::count();
            $totalBurialPatient = PatientRecord::where('case_category', 'Burial Assistance')->count();
            $totalEducationalPatient = PatientRecord::where('case_category', 'Educational Assistance')->count();
            $totalMedicalPatient = PatientRecord::where('case_category', 'Medical Assistance')->count();
            $totalApproved = PatientStatusLog::where('status', 'Approved')->count();

            // Bar Chart: Patients Per Barangay
            $barchartSettings = [
                'chart_title'        => 'Patients Per Barangay',
                'chart_type'         => 'bar',
                'report_type'        => 'group_by_string',
                'model'              => 'App\Models\PatientRecord',
                'group_by_field'     => 'barangay',
                'column_class'       => 'col-md-12',
                'filter_field'       => 'created_at',
                'aggregate_function' => 'count',
            ];
            $barangayChart = new LaravelChart($barchartSettings);

            // Line Chart: Patients Per Month
            $linechartSettings = [
                'chart_title'        => 'Patients Per Month',
                'chart_type'         => 'line',
                'report_type'        => 'group_by_date',
                'model'              => 'App\Models\PatientRecord',
                'group_by_field'     => 'date_processed',
                'group_by_period'    => 'month',
                'aggregate_function' => 'count',
                'filter_field'       => 'date_processed',
                'column_class'       => 'col-md-12',
            ];
            $lineChart = new LaravelChart($linechartSettings);

            return view('dashboard_offices', compact(
                'totalPatients',
                'totalBurialPatient',
                'totalEducationalPatient',
                'totalMedicalPatient',
                'barangayChart',
                'lineChart',
                'availableYears',
                'totalApproved'
            ));
        }
    }
}
