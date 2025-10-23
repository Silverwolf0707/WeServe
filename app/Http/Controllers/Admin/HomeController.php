<?php

namespace App\Http\Controllers\Admin;

use App\Models\PatientRecord;
use App\Models\PatientStatusLog;
use App\Models\Role;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class HomeController
{
    public function index()
    {
        $user = Auth::user();

        if ($user->roles->contains('title', 'Admin')) {
            $settings1 = [
                'chart_title' => 'Roles',
                'chart_type' => 'bar',
                'report_type' => 'group_by_string',
                'model' => 'App\Models\Role',
                'group_by_field' => 'title',
                'aggregate_function' => 'count',
                'filter_field' => 'created_at',
                'column_class' => 'col-md-3',
                'entries_number' => '5',
                'translation_key' => 'role',
            ];

            $chart1 = new LaravelChart($settings1);

            $settings2 = [
                'chart_title' => 'Users',
                'chart_type' => 'pie',
                'report_type' => 'group_by_string',
                'model' => 'App\Models\User',
                'group_by_field' => 'name',
                'aggregate_function' => 'count',
                'filter_field' => 'created_at',
                'column_class' => 'col-md-3',
                'entries_number' => '5',
                'translation_key' => 'user',
            ];

            $chart2 = new LaravelChart($settings2);

            $activeUserIds = DB::table('sessions')
                ->where('last_activity', '>=', now()->subMinutes(config('session.lifetime'))->timestamp)
                ->pluck('user_id')
                ->toArray();

            $departments = Role::with('users')->get()->map(function ($role) use ($activeUserIds) {
                $totalUsers = $role->users()->count();
                $activeUsers = $role->users()->whereIn('id', $activeUserIds)->count();

                $titleKey = strtolower(trim($role->title));

                $icons = [
                    'cswd office' => 'fa-users',
                    "mayor's office" => 'fa-building',
                    'budget office' => 'fa-wallet',
                    'accounting office' => 'fa-calculator',
                    "treasury office" => 'fa-coins',
                ];

                $colors = [
                    'cswd office' => ['#4e73df', '#2b59cb'],
                    "mayor's office" => ['#1cc88a', '#17a673'],
                    'budget office' => ['#f6c23e', '#dda20a'],
                    'accounting office' => ['#36b9cc', '#2a96a8'],
                    "treasury office" => ['#e74a3b', '#c93020'],
                ];

                $colorPair = $colors[$titleKey] ?? ['#6c63ff', '#3b3bbf'];
                $icon = $icons[$titleKey] ?? 'fa-user';

                return [
                    'name' => $role->title,
                    'total_users' => $totalUsers,
                    'active_users' => $activeUsers,
                    'icon' => $icon,
                    'color' => $colorPair[0],
                    'color-dark' => $colorPair[1],
                ];
            });

            $recentActivities = AuditLog::with('user')
                ->latest()
                ->take(10)
                ->get()
                ->map(function ($log) {
                    $department = $log->user
                        ? $log->user->roles->pluck('title')->first()
                        : 'System';
                    $colors = [
                        'user' => '#4e73df',
                        'mayors office' => '#1cc88a',
                        'budget office' => '#f6c23e',
                        'accounting office' => '#36b9cc',
                        'treasury office' => '#e74a3b',
                        'cswd' => '#4e73df',
                    ];

                    $color = $colors[strtolower($department)] ?? '#6c63ff';
                    $badgeClass = 'badge-secondary';
                    if (str_contains(strtolower($log->description), 'created')) {
                        $badgeClass = 'badge-success';
                    } elseif (str_contains(strtolower($log->description), 'updated')) {
                        $badgeClass = 'badge-info';
                    } elseif (str_contains(strtolower($log->description), 'deleted')) {
                        $badgeClass = 'badge-danger';
                    }

                    return [
                        'date' => $log->created_at->format('Y-m-d H:i A'),
                        'department' => $department,
                        'action' => ucfirst(str_replace('audit:', '', $log->description)),
                        'color' => $color,
                        'username' => $log->user->name ?? 'System',
                        'host' => $log->host ?? request()->ip(),
                        'subject_type' => $log->subject_type ?? 'N/A',
                        'badge' => $badgeClass,

                    ];
                });


            return view('home', compact('chart1', 'chart2', 'departments', 'recentActivities'));
        } else {

            $totalPatients = PatientRecord::count();
            $totalBurialPatient = PatientRecord::where('case_category', 'Burial Assistance')->count();
            $totalEducationalPatient = PatientRecord::where('case_category', 'Educational Assistance')->count();
            $totalMedicalPatient = PatientRecord::where('case_category', 'Medical Assistance')->count();
            $totalApproved = PatientStatusLog::where('status', 'Approved')->count();

            $barangayStats = PatientRecord::get()
                ->groupBy('barangay')
                ->map->count();

            $barangayLabels = $barangayStats->keys();
            $barangayData = $barangayStats->values();

            $monthlyStats = PatientRecord::select(
                DB::raw("DATE_FORMAT(date_processed, '%M') as month"),
                DB::raw('COUNT(*) as total')
            )
                ->groupBy('month')
                ->orderByRaw("MIN(date_processed)")
                ->get();

            $monthlyLabels = $monthlyStats->pluck('month');
            $monthlyData = $monthlyStats->pluck('total');

            $recentlyDraft = PatientStatusLog::with('patient')
                ->whereIn('id', function ($query) {
                    $query->select(DB::raw('MAX(id)'))
                        ->from('patient_status_logs')
                        ->groupBy('patient_id');
                })
                ->whereIn('status', [PatientStatusLog::STATUS_DRAFT, PatientStatusLog::STATUS_PROCESSING, PatientStatusLog::STATUS_REJECTED])
                ->orderByDesc('status_date')
                ->get();


            $recentlySubmitted = PatientStatusLog::with('patient')
                ->whereIn('id', function ($query) {
                    $query->select(DB::raw('MAX(id)'))
                        ->from('patient_status_logs')
                        ->groupBy('patient_id');
                })
                ->whereIn('status', [PatientStatusLog::STATUS_SUBMITTED, PatientStatusLog::STATUS_SUBMITTED_EMERGENCY])
                ->orderByDesc('status_date')
                ->get();

            // $recentlyApprovedRejected = PatientStatusLog::with('patient')
            //     ->whereIn('id', function ($query) {
            //         $query->select(DB::raw('MAX(id)'))
            //             ->from('patient_status_logs')
            //             ->groupBy('patient_id');
            //     })
            //     ->whereIn('status', [PatientStatusLog::STATUS_APPROVED, PatientStatusLog::STATUS_REJECTED])
            //     ->orderByDesc('status_date')
            //     ->get();

            $recentlyApproved = PatientStatusLog::with('patient')
                ->whereIn('id', function ($query) {
                    $query->select(DB::raw('MAX(id)'))
                        ->from('patient_status_logs')
                        ->groupBy('patient_id');
                })
                ->where('status', PatientStatusLog::STATUS_APPROVED)
                ->orderByDesc('status_date')
                ->get();


            $recentlyBudgetAllocated = PatientStatusLog::with('patient')->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('patient_status_logs')
                    ->groupBy('patient_id');
            })
                ->where('status', PatientStatusLog::STATUS_BUDGET_ALLOCATED)
                ->orderByDesc('status_date')
                ->get();

            $recentlyDvSubmitted = PatientStatusLog::with('patient')->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('patient_status_logs')
                    ->groupBy('patient_id');
            })
                ->where('status', PatientStatusLog::STATUS_DV_SUBMITTED)
                ->orderByDesc('status_date')
                ->get();



            return view('dashboard_offices', compact(
                'totalPatients',
                'totalBurialPatient',
                'totalEducationalPatient',
                'totalMedicalPatient',
                'totalApproved',
                'barangayLabels',
                'barangayData',
                'monthlyLabels',
                'monthlyData',
                'recentlySubmitted',
                'recentlyBudgetAllocated',
                'recentlyDvSubmitted',
                'recentlyDraft',
                'recentlyApproved'
            ));
        }
    }
}
