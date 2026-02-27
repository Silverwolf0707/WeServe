@extends('layouts.admin')
@section('content')
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

    <style>
        :root {
            --pr-forest: #064e3b; --pr-forest-deep: #052e22; --pr-forest-mid: #065f46;
            --pr-lime: #74ff70; --pr-lime-dim: #52e84e;
            --pr-lime-ghost: rgba(116,255,112,.10); --pr-lime-border: rgba(116,255,112,.30);
            --pr-surface: #ffffff; --pr-surface2: #f0fdf4; --pr-muted: #ecfdf5;
            --pr-border: #d1fae5; --pr-border-dark: #a7f3d0;
            --pr-text: #052e22; --pr-sub: #3d7a62; --pr-danger: #ef4444;
            --pr-radius: 12px;
            --pr-shadow: 0 2px 8px rgba(6,78,59,.08), 0 8px 24px rgba(6,78,59,.06);
            --pr-shadow-lg: 0 4px 24px rgba(6,78,59,.16), 0 16px 48px rgba(6,78,59,.10);
            --pr-shadow-lime: 0 2px 12px rgba(116,255,112,.25);
        }
        .pr-page { font-family:'DM Sans',sans-serif; color:var(--pr-text);}

        /* hero */
        .pr-hero { background:linear-gradient(135deg,#052e22 0%,#064e3b 55%,#065f46 100%); border-radius:var(--pr-radius); padding:22px 28px; margin-bottom:16px; position:relative;box-shadow:var(--pr-shadow-lg); }
        .pr-hero::before { content:''; position:absolute; inset:0; border-radius:var(--pr-radius); background:radial-gradient(ellipse 380px 200px at 95% 50%,rgba(116,255,112,.13) 0%,transparent 65%),radial-gradient(ellipse 180px 100px at 5% 80%,rgba(116,255,112,.07) 0%,transparent 70%),radial-gradient(ellipse 250px 120px at 50% -20%,rgba(255,255,255,.04) 0%,transparent 60%); pointer-events:none; z-index:0; }
        .pr-hero::after { content:''; position:absolute; top:0; left:28px; right:28px; height:2px; background:linear-gradient(to right,transparent,var(--pr-lime),transparent); border-radius:2px; opacity:.55; }
        .pr-hero-inner { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:14px; position:relative; z-index:1; }
        .pr-hero-left { display:flex; align-items:center; gap:16px; }
        .pr-hero-icon { width:46px; height:46px; background:rgba(116,255,112,.12); border:1px solid rgba(116,255,112,.30); border-radius:11px; display:flex; align-items:center; justify-content:center; font-size:1.15rem; color:var(--pr-lime); backdrop-filter:blur(4px); flex-shrink:0; }
        .pr-hero-title { font-size:1.18rem; font-weight:700; color:#fff; letter-spacing:-.01em; margin:0 0 3px; line-height:1.2; }
        .pr-hero-meta { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
        .pr-badge { display:inline-flex; align-items:center; gap:4px; border-radius:20px; font-size:.72rem; font-weight:600; padding:2px 10px; letter-spacing:.03em; line-height:1.6; }
        .pr-badge-count { background:rgba(116,255,112,.14); border:1px solid rgba(116,255,112,.32); color:var(--pr-lime); }

        /* filter pills (multi-toggle) */
        .pr-filter-pills { display:flex; align-items:center; gap:5px; flex-wrap:wrap; }
        .pr-filter-pill { display:inline-flex; align-items:center; gap:5px; border-radius:20px; font-size:.71rem; font-weight:600; padding:3px 11px; border:1px solid rgba(116,255,112,.25); background:rgba(116,255,112,.08); color:rgba(255,255,255,.75); cursor:pointer; transition:background .18s,border-color .18s,color .18s; text-decoration:none; white-space:nowrap; user-select:none; }
        .pr-filter-pill:hover { background:rgba(116,255,112,.18); border-color:rgba(116,255,112,.5); color:#fff; }
        .pr-filter-pill.active { background:var(--pr-lime); border-color:var(--pr-lime); color:var(--pr-forest); font-weight:700; }
        /* role-hint: subtle indicator that this pill belongs to user's role queue */
        .pr-filter-pill.role-hint { border-color:rgba(116,255,112,.45); background:rgba(116,255,112,.12); }
        /* role-default "My Queue" pill */
        .pr-filter-pill.role-default { background:rgba(116,255,112,.20); border-color:rgba(116,255,112,.55); color:#fff; font-weight:700; }
        .pr-filter-pill.role-default:hover { background:rgba(116,255,112,.32); border-color:rgba(116,255,112,.75); }
        .pr-filter-pill.role-default.active { background:var(--pr-lime); border-color:var(--pr-lime); color:var(--pr-forest); }
        /* separator between pill groups */
        .pr-pill-sep { width:1px; height:18px; background:rgba(116,255,112,.25); flex-shrink:0; align-self:center; }

        /* table card */
        .pr-table-card { background:var(--pr-surface); border-radius:var(--pr-radius); border:1px solid var(--pr-border); box-shadow:var(--pr-shadow); overflow:hidden; }

        /* toolbar */
        .pr-dt-toolbar { display:flex; align-items:center; justify-content:space-between; padding:10px 16px; border-bottom:1px solid var(--pr-border); background:var(--pr-surface2); flex-wrap:wrap; gap:8px; }
        .pr-dt-toolbar-left { display:flex; align-items:center; gap:6px; flex-wrap:wrap; }
        .pr-dt-toolbar-right { display:flex; align-items:center; gap:8px; flex-shrink:0; }
        .pr-toolbar-search { position:relative; display:flex; align-items:center; }
        .pr-toolbar-search .si { position:absolute; left:10px; color:var(--pr-sub); font-size:.76rem; pointer-events:none; }
        .pr-toolbar-search-input { border:1.5px solid var(--pr-border-dark); border-radius:8px; padding:6px 32px 6px 30px; font-size:.78rem; font-family:'DM Sans',sans-serif; width:240px; color:var(--pr-text); background:var(--pr-surface); transition:border-color .2s,box-shadow .2s; }
        .pr-toolbar-search-input:focus { outline:none; border-color:var(--pr-forest-mid); box-shadow:0 0 0 3px rgba(6,78,59,.12); }
        .pr-toolbar-search-input::placeholder { color:var(--pr-border-dark); }
        .pr-toolbar-search-clear { position:absolute; right:8px; background:none; border:none; color:var(--pr-border-dark); font-size:.7rem; cursor:pointer; padding:2px 3px; line-height:1; transition:color .15s; display:none; }
        .pr-toolbar-search-clear.visible { display:flex; align-items:center; }
        .pr-toolbar-search-clear:hover { color:var(--pr-danger); }
        .pr-toolbar-date { position:relative; display:flex; align-items:center; }
        .pr-toolbar-date .di { position:absolute; left:10px; color:var(--pr-sub); font-size:.76rem; pointer-events:none; z-index:1; }
        .pr-toolbar-date-input { border:1.5px solid var(--pr-border-dark); border-radius:8px; padding:6px 32px 6px 30px; font-size:.78rem; font-family:'DM Sans',sans-serif; width:158px; color:var(--pr-text); background:var(--pr-surface); transition:border-color .2s,box-shadow .2s; cursor:pointer; }
        .pr-toolbar-date-input:focus { outline:none; border-color:var(--pr-forest-mid); box-shadow:0 0 0 3px rgba(6,78,59,.12); }
        .pr-toolbar-date-input::-webkit-calendar-picker-indicator { opacity:0; position:absolute; right:0; width:100%; cursor:pointer; }
        .pr-toolbar-date-clear { position:absolute; right:8px; background:none; border:none; color:var(--pr-border-dark); font-size:.7rem; cursor:pointer; padding:2px 3px; line-height:1; transition:color .15s; display:none; z-index:2; }
        .pr-toolbar-date-clear.visible { display:flex; align-items:center; }
        .pr-toolbar-date-clear:hover { color:var(--pr-danger); }
        .pr-toolbar-date-input.has-value { border-color:var(--pr-forest-mid); background:var(--pr-surface2); padding-right:28px; }
        .pr-record-indicator { display:inline-flex; align-items:center; gap:6px; background:var(--pr-lime-ghost); border:1px solid var(--pr-lime-border); border-radius:20px; padding:4px 12px; font-size:.72rem; font-weight:600; color:var(--pr-forest); white-space:nowrap; font-family:'DM Sans',sans-serif; transition:background .2s,border-color .2s; }
        .pr-record-indicator .ri-dot { width:5px; height:5px; border-radius:50%; background:var(--pr-lime-dim); flex-shrink:0; transition:background .2s; }

        /* table */
        .pr-table-card .table { margin:0; font-family:'DM Sans',sans-serif; font-size:.84rem; }
        .pr-table-card .table thead tr { background:var(--pr-forest); border-bottom:1.5px solid var(--pr-forest-mid); }
        .pr-table-card .table thead th { font-size:.69rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:#ffffff; padding:10px 14px; border:none; white-space:nowrap; }
        .pr-table-card .table tbody tr { border-bottom:1px solid var(--pr-border); transition:background .15s; }
        .pr-table-card .table tbody tr:last-child { border-bottom:none; }
        .pr-table-card .table tbody tr:hover { background:var(--pr-surface2); }
        .pr-table-card .table tbody td { padding:11px 14px; border:none; color:var(--pr-text); vertical-align:middle; }
        .pr-control-cell { font-size:.82rem; font-weight:700; color:var(--pr-forest); letter-spacing:.01em; }
        .pr-name-cell { font-size:.82rem; font-weight:600; color:var(--pr-text); }
        .pr-sub-cell { font-size:.78rem; color:var(--pr-sub); }
        .pr-date-cell { font-size:.78rem; font-weight:500; color:var(--pr-text); white-space:nowrap; }
        .pr-date-cell small { display:block; font-size:.7rem; color:var(--pr-sub); font-weight:400; }
        .pr-dept-badge { display:inline-flex; align-items:center; gap:5px; background:rgba(6,78,59,.07); border:1px solid var(--pr-border-dark); border-radius:20px; padding:2px 10px; font-size:.71rem; font-weight:600; color:var(--pr-forest); white-space:nowrap; }
        .pr-dept-badge.completed { background:var(--pr-lime-ghost); border-color:var(--pr-lime-border); color:var(--pr-forest-mid); }

        /* status badges */
        .pt-status { display:inline-flex; align-items:center; gap:6px; border-radius:20px; padding:4px 12px; font-size:.74rem; font-weight:700; white-space:nowrap; line-height:1.4; }
        .pt-status i { font-size:.72rem; }
        .pt-s-processing   { background:rgba(107,114,128,.12); color:#374151; border:1px solid rgba(107,114,128,.28); }
        .pt-s-submitted    { background:rgba(59,130,246,.12);  color:#1d4ed8; border:1px solid rgba(59,130,246,.28); }
        .pt-s-emergency    { background:rgba(239,68,68,.12);   color:#b91c1c; border:1px solid rgba(239,68,68,.28); }
        .pt-s-approved     { background:rgba(5,150,105,.12);   color:#065f46; border:1px solid rgba(5,150,105,.28); }
        .pt-s-rejected     { background:rgba(239,68,68,.12);   color:#b91c1c; border:1px solid rgba(239,68,68,.28); }
        .pt-s-budget       { background:rgba(245,158,11,.12);  color:#92400e; border:1px solid rgba(245,158,11,.28); }
        .pt-s-dv           { background:rgba(6,182,212,.12);   color:#0e7490; border:1px solid rgba(6,182,212,.28); }
        .pt-s-ready        { background:rgba(139,92,246,.12);  color:#5b21b6; border:1px solid rgba(139,92,246,.28); }
        .pt-s-disbursed    { background:rgba(124,58,237,.12);  color:#4c1d95; border:1px solid rgba(124,58,237,.28); }
        .pt-s-rollback { font-size:.62rem; padding:1px 6px; border-radius:10px; margin-left:3px; vertical-align:middle; background:rgba(245,158,11,.12); color:#78350f; border:1px solid rgba(245,158,11,.3); }

        /* action wrap */
        .pr-action-wrap { display:flex; align-items:center; gap:8px; }
        .pr-action-wrap a, .pr-action-wrap button { display:inline-flex; align-items:center; justify-content:center; width:30px; height:30px; border-radius:7px; border:1px solid var(--pr-border-dark); background:var(--pr-surface); color:var(--pr-sub); font-size:.78rem; text-decoration:none; cursor:pointer; transition:all .18s; flex-shrink:0; }
        .pr-action-wrap a:hover { border-color:var(--pr-forest); background:var(--pr-lime-ghost); color:var(--pr-forest); }

        /* hide DT native pagination/info */
        .pr-table-card .dataTables_wrapper .dataTables_paginate,
        .pr-table-card .dataTables_wrapper .dataTables_info { display:none !important; }
        .dataTables_wrapper .row { margin:0 !important; }
        .pr-table-card .table-responsive { padding:0 !important; margin:0 !important; }

        /* pagination footer */
        .pr-pagination-wrap { padding:11px 16px; border-top:1px solid var(--pr-border); background:var(--pr-surface2); display:grid; grid-template-columns:1fr auto 1fr; align-items:center; gap:10px; }
        .pr-pagination-info { font-size:.74rem; font-weight:500; color:var(--pr-sub); font-family:'DM Sans',sans-serif; white-space:nowrap; }
        .pr-pagination-info strong { font-weight:700; color:var(--pr-forest); }
        .pr-pagination-wrap .pagination { gap:3px; margin:0; justify-content:center; flex-wrap:wrap; }
        .pr-pagination-wrap .page-link { border-radius:7px !important; border:1px solid var(--pr-border-dark) !important; color:var(--pr-text) !important; font-size:.78rem !important; font-family:'DM Sans',sans-serif !important; padding:5px 11px !important; transition:background .15s,color .15s; cursor:pointer; background:var(--pr-surface); user-select:none; }
        .pr-pagination-wrap .page-item.active .page-link { background:var(--pr-forest) !important; border-color:var(--pr-forest) !important; color:var(--pr-lime) !important; font-weight:700; }
        .pr-pagination-wrap .page-item.disabled .page-link { opacity:.45; cursor:default; pointer-events:none; }
        .pr-pagination-wrap .page-link:hover:not(.active) { background:var(--pr-muted) !important; color:var(--pr-forest) !important; }
        .pr-pagination-jump { display:flex; align-items:center; gap:6px; justify-content:flex-end; font-size:.74rem; font-weight:500; color:var(--pr-sub); font-family:'DM Sans',sans-serif; }
        .pr-pagination-jump input { width:50px; border:1.5px solid var(--pr-border-dark); border-radius:7px; padding:4px 7px; font-size:.74rem; font-family:'DM Sans',sans-serif; color:var(--pr-text); text-align:center; background:var(--pr-surface); transition:border-color .2s,box-shadow .2s; }
        .pr-pagination-jump input:focus { outline:none; border-color:var(--pr-forest-mid); box-shadow:0 0 0 3px rgba(6,78,59,.10); }
        .pr-pagination-jump button { background:var(--pr-forest); color:var(--pr-lime); border:none; border-radius:7px; padding:4px 10px; font-size:.72rem; font-weight:700; font-family:'DM Sans',sans-serif; cursor:pointer; transition:background .18s; }
        .pr-pagination-jump button:hover { background:var(--pr-forest-mid); }
        .pr-table-card .dt-buttons .btn { border-radius:7px !important; font-size:.78rem !important; font-family:'DM Sans',sans-serif !important; font-weight:600 !important; padding:5px 14px !important; transition:opacity .18s,transform .15s !important; background-image:none !important; }
        .pr-table-card .dt-buttons .btn:hover { opacity:.88; transform:translateY(-1px); }

        @media (max-width:640px) { .pr-pagination-wrap { grid-template-columns:1fr; justify-items:center; } .pr-pagination-info,.pr-pagination-jump { display:none; } }

        /* hero export button */
        .pr-hero-export-btn { display:inline-flex; align-items:center; gap:6px; background:rgba(255,255,255,.08); color:rgba(255,255,255,.82); border:1px solid rgba(255,255,255,.18); border-radius:8px; padding:7px 14px; font-size:.8rem; font-weight:500; font-family:'DM Sans',sans-serif; cursor:pointer; transition:all .18s; white-space:nowrap; }
        .pr-hero-export-btn:hover { background:rgba(116,255,112,.12); border-color:rgba(116,255,112,.35); color:var(--pr-lime); }
        .pr-export-menu { border-radius:10px !important; border:1px solid var(--pr-border-dark) !important; padding:6px !important; min-width:180px; font-family:'DM Sans',sans-serif; box-shadow:var(--pr-shadow-lg) !important; }
        .pr-export-menu .dropdown-item { border-radius:7px; font-size:.8rem; font-weight:500; padding:8px 12px; display:flex; align-items:center; gap:8px; color:var(--pr-text); transition:background .15s; }
        .pr-export-menu .dropdown-item:hover { background:var(--pr-muted); color:var(--pr-forest); }
        @media (max-width:768px) { .pr-hero-inner { flex-direction:column; align-items:flex-start; } .pr-hero { padding:16px 18px; } .pr-dt-toolbar { flex-direction:column; align-items:flex-start; } .pr-dt-toolbar-right { width:100%; } .pr-toolbar-search-input { width:100%; } }
        @keyframes pr-rt-flash-kf {
    0%   { background: rgba(116, 255, 112, .35) !important; }
    60%  { background: rgba(116, 255, 112, .12) !important; }
    100% { background: transparent !important; }
}
.pr-rt-flash {
    animation: pr-rt-flash-kf 2.5s ease-out forwards !important;
}
    </style>

    <div class="pr-page">

    {{-- HERO --}}
    <div class="pr-hero">
        {{-- TOP ROW: icon + title/meta + export --}}
        <div class="pr-hero-inner" style="margin-bottom:14px;">
            <div class="pr-hero-left">
                <div class="pr-hero-icon"><i class="fas fa-tasks"></i></div>
                <div>
                    <div class="pr-hero-title">Process Tracking</div>
                    <div class="pr-hero-meta">
                        <span class="pr-badge pr-badge-count">
                            {{ $patients->total() }} {{ $patients->total() === 1 ? 'record' : 'records' }}
                        </span>
                        @if($searchTerm)
                            <span class="pr-badge" style="background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);color:rgba(255,255,255,.8);">
                                <i class="fas fa-search" style="font-size:.6rem;"></i> "{{ $searchTerm }}"
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Export — rightmost of hero, aligned with title --}}
            {{-- <div class="dropdown" style="flex-shrink:0;margin-left:auto;">
                <button class="pr-hero-export-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-file-export" style="font-size:.72rem;"></i>
                    Export
                    <i class="fas fa-chevron-down" style="font-size:.58rem;opacity:.7;"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end pr-export-menu">
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.process-tracking.export', array_merge(request()->all(), ['format' => 'csv'])) }}">
                            <i class="fas fa-file-csv" style="color:#52e84e;width:14px;"></i> Export as CSV
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.process-tracking.export', array_merge(request()->all(), ['format' => 'excel'])) }}">
                            <i class="fas fa-file-excel" style="color:#52e84e;width:14px;"></i> Export as Excel
                        </a>
                    </li>
                </ul>
            </div> --}}
        </div>

        {{-- BOTTOM ROW: filter pills (full width under title) --}}
        <div style="position:relative;z-index:1;">
            {{-- FILTER PILLS (multi-toggle) --}}
            @php
                // Active filters come in as filters[] array param
                $activeFilters = request()->has('filters')
                    ? (array) request('filters')
                    : [];

                $userRole = Auth::user()->roles->pluck('title')->first();

                // Role → statuses that get priority sort (float to top)
                $roleStatuses = [
                    'CSWD Office'       => ['Processing', 'Rejected'],
                    'Mayors Office'     => ['Submitted', 'Submitted[Emergency]'],
                    'Budget Office'     => ['Approved'],
                    'Accounting Office' => ['Budget Allocated'],
                    'Treasury Office'   => ['DV Submitted'],
                ];
                $myStatuses = $roleStatuses[$userRole] ?? [];

                // If no filters set yet and user has a role → auto-select their statuses
                $isFirstVisit = !request()->has('filters') && !request()->has('_visited');

                $statusPills = [
                    'Processing'             => ['label'=>'Processing', 'icon'=>'fa-spinner'],
                    'Submitted'              => ['label'=>'Submitted',  'icon'=>'fa-paper-plane'],
                    'Submitted[Emergency]'   => ['label'=>'Emergency',  'icon'=>'fa-exclamation-triangle'],
                    'Approved'               => ['label'=>'Approved',   'icon'=>'fa-thumbs-up'],
                    'Rejected'               => ['label'=>'Rejected',   'icon'=>'fa-ban'],
                    'Budget Allocated'       => ['label'=>'Budget',     'icon'=>'fa-money-bill-wave'],
                    'DV Submitted'           => ['label'=>'DV',         'icon'=>'fa-file'],
                    'Ready for Disbursement' => ['label'=>'Ready',      'icon'=>'fa-check-circle'],
                    'Disbursed'              => ['label'=>'Disbursed',  'icon'=>'fa-coins'],
                ];
            @endphp

            <div class="pr-filter-pills" id="prFilterPills">
                {{-- All pill (clears filters) --}}
                <a href="{{ route('admin.process-tracking.index', array_merge(request()->except('filters','page'), ['_visited'=>1])) }}"
                   class="pr-filter-pill {{ empty($activeFilters) ? 'active' : '' }}"
                   title="Show all">
                    <i class="fas fa-list" style="font-size:.65rem;"></i> All
                </a>

                @if(count($myStatuses) > 0)
                    <span class="pr-pill-sep"></span>
                    {{-- My Queue pill: toggles all role statuses at once --}}
                    @php
                        $myQueueActive = !empty($activeFilters) && count(array_diff($myStatuses, $activeFilters)) === 0 && count(array_diff($activeFilters, $myStatuses)) === 0;
                        $myQueueUrl = route('admin.process-tracking.index', array_merge(
                            request()->except('filters','page'),
                            ['_visited'=>1, 'filters' => $myStatuses]
                        ));
                    @endphp
                    <a href="{{ $myQueueUrl }}"
                       class="pr-filter-pill role-default {{ $myQueueActive ? 'active' : '' }}"
                       title="My priority queue">
                        <i class="fas fa-inbox" style="font-size:.65rem;"></i>
                        My Queue
                    </a>
                    <span class="pr-pill-sep"></span>
                @endif

                {{-- Individual toggle pills --}}
                @foreach($statusPills as $filterVal => $meta)
                    @php
                        $isActive = in_array($filterVal, $activeFilters);
                        // Toggle: if active → remove it, if inactive → add it
                        $newFilters = $isActive
                            ? array_values(array_diff($activeFilters, [$filterVal]))
                            : array_merge($activeFilters, [$filterVal]);
                        $pillUrl = route('admin.process-tracking.index', array_merge(
                            request()->except('filters','page'),
                            ['_visited'=>1],
                            count($newFilters) ? ['filters' => $newFilters] : []
                        ));
                        $isMyStatus = in_array($filterVal, $myStatuses);
                    @endphp
                    <a href="{{ $pillUrl }}"
                       class="pr-filter-pill {{ $isActive ? 'active' : '' }} {{ $isMyStatus && !$isActive ? 'role-hint' : '' }}"
                       title="{{ $isActive ? 'Remove filter' : 'Add filter' }}">
                        <i class="fas {{ $meta['icon'] }}" style="font-size:.65rem;"></i>
                        {{ $meta['label'] }}
                        @if($isMyStatus && !$isActive)
                            <span style="width:5px;height:5px;border-radius:50%;background:rgba(116,255,112,.7);display:inline-block;flex-shrink:0;"></span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>{{-- /bottom row pills --}}

        </div>
    </div>

    {{-- ADV FILTER BAR --}}
    @php
        $dateFrom   = request('date_from', '');
        $searchTerm = request('search', '');
    @endphp
    <div style="background:var(--pr-surface2);border-radius:var(--pr-radius);border:1px solid var(--pr-border);padding:12px 18px;margin-bottom:16px;box-shadow:0 1px 4px rgba(6,78,59,.05);">
        <form method="GET" action="{{ route('admin.process-tracking.index') }}" id="ptAdvFilterForm">
            @if(request('filters'))
                @foreach(request('filters') as $filter)
                    <input type="hidden" name="filters[]" value="{{ $filter }}">
                @endforeach
            @endif
            @if(request('_visited'))<input type="hidden" name="_visited" value="1">@endif
            <div style="display:flex;align-items:flex-end;flex-wrap:wrap;gap:10px;">
                <div style="display:flex;flex-direction:column;gap:4px;min-width:160px;flex:1 1 160px;">
                    <span style="font-size:.68rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--pr-sub);">
                        <i class="fas fa-calendar-alt me-1"></i>Date Processed
                    </span>
                    <input type="date" name="date_from"
                           style="border:1.5px solid var(--pr-border-dark);border-radius:8px;padding:6px 10px;font-size:.8rem;font-family:'DM Sans',sans-serif;color:var(--pr-text);background:{{ $dateFrom ? 'var(--pr-lime-ghost)' : 'var(--pr-surface)' }};transition:border-color .2s,box-shadow .2s;width:100%;{{ $dateFrom ? 'border-color:var(--pr-forest);color:var(--pr-forest);font-weight:600;' : '' }}"
                           value="{{ $dateFrom }}"
                           onchange="this.style.background=this.value?'var(--pr-lime-ghost)':'var(--pr-surface)';this.style.borderColor=this.value?'var(--pr-forest)':'';this.style.color=this.value?'var(--pr-forest)':'';this.style.fontWeight=this.value?'600':'';">
                </div>
                <div style="display:flex;flex-direction:column;gap:4px;flex:2 1 220px;">
                    <span style="font-size:.68rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--pr-sub);">
                        <i class="fas fa-search me-1"></i>Search
                    </span>
                    <div style="position:relative;display:flex;align-items:center;">
                        <i class="fas fa-search" style="position:absolute;left:10px;color:var(--pr-sub);font-size:.76rem;pointer-events:none;"></i>
                        <input type="text" name="search"
                               style="border:1.5px solid var(--pr-border-dark);border-radius:8px;padding:6px 30px 6px 30px;font-size:.8rem;font-family:'DM Sans',sans-serif;color:var(--pr-text);background:{{ $searchTerm ? 'var(--pr-lime-ghost)' : 'var(--pr-surface)' }};transition:border-color .2s,box-shadow .2s;width:100%;{{ $searchTerm ? 'border-color:var(--pr-forest);color:var(--pr-forest);font-weight:600;' : '' }}"
                               value="{{ $searchTerm }}"
                               placeholder="Search records…"
                               oninput="this.style.background=this.value?'var(--pr-lime-ghost)':'var(--pr-surface)';this.style.borderColor=this.value?'var(--pr-forest)':'';this.style.color=this.value?'var(--pr-forest)':'';this.style.fontWeight=this.value?'600':'';">
                        @if($searchTerm)
                            <a href="{{ route('admin.process-tracking.index', array_filter(array_merge(request()->except('search','page'), request()->only(['filters','_visited','date_from'])))) }}"
                               style="position:absolute;right:8px;color:var(--pr-border-dark);font-size:.72rem;text-decoration:none;line-height:1;padding:2px 3px;transition:color .15s;"
                               onmouseover="this.style.color='var(--pr-danger)'" onmouseout="this.style.color='var(--pr-border-dark)'"
                               title="Clear search"><i class="fas fa-times"></i></a>
                        @endif
                    </div>
                </div>
                <div style="display:flex;align-items:flex-end;gap:6px;flex-shrink:0;padding-bottom:1px;">
                    <button type="submit" style="display:inline-flex;align-items:center;gap:6px;background:var(--pr-forest);color:var(--pr-lime);border:none;border-radius:8px;padding:7px 16px;font-size:.8rem;font-weight:700;font-family:'DM Sans',sans-serif;cursor:pointer;transition:background .18s,transform .15s;white-space:nowrap;box-shadow:0 2px 8px rgba(6,78,59,.25);"
                            onmouseover="this.style.background='var(--pr-forest-mid)';this.style.transform='translateY(-1px)'"
                            onmouseout="this.style.background='var(--pr-forest)';this.style.transform=''">
                        <i class="fas fa-filter" style="font-size:.72rem;"></i> Apply
                    </button>
                    @if($dateFrom || $searchTerm || !empty($activeFilters))
                        <a href="{{ route('admin.process-tracking.index', request()->only(['_visited'])) }}"
                           style="display:inline-flex;align-items:center;gap:6px;background:var(--pr-muted);color:var(--pr-sub);border:1px solid var(--pr-border-dark);border-radius:8px;padding:7px 13px;font-size:.8rem;font-weight:600;font-family:'DM Sans',sans-serif;cursor:pointer;transition:background .18s;white-space:nowrap;text-decoration:none;"
                           onmouseover="this.style.background='var(--pr-border-dark)';this.style.color='var(--pr-text)'"
                           onmouseout="this.style.background='var(--pr-muted)';this.style.color='var(--pr-sub)'">
                            <i class="fas fa-times" style="font-size:.72rem;"></i> Clear
                        </a>
                    @endif
                </div>
            </div>
            @if($dateFrom || !empty($activeFilters))
                <div style="display:flex;flex-wrap:wrap;gap:6px;margin-top:10px;padding-top:10px;border-top:1px solid var(--pr-border);">
                    @if($dateFrom)
                        <div style="display:inline-flex;align-items:center;gap:5px;background:var(--pr-lime-ghost);border:1px solid var(--pr-lime-border);border-radius:20px;padding:3px 10px;font-size:.73rem;font-weight:600;color:var(--pr-forest);">
                            <span style="color:var(--pr-sub);font-weight:500;">Date:</span> {{ \Carbon\Carbon::parse($dateFrom)->format('M j, Y') }}
                            <a href="{{ route('admin.process-tracking.index', array_filter(array_merge(request()->except('date_from','page'), request()->only(['filters','_visited'])))) }}" style="color:var(--pr-border-dark);text-decoration:none;font-size:.7rem;line-height:1;margin-left:2px;" onmouseover="this.style.color='var(--pr-danger)'" onmouseout="this.style.color='var(--pr-border-dark)'"><i class="fas fa-times"></i></a>
                        </div>
                    @endif
                    @foreach($activeFilters as $filter)
                        @php $filterLabel = $statusPills[$filter]['label'] ?? $filter; $newFilters = array_values(array_diff($activeFilters, [$filter])); @endphp
                        <div style="display:inline-flex;align-items:center;gap:5px;background:var(--pr-lime-ghost);border:1px solid var(--pr-lime-border);border-radius:20px;padding:3px 10px;font-size:.73rem;font-weight:600;color:var(--pr-forest);">
                            <span style="color:var(--pr-sub);font-weight:500;">Status:</span> {{ $filterLabel }}
                            <a href="{{ route('admin.process-tracking.index', array_filter(array_merge(request()->except('filters','page'), $newFilters ? ['filters' => $newFilters] : [], request()->only(['_visited','date_from'])))) }}" style="color:var(--pr-border-dark);text-decoration:none;font-size:.7rem;line-height:1;margin-left:2px;" onmouseover="this.style.color='var(--pr-danger)'" onmouseout="this.style.color='var(--pr-border-dark)'"><i class="fas fa-times"></i></a>
                        </div>
                    @endforeach
                </div>
            @endif
        </form>
    </div>

    {{-- TABLE --}}
    <div class="pr-table-card">
        <div class="table-responsive">
            <table class="table datatable datatable-ProcessTracking" style="width:100%">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>Control #</th>
                        <th>Date Processed</th>
                        <th>Claimant</th>
                        <th>Case Worker</th>
                        <th>Status</th>
                        <th>Department</th>
                        <th class="text-center" width="60">Actions</th>
                        <th style="display:none;">SortPriority</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patients as $patient)
                        @php
                            $currentStatus = $patient->latestStatusLog->status ?? 'Submitted';
                            $isRollback    = str_contains($currentStatus, '[ROLLED BACK]');
                            $baseStatus    = $isRollback ? trim(str_replace('[ROLLED BACK]', '', $currentStatus)) : $currentStatus;
                            $statusMap = ['Submitted'=>"Mayor's Office",'Submitted[Emergency]'=>"Mayor's Office",'Approved'=>'Budget Office','Rejected'=>'CSWD Office','Budget Allocated'=>'Accounting Office','DV Submitted'=>'Treasury Office','Disbursed'=>'Completed','Ready for Disbursement'=>'Treasury Office','Processing'=>'CSWD Office','Draft'=>'CSWD Office'];
                            $department  = $statusMap[str_replace('[ROLLED BACK]','',$baseStatus)] ?? 'N/A';
                            $statusClass = match($baseStatus) {
                                'Processing'=>'pt-s-processing','Submitted'=>'pt-s-submitted','Submitted[Emergency]'=>'pt-s-emergency',
                                'Approved'=>'pt-s-approved','Rejected'=>'pt-s-rejected','Budget Allocated'=>'pt-s-budget',
                                'DV Submitted'=>'pt-s-dv','Ready for Disbursement'=>'pt-s-ready','Disbursed'=>'pt-s-disbursed',
                                default=>'pt-s-processing'
                            };
                            $statusIcon = match($baseStatus) {
                                'Processing'=>'fa-spinner','Submitted'=>'fa-paper-plane','Submitted[Emergency]'=>'fa-exclamation-triangle',
                                'Approved'=>'fa-thumbs-up','Rejected'=>'fa-ban','Budget Allocated'=>'fa-money-bill-wave',
                                'DV Submitted'=>'fa-file','Ready for Disbursement'=>'fa-check-circle','Disbursed'=>'fa-coins',
                                default=>'fa-question-circle'
                            };
                            $dateProcessed = \Carbon\Carbon::parse($patient->date_processed);

                            // Priority sort: role-relevant statuses (incl. ROLLED BACK) get 0, all others get 1
                            $roleStatuses = [
                                'CSWD Office'       => ['Processing','Rejected'],
                                'Mayors Office'     => ['Submitted','Submitted[Emergency]'],
                                'Budget Office'     => ['Approved'],
                                'Accounting Office' => ['Budget Allocated'],
                                'Treasury Office'   => ['DV Submitted'],
                            ];
                            $myRoleStatuses = $roleStatuses[Auth::user()->roles->pluck('title')->first() ?? ''] ?? [];
                            $sortPriority = (in_array($baseStatus, $myRoleStatuses) || in_array($currentStatus, $myRoleStatuses)) ? 0 : 1;
                        @endphp
                        <tr data-entry-id="{{ $patient->id }}">
                            <td></td>
                            <td class="pr-control-cell">{{ $patient->control_number }}</td>
                            <td class="pr-date-cell" data-order="{{ $dateProcessed->timestamp }}">
                                {{ $dateProcessed->format('M j, Y') }}
                                <small>{{ $dateProcessed->format('g:i A') }}</small>
                            </td>
                            <td><div class="pr-name-cell">{{ $patient->claimant_name }}</div></td>
                            <td class="pr-sub-cell">{{ $patient->case_worker }}</td>
                            <td>
                                <span class="pt-status {{ $statusClass }}">
                                    <i class="fas {{ $statusIcon }}"></i>
                                    {{ $baseStatus === 'Submitted[Emergency]' ? 'Emergency' : $baseStatus }}
                                    @if($isRollback)<span class="pt-s-rollback">ROLLED BACK</span>@endif
                                </span>
                            </td>
                            <td>
                                <span class="pr-dept-badge {{ $department === 'Completed' ? 'completed' : '' }}">
                                    <i class="fas {{ $department === 'Completed' ? 'fa-check' : 'fa-building' }}" style="font-size:.65rem;"></i>
                                    {{ $department }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="pr-action-wrap" style="justify-content:center;">
                                    <a href="{{ route('admin.process-tracking.show', ['process_tracking'=>$patient->id]) }}" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                            <td style="display:none;">{{ $sortPriority }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- PAGINATION FOOTER --}}
        @php
            $cur=$patients->currentPage(); $last=$patients->lastPage();
            $perPg=$patients->perPage(); $tot=$patients->total();
            $from=$tot>0?($cur-1)*$perPg+1:0; $to=min($cur*$perPg,$tot);
            $pages=collect(); for($i=1;$i<=$last;$i++){if($i===1||$i===$last||abs($i-$cur)<=2)$pages->push($i);}
            $pageUrl=fn($p)=>request()->fullUrlWithQuery(['page'=>$p]);
        @endphp
        <div class="pr-pagination-wrap">
            <div class="pr-pagination-info">
                @if($tot>0) Showing <strong>{{ $from }}–{{ $to }}</strong> of <strong>{{ $tot }}</strong> records
                @else No records found @endif
            </div>
            @if($last>1)
                <nav><ul class="pagination">
                    <li class="page-item {{ $cur<=1?'disabled':'' }}"><a class="page-link" href="{{ $cur>1?$pageUrl($cur-1):'#' }}">‹</a></li>
                    @php $prev=null; @endphp
                    @foreach($pages as $p)
                        @if($prev!==null&&$p-$prev>1)<li class="page-item disabled"><span class="page-link" style="border:none;background:transparent;padding:5px 4px;color:var(--pr-sub);">…</span></li>@endif
                        <li class="page-item {{ $p===$cur?'active':'' }}"><a class="page-link" href="{{ $pageUrl($p) }}">{{ $p }}</a></li>
                        @php $prev=$p; @endphp
                    @endforeach
                    <li class="page-item {{ $cur>=$last?'disabled':'' }}"><a class="page-link" href="{{ $cur<$last?$pageUrl($cur+1):'#' }}">›</a></li>
                </ul></nav>
            @else <div></div> @endif
            @if($last>1)
                <div class="pr-pagination-jump">
                    <span>Go to</span>
                    <input type="number" id="pr-pt-jump-input" min="1" max="{{ $last }}" placeholder="{{ $cur }}">
                    <button type="button" id="pr-pt-jump-btn">Go</button>
                </div>
            @else <div></div> @endif
        </div>
    </div>

    </div>{{-- /pr-page --}}

    {{-- MODALS (unchanged from original) --}}
    <!-- Mass Decision Modal -->
    <div class="modal fade" id="massDecisionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog"><form id="massDecisionForm">@csrf<input type="hidden" name="action" id="massDecisionAction">
            <div class="modal-content" style="border:none;border-radius:14px;overflow:hidden;font-family:'DM Sans',sans-serif;">
                <div class="modal-header" id="massDecisionHeader" style="border-bottom:1px solid var(--pr-border);">
                    <h5 class="modal-title" id="massDecisionModalLabel" style="font-size:.95rem;font-weight:700;">Mass Decision</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:20px 22px;">
                    <div id="massDecisionRejectFields" style="display:none;">
                        <div class="mb-3">
                            <label class="form-label" style="font-size:.78rem;font-weight:700;color:var(--pr-sub);text-transform:uppercase;letter-spacing:.05em;">Reason(s) for Rejection</label>
                            @foreach(['Missing ID','No signature','Expired documents','Wrong name','Missing document'] as $i=>$reason)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="reasons[]" value="{{ $reason }}" id="massReason{{ $i }}">
                                    <label class="form-check-label" for="massReason{{ $i }}" style="font-size:.82rem;">{{ $reason }}</label>
                                </div>
                            @endforeach
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="font-size:.78rem;font-weight:700;color:var(--pr-sub);text-transform:uppercase;letter-spacing:.05em;">Other Reason (Optional)</label>
                            <input type="text" name="other_reason" id="massDecisionOtherReason" class="form-control" placeholder="Specify other reason" style="border-radius:7px;border:1.5px solid var(--pr-border-dark);font-size:.82rem;font-family:'DM Sans',sans-serif;">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:.78rem;font-weight:700;color:var(--pr-sub);text-transform:uppercase;letter-spacing:.05em;">Decision Date</label>
                        <input type="datetime-local" class="form-control" id="massDecisionStatusDate" name="status_date" value="{{ now()->toDateTimeLocalString() }}" required style="border-radius:7px;border:1.5px solid var(--pr-border-dark);font-size:.82rem;font-family:'DM Sans',sans-serif;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:.78rem;font-weight:700;color:var(--pr-sub);text-transform:uppercase;letter-spacing:.05em;">Remarks</label>
                        <textarea name="remarks" id="massDecisionRemarks" class="form-control" rows="3" placeholder="Enter remarks..." style="border-radius:7px;border:1.5px solid var(--pr-border-dark);font-size:.82rem;font-family:'DM Sans',sans-serif;"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--pr-border);background:var(--pr-surface2);padding:14px 22px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius:8px;font-size:.8rem;font-family:'DM Sans',sans-serif;font-weight:600;background:var(--pr-muted);color:var(--pr-sub);border:1px solid var(--pr-border-dark);">Cancel</button>
                    <button type="submit" id="massDecisionConfirmBtn" class="btn btn-primary" style="border-radius:8px;font-size:.8rem;font-family:'DM Sans',sans-serif;font-weight:600;">Confirm</button>
                </div>
            </div>
        </form></div>
    </div>

    <!-- Mass Budget Modal -->
    <div class="modal fade" id="massBudgetModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog"><form id="massBudgetForm">@csrf
            <div class="modal-content" style="border:none;border-radius:14px;overflow:hidden;font-family:'DM Sans',sans-serif;">
                <div class="modal-header" style="background:linear-gradient(135deg,#052e22 0%,#064e3b 100%);border-bottom:none;">
                    <h5 class="modal-title" style="color:#fff;font-size:.95rem;font-weight:700;"><i class="fas fa-wallet me-2" style="color:var(--pr-lime);"></i>Allocate Budget</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1);"></button>
                </div>
                <div class="modal-body" style="padding:20px 22px;">
                    <div class="mb-4">
                        <label class="form-label" style="font-size:.78rem;font-weight:700;color:var(--pr-sub);text-transform:uppercase;letter-spacing:.05em;">Amount (₱)</label>
                        <input type="number" step="0.01" name="amount" id="massBudgetAmount" class="form-control form-control-lg" required style="border-radius:7px;border:1.5px solid var(--pr-border-dark);font-size:.9rem;font-family:'DM Sans',sans-serif;">
                        <div style="display:flex;flex-wrap:wrap;gap:5px;margin-top:8px;">
                            @foreach([1000,2000,3000,4000,5000,6000,7000,8000,9000,10000] as $s)
                                <button type="button" class="suggested-amount" data-value="{{ $s }}" style="border:1.5px solid var(--pr-border-dark);background:var(--pr-surface);border-radius:20px;padding:3px 12px;font-size:.74rem;font-weight:600;color:var(--pr-sub);cursor:pointer;font-family:'DM Sans',sans-serif;transition:all .18s;">₱{{ number_format($s) }}</button>
                            @endforeach
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:.78rem;font-weight:700;color:var(--pr-sub);text-transform:uppercase;letter-spacing:.05em;">Status Date</label>
                        <input type="datetime-local" name="status_date" id="massBudgetStatusDate" class="form-control" value="{{ now()->toDateTimeLocalString() }}" required style="border-radius:7px;border:1.5px solid var(--pr-border-dark);font-size:.82rem;font-family:'DM Sans',sans-serif;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size:.78rem;font-weight:700;color:var(--pr-sub);text-transform:uppercase;letter-spacing:.05em;">Remarks (Optional)</label>
                        <textarea name="remarks" id="massBudgetRemarks" class="form-control" rows="3" placeholder="Enter remarks..." style="border-radius:7px;border:1.5px solid var(--pr-border-dark);font-size:.82rem;font-family:'DM Sans',sans-serif;"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--pr-border);background:var(--pr-surface2);padding:14px 22px;gap:8px;">
                    <button type="button" class="btn w-100" data-bs-dismiss="modal" style="border-radius:8px;font-size:.8rem;font-family:'DM Sans',sans-serif;font-weight:600;background:var(--pr-muted);color:var(--pr-sub);border:1px solid var(--pr-border-dark);">Cancel</button>
                    <button type="submit" class="btn w-100" style="border-radius:8px;font-size:.8rem;font-family:'DM Sans',sans-serif;font-weight:700;background:var(--pr-lime);color:var(--pr-forest);border:none;box-shadow:var(--pr-shadow-lime);"><i class="fas fa-check-circle me-1"></i>Confirm</button>
                </div>
            </div>
        </form></div>
    </div>

    <!-- Mass DV Modal -->
    <div class="modal fade" id="massDVModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog"><form id="massDVForm" method="POST">@csrf
            <div class="modal-content" style="border:none;border-radius:14px;overflow:hidden;font-family:'DM Sans',sans-serif;">
                <div class="modal-header" style="background:linear-gradient(135deg,#1e40af 0%,#3b82f6 100%);border-bottom:none;">
                    <h5 class="modal-title" style="color:#fff;font-size:.95rem;font-weight:700;"><i class="fas fa-file-invoice me-2"></i>Mass DV Input</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1);"></button>
                </div>
                <div class="modal-body" style="padding:20px 22px;">
                    <div class="mb-3">
                        <label style="font-size:.78rem;font-weight:700;color:var(--pr-sub);text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:5px;">DV Date *</label>
                        <input type="date" id="massDvDate" name="dv_date" class="form-control" required style="border-radius:7px;border:1.5px solid var(--pr-border-dark);font-size:.82rem;font-family:'DM Sans',sans-serif;">
                    </div>
                    <div class="mb-3">
                        <label style="font-size:.78rem;font-weight:700;color:var(--pr-sub);text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:5px;">Status Date *</label>
                        <input type="datetime-local" id="massDvStatusDate" name="status_date" class="form-control" value="{{ now()->toDateTimeLocalString() }}" required style="border-radius:7px;border:1.5px solid var(--pr-border-dark);font-size:.82rem;font-family:'DM Sans',sans-serif;">
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--pr-border);background:var(--pr-surface2);padding:14px 22px;gap:8px;flex-direction:column;">
                    <button type="submit" class="btn w-100" style="border-radius:8px;font-size:.8rem;font-family:'DM Sans',sans-serif;font-weight:700;background:var(--pr-forest);color:var(--pr-lime);border:none;"><i class="fas fa-check-circle me-1"></i>Submit DV</button>
                    <button type="button" class="btn w-100" data-bs-dismiss="modal" style="border-radius:8px;font-size:.8rem;font-family:'DM Sans',sans-serif;font-weight:600;background:var(--pr-muted);color:var(--pr-sub);border:1px solid var(--pr-border-dark);">Cancel</button>
                </div>
            </div>
        </form></div>
    </div>

    <!-- Mass Ready for Disbursement Modal -->
    <div class="modal fade" id="massReadyForDisbursementModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog"><form id="massReadyForDisbursementForm" method="POST">@csrf
            <div class="modal-content" style="border:none;border-radius:14px;overflow:hidden;font-family:'DM Sans',sans-serif;">
                <div class="modal-header" style="background:linear-gradient(135deg,#b45309 0%,#f59e0b 100%);border-bottom:none;">
                    <h5 class="modal-title" style="color:#fff;font-size:.95rem;font-weight:700;"><i class="fas fa-check-circle me-2"></i>Mark as Ready for Disbursement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1);"></button>
                </div>
                <div class="modal-body" style="padding:20px 22px;">
                    <div class="alert alert-info mb-3" style="border-radius:8px;font-size:.8rem;font-weight:500;border:none;padding:10px 14px;background:#eff6ff;color:#1e40af;">
                        <i class="fas fa-info-circle me-2"></i>Only <strong>DV Submitted</strong> or <strong>DV Submitted[ROLLED BACK]</strong> patients will be processed.
                    </div>
                    <div class="mb-3">
                        <label style="font-size:.78rem;font-weight:700;color:var(--pr-sub);text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:5px;">Status Date</label>
                        <input type="datetime-local" name="status_date" id="massReadyStatusDate" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" step="60" required style="border-radius:7px;border:1.5px solid var(--pr-border-dark);font-size:.82rem;font-family:'DM Sans',sans-serif;">
                    </div>
                    <div class="mb-3">
                        <label style="font-size:.78rem;font-weight:700;color:var(--pr-sub);text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:5px;">Remarks (Optional)</label>
                        <textarea name="remarks" id="massReadyRemarks" class="form-control" rows="3" placeholder="Enter remarks..." style="border-radius:7px;border:1.5px solid var(--pr-border-dark);font-size:.82rem;font-family:'DM Sans',sans-serif;"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--pr-border);background:var(--pr-surface2);padding:14px 22px;gap:8px;flex-direction:column;">
                    <button type="submit" class="btn w-100" style="border-radius:8px;font-size:.8rem;font-family:'DM Sans',sans-serif;font-weight:700;background:#f59e0b;color:#fff;border:none;"><i class="fas fa-check-circle me-1"></i>Mark as Ready</button>
                    <button type="button" class="btn w-100" data-bs-dismiss="modal" style="border-radius:8px;font-size:.8rem;font-family:'DM Sans',sans-serif;font-weight:600;background:var(--pr-muted);color:var(--pr-sub);border:1px solid var(--pr-border-dark);">Cancel</button>
                </div>
            </div>
        </form></div>
    </div>

    <!-- Mass Disburse Modal -->
    <div class="modal fade" id="massDisburseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog"><form id="massDisburseForm" method="POST" action="{{ route('admin.process-tracking.massQuickDisburse') }}">@csrf
            <div class="modal-content" style="border:none;border-radius:14px;overflow:hidden;font-family:'DM Sans',sans-serif;">
                <div class="modal-header" style="background:linear-gradient(135deg,#991b1b 0%,#ef4444 100%);border-bottom:none;">
                    <h5 class="modal-title" style="color:#fff;font-size:.95rem;font-weight:700;"><i class="fas fa-coins me-2"></i>Quick Disburse</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1);"></button>
                </div>
                <div class="modal-body" style="padding:20px 22px;">
                    <p style="font-size:.85rem;color:var(--pr-text);margin-bottom:14px;">Mark selected patients as <strong>Disbursed</strong>?</p>
                    <div class="mb-3">
                        <label style="font-size:.78rem;font-weight:700;color:var(--pr-sub);text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:5px;">Disbursement Date</label>
                        <input type="datetime-local" class="form-control" id="massDisburseDate" name="status_date" value="{{ now()->format('Y-m-d\TH:i') }}" step="60" required style="border-radius:7px;border:1.5px solid var(--pr-border-dark);font-size:.82rem;font-family:'DM Sans',sans-serif;">
                    </div>
                    <div class="mb-3">
                        <label style="font-size:.78rem;font-weight:700;color:var(--pr-sub);text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:5px;">Remarks (Optional)</label>
                        <textarea class="form-control" id="massDisburseRemarks" name="remarks" rows="3" placeholder="Enter remarks..." style="border-radius:7px;border:1.5px solid var(--pr-border-dark);font-size:.82rem;font-family:'DM Sans',sans-serif;"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--pr-border);background:var(--pr-surface2);padding:14px 22px;gap:8px;flex-direction:column;">
                    <button type="submit" class="btn w-100" style="border-radius:8px;font-size:.8rem;font-family:'DM Sans',sans-serif;font-weight:700;background:var(--pr-danger);color:#fff;border:none;"><i class="fas fa-check-circle me-1"></i>Confirm Disbursement</button>
                    <button type="button" class="btn w-100" data-bs-dismiss="modal" style="border-radius:8px;font-size:.8rem;font-family:'DM Sans',sans-serif;font-weight:600;background:var(--pr-muted);color:var(--pr-sub);border:1px solid var(--pr-border-dark);">Cancel</button>
                </div>
            </div>
        </form></div>
    </div>

@endsection

@section('scripts')
    @parent
    <script>
    'use strict';

    /* ── Auto-activate My Queue on first visit ── */
    (function () {
        const url        = new URL(window.location.href);
        const hasFilters = url.searchParams.has('filters[]') || url.searchParams.getAll('filters%5B%5D').length > 0;
        const hasVisited = url.searchParams.has('_visited');
        if (!hasFilters && !hasVisited) {
            @php
                $roleAutoFilters = match(Auth::user()->roles->pluck('title')->first()) {
                    'CSWD Office'       => ['Processing','Rejected'],
                    'Mayors Office'     => ['Submitted','Submitted[Emergency]'],
                    'Budget Office'     => ['Approved'],
                    'Accounting Office' => ['Budget Allocated'],
                    'Treasury Office'   => ['DV Submitted'],
                    default             => [],
                };
            @endphp
            @if(count($roleAutoFilters) > 0)
                url.searchParams.set('_visited', '1');
                @foreach($roleAutoFilters as $f)
                url.searchParams.append('filters[]', @json($f));
                @endforeach
                window.location.replace(url.toString());
            @endif
        }
    })();

    /* ── Toast ── */
    document.addEventListener('DOMContentLoaded', function () {
        const toastEl = document.getElementById('liveToast');
        const timerEl = document.getElementById('toast-timer');
        if (toastEl) {
            new bootstrap.Toast(toastEl, { autohide: true, delay: 5000 }).show();
            let rem = 5;
            const iv = setInterval(() => {
                rem--;
                if (timerEl) timerEl.textContent = `Closing in ${rem}s`;
                if (rem <= 0) clearInterval(iv);
            }, 1000);
        }
    });

    /* ── Badge helpers ── */
    function generateBadge(status) {
        const icons = {
            'Processing'             : 'fa-spinner',
            'Submitted'              : 'fa-paper-plane',
            'Submitted[Emergency]'   : 'fa-exclamation-triangle',
            'Approved'               : 'fa-thumbs-up',
            'Rejected'               : 'fa-ban',
            'Budget Allocated'       : 'fa-money-bill-wave',
            'DV Submitted'           : 'fa-file',
            'Disbursed'              : 'fa-coins',
            'Ready for Disbursement' : 'fa-check-circle',
        };
        const cls = {
            'Processing'             : 'pt-s-processing',
            'Submitted'              : 'pt-s-submitted',
            'Submitted[Emergency]'   : 'pt-s-emergency',
            'Approved'               : 'pt-s-approved',
            'Rejected'               : 'pt-s-rejected',
            'Budget Allocated'       : 'pt-s-budget',
            'DV Submitted'           : 'pt-s-dv',
            'Ready for Disbursement' : 'pt-s-ready',
            'Disbursed'              : 'pt-s-disbursed',
        };
        const isRB  = status.includes('[ROLLED BACK]');
        const base  = status.replace('[ROLLED BACK]', '').trim();
        const label = base === 'Submitted[Emergency]' ? 'Emergency' : base;
        const rb    = isRB ? ' <span class="pt-s-rollback">ROLLED BACK</span>' : '';
        return `<span class="pt-status ${cls[base] || 'pt-s-processing'}">` +
               `<i class="fas ${icons[base] || 'fa-question-circle'}"></i>${label}${rb}</span>`;
    }

    function getDepartmentBadge(status) {
        const clean = status.replace('[ROLLED BACK]', '').trim();
        const map = {
            'Processing'             : 'CSWD Office',
            'Draft'                  : 'CSWD Office',
            'Rejected'               : 'CSWD Office',
            'Submitted'              : "Mayor's Office",
            'Submitted[Emergency]'   : "Mayor's Office",
            'Approved'               : 'Budget Office',
            'Budget Allocated'       : 'Accounting Office',
            'DV Submitted'           : 'Treasury Office',
            'Ready for Disbursement' : 'Treasury Office',
            'Disbursed'              : 'Completed',
        };
        const dept = map[clean] || 'N/A';
        const done = dept === 'Completed';
        return `<span class="pr-dept-badge ${done ? 'completed' : ''}">` +
               `<i class="fas ${done ? 'fa-check' : 'fa-building'}" style="font-size:.65rem;"></i>` +
               `${dept}</span>`;
    }

    /* ── DOM patcher ── */
    function applyUpdate(item) {
        // item = { id, status }
        const $row = jQuery(`tr[data-entry-id="${item.id}"]`);
        if ($row.length === 0) return; // not on this page — skip silently

        const $tds = $row.find('td');
        // td layout: [0]checkbox [1]control# [2]date [3]claimant [4]caseworker
        //            [5]STATUS   [6]DEPT     [7]actions [8]sortpriority(hidden)
        $tds.eq(5).html(generateBadge(item.status));
        $tds.eq(6).html(getDepartmentBadge(item.status));

        // Lime-green flash so the watching user notices the change
        $tds.addClass('pr-rt-flash');
        setTimeout(() => $tds.removeClass('pr-rt-flash'), 2500);
    }

    /* ── Polling engine ── */
    let _lastLogId   = null;   // cursor: last PatientStatusLog.id we've seen
    let _pollTimer   = null;
    let _polling     = false;  // guard against overlapping requests
    const POLL_MS    = 5000;   // poll every 5 seconds
    const POLL_URL   = "{{ route('admin.process-tracking.pollUpdates') }}";
    const CSRF_TOKEN = jQuery('meta[name="csrf-token"]').attr('content');

    function doPoll() {
        if (_polling) return;          // previous request still in flight — skip
        if (_lastLogId === null) return; // not initialized yet

        _polling = true;

        fetch(`${POLL_URL}?since=${_lastLogId}`, {
            headers: {
                'X-Requested-With' : 'XMLHttpRequest',
                'X-CSRF-TOKEN'     : CSRF_TOKEN,
                'Accept'           : 'application/json',
            },
            credentials: 'same-origin',
        })
        .then(r => r.ok ? r.json() : Promise.reject(r.status))
        .then(data => {
            // Advance the cursor so we never re-process the same logs
            if (data.last_log_id > _lastLogId) {
                _lastLogId = data.last_log_id;
            }
            // Apply each update to the DOM
            (data.updates || []).forEach(item => applyUpdate(item));
        })
        .catch(err => {
            // Network blip or server error — just log quietly, will retry
            console.warn('[poll] error:', err);
        })
        .finally(() => {
            _polling = false;
        });
    }

    function startPolling() {
        if (_pollTimer) return; // already running
        _pollTimer = setInterval(doPoll, POLL_MS);
    }

    function stopPolling() {
        clearInterval(_pollTimer);
        _pollTimer = null;
    }

    function initPolling() {
        // Step 1 — get the current cursor (max log id right now) without returning updates
        fetch(POLL_URL, {
            headers: {
                'X-Requested-With' : 'XMLHttpRequest',
                'X-CSRF-TOKEN'     : CSRF_TOKEN,
                'Accept'           : 'application/json',
            },
            credentials: 'same-origin',
        })
        .then(r => r.ok ? r.json() : Promise.reject(r.status))
        .then(data => {
            _lastLogId = data.last_log_id;
            console.log(`[poll] initialized — cursor: ${_lastLogId}`);
            startPolling();
        })
        .catch(err => {
            console.warn('[poll] init failed:', err);
            // Retry init after 10s if something went wrong
            setTimeout(initPolling, 10000);
        });
    }

    // Pause polling when the tab is hidden, resume when visible again
    // (saves server load and browser resources)
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            stopPolling();
        } else {
            // Immediately poll on return so the user sees fresh data at once
            doPoll();
            startPolling();
        }
    });

    jQuery(function () {
        let _token    = jQuery('meta[name="csrf-token"]').attr('content');
        let dtButtons = jQuery.extend(true, [], jQuery.fn.dataTable.defaults.buttons);

        /* ── DataTable init ── */
        const table = jQuery('.datatable-ProcessTracking:not(.ajaxTable)').DataTable({
            buttons     : dtButtons,
            order       : [[8, 'asc'], [2, 'desc']],
            orderCellsTop: true,
            searching   : true,
            paging      : false,
            info        : false,
            processing  : true,
            serverSide  : false,
            columnDefs  : [
                { targets: 0, orderable: false, searchable: false, className: 'select-checkbox' },
                { targets: 7, orderable: false, searchable: false },
                { targets: 8, visible: false,   searchable: false },
            ],
            initComplete: function () {
                const dtWrapper  = jQuery('.pr-table-card .dataTables_wrapper');
                const dtBtnGroup = dtWrapper.find('.dt-buttons');

                const currentPage = {{ $patients->currentPage() }};
                const lastPage    = {{ $patients->lastPage() }};

                const indicator = jQuery(`
                    <div class="pr-record-indicator" id="pr-pt-indicator">
                        <span class="ri-dot"></span>
                        <span style="color:var(--pr-sub);font-weight:500;">Page</span>
                        <span class="ri-count" style="font-weight:800;color:var(--pr-forest);">${currentPage}</span>
                        <span style="color:var(--pr-sub);font-weight:500;">of ${lastPage}</span>
                    </div>`);

                const toolbar   = jQuery('<div class="pr-dt-toolbar"></div>');
                const leftWrap  = jQuery('<div class="pr-dt-toolbar-left"></div>');
                const rightWrap = jQuery('<div class="pr-dt-toolbar-right"></div>');
                leftWrap.append(dtBtnGroup.children().detach());
                rightWrap.append(indicator);
                toolbar.append(leftWrap).append(rightWrap);
                jQuery('.pr-table-card .table-responsive').before(toolbar);

                table.on('select deselect', function () {
                    const sel = table.rows({ selected: true }).count();
                    if (sel > 0) {
                        jQuery('#pr-pt-indicator').css({ background: 'rgba(6,78,59,.10)', borderColor: 'rgba(6,78,59,.35)' });
                        jQuery('#pr-pt-indicator .ri-dot').css('background', 'var(--pr-forest)');
                        jQuery('#pr-pt-indicator span:last').text(`of ${lastPage} · ${sel} selected`);
                    } else {
                        jQuery('#pr-pt-indicator').css({ background: '', borderColor: '' });
                        jQuery('#pr-pt-indicator .ri-dot').css('background', '');
                        jQuery('#pr-pt-indicator span:last').text(`of ${lastPage}`);
                    }
                });

                jQuery('#pr-pt-jump-btn').on('click', function () {
                    const val = parseInt(jQuery('#pr-pt-jump-input').val());
                    const max = parseInt(jQuery('#pr-pt-jump-input').attr('max') || 1);
                    if (!val || val < 1 || val > max) { jQuery('#pr-pt-jump-input').focus(); return; }
                    const url = new URL(window.location.href);
                    url.searchParams.set('page', val);
                    window.location.href = url.toString();
                });
                jQuery('#pr-pt-jump-input').on('keydown', e => {
                    if (e.key === 'Enter') jQuery('#pr-pt-jump-btn').trigger('click');
                });

                // ── Start polling AFTER DataTable is fully ready ──
                initPolling();
            }
        });

        jQuery('a[data-toggle="tab"]').on('shown.bs.tab click', () =>
            jQuery(jQuery.fn.dataTable.tables(true)).DataTable().columns.adjust()
        );

        /* ── Mass buttons ── */

        @can('approve_patient')
        let selectedIds = [];
        dtButtons.push({ text: 'Approve Selected', className: 'btn-success', action: function (e, dt) {
            selectedIds = jQuery.map(dt.rows({ selected: true }).nodes(), el => jQuery(el).data('entry-id'));
            if (!selectedIds.length) { alert('No records selected'); return; }
            jQuery('#massDecisionAction').val('approve');
            jQuery('#massDecisionRemarks').val('');
            jQuery('#massDecisionModal').modal('show');
        }});
        dtButtons.push({ text: 'Reject Selected', className: 'btn-danger', action: function (e, dt) {
            selectedIds = jQuery.map(dt.rows({ selected: true }).nodes(), el => jQuery(el).data('entry-id'));
            if (!selectedIds.length) { alert('No records selected'); return; }
            jQuery('#massDecisionAction').val('reject');
            jQuery('#massDecisionRemarks').val('');
            jQuery('#massDecisionModal').modal('show');
        }});
        jQuery('#massDecisionForm').on('submit', function (e) {
            e.preventDefault();
            const confirmBtn = jQuery('#massDecisionConfirmBtn'), action = jQuery('#massDecisionAction').val();
            confirmBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Processing...');
            jQuery('#massDecisionModal').find('button[data-bs-dismiss="modal"]').prop('disabled', true);
            const form = jQuery('<form>', { method: 'POST', action: "{{ route('admin.process-tracking.massDecision') }}" })
                .append(jQuery('<input>', { type: 'hidden', name: '_token',      value: _token }))
                .append(jQuery('<input>', { type: 'hidden', name: 'action',      value: action }))
                .append(jQuery('<input>', { type: 'hidden', name: 'remarks',     value: jQuery('#massDecisionRemarks').val() }))
                .append(jQuery('<input>', { type: 'hidden', name: 'status_date', value: jQuery('#massDecisionStatusDate').val() }));
            selectedIds.forEach(id => form.append(jQuery('<input>', { type: 'hidden', name: 'ids[]', value: id })));
            if (action === 'reject') {
                let reasons = [];
                jQuery('input[name="reasons[]"]:checked').each(function () { reasons.push(jQuery(this).val()); });
                const other = jQuery('#massDecisionOtherReason').val().trim();
                if (other) reasons.push(other);
                reasons.forEach(r => form.append(jQuery('<input>', { type: 'hidden', name: 'reasons[]', value: r })));
            }
            form.appendTo('body').submit();
        });
        jQuery('#massDecisionModal').on('hidden.bs.modal', function () {
            const action = jQuery('#massDecisionAction').val();
            jQuery('#massDecisionConfirmBtn').prop('disabled', false)
                .html(action === 'approve' ? 'Confirm Approve' : 'Confirm Reject');
            jQuery(this).find('button[data-bs-dismiss="modal"]').prop('disabled', false);
        });
        jQuery('#massDecisionModal').on('show.bs.modal', function () {
            const action = jQuery('#massDecisionAction').val();
            const header = jQuery('#massDecisionHeader');
            const confirmBtn = jQuery('#massDecisionConfirmBtn');
            if (action === 'approve') {
                header.attr('style', 'background:linear-gradient(135deg,#052e22 0%,#064e3b 100%);border-bottom:none;');
                header.find('.modal-title').css('color', '#fff');
                jQuery('#massDecisionModalLabel').text('Approve Selected Applications');
                confirmBtn.removeClass('btn-danger').addClass('btn-success').text('Confirm Approve')
                    .css({ background: 'var(--pr-lime)', color: 'var(--pr-forest)', border: 'none' });
                jQuery('#massDecisionRejectFields').hide();
            } else {
                header.attr('style', 'background:linear-gradient(135deg,#991b1b 0%,#ef4444 100%);border-bottom:none;');
                header.find('.modal-title').css('color', '#fff');
                jQuery('#massDecisionModalLabel').text('Reject Selected Applications');
                confirmBtn.removeClass('btn-success').addClass('btn-danger').text('Confirm Reject')
                    .css({ background: 'var(--pr-danger)', color: '#fff', border: 'none' });
                jQuery('#massDecisionRejectFields').show();
            }
        });
        @endcan

        @can('accounting_dv_input')
        let selectedDvIds = [];
        dtButtons.push({ text: 'Mass DV Input', className: 'btn-primary', action: function (e, dt) {
            selectedDvIds = jQuery.map(dt.rows({ selected: true }).nodes(), el => jQuery(el).data('entry-id'));
            if (!selectedDvIds.length) { alert('No records selected'); return; }
            jQuery('#massDvDate').val('');
            jQuery('#massDVModal').modal('show');
        }});
        jQuery('#massDVForm').on('submit', function (e) {
            e.preventDefault();
            const form = jQuery('<form>', { method: 'POST', action: "{{ route('admin.process-tracking.massDVInput') }}" })
                .append(jQuery('<input>', { type: 'hidden', name: '_token',      value: _token }))
                .append(jQuery('<input>', { type: 'hidden', name: 'dv_date',     value: jQuery('#massDvDate').val() }))
                .append(jQuery('<input>', { type: 'hidden', name: 'status_date', value: jQuery('#massDvStatusDate').val() }));
            selectedDvIds.forEach(id => form.append(jQuery('<input>', { type: 'hidden', name: 'ids[]', value: id })));
            form.appendTo('body').submit();
        });
        @endcan

        @can('budget_allocate')
        let selectedBudgetIds = [];
        dtButtons.push({ text: 'Allocate Budget', className: 'btn-success', action: function (e, dt) {
            selectedBudgetIds = jQuery.map(dt.rows({ selected: true }).nodes(), el => jQuery(el).data('entry-id'));
            if (!selectedBudgetIds.length) { alert('No records selected'); return; }
            jQuery('#massBudgetAmount').val('');
            jQuery('#massBudgetRemarks').val('');
            jQuery('#massBudgetModal').modal('show');
        }});
        jQuery('#massBudgetForm').on('submit', function (e) {
            e.preventDefault();
            const form = jQuery('<form>', { method: 'POST', action: "{{ route('admin.process-tracking.massBudgetAllocate') }}" })
                .append(jQuery('<input>', { type: 'hidden', name: '_token',      value: _token }))
                .append(jQuery('<input>', { type: 'hidden', name: 'amount',      value: jQuery('#massBudgetAmount').val() }))
                .append(jQuery('<input>', { type: 'hidden', name: 'remarks',     value: jQuery('#massBudgetRemarks').val() }))
                .append(jQuery('<input>', { type: 'hidden', name: 'status_date', value: jQuery('#massBudgetStatusDate').val() }));
            selectedBudgetIds.forEach(id => form.append(jQuery('<input>', { type: 'hidden', name: 'ids[]', value: id })));
            form.appendTo('body').submit();
        });
        jQuery(document).on('click', '.suggested-amount', function () {
            jQuery('#massBudgetAmount').val(jQuery(this).data('value'));
            jQuery('.suggested-amount').css({ borderColor: '', background: '', color: '' });
            jQuery(this).css({ borderColor: 'var(--pr-forest)', background: 'var(--pr-lime-ghost)', color: 'var(--pr-forest)' });
        });
        @endcan

        @can('treasury_disburse')
        let selectedReadyIds = [], selectedDisburseIds = [];
        function getManilaDateTime() {
            const now = new Date(), manila = new Date(now.getTime() + 8 * 3600000);
            const pad = n => String(n).padStart(2, '0');
            return `${manila.getUTCFullYear()}-${pad(manila.getUTCMonth() + 1)}-${pad(manila.getUTCDate())}` +
                   `T${pad(manila.getUTCHours())}:${pad(manila.getUTCMinutes())}`;
        }
        dtButtons.push({ text: 'Ready for Disbursement', className: 'btn-warning', action: function (e, dt) {
            selectedReadyIds = jQuery.map(dt.rows({ selected: true }).nodes(), el => jQuery(el).data('entry-id'));
            if (!selectedReadyIds.length) { alert('No records selected'); return; }
            jQuery('#massReadyStatusDate').val(getManilaDateTime());
            jQuery('#massReadyRemarks').val('');
            jQuery('#massReadyForDisbursementModal').modal('show');
        }});
        dtButtons.push({ text: 'Disburse', className: 'btn-danger', action: function (e, dt) {
            selectedDisburseIds = jQuery.map(dt.rows({ selected: true }).nodes(), el => jQuery(el).data('entry-id'));
            if (!selectedDisburseIds.length) { alert('No records selected'); return; }
            jQuery('#massDisburseDate').val(getManilaDateTime());
            jQuery('#massDisburseRemarks').val('');
            jQuery('#massDisburseModal').modal('show');
        }});
        jQuery('#massReadyForDisbursementForm').on('submit', function (e) {
            e.preventDefault();
            const btn = jQuery(this).find('button[type="submit"]');
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Processing...');
            jQuery('#massReadyForDisbursementModal').find('button[data-bs-dismiss="modal"]').prop('disabled', true);
            const form = jQuery('<form>', { method: 'POST', action: "{{ route('admin.process-tracking.massReadyForDisbursement') }}" })
                .append(jQuery('<input>', { type: 'hidden', name: '_token',      value: _token }))
                .append(jQuery('<input>', { type: 'hidden', name: 'status_date', value: jQuery('#massReadyStatusDate').val() }))
                .append(jQuery('<input>', { type: 'hidden', name: 'remarks',     value: jQuery('#massReadyRemarks').val() }));
            selectedReadyIds.forEach(id => form.append(jQuery('<input>', { type: 'hidden', name: 'ids[]', value: id })));
            form.appendTo('body').submit();
        });
        jQuery('#massDisburseForm').on('submit', function (e) {
            e.preventDefault();
            const btn = jQuery(this).find('button[type="submit"]');
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Processing...');
            jQuery('#massDisburseModal').find('button[data-bs-dismiss="modal"]').prop('disabled', true);
            const form = jQuery('<form>', { method: 'POST', action: "{{ route('admin.process-tracking.massQuickDisburse') }}" })
                .append(jQuery('<input>', { type: 'hidden', name: '_token',      value: _token }))
                .append(jQuery('<input>', { type: 'hidden', name: 'status_date', value: jQuery('#massDisburseDate').val() }))
                .append(jQuery('<input>', { type: 'hidden', name: 'remarks',     value: jQuery('#massDisburseRemarks').val() }));
            selectedDisburseIds.forEach(id => form.append(jQuery('<input>', { type: 'hidden', name: 'ids[]', value: id })));
            form.appendTo('body').submit();
        });
        @endcan
    });
    </script>
@endsection