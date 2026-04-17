@extends('layouts.admin')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

<style>
:root {
    --pr-forest:        #064e3b;
    --pr-forest-deep:   #052e22;
    --pr-forest-mid:    #065f46;
    --pr-forest-lite:   #047857;
    --pr-lime:          #74ff70;
    --pr-lime-dim:      #52e84e;
    --pr-lime-ghost:    rgba(116,255,112,.10);
    --pr-lime-border:   rgba(116,255,112,.30);
    --pr-surface:       #ffffff;
    --pr-surface2:      #f0fdf4;
    --pr-muted:         #ecfdf5;
    --pr-border:        #d1fae5;
    --pr-border-dark:   #a7f3d0;
    --pr-text:          #052e22;
    --pr-sub:           #3d7a62;
    --pr-warn:          #f59e0b;
    --pr-danger:        #ef4444;
    --pr-radius:        12px;
    --pr-radius-sm:     7px;
    --pr-shadow:        0 2px 8px rgba(6,78,59,.08), 0 8px 24px rgba(6,78,59,.06);
    --pr-shadow-lg:     0 4px 24px rgba(6,78,59,.16), 0 16px 48px rgba(6,78,59,.10);
    --pr-shadow-lime:   0 2px 12px rgba(116,255,112,.25);
}

.db-page { font-family: 'DM Sans', sans-serif; color: var(--pr-text); padding: 0 0 2rem; }

/* ══ HERO WELCOME ══ */
.db-hero {
    background: linear-gradient(135deg, #052e22 0%, #064e3b 55%, #065f46 100%);
    border-radius: var(--pr-radius);
    padding: 28px 32px;
    margin-bottom: 20px;
    position: relative;
    overflow: hidden;
    box-shadow: var(--pr-shadow-lg);
}
.db-hero::before {
    content: '';
    position: absolute; inset: 0; border-radius: var(--pr-radius);
    background:
        radial-gradient(ellipse 500px 260px at 100% 50%,  rgba(116,255,112,.13) 0%, transparent 60%),
        radial-gradient(ellipse 200px 120px at 0%   80%,  rgba(116,255,112,.07) 0%, transparent 70%),
        radial-gradient(ellipse 300px 150px at 50%  -30%, rgba(255,255,255,.04) 0%, transparent 60%);
    pointer-events: none; z-index: 0;
}
.db-hero::after {
    content: '';
    position: absolute; top: 0; left: 32px; right: 32px; height: 2px;
    background: linear-gradient(to right, transparent, var(--pr-lime), transparent);
    border-radius: 2px; opacity: .55;
}

/* Floating particles */
.db-particles { position: absolute; inset: 0; pointer-events: none; z-index: 0; overflow: hidden; border-radius: var(--pr-radius); }
.db-particle {
    position: absolute; border-radius: 50%;
    background: var(--pr-lime); opacity: 0;
    animation: db-float linear infinite;
}
@keyframes db-float {
    0%   { transform: translateY(100%) scale(0); opacity: 0; }
    10%  { opacity: .15; }
    90%  { opacity: .08; }
    100% { transform: translateY(-120%) scale(1.2); opacity: 0; }
}

.db-hero-inner {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 16px; position: relative; z-index: 1;
}
.db-hero-left  { display: flex; align-items: center; gap: 18px; }

.db-avatar {
    width: 56px; height: 56px; flex-shrink: 0;
    background: rgba(116,255,112,.14);
    border: 2px solid rgba(116,255,112,.35);
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; color: var(--pr-lime);
    box-shadow: 0 4px 16px rgba(116,255,112,.20);
    backdrop-filter: blur(4px);
}

.db-welcome-label { font-size: .72rem; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; color: rgba(116,255,112,.7); margin-bottom: 3px; }
.db-welcome-name  { font-size: 1.4rem; font-weight: 800; color: #fff; letter-spacing: -.02em; line-height: 1.1; margin-bottom: 5px; }
.db-welcome-name span { color: var(--pr-lime); }
.db-datetime {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: .78rem; font-weight: 500; color: rgba(255,255,255,.55);
}
.db-datetime i { color: rgba(116,255,112,.6); font-size: .72rem; }

.db-hero-right { display: flex; align-items: center; gap: 10px; }
.db-stat-pill {
    display: flex; flex-direction: column; align-items: center;
    background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.12);
    border-radius: 10px; padding: 10px 18px; min-width: 80px; text-align: center;
    transition: background .2s, border-color .2s, transform .2s;
    text-decoration: none;
}
.db-stat-pill:hover {
    background: rgba(116,255,112,.10); border-color: var(--pr-lime-border);
    transform: translateY(-2px); cursor: pointer;
}
.db-stat-pill .sp-val { font-size: 1.3rem; font-weight: 800; color: var(--pr-lime); line-height: 1; }
.db-stat-pill .sp-label { font-size: .66rem; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; color: rgba(255,255,255,.45); margin-top: 3px; }

/* ══ DEPARTMENT CARDS ══ */
.db-dept-card {
    background: var(--pr-surface);
    border-radius: var(--pr-radius);
    border: 1px solid var(--pr-border);
    box-shadow: var(--pr-shadow);
    overflow: hidden;
    transition: transform .2s, box-shadow .2s, border-color .2s;
    cursor: pointer;
    position: relative;
    text-decoration: none;
    display: block;
}
.db-dept-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--pr-shadow-lg);
    border-color: var(--pr-border-dark);
    text-decoration: none;
}
.db-dept-card:hover .db-dept-arrow { opacity: 1; transform: translateX(0); }

