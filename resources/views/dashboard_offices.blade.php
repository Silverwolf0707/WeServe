@extends('layouts.admin')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

    <style>
        :root {
            --pr-forest: #064e3b; --pr-forest-deep: #052e22; --pr-forest-mid: #065f46;
            --pr-lime: #74ff70; --pr-lime-dim: #52e84e;
            --pr-lime-ghost: rgba(116,255,112,.10); --pr-lime-border: rgba(116,255,112,.30);
            --pr-surface: #ffffff; --pr-surface2: #f0fdf4;
            --pr-border: #d1fae5; --pr-border-dark: #a7f3d0;
            --pr-text: #052e22; --pr-sub: #3d7a62;
            --pr-radius: 12px;
            --pr-shadow: 0 2px 8px rgba(6,78,59,.08), 0 8px 24px rgba(6,78,59,.06);
            --pr-shadow-lg: 0 4px 24px rgba(6,78,59,.16), 0 16px 48px rgba(6,78,59,.10);
        }
        .pr-page { font-family:'DM Sans',sans-serif; color:var(--pr-text); padding:0 0 2rem; }

        /* ── Hero ── */
        .db-hero { background:linear-gradient(135deg,#052e22 0%,#064e3b 55%,#065f46 100%); border-radius:var(--pr-radius); padding:22px 28px; margin-bottom:20px; position:relative; overflow:hidden; box-shadow:var(--pr-shadow-lg); }
        .db-hero::before { content:''; position:absolute; inset:0; background:radial-gradient(ellipse 420px 220px at 95% 50%,rgba(116,255,112,.14) 0%,transparent 65%),radial-gradient(ellipse 200px 120px at 5% 80%,rgba(116,255,112,.07) 0%,transparent 70%); pointer-events:none; }
        .db-hero::after { content:''; position:absolute; top:0; left:28px; right:28px; height:2px; background:linear-gradient(to right,transparent,var(--pr-lime),transparent); opacity:.55; border-radius:2px; }
        .db-hero-inner { display:flex; align-items:center; justify-content:space-between; gap:16px; position:relative; z-index:1; flex-wrap:wrap; }
        .db-hero-left  { display:flex; align-items:center; gap:16px; }
        .db-avatar { width:52px; height:52px; border-radius:50%; background:rgba(116,255,112,.12); border:2px solid rgba(116,255,112,.35); display:flex; align-items:center; justify-content:center; font-size:1.2rem; color:var(--pr-lime); flex-shrink:0; overflow:hidden; box-shadow:0 0 0 4px rgba(116,255,112,.08); }
        .db-welcome-title { font-size:1.1rem; font-weight:700; color:#fff; margin:0 0 4px; letter-spacing:-.01em; }
        .db-welcome-title span { color:var(--pr-lime); }
        .db-datetime { font-size:.75rem; color:rgba(255,255,255,.5); font-weight:500; }
        .db-hero-pills { display:flex; gap:7px; flex-wrap:wrap; align-items:center; }
        .db-pill { display:inline-flex; align-items:center; gap:5px; border-radius:20px; padding:3px 11px; font-size:.7rem; font-weight:700; }
        .db-pill-emergency { background:rgba(239,68,68,.22); border:1px solid rgba(239,68,68,.45); color:#fca5a5; }
        .db-pill-warn      { background:rgba(245,158,11,.18); border:1px solid rgba(245,158,11,.4);  color:#fcd34d; }
        .db-pill-ok        { background:rgba(116,255,112,.12); border:1px solid rgba(116,255,112,.28); color:rgba(255,255,255,.75); }

        /* ── Stat cards ── */
        .db-stat-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:18px; }
        .db-stat-card { border-radius:var(--pr-radius); overflow:hidden; box-shadow:var(--pr-shadow); transition:transform .2s,box-shadow .2s; }
        .db-stat-card:hover { transform:translateY(-3px); box-shadow:var(--pr-shadow-lg); }
        .db-stat-inner { padding:14px 16px 10px; display:flex; align-items:flex-start; justify-content:space-between; }
        .db-stat-label { font-size:.67rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:rgba(255,255,255,.7); margin-bottom:6px; }
        .db-stat-value { font-size:1.75rem; font-weight:800; color:#fff; line-height:1; letter-spacing:-.02em; }
        .db-stat-icon { width:38px; height:38px; border-radius:9px; background:rgba(255,255,255,.15); display:flex; align-items:center; justify-content:center; font-size:.95rem; color:#fff; flex-shrink:0; }
        .db-stat-foot { padding:7px 16px; font-size:.7rem; font-weight:600; color:rgba(255,255,255,.6); border-top:1px solid rgba(255,255,255,.12); }
        .db-blue   { background:linear-gradient(135deg,#1d4ed8,#3b82f6); }
        .db-green  { background:linear-gradient(135deg,#064e3b,#10b981); }
        .db-amber  { background:linear-gradient(135deg,#92400e,#f59e0b); }
        .db-red    { background:linear-gradient(135deg,#991b1b,#ef4444); }

        /* ── Insight cards row ── */
        .db-insight-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:18px; }
        .db-insight-card { background:var(--pr-surface); border-radius:var(--pr-radius); border:1px solid var(--pr-border); box-shadow:var(--pr-shadow); padding:13px 15px; display:flex; align-items:center; gap:13px; transition:transform .18s; }
        .db-insight-card:hover { transform:translateY(-2px); box-shadow:var(--pr-shadow-lg); }
        .db-insight-icon { width:38px; height:38px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:.85rem; flex-shrink:0; }
        .db-insight-label { font-size:.66rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--pr-sub); margin-bottom:3px; }
        .db-insight-value { font-size:1.3rem; font-weight:800; color:var(--pr-text); line-height:1; }
        .db-insight-sub { font-size:.7rem; color:var(--pr-sub); font-weight:500; margin-top:2px; }
        .db-rate-bar  { height:5px; border-radius:5px; background:var(--pr-border); margin-top:5px; overflow:hidden; }
        .db-rate-fill { height:100%; border-radius:5px; background:linear-gradient(to right,#10b981,#74ff70); }

        /* ── Pipeline ── */
        .db-pipeline { background:var(--pr-surface); border-radius:var(--pr-radius); border:1px solid var(--pr-border); box-shadow:var(--pr-shadow); margin-bottom:18px; overflow:hidden; }
        .db-pipe-header { display:flex; align-items:center; gap:10px; padding:12px 18px; background:linear-gradient(135deg,#052e22,#064e3b); }
        .db-pipe-grid { display:grid; grid-template-columns:repeat(6,1fr); }
        .db-pipe-stage { display:flex; flex-direction:column; align-items:center; padding:14px 8px 12px; border-right:1px solid var(--pr-border); position:relative; }
        .db-pipe-stage:last-child { border-right:none; }
        .db-pipe-dot { width:9px; height:9px; border-radius:50%; margin-bottom:7px; }
        .db-pipe-name { font-size:.62rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:var(--pr-sub); text-align:center; line-height:1.3; margin-bottom:5px; }
        .db-pipe-count { font-size:1.45rem; font-weight:800; line-height:1; }
        .db-pipe-avg { font-size:.65rem; color:var(--pr-sub); font-weight:500; margin-top:3px; }
        /* connector arrow between stages */
        .db-pipe-stage:not(:last-child)::after { content:'›'; position:absolute; right:-8px; top:50%; transform:translateY(-50%); font-size:.9rem; color:var(--pr-border-dark); z-index:1; }

        /* ── Alert tables (rolled back / stale) ── */
        .db-alerts-row { display:grid; grid-template-columns:3fr 3fr; gap:14px; margin-bottom:18px; }
        .db-alert-card { border-radius:var(--pr-radius); overflow:hidden; box-shadow:var(--pr-shadow); border:1px solid; }
        .db-alert-card.rb { border-color:rgba(245,158,11,.35); }
        .db-alert-card.st { border-color:rgba(239,68,68,.3); }
        .db-alert-card.rj { border-color:rgba(247, 39, 91, 0.281); }
        .db-alert-hdr.rj { background:linear-gradient(135deg,#640707,#ed3a3a); }
        .db-alert-hdr { display:flex; align-items:center; gap:9px; padding:11px 15px; }
        .db-alert-hdr.rb { background:linear-gradient(135deg,#78350f,#d97706); }
        .db-alert-hdr.st { background:linear-gradient(135deg,#991b1b,#ef4444); }
        .db-alert-hdr-icon { width:26px; height:26px; border-radius:7px; background:rgba(255,255,255,.15); display:flex; align-items:center; justify-content:center; font-size:.7rem; color:#fff; flex-shrink:0; }
        .db-alert-hdr-title { font-size:.82rem; font-weight:700; color:#fff; }
        .db-alert-hdr-badge { margin-left:auto; background:rgba(255,255,255,.2); border-radius:20px; padding:1px 9px; font-size:.67rem; font-weight:700; color:#fff; }

        /* ── Shared table style ── */
        .dbt { width:100%; border-collapse:collapse; font-family:'DM Sans',sans-serif; font-size:.79rem; }
        .dbt thead tr { background:var(--pr-surface2); border-bottom:1.5px solid var(--pr-border-dark); }
        .dbt thead th { padding:7px 11px; font-size:.63rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:var(--pr-sub); border:none; white-space:nowrap; }
        .dbt tbody tr { border-bottom:1px solid var(--pr-border); transition:background .12s; }
        .dbt tbody tr:last-child { border-bottom:none; }
        .dbt tbody tr:hover { background:var(--pr-surface2); }
        .dbt td { padding:8px 11px; border:none; vertical-align:middle; }
        .dbt-code { font-size:.74rem; font-weight:700; color:var(--pr-forest); font-family:monospace; letter-spacing:.02em; }
        .dbt-name { font-size:.79rem; font-weight:600; color:var(--pr-text); }
        .dbt-sub  { font-size:.73rem; color:var(--pr-sub); }
        .dbt-scroll { max-height:270px; overflow-y:auto; overflow-x:hidden; }
        .dbt-scroll::-webkit-scrollbar { width:4px; }
        .dbt-scroll::-webkit-scrollbar-thumb { background:var(--pr-border-dark); border-radius:4px; }
        .dbt-empty td { text-align:center; padding:20px !important; color:var(--pr-sub); font-size:.79rem; }
        .db-eye { width:25px; height:25px; border-radius:7px; background:var(--pr-lime-ghost); border:1px solid var(--pr-lime-border); display:inline-flex; align-items:center; justify-content:center; color:var(--pr-forest); font-size:.68rem; text-decoration:none; transition:all .14s; }
        .db-eye:hover { background:var(--pr-lime); color:var(--pr-forest-deep); transform:scale(1.1); }
        .db-days { display:inline-flex; align-items:center; gap:3px; font-size:.68rem; font-weight:700; border-radius:20px; padding:1px 7px; }
        .db-days.w { background:rgba(245,158,11,.12); border:1px solid rgba(245,158,11,.3); color:#92400e; }
        .db-days.d { background:rgba(239,68,68,.12); border:1px solid rgba(239,68,68,.3); color:#991b1b; }

        /* ── Pending tables grid ── */
        .db-pending-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:18px; }
        .db-table-card { background:var(--pr-surface); border-radius:var(--pr-radius); border:1px solid var(--pr-border); box-shadow:var(--pr-shadow); overflow:hidden; height:100%; transition:transform .18s; }
        .db-table-card:hover { transform:translateY(-2px); box-shadow:var(--pr-shadow-lg); }
        .db-table-hdr { display:flex; align-items:center; gap:9px; padding:12px 15px; background:linear-gradient(135deg,#052e22,#064e3b); }
        .db-table-hdr-icon { width:27px; height:27px; border-radius:7px; background:rgba(116,255,112,.12); border:1px solid rgba(116,255,112,.28); display:flex; align-items:center; justify-content:center; font-size:.7rem; color:var(--pr-lime); flex-shrink:0; }
        .db-table-hdr-title { font-size:.82rem; font-weight:700; color:#fff; }
        .db-table-hdr-count { margin-left:auto; background:rgba(116,255,112,.15); border:1px solid rgba(116,255,112,.3); border-radius:20px; padding:1px 9px; font-size:.67rem; font-weight:700; color:var(--pr-lime); }

        /* ── Charts ── */
        .db-charts-col { display:flex; flex-direction:column; gap:14px; }
        .db-chart-card { background:var(--pr-surface); border-radius:var(--pr-radius); border:1px solid var(--pr-border); box-shadow:var(--pr-shadow); overflow:hidden; }
        .db-chart-hdr { display:flex; align-items:center; gap:9px; padding:12px 15px; background:linear-gradient(135deg,#052e22,#064e3b); }
        .db-chart-body { padding:14px; }

        /* ── Session flash ── */
        .db-flash { display:flex; align-items:center; gap:8px; background:var(--pr-surface2); border:1px solid var(--pr-lime-border); border-radius:9px; padding:10px 14px; font-size:.81rem; color:var(--pr-forest); font-weight:500; margin-bottom:16px; }

        @media (max-width:1100px) { .db-stat-grid,.db-insight-grid { grid-template-columns:repeat(2,1fr); } .db-pipe-grid { grid-template-columns:repeat(3,1fr); } }
        @media (max-width:768px)  { .db-stat-grid,.db-insight-grid { grid-template-columns:1fr 1fr; } .db-pending-grid,.db-alerts-row { grid-template-columns:1fr; } .db-pipe-grid { grid-template-columns:repeat(2,1fr); } }
    </style>

    <div class="pr-page">

        {{-- ── HERO ── --}}
        <div class="db-hero">
            <div class="db-hero-inner">
                <div class="db-hero-left">
                    <div class="db-avatar">
                        @if(Auth::user()->currentProfileImage ?? false)
                            <img src="{{ Auth::user()->currentProfileImage->image_url }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <i class="fas fa-user"></i>
                        @endif
                    </div>
                    <div>
                        <div class="db-welcome-title">Welcome back, <span>{{ Auth::user()->name }}</span>!</div>
                        <div class="db-datetime" id="current-datetime">Loading…</div>
                    </div>
                </div>
                <div class="db-hero-pills">
                    @if($emergencyPending > 0)
                        <span class="db-pill db-pill-emergency">
                            <i class="fas fa-exclamation-triangle" style="font-size:.58rem;"></i>
                            {{ $emergencyPending }} Emergency {{ $emergencyPending === 1 ? 'Case' : 'Cases' }}
                        </span>
                    @endif
                    @if($rolledBackCases->count() > 0)
                        <span class="db-pill db-pill-warn">
                            <i class="fas fa-undo" style="font-size:.58rem;"></i>
                            {{ $rolledBackCases->count() }} Rolled Back
                        </span>
                    @endif
                    @if($staleCases->count() > 0)
                        <span class="db-pill db-pill-warn">
                            <i class="fas fa-clock" style="font-size:.58rem;"></i>
                            {{ $staleCases->count() }} Stale {{ $staleCases->count() === 1 ? 'Case' : 'Cases' }}
                        </span>
                    @endif
                    @if($emergencyPending === 0 && $rolledBackCases->count() === 0 && $staleCases->count() === 0 && $rejectedCases->count() === 0)
                        <span class="db-pill db-pill-ok">
                            <i class="fas fa-check-circle" style="font-size:.58rem;color:var(--pr-lime);"></i>
                            All clear — no urgent items
                        </span>
                    @endif
                </div>
            </div>
        </div>

        @if(session('status'))
            <div class="db-flash">
                <i class="fas fa-check-circle" style="color:var(--pr-lime-dim);"></i> {{ session('status') }}
            </div>
        @endif

        {{-- ── STAT CARDS ── --}}
        <div class="db-stat-grid">
            @php $statCards = [
                ['label'=>'Total Patients',   'value'=>$totalPatients,           'icon'=>'users',    'cls'=>'db-blue',  'foot'=>'All-time records'],
                ['label'=>'Burial Aid',        'value'=>$totalBurialPatient,      'icon'=>'dove',     'cls'=>'db-green', 'foot'=>'Case type'],
                ['label'=>'Educational Aid',   'value'=>$totalEducationalPatient, 'icon'=>'book',     'cls'=>'db-amber', 'foot'=>'Case type'],
                ['label'=>'Medical Aid',       'value'=>$totalMedicalPatient,     'icon'=>'hospital', 'cls'=>'db-red',   'foot'=>'Case type'],
            ]; @endphp
            @foreach($statCards as $c)
                <div class="db-stat-card {{ $c['cls'] }}">
                    <div class="db-stat-inner">
                        <div><div class="db-stat-label">{{ $c['label'] }}</div><div class="db-stat-value">{{ number_format($c['value']) }}</div></div>
                        <div class="db-stat-icon"><i class="fas fa-{{ $c['icon'] }}"></i></div>
                    </div>
                    <div class="db-stat-foot"><i class="fas fa-tag" style="font-size:.55rem;opacity:.65;margin-right:4px;"></i>{{ $c['foot'] }}</div>
                </div>
            @endforeach
        </div>

        {{-- ── INSIGHT CARDS ── --}}
        <div class="db-insight-grid">

            {{-- New Cases This Month --}}
            <div class="db-insight-card">
                <div class="db-insight-icon" style="background:rgba(59,130,246,.10);border:1px solid rgba(59,130,246,.28);color:#1d4ed8;">
                    <i class="fas fa-folder-plus"></i>
                </div>
                <div>
                    <div class="db-insight-label">New Cases This Month</div>
                    <div class="db-insight-value">{{ number_format($newCasesThisMonth) }}</div>
                    <div class="db-insight-sub">{{ now()->format('F Y') }} intake</div>
                </div>
            </div>

            {{-- Completed This Month --}}
            <div class="db-insight-card">
                <div class="db-insight-icon" style="background:rgba(116,255,112,.12);border:1px solid rgba(116,255,112,.28);color:var(--pr-forest);">
                    <i class="fas fa-check-double"></i>
                </div>
                <div>
                    <div class="db-insight-label">Disbursed This Month</div>
                    <div class="db-insight-value">{{ number_format($disbursedThisMonth) }}</div>
                    <div class="db-insight-sub">Cases fully processed</div>
                </div>
            </div>

            {{-- Approval Rate --}}
            @can('approve_patient')
            <div class="db-insight-card" style="flex-direction:column;align-items:flex-start;gap:8px;">
                <div style="display:flex;align-items:center;gap:10px;width:100%;">
                    <div class="db-insight-icon" style="background:rgba(5,150,105,.10);border:1px solid rgba(5,150,105,.28);color:#065f46;">
                        <i class="fas fa-percent"></i>
                    </div>
                    <div style="flex:1;">
                        <div class="db-insight-label">Approval Rate</div>
                        <div class="db-insight-value">{{ $approvalRate }}%</div>
                    </div>
                </div>
                <div style="width:100%;">
                    <div class="db-rate-bar"><div class="db-rate-fill" style="width:{{ $approvalRate }}%;"></div></div>
                    <div class="db-insight-sub" style="margin-top:4px;">{{ number_format($totalApprovedCount) }} approved · {{ number_format($totalRejectedCount) }} rejected</div>
                </div>
            </div>
            @else
            <div class="db-insight-card">
                <div class="db-insight-icon" style="background:rgba(139,92,246,.10);border:1px solid rgba(139,92,246,.28);color:#5b21b6;">
                    <i class="fas fa-thumbs-up"></i>
                </div>
                <div>
                    <div class="db-insight-label">Total Approved</div>
                    <div class="db-insight-value">{{ number_format($totalApproved) }}</div>
                    <div class="db-insight-sub">All-time approvals</div>
                </div>
            </div>
            @endcan

            {{-- Total Approved (for non-approval roles) / Emergency for others --}}
            <div class="db-insight-card">
                <div class="db-insight-icon" style="background:rgba(239,68,68,.10);border:1px solid rgba(239,68,68,.28);color:#991b1b;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <div class="db-insight-label">Emergency Pending</div>
                    <div class="db-insight-value" style="{{ $emergencyPending > 0 ? 'color:#ef4444;' : '' }}">
                        {{ number_format($emergencyPending) }}
                    </div>
                    <div class="db-insight-sub">Awaiting priority approval</div>
                </div>
            </div>

        </div>

        {{-- ── ALERT TABLES: ROLLED BACK + STALE ── --}}
        @if($rolledBackCases->count() > 0 || $staleCases->count() > 0 || $rejectedCases->count() > 0)
        <div class="db-alerts-row">

            @if($rolledBackCases->count() > 0)
            <div class="db-alert-card rb">
                <div class="db-alert-hdr rb">
                    <div class="db-alert-hdr-icon"><i class="fas fa-undo"></i></div>
                    <span class="db-alert-hdr-title">Rolled Back Cases</span>
                    <span class="db-alert-hdr-badge">{{ $rolledBackCases->count() }}</span>
                </div>
                <div class="dbt-scroll">
                    <table class="dbt">
                        <thead><tr><th>Code</th><th>Claimant</th><th>Returned To</th><th></th></tr></thead>
                        <tbody>
                            @foreach($rolledBackCases->take(10) as $log)
                                <tr>
                                    <td class="dbt-code">{{ $log->patient->control_number ?? 'N/A' }}</td>
                                    <td class="dbt-name">{{ $log->patient->claimant_name ?? 'Unknown' }}</td>
                                    <td class="dbt-sub" style="color:#92400e;font-weight:600;">
                                        {{ str_replace('[ROLLED BACK]','',trim($log->status)) }}
                                    </td>
                                    <td><a href="{{ route('admin.process-tracking.show', $log->patient->id) }}" class="db-eye"><i class="fas fa-eye"></i></a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if($staleCases->count() > 0)
            <div class="db-alert-card st">
                <div class="db-alert-hdr st">
                    <div class="db-alert-hdr-icon"><i class="fas fa-hourglass-half"></i></div>
                    <span class="db-alert-hdr-title">Stale Cases (stuck 7+ days)</span>
                    <span class="db-alert-hdr-badge">{{ $staleCases->count() }}</span>
                </div>
                <div class="dbt-scroll">
                    <table class="dbt">
                        <thead><tr><th>Code</th><th>Claimant</th><th>Stage</th><th>Stuck</th><th></th></tr></thead>
                        <tbody>
                            @foreach($staleCases->take(10) as $log)
                                @php $days = now()->diffInDays(\Carbon\Carbon::parse($log->status_date)); @endphp
                                <tr>
                                    <td class="dbt-code">{{ $log->patient->control_number ?? 'N/A' }}</td>
                                    <td class="dbt-name">{{ $log->patient->claimant_name ?? 'Unknown' }}</td>
                                    <td class="dbt-sub">{{ trim(str_replace('[ROLLED BACK]','',$log->status)) }}</td>
                                    <td>
                                        <span class="db-days {{ $days >= 14 ? 'd' : 'w' }}">
                                            <i class="fas fa-clock" style="font-size:.56rem;"></i> {{ $days }}d
                                        </span>
                                    </td>
                                    <td><a href="{{ route('admin.process-tracking.show', $log->patient->id) }}" class="db-eye"><i class="fas fa-eye"></i></a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if($rejectedCases->count() > 0)
            <div class="db-alert-card rj">
                <div class="db-alert-hdr rj">
                    <div class="db-alert-hdr-icon"><i class="fas fa-times-circle"></i></div>
                    <span class="db-alert-hdr-title">Rejected Cases</span>
                    <span class="db-alert-hdr-badge">{{ $rejectedCases->count() }}</span>
                </div>
                <div class="dbt-scroll">
                    <table class="dbt">
                        <thead><tr><th>Code</th><th>Claimant</th><th>Category</th><th></th></tr></thead>
                        <tbody>
                            @foreach($rejectedCases->take(10) as $log)
                                @php $daysAgo = now()->diffInDays(\Carbon\Carbon::parse($log->status_date)); @endphp
                                <tr>
                                    <td class="dbt-code">{{ $log->patient->control_number ?? 'N/A' }}</td>
                                    <td class="dbt-name">{{ $log->patient->claimant_name ?? 'Unknown' }}</td>
                                    <td class="dbt-sub">{{ $log->patient->case_category ?? 'N/A' }}</td>
                                    
                                    <td><a href="{{ route('admin.process-tracking.show', $log->patient->id) }}" class="db-eye"><i class="fas fa-eye"></i></a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>
        @endif

        {{-- ── PENDING QUEUES + CHARTS ── --}}
        <div class="db-pending-grid">

            @can('submit_patient_application')
            <div class="db-table-card">
                <div class="db-table-hdr">
                    <div class="db-table-hdr-icon"><i class="fas fa-paper-plane"></i></div>
                    <span class="db-table-hdr-title">Pending Submission</span>
                    <span class="db-table-hdr-count">{{ $recentlyDraft->count() }}</span>
                </div>
                <div class="dbt-scroll">
                    <table class="dbt">
                        <thead><tr><th>Code</th><th>Claimant</th><th>Category</th><th></th></tr></thead>
                        <tbody>
                            @forelse($recentlyDraft as $log)
                                <tr>
                                    <td class="dbt-code">{{ $log->patient->control_number ?? 'N/A' }}</td>
                                    <td class="dbt-name">{{ $log->patient->claimant_name ?? 'Unknown' }}</td>
                                    <td class="dbt-sub">{{ $log->patient->case_category ?? 'N/A' }}</td>
                                    <td><a href="{{ route('admin.patient-records.show', $log->patient->id) }}" class="db-eye"><i class="fas fa-eye"></i></a></td>
                                </tr>
                            @empty
                                <tr class="dbt-empty"><td colspan="4">
                                    <i class="fas fa-inbox" style="font-size:.9rem;opacity:.35;display:block;margin-bottom:4px;"></i>
                                    No pending submissions
                                </td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endcan

            @can('approve_patient')
            <div class="db-table-card">
                <div class="db-table-hdr">
                    <div class="db-table-hdr-icon"><i class="fas fa-thumbs-up"></i></div>
                    <span class="db-table-hdr-title">Pending Approvals</span>
                    <span class="db-table-hdr-count">{{ $recentlySubmitted->count() }}</span>
                </div>
                <div class="dbt-scroll">
                    <table class="dbt">
                        <thead><tr><th>Code</th><th>Claimant</th><th>Category</th><th></th></tr></thead>
                        <tbody>
                            @forelse($recentlySubmitted as $log)
                                <tr>
                                    <td class="dbt-code">{{ $log->patient->control_number ?? 'N/A' }}</td>
                                    <td class="dbt-name">{{ $log->patient->claimant_name ?? 'Unknown' }}</td>
                                    <td class="dbt-sub">{{ $log->patient->case_category ?? 'N/A' }}</td>
                                    <td><a href="{{ route('admin.process-tracking.show', $log->patient->id) }}" class="db-eye"><i class="fas fa-eye"></i></a></td>
                                </tr>
                            @empty
                                <tr class="dbt-empty"><td colspan="4">
                                    <i class="fas fa-inbox" style="font-size:.9rem;opacity:.35;display:block;margin-bottom:4px;"></i>
                                    No pending approvals
                                </td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endcan

            @can('budget_allocate')
            <div class="db-table-card">
                <div class="db-table-hdr">
                    <div class="db-table-hdr-icon"><i class="fas fa-wallet"></i></div>
                    <span class="db-table-hdr-title">Pending Budget Allocation</span>
                    <span class="db-table-hdr-count">{{ $recentlyApproved->count() }}</span>
                </div>
                <div class="dbt-scroll">
                    <table class="dbt">
                        <thead><tr><th>Code</th><th>Claimant</th><th>Category</th><th></th></tr></thead>
                        <tbody>
                            @forelse($recentlyApproved as $log)
                                <tr>
                                    <td class="dbt-code">{{ $log->patient->control_number ?? 'N/A' }}</td>
                                    <td class="dbt-name">{{ $log->patient->claimant_name ?? 'Unknown' }}</td>
                                    <td class="dbt-sub">{{ $log->patient->case_category ?? 'N/A' }}</td>
                                    <td><a href="{{ route('admin.process-tracking.show', $log->patient->id) }}" class="db-eye"><i class="fas fa-eye"></i></a></td>
                                </tr>
                            @empty
                                <tr class="dbt-empty"><td colspan="4">
                                    <i class="fas fa-inbox" style="font-size:.9rem;opacity:.35;display:block;margin-bottom:4px;"></i>
                                    No pending allocations
                                </td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endcan

            @can('accounting_dv_input')
            <div class="db-table-card">
                <div class="db-table-hdr">
                    <div class="db-table-hdr-icon"><i class="fas fa-file-invoice"></i></div>
                    <span class="db-table-hdr-title">Pending DV Input</span>
                    <span class="db-table-hdr-count">{{ $recentlyBudgetAllocated->count() }}</span>
                </div>
                <div class="dbt-scroll">
                    <table class="dbt">
                        <thead><tr><th>Code</th><th>Claimant</th><th>Category</th><th></th></tr></thead>
                        <tbody>
                            @forelse($recentlyBudgetAllocated as $log)
                                <tr>
                                    <td class="dbt-code">{{ $log->patient->control_number ?? 'N/A' }}</td>
                                    <td class="dbt-name">{{ $log->patient->claimant_name ?? 'Unknown' }}</td>
                                    <td class="dbt-sub">{{ $log->patient->case_category ?? 'N/A' }}</td>
                                    <td><a href="{{ route('admin.process-tracking.show', $log->patient->id) }}" class="db-eye"><i class="fas fa-eye"></i></a></td>
                                </tr>
                            @empty
                                <tr class="dbt-empty"><td colspan="4">
                                    <i class="fas fa-inbox" style="font-size:.9rem;opacity:.35;display:block;margin-bottom:4px;"></i>
                                    No pending DV input
                                </td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endcan

            @can('treasury_disburse')
            <div class="db-table-card">
                <div class="db-table-hdr">
                    <div class="db-table-hdr-icon"><i class="fas fa-coins"></i></div>
                    <span class="db-table-hdr-title">Pending Disbursement</span>
                    <span class="db-table-hdr-count">{{ $recentlyDvSubmitted->count() }}</span>
                </div>
                <div class="dbt-scroll">
                    <table class="dbt">
                        <thead><tr><th>Code</th><th>Claimant</th><th>Category</th><th></th></tr></thead>
                        <tbody>
                            @forelse($recentlyDvSubmitted as $log)
                                <tr>
                                    <td class="dbt-code">{{ $log->patient->control_number ?? 'N/A' }}</td>
                                    <td class="dbt-name">{{ $log->patient->claimant_name ?? 'Unknown' }}</td>
                                    <td class="dbt-sub">{{ $log->patient->case_category ?? 'N/A' }}</td>
                                    <td><a href="{{ route('admin.process-tracking.show', $log->patient->id) }}" class="db-eye"><i class="fas fa-eye"></i></a></td>
                                </tr>
                            @empty
                                <tr class="dbt-empty"><td colspan="4">
                                    <i class="fas fa-inbox" style="font-size:.9rem;opacity:.35;display:block;margin-bottom:4px;"></i>
                                    No pending disbursements
                                </td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endcan

            {{-- Charts column --}}
            <div class="db-charts-col">
                <div class="db-chart-card">
                    <div class="db-chart-hdr">
                        <div class="db-table-hdr-icon" style="width:27px;height:27px;border-radius:7px;background:rgba(116,255,112,.12);border:1px solid rgba(116,255,112,.28);display:flex;align-items:center;justify-content:center;font-size:.7rem;color:var(--pr-lime);flex-shrink:0;">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <span style="font-size:.82rem;font-weight:700;color:#fff;">Patients per Barangay</span>
                    </div>
                    <div class="db-chart-body">
                        <div style="position:relative;height:215px;"><canvas id="barangayChart"></canvas></div>
                    </div>
                </div>
                <div class="db-chart-card">
                    <div class="db-chart-hdr">
                        <div class="db-table-hdr-icon" style="width:27px;height:27px;border-radius:7px;background:rgba(116,255,112,.12);border:1px solid rgba(116,255,112,.28);display:flex;align-items:center;justify-content:center;font-size:.7rem;color:var(--pr-lime);flex-shrink:0;">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <span style="font-size:.82rem;font-weight:700;color:#fff;">Patients per Month</span>
                    </div>
                    <div class="db-chart-body">
                        <div style="position:relative;height:215px;"><canvas id="monthlyChart"></canvas></div>
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection

@section('scripts')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // ── DateTime ──
        function updateDateTime() {
            const now = new Date();
            document.getElementById('current-datetime').textContent =
                now.toLocaleDateString(undefined, { weekday:'long', year:'numeric', month:'long', day:'numeric' }) +
                '  ·  ' + now.toLocaleTimeString();
        }
        setInterval(updateDateTime, 1000);
        updateDateTime();

        // ── Palette ──
        const palette = ['#064e3b','#10b981','#34d399','#6ee7b7','#3b82f6','#f59e0b','#ef4444','#8b5cf6','#06b6d4','#ec4899','#84cc16','#f97316'];

        // ── Barangay doughnut ──
        new Chart(document.getElementById('barangayChart'), {
            type: 'doughnut',
            data: {
                labels: @json($barangayLabels),
                datasets: [{ data: @json($barangayData), backgroundColor: palette, borderWidth: 2, borderColor: '#fff' }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '62%',
                plugins: { legend: { position:'bottom', labels:{ font:{ family:'DM Sans', size:11 }, padding:12, boxWidth:12, color:'#052e22' } } }
            }
        });

        // ── Monthly line ──
        new Chart(document.getElementById('monthlyChart'), {
            type: 'line',
            data: {
                labels: @json($monthlyLabels),
                datasets: [{
                    label: 'Patients', data: @json($monthlyData),
                    borderColor: '#064e3b', backgroundColor: 'rgba(6,78,59,.08)',
                    pointBackgroundColor: '#74ff70', pointBorderColor: '#064e3b',
                    pointBorderWidth: 2, pointRadius: 4, tension: 0.4, fill: true, borderWidth: 2
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid:{ color:'rgba(6,78,59,.06)' }, ticks:{ font:{ family:'DM Sans', size:11 }, color:'#3d7a62' } },
                    y: { beginAtZero: true, grid:{ color:'rgba(6,78,59,.06)' }, ticks:{ font:{ family:'DM Sans', size:11 }, color:'#3d7a62' } }
                }
            }
        });
    </script>
@endsection