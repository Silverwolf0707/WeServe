@extends('layouts.admin')
@section('content')
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

    <style>
        :root {
            --pr-forest:        #064e3b;
            --pr-forest-deep:   #052e22;
            --pr-forest-mid:    #065f46;
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
            --pr-danger:        #ef4444;
            --pr-warning:       #f59e0b;
            --pr-info:          #3b82f6;
            --pr-success:       #10b981;
            --pr-radius:        12px;
            --pr-shadow:        0 2px 8px rgba(6,78,59,.08), 0 8px 24px rgba(6,78,59,.06);
            --pr-shadow-lg:     0 4px 24px rgba(6,78,59,.16), 0 16px 48px rgba(6,78,59,.10);
            --pr-shadow-lime:   0 2px 12px rgba(116,255,112,.25);
        }

        .pr-page { font-family: 'DM Sans', sans-serif; color: var(--pr-text); padding: 0 0 2rem; }

        /* ── hero ── */
        .pr-hero {
            background: linear-gradient(135deg, #052e22 0%, #064e3b 55%, #065f46 100%);
            border-radius: var(--pr-radius); padding: 22px 28px; margin-bottom: 16px;
            position: relative; overflow: hidden; box-shadow: var(--pr-shadow-lg);
        }
        .pr-hero::before {
            content: ''; position: absolute; inset: 0; border-radius: var(--pr-radius);
            background:
                radial-gradient(ellipse 380px 200px at 95% 50%, rgba(116,255,112,.13) 0%, transparent 65%),
                radial-gradient(ellipse 180px 100px at 5% 80%,  rgba(116,255,112,.07) 0%, transparent 70%),
                radial-gradient(ellipse 250px 120px at 50% -20%, rgba(255,255,255,.04) 0%, transparent 60%);
            pointer-events: none; z-index: 0;
        }
        .pr-hero::after {
            content: ''; position: absolute; top: 0; left: 28px; right: 28px; height: 2px;
            background: linear-gradient(to right, transparent, var(--pr-lime), transparent);
            border-radius: 2px; opacity: .55;
        }
        .pr-hero-inner {
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 14px; position: relative; z-index: 1;
        }
        .pr-hero-left { display: flex; align-items: center; gap: 16px; }
        .pr-hero-icon {
            width: 46px; height: 46px;
            background: rgba(116,255,112,.12); border: 1px solid rgba(116,255,112,.30);
            border-radius: 11px; display: flex; align-items: center; justify-content: center;
            font-size: 1.15rem; color: var(--pr-lime); backdrop-filter: blur(4px); flex-shrink: 0;
        }
        .pr-hero-title { font-size: 1.18rem; font-weight: 700; color: #fff; letter-spacing: -.01em; margin: 0 0 3px; line-height: 1.2; }
        .pr-hero-meta  { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .pr-badge { display: inline-flex; align-items: center; gap: 4px; border-radius: 20px; font-size: .72rem; font-weight: 600; padding: 2px 10px; letter-spacing: .03em; line-height: 1.6; }
        .pr-badge-count { background: rgba(116,255,112,.14); border: 1px solid rgba(116,255,112,.32); color: var(--pr-lime); }
        .pr-badge-status { background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2); color: rgba(255,255,255,.9); }

        /* ── status badges ── */
        .pr-status-badge {
            display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px;
            border-radius: 20px; font-size: .72rem; font-weight: 700; letter-spacing: .02em;
            white-space: nowrap; border: 1px solid transparent;
        }
        .pr-status-disbursed { background: rgba(16,185,129,.15); color: #047857; border-color: rgba(16,185,129,.3); }
        .pr-status-ready { background: rgba(245,158,11,.15); color: #b45309; border-color: rgba(245,158,11,.3); }
        .pr-status-not { background: rgba(59,130,246,.15); color: #1e40af; border-color: rgba(59,130,246,.3); }
        .pr-status-default { background: var(--pr-muted); color: var(--pr-sub); border-color: var(--pr-border-dark); }

        /* ── adv filter bar ── */
        .pr-adv-bar {
            background: var(--pr-surface2); border-radius: var(--pr-radius);
            border: 1px solid var(--pr-border); padding: 12px 18px; margin-bottom: 16px;
            box-shadow: 0 1px 4px rgba(6,78,59,.05);
        }
        .pr-adv-bar-inner { display: flex; align-items: flex-end; flex-wrap: wrap; gap: 10px; }
        .pr-adv-group { display: flex; flex-direction: column; gap: 4px; min-width: 160px; flex: 1 1 160px; }
        .pr-adv-label { font-size: .68rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--pr-sub); white-space: nowrap; }
        .pr-adv-input, .pr-adv-select {
            border: 1.5px solid var(--pr-border-dark); border-radius: 8px; padding: 6px 10px;
            font-size: .8rem; font-family: 'DM Sans', sans-serif; color: var(--pr-text); background: var(--pr-surface);
            transition: border-color .2s, box-shadow .2s; width: 100%;
        }
        .pr-adv-select { padding: 5px 10px; cursor: pointer; }
        .pr-adv-input:focus, .pr-adv-select:focus { outline: none; border-color: var(--pr-forest-mid); box-shadow: 0 0 0 3px rgba(6,78,59,.12); }
        .pr-adv-input::placeholder { color: var(--pr-border-dark); }
        .pr-adv-input.has-value, .pr-adv-select.has-value { border-color: var(--pr-forest); background-color: var(--pr-lime-ghost); color: var(--pr-forest); font-weight: 600; }
        .pr-adv-actions { display: flex; align-items: flex-end; gap: 6px; flex-shrink: 0; padding-bottom: 1px; }
        .pr-adv-apply {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--pr-forest); color: var(--pr-lime); border: none;
            border-radius: 8px; padding: 7px 16px; font-size: .8rem; font-weight: 700;
            font-family: 'DM Sans', sans-serif; cursor: pointer; transition: background .18s, transform .15s;
            white-space: nowrap; box-shadow: 0 2px 8px rgba(6,78,59,.25);
        }
        .pr-adv-apply:hover { background: var(--pr-forest-mid); transform: translateY(-1px); }
        .pr-adv-reset {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--pr-muted); color: var(--pr-sub); border: 1px solid var(--pr-border-dark);
            border-radius: 8px; padding: 7px 13px; font-size: .8rem; font-weight: 600;
            font-family: 'DM Sans', sans-serif; cursor: pointer; transition: background .18s; white-space: nowrap; text-decoration: none;
        }
        .pr-adv-reset:hover { background: var(--pr-border-dark); color: var(--pr-text); }

        /* ── table card ── */
        .pr-table-card {
            background: var(--pr-surface); border-radius: var(--pr-radius);
            border: 1px solid var(--pr-border); box-shadow: var(--pr-shadow); overflow: hidden;
        }
        .pr-dt-toolbar {
            display: flex; align-items: center; justify-content: space-between;
            padding: 10px 16px; border-bottom: 1px solid var(--pr-border);
            background: var(--pr-surface2); flex-wrap: wrap; gap: 8px;
        }
        .pr-dt-toolbar-left  { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
        .pr-dt-toolbar-right { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
        .pr-record-indicator {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--pr-lime-ghost); border: 1px solid var(--pr-lime-border);
            border-radius: 20px; padding: 4px 12px; font-size: .72rem;
            font-weight: 600; color: var(--pr-forest); white-space: nowrap;
            font-family: 'DM Sans', sans-serif;
        }
        .pr-record-indicator .ri-dot { width: 5px; height: 5px; border-radius: 50%; background: var(--pr-lime-dim); flex-shrink: 0; }

        /* ── table ── */
        .pr-table-card .table { margin: 0; font-family: 'DM Sans', sans-serif; font-size: .84rem; }
        .pr-table-card .table thead tr { background: var(--pr-forest); border-bottom: 1.5px solid var(--pr-forest-mid); }
        .pr-table-card .table thead th {
            font-size: .69rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: .07em; color: #fff; padding: 10px 14px; border: none; white-space: nowrap;
        }
        .pr-table-card .table tbody tr { border-bottom: 1px solid var(--pr-border); transition: background .15s; }
        .pr-table-card .table tbody tr:last-child { border-bottom: none; }
        .pr-table-card .table tbody tr:hover { background: var(--pr-surface2); }
        .pr-table-card .table tbody td { padding: 11px 14px; border: none; color: var(--pr-text); vertical-align: middle; }
        .pr-name-cell    { font-size: .82rem; font-weight: 600; color: var(--pr-text); }
        .pr-sub-cell     { font-size: .78rem; color: var(--pr-sub); }
        .pr-amount-cell  { font-size: .85rem; font-weight: 700; color: var(--pr-success); white-space: nowrap; }
        .pr-date-cell    { font-size: .78rem; font-weight: 500; color: var(--pr-text); white-space: nowrap; }
        .pr-remarks-cell { font-size: .78rem; color: var(--pr-sub); max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        /* ── action wrap ── */
        .pr-action-wrap { display: flex; align-items: center; gap: 6px; justify-content: center; }
        .pr-action-wrap a,
        .pr-action-wrap button {
            display: inline-flex; align-items: center; justify-content: center;
            width: 30px; height: 30px; border-radius: 7px;
            border: 1px solid var(--pr-border-dark); background: var(--pr-surface);
            color: var(--pr-sub); font-size: .78rem; text-decoration: none;
            cursor: pointer; transition: all .18s; flex-shrink: 0; padding: 0;
        }
        .pr-action-wrap a:hover { border-color: var(--pr-forest); background: var(--pr-lime-ghost); color: var(--pr-forest); }

        /* ── DT overrides ── */
        .pr-table-card .dt-buttons .btn {
            border-radius: 7px !important; font-size: .78rem !important;
            font-family: 'DM Sans', sans-serif !important; font-weight: 600 !important;
            padding: 5px 14px !important; transition: opacity .18s, transform .15s !important;
            background-image: none !important;
        }
        .pr-table-card .dt-buttons .btn:hover { opacity: .88; transform: translateY(-1px); }
        .pr-table-card .dataTables_wrapper .dataTables_paginate,
        .pr-table-card .dataTables_wrapper .dataTables_info { display: none !important; }
        .dataTables_wrapper .row { margin: 0 !important; }
        .pr-table-card .table-responsive { padding: 0 !important; margin: 0 !important; }

        /* ── pagination ── */
        .pr-pagination-wrap {
            padding: 11px 16px; border-top: 1px solid var(--pr-border);
            background: var(--pr-surface2);
            display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; gap: 10px;
        }
        .pr-pagination-info { font-size: .74rem; font-weight: 500; color: var(--pr-sub); font-family: 'DM Sans', sans-serif; white-space: nowrap; }
        .pr-pagination-info strong { font-weight: 700; color: var(--pr-forest); }
        .pr-pagination-wrap .pagination { gap: 3px; margin: 0; justify-content: center; flex-wrap: wrap; }
        .pr-pagination-wrap .page-link {
            border-radius: 7px !important; border: 1px solid var(--pr-border-dark) !important;
            color: var(--pr-text) !important; font-size: .78rem !important;
            font-family: 'DM Sans', sans-serif !important; padding: 5px 11px !important;
            transition: background .15s, color .15s; background: var(--pr-surface);
        }
        .pr-pagination-wrap .page-item.active .page-link { background: var(--pr-forest) !important; border-color: var(--pr-forest) !important; color: var(--pr-lime) !important; font-weight: 700; }
        .pr-pagination-wrap .page-item.disabled .page-link { opacity: .45; cursor: default; pointer-events: none; }
        .pr-pagination-wrap .page-link:hover:not(.active) { background: var(--pr-muted) !important; color: var(--pr-forest) !important; }
        .pr-pagination-jump { display: flex; align-items: center; gap: 6px; justify-content: flex-end; font-size: .74rem; font-weight: 500; color: var(--pr-sub); font-family: 'DM Sans', sans-serif; }
        .pr-pagination-jump input {
            width: 50px; border: 1.5px solid var(--pr-border-dark); border-radius: 7px;
            padding: 4px 7px; font-size: .74rem; font-family: 'DM Sans', sans-serif;
            color: var(--pr-text); text-align: center; background: var(--pr-surface);
            transition: border-color .2s, box-shadow .2s;
        }
        .pr-pagination-jump input:focus { outline: none; border-color: var(--pr-forest-mid); box-shadow: 0 0 0 3px rgba(6,78,59,.10); }
        .pr-pagination-jump button {
            background: var(--pr-forest); color: var(--pr-lime); border: none;
            border-radius: 7px; padding: 4px 10px; font-size: .72rem; font-weight: 700;
            font-family: 'DM Sans', sans-serif; cursor: pointer; transition: background .18s;
        }
        .pr-pagination-jump button:hover { background: var(--pr-forest-mid); }

        @media (max-width: 640px) {
            .pr-pagination-wrap { grid-template-columns: 1fr; justify-items: center; }
            .pr-pagination-info, .pr-pagination-jump { display: none; }
        }
        @media (max-width: 768px) {
            .pr-hero-inner { flex-direction: column; align-items: flex-start; }
            .pr-hero { padding: 16px 18px; }
            .pr-dt-toolbar { flex-direction: column; align-items: flex-start; }
            .pr-adv-group { min-width: 100%; }
        }
    </style>

    <div class="pr-page">

        {{-- ══ HERO ══ --}}
        <div class="pr-hero">
            <div class="pr-hero-inner">
                <div class="pr-hero-left">
                    <div class="pr-hero-icon"><i class="fas fa-wallet"></i></div>
                    <div>
                        <div class="pr-hero-title">{{ __('Budget Records') }}</div>
                        <div class="pr-hero-meta">
                            <span class="pr-badge pr-badge-count">
                                {{ $budgetAllocations->total() }} {{ $budgetAllocations->total() === 1 ? 'record' : 'records' }}
                            </span>
                            @if(request('search'))
                                <span class="pr-badge pr-badge-status">
                                    <i class="fas fa-search" style="font-size:.6rem;"></i>
                                    "{{ request('search') }}"
                                </span>
                            @endif
                            @if(request('case_category'))
                                <span class="pr-badge pr-badge-status">
                                    <i class="fas fa-folder" style="font-size:.6rem;"></i>
                                    {{ request('case_category') }}
                                </span>
                            @endif
                            @if(request('case_type'))
                                <span class="pr-badge pr-badge-status">
                                    <i class="fas fa-tag" style="font-size:.6rem;"></i>
                                    {{ request('case_type') }}
                                </span>
                            @endif
                            @if(request('date_processed'))
                                <span class="pr-badge pr-badge-status">
                                    <i class="fas fa-calendar-alt" style="font-size:.6rem;"></i>
                                    {{ \Carbon\Carbon::parse(request('date_processed'))->format('M j, Y') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══ FILTER BAR ══ --}}
@php
    $searchTerm = request('search', '');
    $caseCategory = request('case_category', '');
    $caseType = request('case_type', '');
    $dateProcessed = request('date_processed', '');
@endphp
<div class="pr-adv-bar">
    <form method="GET" action="{{ route('admin.budget-records.index') }}" id="budgetFilterForm">
        <div class="pr-adv-bar-inner">
            <div class="pr-adv-group">
                <span class="pr-adv-label"><i class="fas fa-calendar-alt me-1"></i>Date Processed</span>
                <input type="date" name="date_processed"
                       class="pr-adv-input {{ $dateProcessed ? 'has-value' : '' }}"
                       value="{{ $dateProcessed }}"
                       onchange="this.classList.toggle('has-value', this.value !== '')">
            </div>
            
            <div class="pr-adv-group">
                <span class="pr-adv-label"><i class="fas fa-folder me-1"></i>Case Category</span>
                <select name="case_category" class="pr-adv-select {{ $caseCategory ? 'has-value' : '' }}">
                    <option value="">All Categories</option>
                    @foreach($caseCategoryOptions as $value => $label)
                        <option value="{{ $value }}" {{ $caseCategory == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="pr-adv-group">
                <span class="pr-adv-label"><i class="fas fa-tag me-1"></i>Case Type</span>
                <select name="case_type" class="pr-adv-select {{ $caseType ? 'has-value' : '' }}">
                    <option value="">All Types</option>
                    @foreach($caseTypeOptions as $value => $label)
                        <option value="{{ $value }}" {{ $caseType == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="pr-adv-group" style="flex:2 1 300px;">
                <span class="pr-adv-label"><i class="fas fa-search me-1"></i>Search</span>
                <div style="position:relative;display:flex;align-items:center;">
                    <i class="fas fa-search" style="position:absolute;left:10px;color:var(--pr-sub);font-size:.76rem;pointer-events:none;"></i>
                    <input type="text" name="search"
                           class="pr-adv-input {{ $searchTerm ? 'has-value' : '' }}"
                           style="padding-left:30px;padding-right:{{ $searchTerm ? '28px' : '10px' }};"
                           value="{{ $searchTerm }}"
                           placeholder="Search by patient name, control #, claimant, amount..."
                           oninput="this.classList.toggle('has-value', this.value !== '')">
                    @if($searchTerm)
                        <a href="{{ route('admin.budget-records.index', array_filter(request()->except('search', 'page'))) }}"
                           style="position:absolute;right:8px;color:var(--pr-border-dark);font-size:.72rem;text-decoration:none;line-height:1;padding:2px 3px;transition:color .15s;"
                           onmouseover="this.style.color='var(--pr-danger)'" onmouseout="this.style.color='var(--pr-border-dark)'"
                           title="Clear search"><i class="fas fa-times"></i></a>
                    @endif
                </div>
            </div>
            
            <div class="pr-adv-actions">
                <button type="submit" class="pr-adv-apply">
                    <i class="fas fa-filter" style="font-size:.72rem;"></i> Apply
                </button>
                @if($searchTerm || $caseCategory || $caseType || $dateProcessed)
                    <a href="{{ route('admin.budget-records.index') }}" class="pr-adv-reset">
                        <i class="fas fa-times" style="font-size:.72rem;"></i> Clear
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>

        <div class="pr-table-card">
            <div class="table-responsive">
                <table class="table datatable datatable-Budget" style="width:100%">
                    <thead>
                        <tr>
                            <th width="10"></th>
                            <th>{{ __('Patient Name') }}</th>
                            <th>{{ __('Case Category') }}</th>
                            <th>{{ __('Case Type') }}</th>
                            <th>{{ __('Budget Allocated') }}</th>
                            <th>{{ __('Remarks') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Allocation Date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($budgetAllocations as $record)
                            <tr data-entry-id="{{ $record->id }}">
                                <td></td>
                                <td class="pr-name-cell">{{ $record->patient->patient_name ?? 'N/A' }}</td>
                                <td class="pr-sub-cell">{{ $record->patient->case_category ?? 'N/A' }}</td>
                                <td class="pr-sub-cell">{{ $record->patient->case_type ?? 'N/A' }}</td>
                                <td class="pr-amount-cell">₱{{ number_format($record->amount, 2) }}</td>
                                <td class="pr-remarks-cell" title="{{ $record->remarks ?? '-' }}">
                                    {{ $record->remarks ?? '-' }}
                                </td>
                                <td>
                                    @php
                                        $statusClass = match($record->budget_status) {
                                            'Disbursed' => 'pr-status-disbursed',
                                            'Ready for Disbursement' => 'pr-status-ready',
                                            'Not Disbursed' => 'pr-status-not',
                                            default => 'pr-status-default'
                                        };
                                    @endphp
                                    <span class="pr-status-badge {{ $statusClass }}">
                                        @if($record->budget_status == 'Disbursed')
                                            <i class="fas fa-check-circle" style="font-size:.7rem;"></i>
                                        @elseif($record->budget_status == 'Ready for Disbursement')
                                            <i class="fas fa-clock" style="font-size:.7rem;"></i>
                                        @elseif($record->budget_status == 'Not Disbursed')
                                            <i class="fas fa-hourglass" style="font-size:.7rem;"></i>
                                        @endif
                                        {{ $record->budget_status ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="pr-date-cell"
                                    data-order="{{ \Carbon\Carbon::parse($record->allocation_date)->timestamp }}">
                                    {{ \Carbon\Carbon::parse($record->allocation_date)->format('M j, Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- ══ PAGINATION FOOTER ══ --}}
            @php
                $cur   = $budgetAllocations->currentPage();
                $last  = $budgetAllocations->lastPage();
                $perPg = $budgetAllocations->perPage();
                $tot   = $budgetAllocations->total();
                $from  = $tot > 0 ? ($cur - 1) * $perPg + 1 : 0;
                $to    = min($cur * $perPg, $tot);
                $pages = collect();
                for ($i = 1; $i <= $last; $i++) {
                    if ($i === 1 || $i === $last || abs($i - $cur) <= 2) $pages->push($i);
                }
                $pageUrl = fn($p) => request()->fullUrlWithQuery(['page' => $p]);
            @endphp
            <div class="pr-pagination-wrap">
                <div class="pr-pagination-info">
                    @if($tot > 0)
                        Showing <strong>{{ $from }}–{{ $to }}</strong> of <strong>{{ $tot }}</strong> records
                    @else
                        No records found
                    @endif
                </div>
                @if($last > 1)
                    <nav aria-label="Budget records pagination">
                        <ul class="pagination">
                            <li class="page-item {{ $cur <= 1 ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $cur > 1 ? $pageUrl($cur - 1) : '#' }}">‹</a>
                            </li>
                            @php $prev = null; @endphp
                            @foreach($pages as $p)
                                @if($prev !== null && $p - $prev > 1)
                                    <li class="page-item disabled">
                                        <span class="page-link" style="border:none;background:transparent;padding:5px 4px;color:var(--pr-sub);">…</span>
                                    </li>
                                @endif
                                <li class="page-item {{ $p === $cur ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $pageUrl($p) }}">{{ $p }}</a>
                                </li>
                                @php $prev = $p; @endphp
                            @endforeach
                            <li class="page-item {{ $cur >= $last ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $cur < $last ? $pageUrl($cur + 1) : '#' }}">›</a>
                            </li>
                        </ul>
                    </nav>
                @else
                    <div></div>
                @endif
                @if($last > 1)
                    <div class="pr-pagination-jump">
                        <span>Go to</span>
                        <input type="number" id="budget-page-jump" min="1" max="{{ $last }}"
                               placeholder="{{ $cur }}" aria-label="Go to page">
                        <button type="button" id="budget-page-jump-btn">Go</button>
                    </div>
                @else
                    <div></div>
                @endif
            </div>
        </div>

    </div>{{-- /pr-page --}}

@endsection

@section('scripts')
@parent
<script>
    $(function () {
        const total = {{ $budgetAllocations->total() }};
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
        let _token = $('meta[name="csrf-token"]').attr('content');

        @can('budget_delete')
        dtButtons.push({
            text: '<i class="fas fa-trash me-1"></i> Delete Selected',
            className: 'btn-danger',
            action: function (e, dt) {
                const ids = $.map(dt.rows({ selected: true }).nodes(), entry => $(entry).data('entry-id'));
                if (!ids.length) { alert('No records selected'); return; }
                if (!confirm('Are you sure you want to delete the selected budget records?')) return;
                $.ajax({
                    headers: { 'x-csrf-token': _token },
                    method: 'POST',
                    url: "{{ route('admin.budget-records.massDestroy') }}",
                    data: { ids: ids, _method: 'DELETE' }
                }).done(function () { location.reload(); });
            }
        });
        @endcan

        const table = $('.datatable-Budget:not(.ajaxTable)').DataTable({
            buttons: dtButtons,
            order: [[7, 'desc']], // Sort by allocation date
            orderCellsTop: true,
            columnDefs: [
                { targets: 0, orderable: false, searchable: false, className: 'select-checkbox' },
                { targets: 4, type: 'num' }, // Amount column
                { targets: 7, type: 'num' } // Date column
            ],
            select: { style: 'multi', selector: 'td:first-child' },
            paging: false, info: false, searching: false, processing: true, serverSide: false,
            initComplete: function () {
                const dtWrapper  = $('.pr-table-card .dataTables_wrapper');
                const dtBtnGroup = dtWrapper.find('.dt-buttons');

                const currentPage = {{ $budgetAllocations->currentPage() }};
                const lastPage    = {{ $budgetAllocations->lastPage() }};

                const indicator = $(`
                    <div class="pr-record-indicator" id="budget-indicator">
                        <span class="ri-dot"></span>
                        <span style="color:var(--pr-sub);font-weight:500;">Page</span>
                        <span class="ri-count" style="font-weight:800;color:var(--pr-forest);">${currentPage}</span>
                        <span style="color:var(--pr-sub);font-weight:500;">of ${lastPage}</span>
                    </div>
                `);

                const toolbar   = $('<div class="pr-dt-toolbar"></div>');
                const leftWrap  = $('<div class="pr-dt-toolbar-left"></div>');
                const rightWrap = $('<div class="pr-dt-toolbar-right"></div>');
                leftWrap.append(dtBtnGroup.children().detach());
                rightWrap.append(indicator);
                toolbar.append(leftWrap).append(rightWrap);
                $('.pr-table-card .table-responsive').before(toolbar);

                table.on('select deselect', function () {
                    const sel = table.rows({ selected: true }).count();
                    if (sel > 0) {
                        $('#budget-indicator').css({ background: 'rgba(6,78,59,.10)', borderColor: 'rgba(6,78,59,.35)' });
                        $('#budget-indicator .ri-dot').css('background', 'var(--pr-forest)');
                        $('#budget-indicator span:last').text(`of ${lastPage} · ${sel} selected`);
                    } else {
                        $('#budget-indicator').css({ background: '', borderColor: '' });
                        $('#budget-indicator .ri-dot').css('background', '');
                        $('#budget-indicator .ri-count').text(currentPage);
                        $('#budget-indicator span:last').text(`of ${lastPage}`);
                    }
                });
            }
        });

        $('#budget-page-jump-btn').on('click', function () {
            const val = parseInt($('#budget-page-jump').val());
            const max = parseInt($('#budget-page-jump').attr('max') || 1);
            if (!val || val < 1 || val > max) { $('#budget-page-jump').focus(); return; }
            const url = new URL(window.location.href);
            url.searchParams.set('page', val);
            window.location.href = url.toString();
        });
        $('#budget-page-jump').on('keydown', function (e) {
            if (e.key === 'Enter') $('#budget-page-jump-btn').trigger('click');
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab click', function () {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    });
</script>
@endsection