.db-dept-card-top {
    padding: 18px 18px 14px;
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, var(--dept-dark, #052e22) 0%, var(--dept-color, #064e3b) 100%);
}
.db-dept-card-top::after {
    content: '';
    position: absolute; top: 0; left: 14px; right: 14px; height: 1.5px;
    background: linear-gradient(to right, transparent, rgba(255,255,255,.25), transparent);
    border-radius: 2px;
}
.db-dept-icon-wrap {
    width: 40px; height: 40px; border-radius: 10px;
    background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; color: #fff; margin-bottom: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,.15);
}
.db-dept-name {
    font-size: .82rem; font-weight: 700; color: #fff;
    letter-spacing: -.01em; line-height: 1.3;
}
.db-dept-glow {
    position: absolute; right: -20px; bottom: -20px;
    width: 80px; height: 80px; border-radius: 50%;
    background: rgba(255,255,255,.05);
    pointer-events: none;
}
/* Arrow indicator on hover */
.db-dept-arrow {
    position: absolute; top: 12px; right: 12px;
    width: 22px; height: 22px; border-radius: 50%;
    background: rgba(255,255,255,.15);
    display: flex; align-items: center; justify-content: center;
    font-size: .6rem; color: #fff;
    opacity: 0; transform: translateX(-4px);
    transition: opacity .2s, transform .2s;
}

.db-dept-stats {
    display: grid; grid-template-columns: 1fr 1fr;
    border-top: 1px solid var(--pr-border);
}
.db-dept-stat {
    padding: 12px 14px; text-align: center;
    transition: background .18s;
}
.db-dept-stat:first-child { border-right: 1px solid var(--pr-border); }
.db-dept-card:hover .db-dept-stat { background: var(--pr-surface2); }
.db-dept-stat-val {
    font-size: 1.25rem; font-weight: 800; color: var(--pr-forest);
    letter-spacing: -.02em; line-height: 1;
}
.db-dept-stat-label {
    font-size: .65rem; font-weight: 600; text-transform: uppercase;
    letter-spacing: .06em; color: var(--pr-sub); margin-top: 3px;
}
.db-dept-stat .active-val { color: var(--pr-forest-mid); }

/* Active dot indicator */
.db-dept-stat-label .dot {
    display: inline-block; width: 5px; height: 5px;
    border-radius: 50%; background: var(--pr-lime-dim);
    vertical-align: middle; margin-right: 3px;
}

/* Section heading */
.db-section-head {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 12px; margin-top: 6px;
}
.db-section-title {
    display: flex; align-items: center; gap: 8px;
    font-size: .82rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .06em; color: var(--pr-sub);
}
.db-section-title i { color: var(--pr-forest-mid); }
.db-section-badge {
    font-size: .68rem; font-weight: 600;
    background: var(--pr-lime-ghost); border: 1px solid var(--pr-lime-border);
    border-radius: 20px; padding: 1px 8px; color: var(--pr-forest);
}

/* ══ QUICK NAV PILLS ══ */
.db-quick-nav {
    display: flex; flex-wrap: wrap; gap: 8px;
    margin-bottom: 20px;
}
.db-nav-pill {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 7px 14px; border-radius: 20px;
    font-size: .76rem; font-weight: 600;
    background: var(--pr-surface); border: 1px solid var(--pr-border);
    color: var(--pr-forest); text-decoration: none;
    box-shadow: var(--pr-shadow);
    transition: all .2s;
}
.db-nav-pill i { font-size: .7rem; color: var(--pr-sub); transition: color .2s; }
.db-nav-pill:hover {
    background: var(--pr-forest);
    border-color: var(--pr-forest);
    color: var(--pr-lime);
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: var(--pr-shadow-lg);
}
.db-nav-pill:hover i { color: var(--pr-lime); }

/* ══ ACTIVITY TABLE ══ */
.db-activity-card {
    background: var(--pr-surface);
    border-radius: var(--pr-radius);
    border: 1px solid var(--pr-border);
    box-shadow: var(--pr-shadow);
    overflow: hidden;
    margin-top: 20px;
}
.db-activity-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 20px 12px;
    border-bottom: 1px solid var(--pr-border);
    background: var(--pr-surface2);
    flex-wrap: wrap; gap: 8px;
}
.db-activity-title {
    display: flex; align-items: center; gap: 9px;
    font-size: .84rem; font-weight: 700; color: var(--pr-forest);
    letter-spacing: -.01em;
}
.db-activity-icon {
    width: 28px; height: 28px; border-radius: 7px;
    background: var(--pr-lime-ghost); border: 1px solid var(--pr-lime-border);
    display: flex; align-items: center; justify-content: center;
    font-size: .72rem; color: var(--pr-forest);
}
.db-view-all {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: .73rem; font-weight: 600; color: var(--pr-forest-mid);
    text-decoration: none; padding: 4px 10px;
    border-radius: 6px; border: 1px solid var(--pr-border-dark);
    background: var(--pr-surface); transition: all .2s;
}
.db-view-all:hover {
    background: var(--pr-forest); color: var(--pr-lime);
    border-color: var(--pr-forest); text-decoration: none;
}

