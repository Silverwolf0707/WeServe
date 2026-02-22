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
                'chart_title' => 'Roles', 'chart_type' => 'bar',
                'report_type' => 'group_by_string', 'model' => 'App\Models\Role',
                'group_by_field' => 'title', 'aggregate_function' => 'count',
                'filter_field' => 'created_at', 'column_class' => 'col-md-3',
                'entries_number' => '5', 'translation_key' => 'role',
            ];
            $chart1 = new LaravelChart($settings1);

            $settings2 = [
                'chart_title' => 'Users', 'chart_type' => 'pie',
                'report_type' => 'group_by_string', 'model' => 'App\Models\User',
                'group_by_field' => 'name', 'aggregate_function' => 'count',
                'filter_field' => 'created_at', 'column_class' => 'col-md-3',
                'entries_number' => '5', 'translation_key' => 'user',
            ];
            $chart2 = new LaravelChart($settings2);

            $activeUserIds = DB::table('sessions')
                ->where('last_activity', '>=', now()->subMinutes(config('session.lifetime'))->timestamp)
                ->pluck('user_id')->toArray();

            $departments = Role::with('users')->get()->map(function ($role) use ($activeUserIds) {
                $titleKey = strtolower(trim($role->title));
                $icons  = ['cswd office' => 'fa-users', "mayor's office" => 'fa-building', 'budget office' => 'fa-wallet', 'accounting office' => 'fa-calculator', 'treasury office' => 'fa-coins'];
                $colors = ['cswd office' => ['#4e73df','#2b59cb'], "mayor's office" => ['#1cc88a','#17a673'], 'budget office' => ['#f6c23e','#dda20a'], 'accounting office' => ['#36b9cc','#2a96a8'], 'treasury office' => ['#e74a3b','#c93020']];
                $cp = $colors[$titleKey] ?? ['#6c63ff','#3b3bbf'];
                return ['name' => $role->title, 'total_users' => $role->users()->count(), 'active_users' => $role->users()->whereIn('id', $activeUserIds)->count(), 'icon' => $icons[$titleKey] ?? 'fa-user', 'color' => $cp[0], 'color-dark' => $cp[1]];
            });

            $recentActivities = AuditLog::with('user')->latest()->take(10)->get()->map(function ($log) {
                $department = $log->user ? $log->user->roles->pluck('title')->first() : 'System';
                $colors = ['user' => '#4e73df', 'mayors office' => '#1cc88a', 'budget office' => '#f6c23e', 'accounting office' => '#36b9cc', 'treasury office' => '#e74a3b', 'cswd' => '#4e73df'];
                $color = $colors[strtolower($department)] ?? '#6c63ff';
                $badgeClass = str_contains(strtolower($log->description),'created') ? 'badge-success' : (str_contains(strtolower($log->description),'updated') ? 'badge-info' : (str_contains(strtolower($log->description),'deleted') ? 'badge-danger' : 'badge-secondary'));
                return ['date' => $log->created_at->format('Y-m-d H:i A'), 'department' => $department, 'action' => ucfirst(str_replace('audit:','',$log->description)), 'color' => $color, 'username' => $log->user->name ?? 'System', 'host' => $log->host ?? request()->ip(), 'subject_type' => $log->subject_type ?? 'N/A', 'badge' => $badgeClass];
            });

            return view('home', compact('chart1', 'chart2', 'departments', 'recentActivities'));

        } else {

            // ── Reusable subquery: latest log id per patient ─────────────────────
            $latestLogIds = function ($query) {
                $query->select(DB::raw('MAX(id)'))->from('patient_status_logs')->groupBy('patient_id');
            };

            // ── ORIGINAL: totals ─────────────────────────────────────────────────
            $totalPatients           = PatientRecord::count();
            $totalBurialPatient      = PatientRecord::where('case_category', 'Burial Assistance')->count();
            $totalEducationalPatient = PatientRecord::where('case_category', 'Educational Assistance')->count();
            $totalMedicalPatient     = PatientRecord::where('case_category', 'Medical Assistance')->count();
            $totalApproved           = PatientStatusLog::where('status', 'Approved')->count();

            // ── ORIGINAL: charts ─────────────────────────────────────────────────
            $barangayStats  = PatientRecord::get()->groupBy('barangay')->map->count();
            $barangayLabels = $barangayStats->keys();
            $barangayData   = $barangayStats->values();

            $monthlyStats  = PatientRecord::select(DB::raw("DATE_FORMAT(date_processed, '%M') as month"), DB::raw('COUNT(*) as total'))->groupBy('month')->orderByRaw("MIN(date_processed)")->get();
            $monthlyLabels = $monthlyStats->pluck('month');
            $monthlyData   = $monthlyStats->pluck('total');

            // ── ORIGINAL: pending queues ─────────────────────────────────────────
            $recentlyDraft = PatientStatusLog::with('patient')->whereIn('id', $latestLogIds)
                ->whereIn('status', [PatientStatusLog::STATUS_DRAFT, PatientStatusLog::STATUS_PROCESSING, PatientStatusLog::STATUS_REJECTED, PatientStatusLog::STATUS_ROLLED_BACK_TO_PROCESSING])
                ->orderByDesc('status_date')->get();

            $recentlySubmitted = PatientStatusLog::with('patient')->whereIn('id', $latestLogIds)
                ->whereIn('status', [PatientStatusLog::STATUS_SUBMITTED, PatientStatusLog::STATUS_SUBMITTED_EMERGENCY, PatientStatusLog::STATUS_ROLLED_BACK_TO_SUBMITTED])
                ->orderByDesc('status_date')->get();

            $recentlyApproved = PatientStatusLog::with('patient')->whereIn('id', $latestLogIds)
                ->whereIn('status', [PatientStatusLog::STATUS_APPROVED, PatientStatusLog::STATUS_ROLLED_BACK_TO_APPROVED])
                ->orderByDesc('status_date')->get();

            $recentlyBudgetAllocated = PatientStatusLog::with('patient')->whereIn('id', $latestLogIds)
                ->whereIn('status', [PatientStatusLog::STATUS_BUDGET_ALLOCATED, PatientStatusLog::STATUS_ROLLED_BACK_TO_BUDGET_ALLOCATED])
                ->orderByDesc('status_date')->get();

            $recentlyDvSubmitted = PatientStatusLog::with('patient')->whereIn('id', $latestLogIds)
                ->where('status', PatientStatusLog::STATUS_DV_SUBMITTED)
                ->orderByDesc('status_date')->get();

            // ════════════════════════════════════════════════════════════════════
            // NEW ADDITIONS
            // ════════════════════════════════════════════════════════════════════

            // 1. EMERGENCY CASES PENDING
            //    Submitted[Emergency] that haven't been approved/rejected yet.
            //    Shown as a hero alert badge + filtered table for Mayor's office.
            $emergencyPending = PatientStatusLog::whereIn('id', $latestLogIds)
                ->where('status', PatientStatusLog::STATUS_SUBMITTED_EMERGENCY)
                ->count();

            // 2. ROLLED-BACK CASES
            //    Cases currently in a rolled-back status — needs attention from the
            //    responsible office to re-process. Shown as an alert table.
            $rolledBackCases = PatientStatusLog::with('patient')->whereIn('id', $latestLogIds)
                ->whereIn('status', [
                    PatientStatusLog::STATUS_ROLLED_BACK_TO_PROCESSING,
                    PatientStatusLog::STATUS_ROLLED_BACK_TO_SUBMITTED,
                    PatientStatusLog::STATUS_ROLLED_BACK_TO_APPROVED,
                    PatientStatusLog::STATUS_ROLLED_BACK_TO_BUDGET_ALLOCATED,
                ])
                ->orderByDesc('status_date')->get();

            // 3. STALE CASES — no movement for 7+ days (excludes terminal statuses)
            //    Sorted oldest first so the most overdue appear at the top.
            $staleCases = PatientStatusLog::with('patient')->whereIn('id', $latestLogIds)
                ->whereNotIn('status', ['Disbursed', 'Rejected'])
                ->where('status_date', '<', now()->subDays(7))
                ->orderBy('status_date')
                ->get();

            // 4. REJECTED CASES — currently sitting at Rejected, awaiting re-submission or closure
            $rejectedCases = PatientStatusLog::with('patient')->whereIn('id', $latestLogIds)
                ->where('status', PatientStatusLog::STATUS_REJECTED)
                ->orderByDesc('status_date')
                ->get();

            // 5. APPROVAL RATE (all-time)
            //    How many decided cases were approved vs rejected.
            $totalApprovedCount = PatientStatusLog::where('status', 'Approved')->count();
            $totalRejectedCount = PatientStatusLog::where('status', 'Rejected')->count();
            $totalDecided       = $totalApprovedCount + $totalRejectedCount;
            $approvalRate       = $totalDecided > 0
                ? round(($totalApprovedCount / $totalDecided) * 100, 1)
                : 0;

            // 5. CASES FULLY DISBURSED THIS MONTH
            //    Quick win metric: how many made it all the way through this month.
            $disbursedThisMonth = PatientStatusLog::where('status', 'Disbursed')
                ->whereMonth('status_date', now()->month)
                ->whereYear('status_date', now()->year)
                ->count();

            // 6. NEW CASES REGISTERED THIS MONTH
            //    Volume indicator — how busy is intake this month.
            $newCasesThisMonth = PatientRecord::whereMonth('date_processed', now()->month)
                ->whereYear('date_processed', now()->year)
                ->count();

            // 7. AVG DAYS PER STAGE
            //    Computes the mean time (days) each case spends in each status
            //    before the next log entry is created. Reveals pipeline bottlenecks.
            //    Uses consecutive log pairs via a correlated subquery.
            $avgDaysPerStage = collect(DB::select("
                SELECT
                    a.status,
                    ROUND(AVG(DATEDIFF(COALESCE(b.status_date, NOW()), a.status_date)), 1) AS avg_days
                FROM patient_status_logs a
                LEFT JOIN patient_status_logs b
                    ON  b.patient_id = a.patient_id
                    AND b.id = (
                        SELECT MIN(x.id)
                        FROM patient_status_logs x
                        WHERE x.patient_id = a.patient_id
                          AND x.id > a.id
                    )
                WHERE a.status NOT LIKE '%ROLLED BACK%'
                GROUP BY a.status
            "))->keyBy('status');

            // 8. CASE CATEGORY BREAKDOWN PER STATUS
            //    For the monthly chart: shows composition of pending cases by type.
            //    Used to render a stacked breakdown under each chart section.
            $categoryBreakdown = [
                'Burial Aid'      => PatientRecord::where('case_category','Burial Assistance')->count(),
                'Educational Aid' => PatientRecord::where('case_category','Educational Assistance')->count(),
                'Medical Aid'     => PatientRecord::where('case_category','Medical Assistance')->count(),
            ];

            return view('dashboard_offices', compact(
                // original
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
                'recentlyApproved',
                // new
                'emergencyPending',
                'rolledBackCases',
                'staleCases',
                'totalApprovedCount',
                'totalRejectedCount',
                'approvalRate',
                'disbursedThisMonth',
                'newCasesThisMonth',
                'avgDaysPerStage',
                'categoryBreakdown',
                'rejectedCases'
            ));
        }
    }
}