.db-table { width: 100%; border-collapse: collapse; }
.db-table thead tr {
    background: var(--pr-surface2);
    border-bottom: 1.5px solid var(--pr-border-dark);
}
.db-table thead th {
    padding: 10px 16px;
    font-size: .68rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .07em; color: var(--pr-sub);
    white-space: nowrap; text-align: left;
}
.db-table tbody tr {
    border-bottom: 1px solid var(--pr-border);
    transition: background .15s, transform .15s;
    cursor: pointer;
}
.db-table tbody tr:last-child { border-bottom: none; }
.db-table tbody tr:hover { background: var(--pr-surface2); }
.db-table tbody tr.clickable-row:hover td:first-child { color: var(--pr-forest); }
.db-table td {
    padding: 11px 16px;
    font-size: .81rem; font-weight: 500; color: var(--pr-text);
    vertical-align: middle;
}
.db-table td.muted { color: var(--pr-sub); font-size: .78rem; }

/* Row hover arrow */
.db-row-arrow {
    display: inline-flex; align-items: center; justify-content: center;
    width: 20px; height: 20px; border-radius: 50%;
    background: var(--pr-lime-ghost); border: 1px solid var(--pr-lime-border);
    font-size: .55rem; color: var(--pr-forest-mid);
    opacity: 0; transition: opacity .15s;
    vertical-align: middle; margin-left: 6px;
}
.db-table tbody tr:hover .db-row-arrow { opacity: 1; }

/* Date cell */
.db-date-cell { white-space: nowrap; color: var(--pr-sub); font-size: .78rem; }

/* Dept pill */
.db-dept-pill {
    display: inline-flex; align-items: center; gap: 5px;
    border-radius: 6px; padding: 3px 10px;
    font-size: .73rem; font-weight: 700; letter-spacing: .02em;
    border: 1px solid transparent;
    text-decoration: none;
    transition: filter .15s;
}
.db-dept-pill:hover { filter: brightness(.92); }
.db-dept-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }

/* Action badge */
.db-action-badge {
    display: inline-flex; align-items: center; gap: 4px;
    border-radius: 20px; padding: 3px 10px;
    font-size: .71rem; font-weight: 700; letter-spacing: .03em;
}
.badge-created  { background: var(--pr-lime-ghost);    border: 1px solid var(--pr-lime-border);    color: var(--pr-forest-mid); }
.badge-updated  { background: rgba(245,158,11,.12);    border: 1px solid rgba(245,158,11,.3);      color: #92400e; }
.badge-deleted  { background: rgba(239,68,68,.10);     border: 1px solid rgba(239,68,68,.28);     color: #b91c1c; }
.badge-login    { background: rgba(99,102,241,.10);    border: 1px solid rgba(99,102,241,.28);    color: #4338ca; }
.badge-default  { background: var(--pr-muted);         border: 1px solid var(--pr-border-dark);   color: var(--pr-sub); }

/* Empty state */
.db-empty { text-align: center; padding: 40px 20px; color: var(--pr-sub); }
.db-empty i { font-size: 2rem; color: var(--pr-border-dark); margin-bottom: 10px; display: block; }
.db-empty span { font-size: .82rem; }

@media (max-width: 768px) {
    .db-hero { padding: 18px 18px; }
    .db-hero-inner { flex-direction: column; align-items: flex-start; }
    .db-hero-right { width: 100%; justify-content: flex-start; }
    .db-stat-pill { flex: 1; }
    .db-quick-nav { gap: 6px; }
}
</style>

<div class="db-page">

{{-- ══ HERO ══ --}}
<div class="db-hero">
    <div class="db-particles" id="dbParticles"></div>
    <div class="db-hero-inner">
        <div class="db-hero-left">
            <div class="db-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div>
                <div class="db-welcome-label"><i class="fas fa-circle" style="font-size:.45rem;vertical-align:middle;margin-right:4px;"></i> Admin Dashboard</div>
                <div class="db-welcome-name">
                    Welcome back, <span>{{ Auth::user()->name }}</span>
                </div>
                <div class="db-datetime">
                    <i class="fas fa-clock"></i>
                    <span id="db-current-datetime">Loading…</span>
                </div>
            </div>
        </div>

        <div class="db-hero-right">
            @php
                $totalUsers  = collect($departments)->sum('total_users');
                $activeUsers = collect($departments)->sum('active_users');
                $totalDepts  = count($departments);
            @endphp
            {{-- Stat pills link to relevant sections --}}
            <a href="{{ route('admin.roles.index') }}" class="db-stat-pill" title="Manage departments">
                <span class="sp-val">{{ $totalDepts }}</span>
                <span class="sp-label">Departments</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="db-stat-pill" title="All users">
                <span class="sp-val">{{ $totalUsers }}</span>
                <span class="sp-label">Total Users</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="db-stat-pill" title="Active users">
                <span class="sp-val">{{ $activeUsers }}</span>
                <span class="sp-label">Active Now</span>
            </a>
        </div>
    </div>
</div>





{{-- ══ DEPARTMENTS SECTION ══ --}}
<div class="db-section-head">
    <div class="db-section-title">
        <i class="fas fa-building"></i> Departments
    </div>
    <span class="db-section-badge">{{ count($departments) }} total</span>
</div>

<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-3 mb-4">
    @foreach ($departments as $dept)
        <div class="col">
            {{--
                Map each department name to a relevant route.
                Adjust the slug/key matching to whatever your $dept['name'] values actually are.
            --}}
            @php
                $deptName  = strtolower($dept['name'] ?? '');
                $deptRoute = match(true) {
                    str_contains($deptName, 'patient') || str_contains($deptName, 'record')
                        => route('admin.patient-records.index'),
                    str_contains($deptName, 'process') || str_contains($deptName, 'track')
                        => route('admin.process-tracking.index'),
                    str_contains($deptName, 'application') || str_contains($deptName, 'online')
                        => route('admin.online-applications.index'),
                    str_contains($deptName, 'budget') || str_contains($deptName, 'finance')
                        => route('admin.budget-records.index'),
                    str_contains($deptName, 'document')
                        => route('admin.document-management.index'),
                    str_contains($deptName, 'user') || str_contains($deptName, 'admin')
                        => route('admin.users.index'),
                    default => route('admin.home'),
                };
            @endphp
            <a href="{{ $deptRoute }}" class="db-dept-card" style="--dept-color: {{ $dept['color'] }}; --dept-dark: {{ $dept['color-dark'] }}">
                <div class="db-dept-card-top">
                    <div class="db-dept-icon-wrap">
                        <i class="fas {{ $dept['icon'] }}"></i>
                    </div>
                    <div class="db-dept-name">{{ $dept['name'] }}</div>
                    <div class="db-dept-glow"></div>
                    <div class="db-dept-arrow"><i class="fas fa-arrow-right"></i></div>
                </div>
                <div class="db-dept-stats">
                    <div class="db-dept-stat">
                        <div class="db-dept-stat-val">{{ $dept['total_users'] }}</div>
                        <div class="db-dept-stat-label">Total Users</div>
                    </div>
                    <div class="db-dept-stat">
                        <div class="db-dept-stat-val active-val">{{ $dept['active_users'] }}</div>
                        <div class="db-dept-stat-label"><span class="dot"></span>Active</div>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>


{{-- ══ RECENT ACTIVITY ══ --}}
<div class="db-activity-card">
    <div class="db-activity-head">
        <div class="db-activity-title">
            <div class="db-activity-icon"><i class="fas fa-history"></i></div>
            Recent Department Activities
        </div>
        <div style="display:flex;align-items:center;gap:10px;">
            <span class="db-section-badge">{{ count($recentActivities) }} entries</span>
            <a href="{{ route('admin.audit-logs.index') }}" class="db-view-all">
                View All <i class="fas fa-arrow-right" style="font-size:.6rem;"></i>
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="db-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Department</th>
                    <th>Subject Type</th>
                    <th>Action</th>
                    <th>Username</th>
                    <th>Host</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentActivities as $activity)
                    @php
                        
                        $subjectType = strtolower($activity['subject_type'] ?? '');
                        $rowRoute = match(true) {
                            str_contains($subjectType, 'patient')     => route('admin.patient-records.index'),
                            str_contains($subjectType, 'process')
                                || str_contains($subjectType, 'track') => route('admin.process-tracking.index'),
                            str_contains($subjectType, 'application') => route('admin.online-applications.index'),
                            str_contains($subjectType, 'budget')      => route('admin.budget-records.index'),
                            str_contains($subjectType, 'document')    => route('admin.document-management.index'),
                            str_contains($subjectType, 'user')
                                || str_contains($subjectType, 'role')
                                || str_contains($subjectType, 'permission') => route('admin.audit-logs.index'),
                            default => route('admin.audit-logs.index'),
                        };
                    @endphp
                    <tr class="clickable-row" onclick="window.location='{{ $rowRoute }}'" title="Go to {{ $activity['subject_type'] ?? 'records' }}">
                        <td class="db-date-cell">
                            <i class="fas fa-clock" style="font-size:.65rem;margin-right:4px;color:var(--pr-border-dark);"></i>
                            {{ $activity['date'] }}
                        </td>
                        <td>
                            @php $deptColor = $activity['color'] ?? '#064e3b'; @endphp
                            <span class="db-dept-pill" style="background:{{ $deptColor }}18;border-color:{{ $deptColor }}44;color:{{ $deptColor }};">
                                <span class="db-dept-dot" style="background:{{ $deptColor }};"></span>
                                {{ $activity['department'] }}
                            </span>
                        </td>
                        <td class="muted">{{ $activity['subject_type'] }}</td>
                        <td>
                            @php
                                $action    = strtolower($activity['action'] ?? '');
                                $badgeCls  = match(true) {
                                    str_contains($action, 'creat') || str_contains($action, 'add')    => 'badge-created',
                                    str_contains($action, 'updat') || str_contains($action, 'edit')   => 'badge-updated',
                                    str_contains($action, 'delet') || str_contains($action, 'remov')  => 'badge-deleted',
                                    str_contains($action, 'login') || str_contains($action, 'sign')   => 'badge-login',
                                    default => 'badge-default',
                                };
                                $actionIcon = match(true) {
                                    str_contains($action, 'creat') || str_contains($action, 'add')   => 'fa-plus-circle',
                                    str_contains($action, 'updat') || str_contains($action, 'edit')  => 'fa-pen',
                                    str_contains($action, 'delet') || str_contains($action, 'remov') => 'fa-trash',
                                    str_contains($action, 'login') || str_contains($action, 'sign')  => 'fa-sign-in-alt',
                                    default => 'fa-circle',
                                };
                            @endphp
                            <span class="db-action-badge {{ $badgeCls }}">
                                <i class="fas {{ $actionIcon }}" style="font-size:.6rem;"></i>
                                {{ $activity['action'] }}
                            </span>
                        </td>
                        <td style="font-weight:600;">{{ $activity['username'] ?? 'System' }}</td>
                        <td class="muted">{{ $activity['host'] ?? 'N/A' }}</td>
                        <td>
                            <span class="db-row-arrow"><i class="fas fa-arrow-right"></i></span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="db-empty">
                                <i class="fas fa-inbox"></i>
                                <span>No recent activities found.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</div>{{-- /db-page --}}

<script>
function updateDateTime() {
    const now = new Date();
    const options = { weekday:'long', year:'numeric', month:'long', day:'numeric', hour:'2-digit', minute:'2-digit' };
    const el = document.getElementById('db-current-datetime');
    if (el) el.textContent = now.toLocaleDateString('en-US', options);
}

document.addEventListener('DOMContentLoaded', function () {
    updateDateTime();
    setInterval(updateDateTime, 1000);

    // Generate floating particles
    const container = document.getElementById('dbParticles');
    if (container) {
        for (let i = 0; i < 8; i++) {
            const p = document.createElement('div');
            p.className = 'db-particle';
            const size = 6 + Math.random() * 14;
            p.style.cssText = `
                width: ${size}px;
                height: ${size}px;
                left: ${Math.random() * 95}%;
                animation-duration: ${5 + Math.random() * 8}s;
                animation-delay: ${Math.random() * 6}s;
            `;
            container.appendChild(p);
        }
    }
});
</script>

@endsection