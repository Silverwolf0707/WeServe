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
.pr-hero-inner { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; position: relative; z-index: 1; }
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
.pr-badge-count   { background: rgba(116,255,112,.14); border: 1px solid rgba(116,255,112,.32); color: var(--pr-lime); }
.pr-hero-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

.pr-btn-primary {
    display: inline-flex; align-items: center; gap: 7px;
    background: var(--pr-lime); color: var(--pr-forest);
    border: none; border-radius: 8px; padding: 7px 16px;
    font-size: .8rem; font-weight: 700; font-family: 'DM Sans', sans-serif;
    text-decoration: none; cursor: pointer;
    transition: background .18s, transform .15s, box-shadow .18s;
    white-space: nowrap; box-shadow: var(--pr-shadow-lime);
}
.pr-btn-primary:hover { background: var(--pr-lime-dim); color: var(--pr-forest); transform: translateY(-1px); box-shadow: 0 4px 18px rgba(116,255,112,.40); }

/* ── ribbon ── */
.pr-ribbon {
    background: var(--pr-surface); border-radius: var(--pr-radius);
    border: 1px solid var(--pr-border); padding: 11px 18px; margin-bottom: 10px;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 10px; box-shadow: 0 1px 4px rgba(6,78,59,.06);
}
.pr-ribbon-left { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.pr-search-tag {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--pr-lime-ghost); border: 1px solid var(--pr-lime-border);
    border-radius: 20px; padding: 3px 10px; font-size: .74rem; font-weight: 600; color: var(--pr-forest-mid);
}
.pr-search-tag strong { font-weight: 700; color: var(--pr-forest); }

/* ── advanced filter bar with search ── */
.pr-adv-bar {
    background: var(--pr-surface2); border-radius: var(--pr-radius);
    border: 1px solid var(--pr-border); padding: 12px 18px; margin-bottom: 16px;
    box-shadow: 0 1px 4px rgba(6,78,59,.05);
}
.pr-adv-bar-inner { 
    display: flex; 
    align-items: flex-end; 
    flex-wrap: wrap; 
    gap: 10px; 
}
.pr-adv-group { 
    display: flex; 
    flex-direction: column; 
    gap: 4px; 
    min-width: 160px; 
    flex: 1 1 160px; 
}
.pr-adv-group.date-group { 
    min-width: 130px; 
    flex: 1 1 130px; 
}
.pr-adv-label { 
    font-size: .68rem; 
    font-weight: 700; 
    letter-spacing: .06em; 
    text-transform: uppercase; 
    color: var(--pr-sub); 
    white-space: nowrap; 
}
.pr-adv-select {
    border: 1.5px solid var(--pr-border-dark); border-radius: 8px; padding: 6px 10px;
    font-size: .8rem; font-family: 'DM Sans', sans-serif; color: var(--pr-text); background: var(--pr-surface);
    transition: border-color .2s, box-shadow .2s; width: 100%; appearance: none; -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%233d7a62'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 10px center; padding-right: 28px;
}
.pr-adv-select:focus { outline: none; border-color: var(--pr-forest-mid); box-shadow: 0 0 0 3px rgba(6,78,59,.12); }
.pr-adv-select option { font-family: 'DM Sans', sans-serif; }
.pr-adv-select.has-value { border-color: var(--pr-forest); background-color: var(--pr-lime-ghost); color: var(--pr-forest); font-weight: 600; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%23064e3b'/%3E%3C/svg%3E"); }

.pr-adv-date {
    border: 1.5px solid var(--pr-border-dark); border-radius: 8px; padding: 6px 10px;
    font-size: .8rem; font-family: 'DM Sans', sans-serif; color: var(--pr-text); background: var(--pr-surface);
    transition: border-color .2s, box-shadow .2s; width: 100%;
}
.pr-adv-date:focus { outline: none; border-color: var(--pr-forest-mid); box-shadow: 0 0 0 3px rgba(6,78,59,.12); }
.pr-adv-date.has-value { border-color: var(--pr-forest); background-color: var(--pr-lime-ghost); color: var(--pr-forest); font-weight: 600; }

.pr-adv-actions { 
    display: flex; 
    align-items: flex-end; 
    gap: 6px; 
    flex-shrink: 0; 
    padding-bottom: 1px; 
}
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

/* Search in advanced filter bar */
.pr-adv-search {
    margin-left: auto;
    display: flex;
    align-items: flex-end;
}
.pr-search-wrap { 
    position: relative; 
    display: flex; 
    align-items: center; 
}
.pr-search-wrap .si { 
    position: absolute; 
    left: 11px; 
    color: var(--pr-sub); 
    font-size: .8rem; 
    pointer-events: none; 
}
.pr-search-input {
    border: 1.5px solid var(--pr-border-dark); border-radius: 8px; padding: 7px 60px 7px 32px;
    font-size: .8rem; font-family: 'DM Sans', sans-serif; width: 250px;
    color: var(--pr-text); background: var(--pr-surface); transition: border-color .2s, box-shadow .2s;
}
.pr-search-input:focus { outline: none; border-color: var(--pr-forest-mid); box-shadow: 0 0 0 3px rgba(6,78,59,.12); }
.pr-search-input::placeholder { color: var(--pr-border-dark); }
.pr-search-clear { 
    position: absolute; 
    right: 34px; 
    background: none; 
    border: none; 
    color: var(--pr-border-dark); 
    font-size: .75rem; 
    cursor: pointer; 
    padding: 2px 4px; 
    transition: color .15s; 
    line-height: 1; 
}
.pr-search-clear:hover { color: var(--pr-danger); }
.pr-search-btn { 
    position: absolute; 
    right: 5px; 
    background: var(--pr-forest); 
    border: none; 
    border-radius: 5px; 
    width: 26px; 
    height: 26px; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    color: var(--pr-lime); 
    font-size: .72rem; 
    cursor: pointer; 
    transition: background .18s; 
}
.pr-search-btn:hover { background: var(--pr-forest-mid); }

.pr-active-filters { 
    display: flex; 
    flex-wrap: wrap; 
    gap: 6px; 
    margin-top: 10px; 
    padding-top: 10px; 
    border-top: 1px solid var(--pr-border); 
}
.pr-filter-chip {
    display: inline-flex; align-items: center; gap: 5px;
    background: var(--pr-lime-ghost); border: 1px solid var(--pr-lime-border);
    border-radius: 20px; padding: 3px 10px; font-size: .73rem; font-weight: 600; color: var(--pr-forest);
}
.pr-filter-chip .chip-label { color: var(--pr-sub); font-weight: 500; }
.pr-filter-chip a { color: var(--pr-border-dark); text-decoration: none; font-size: .7rem; line-height: 1; margin-left: 2px; }
.pr-filter-chip a:hover { color: var(--pr-danger); }

/* ── table card ── */
.pr-table-card {
    background: var(--pr-surface); border-radius: var(--pr-radius);
    border: 1px solid var(--pr-border); box-shadow: var(--pr-shadow); overflow: hidden;
}
.pr-table-card .dt-buttons .btn { border-radius: 7px !important; font-size: .78rem !important; font-family: 'DM Sans', sans-serif !important; font-weight: 600 !important; padding: 5px 14px !important; transition: opacity .18s, transform .15s !important; background-image: none !important; }
.pr-table-card .dt-buttons .btn:hover { opacity: .88; transform: translateY(-1px); }
.pr-table-card .dataTables_wrapper .dataTables_info {
    font-family: 'DM Sans', sans-serif !important; font-size: .74rem !important;
    font-weight: 500 !important; color: var(--pr-sub) !important;
    padding: 10px 16px !important; border-top: 1px solid var(--pr-border) !important;
    background: var(--pr-surface2) !important; margin: 0 !important; line-height: 1.5 !important;
}

/* Remove any default spacing */
.dataTables_wrapper .row {
    margin: 0 !important;
}
/* Ensure table container has no extra spacing */
.pr-table-card .table-responsive {
    padding: 0 !important;
    margin: 0 !important;
}


/* ── dt-buttons toolbar ── */
.pr-dt-toolbar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 16px 8px; border-bottom: 1px solid var(--pr-border);
    background: var(--pr-surface2); flex-wrap: wrap; gap: 8px;
}
.pr-dt-toolbar .dt-buttons { display: flex; gap: 5px; flex-wrap: wrap; align-items: center; }
.pr-record-indicator {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--pr-lime-ghost); border: 1px solid var(--pr-lime-border);
    border-radius: 20px; padding: 4px 12px; font-size: .72rem;
    font-weight: 600; color: var(--pr-forest); white-space: nowrap; flex-shrink: 0;
    font-family: 'DM Sans', sans-serif;
}
.pr-record-indicator .ri-count { font-weight: 800; color: var(--pr-forest); }
.pr-record-indicator .ri-dot { width: 5px; height: 5px; border-radius: 50%; background: var(--pr-lime-dim); flex-shrink: 0; }

/* ── action column buttons ── */
.pr-action-wrap { display: flex; align-items: center; gap: 6px; justify-content: center; }
.pr-action-wrap a,
.pr-action-wrap button {
    display: inline-flex; align-items: center; justify-content: center;
    width: 30px; height: 30px; border-radius: 7px;
    border: 1px solid var(--pr-border-dark); background: var(--pr-surface);
    color: var(--pr-sub); font-size: .78rem; text-decoration: none;
    cursor: pointer; transition: all .18s; flex-shrink: 0;
    padding: 0;
}
.pr-action-wrap a:hover { border-color: var(--pr-forest); background: var(--pr-lime-ghost); color: var(--pr-forest); }

/* ── pagination ── */
.pr-pagination-wrap {
    padding: 11px 16px; border-top: 1px solid var(--pr-border);
    background: var(--pr-surface2);
    display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; gap: 10px;
}
.pr-pagination-info { font-size: .74rem; font-weight: 500; color: var(--pr-sub); font-family: 'DM Sans', sans-serif; white-space: nowrap; }
.pr-pagination-info strong { font-weight: 700; color: var(--pr-forest); }
.pr-pagination-wrap .pagination { gap: 3px; margin: 0; justify-content: center; }
.pr-pagination-wrap .page-link { border-radius: 7px !important; border: 1px solid var(--pr-border-dark) !important; color: var(--pr-text) !important; font-size: .78rem !important; font-family: 'DM Sans', sans-serif !important; padding: 5px 11px !important; transition: background .15s, color .15s; }
.pr-pagination-wrap .page-item.active .page-link { background: var(--pr-forest) !important; border-color: var(--pr-forest) !important; color: var(--pr-lime) !important; font-weight: 700; }
.pr-pagination-wrap .page-link:hover:not(.active) { background: var(--pr-muted) !important; color: var(--pr-forest) !important; }
.pr-pagination-jump { display: flex; align-items: center; gap: 6px; justify-content: flex-end; font-size: .74rem; font-weight: 500; color: var(--pr-sub); font-family: 'DM Sans', sans-serif; }
.pr-pagination-jump input {
    width: 50px; border: 1.5px solid var(--pr-border-dark); border-radius: 7px;
    padding: 4px 7px; font-size: .74rem; font-family: 'DM Sans', sans-serif;
    color: var(--pr-text); text-align: center; background: var(--pr-surface); transition: border-color .2s, box-shadow .2s;
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

/* ── table styles ── */
.table { font-size: .8rem; margin-bottom: 0; }
.table thead th { 
    background: var(--pr-forest); 
    color: var(--pr-surface); 
    font-weight: 700; 
    font-size: .72rem;
    letter-spacing: .03em;
    text-transform: uppercase;
    border-bottom: 2px solid var(--pr-border-dark);
    padding: 12px 10px;
}
.table tbody td { 
    padding: 10px; 
    vertical-align: middle;
    color: var(--pr-text);
    border-bottom: 1px solid var(--pr-border);
}
.table tbody tr:hover { background: var(--pr-muted); }

/* ── modal styles ── */
.pr-modal .modal-content { border: none; border-radius: 14px; overflow: hidden; box-shadow: var(--pr-shadow-lg); font-family: 'DM Sans', sans-serif; }
.pr-modal .modal-header { padding: 18px 22px 16px; border-bottom: 1px solid var(--pr-border); background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%); }
.pr-modal .modal-title { font-size: .95rem; font-weight: 700; color: #fff; }
.pr-modal .modal-body { padding: 20px 22px; font-size: .85rem; color: var(--pr-text); line-height: 1.6; }
.pr-modal .modal-footer { padding: 14px 22px; border-top: 1px solid var(--pr-border); gap: 8px; background: var(--pr-surface2); }
.pr-modal .modal-footer .btn { border-radius: 8px; font-size: .8rem; font-family: 'DM Sans', sans-serif; font-weight: 600; padding: 7px 18px; border: none; transition: opacity .18s, transform .15s; background-image: none !important; }
.pr-modal .modal-footer .btn:hover { opacity: .88; transform: translateY(-1px); }
.pr-modal .modal-footer .btn-secondary { background: var(--pr-muted) !important; color: var(--pr-sub) !important; border: 1px solid var(--pr-border-dark) !important; }
.pr-modal .modal-footer .btn-danger    { background: var(--pr-danger) !important; color: #fff !important; }
</style>

<div class="pr-page">

{{-- ══ HERO ══ --}}
<div class="pr-hero">
    <div class="pr-hero-inner">
        <div class="pr-hero-left">
            <div class="pr-hero-icon"><i class="fas fa-file-medical"></i></div>
            <div>
                <div class="pr-hero-title">Online Patient Applications</div>
                <div class="pr-hero-meta">
                    <span class="pr-badge pr-badge-count">
                        {{ $applications->total() }} {{ $applications->total() === 1 ? 'application' : 'applications' }}
                    </span>
                    @php 
                        $activeFilterCount = collect([
                            request('case_category'),
                            request('case_type'),
                            request('application_date'),
                            request('search')
                        ])->filter()->count(); 
                    @endphp
                    @if($activeFilterCount)
                        <span class="pr-badge" style="background:rgba(245,166,35,.18);border:1px solid rgba(245,166,35,.4);color:#f5a623;">
                            <i class="fas fa-filter" style="font-size:.6rem;"></i> {{ $activeFilterCount }} filter{{ $activeFilterCount > 1 ? 's' : '' }} active
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ ADVANCED FILTER BAR WITH SEARCH ── --}}
<div class="pr-adv-bar">
    <form method="GET" action="{{ route('admin.online-applications.index') }}" id="advFilterForm">
        <div class="pr-adv-bar-inner">
            <div class="pr-adv-group">
                <span class="pr-adv-label"><i class="fas fa-tag me-1"></i>Case Category</span>
                <select name="case_category" class="pr-adv-select {{ request('case_category') ? 'has-value' : '' }}" onchange="this.classList.toggle('has-value', this.value !== '')">
                    <option value="">All Categories</option>
                    @foreach($caseCategoryOptions as $value => $label)
                        <option value="{{ $value }}" {{ request('case_category') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="pr-adv-group">
                <span class="pr-adv-label"><i class="fas fa-folder me-1"></i>Case Type</span>
                <select name="case_type" class="pr-adv-select {{ request('case_type') ? 'has-value' : '' }}" onchange="this.classList.toggle('has-value', this.value !== '')">
                    <option value="">All Types</option>
                    @foreach($caseTypeOptions as $value => $label)
                        <option value="{{ $value }}" {{ request('case_type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            
            {{-- Single Date Filter --}}
            <div class="pr-adv-group date-group">
                <span class="pr-adv-label"><i class="fas fa-calendar-alt me-1"></i>Application Date</span>
                <input type="date" name="application_date" class="pr-adv-date {{ request('application_date') ? 'has-value' : '' }}" 
                       value="{{ request('application_date') }}" 
                       onchange="this.classList.toggle('has-value', this.value !== '')">
            </div>
            
            <div class="pr-adv-actions">
                <button type="submit" class="pr-adv-apply">
                    <i class="fas fa-filter" style="font-size:.72rem;"></i> Apply
                </button>
                @if(request('case_category') || request('case_type') || request('application_date') || request('search'))
                    <a href="{{ route('admin.online-applications.index') }}"
                       class="pr-adv-reset">
                        <i class="fas fa-times" style="font-size:.72rem;"></i> Clear All
                    </a>
                @endif
            </div>
            
            {{-- Search input moved to rightmost side --}}
            <div class="pr-adv-search">
                <div class="pr-search-wrap">
                    <i class="fas fa-search si"></i>
                    <input type="text" name="search" class="pr-search-input" placeholder="Search applications…"
                           value="{{ request('search') }}" aria-label="Search">
                    @if(request('search'))
                        <a href="{{ route('admin.online-applications.index', array_filter(request()->except('search','page'))) }}"
                           class="pr-search-clear" title="Clear"><i class="fas fa-times"></i></a>
                    @endif
                    <button type="submit" class="pr-search-btn"><i class="fas fa-arrow-right"></i></button>
                </div>
            </div>
        </div>
        
        {{-- Active filters display --}}
        @if(request('case_category') || request('case_type') || request('application_date') || request('search'))
            <div class="pr-active-filters">
                @if(request('search'))
                    <div class="pr-filter-chip">
                        <span class="chip-label">Search:</span> "{{ request('search') }}"
                        <a href="{{ route('admin.online-applications.index', array_filter(array_merge(request()->except('search'), ['page' => null]))) }}"><i class="fas fa-times"></i></a>
                    </div>
                @endif
                @if(request('case_category'))
                    <div class="pr-filter-chip">
                        <span class="chip-label">Category:</span> {{ $caseCategoryOptions[request('case_category')] ?? request('case_category') }}
                        <a href="{{ route('admin.online-applications.index', array_filter(array_merge(request()->except('case_category'), ['page' => null]))) }}"><i class="fas fa-times"></i></a>
                    </div>
                @endif
                @if(request('case_type'))
                    <div class="pr-filter-chip">
                        <span class="chip-label">Type:</span> {{ $caseTypeOptions[request('case_type')] ?? request('case_type') }}
                        <a href="{{ route('admin.online-applications.index', array_filter(array_merge(request()->except('case_type'), ['page' => null]))) }}"><i class="fas fa-times"></i></a>
                    </div>
                @endif
                @if(request('application_date'))
                    <div class="pr-filter-chip">
                        <span class="chip-label">Date:</span> {{ \Carbon\Carbon::parse(request('application_date'))->format('M d, Y') }}
                        <a href="{{ route('admin.online-applications.index', array_filter(array_merge(request()->except('application_date'), ['page' => null]))) }}"><i class="fas fa-times"></i></a>
                    </div>
                @endif
            </div>
        @endif
    </form>
</div>

{{-- ══ TABLE ══ --}}
<div class="pr-table-card">
    <div class="table-responsive">
        <table class="table datatable-OnlineApplication" style="width:100%">
            <thead>
                <tr>
                    <th width="10"></th>
                    <th>ID</th>
                    <th>Case Type</th>
                    <th>Case Category</th>
                    <th>Claimant Name</th>
                    <th>Applicant Name</th>
                    <th>Diagnosis</th>
                    <th>Age</th>
                    <th>Tracking ID</th>
                    <th>Date Applied</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $application)
                    <tr data-entry-id="{{ $application->id }}">
                        <td></td>
                        <td>{{ $application->id }}</td>
                        <td>{{ $application->case_type }}</td>
                        <td>{{ $application->case_category }}</td>
                        <td>{{ $application->claimant_name }}</td>
                        <td>{{ $application->applicant_name }}</td>
                        <td class="text-truncate" style="max-width:200px">{{ $application->diagnosis }}</td>
                        <td>{{ $application->age }}</td>
                        <td><span class="badge bg-info">{{ $application->tracking_number }}</span></td>
                        <td data-sort="{{ $application->created_at->timestamp }}">
                            {{ $application->created_at->format('M d, Y') }}
                        </td>
                        <td>
                            <div class="pr-action-wrap">
                                <a href="{{ route('admin.online-applications.show', $application->id) }}" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ── pagination footer ── --}}
    @if($applications->total() > 0)
    @php
        $cur    = $applications->currentPage();
        $last   = $applications->lastPage();
        $from   = ($cur - 1) * $applications->perPage() + 1;
        $to     = min($cur * $applications->perPage(), $applications->total());
        $total  = $applications->total();
        $pages  = collect();
        for ($i = 1; $i <= $last; $i++) {
            if ($i === 1 || $i === $last || abs($i - $cur) <= 2) $pages->push($i);
        }
        $pages   = $pages->unique()->sort()->values();
        $pageUrl = fn($p) => request()->fullUrlWithQuery(['page' => $p]);
    @endphp
    <div class="pr-pagination-wrap">
        <div class="pr-pagination-info">
            Showing <strong>{{ $from }}–{{ $to }}</strong> of <strong>{{ $total }}</strong> records
        </div>
        @if($last > 1)
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item {{ $cur <= 1 ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $cur > 1 ? $pageUrl($cur - 1) : '#' }}" aria-label="Previous">‹</a>
                </li>
                @php $prev = null; @endphp
                @foreach($pages as $p)
                    @if($prev !== null && $p - $prev > 1)
                        <li class="page-item disabled"><span class="page-link" style="border:none;background:transparent;padding:5px 4px;color:var(--pr-sub);;">…</span></li>
                    @endif
                    <li class="page-item {{ $p === $cur ? 'active' : '' }}">
                        <a class="page-link" href="{{ $pageUrl($p) }}">{{ $p }}</a>
                    </li>
                    @php $prev = $p; @endphp
                @endforeach
                <li class="page-item {{ $cur >= $last ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $cur < $last ? $pageUrl($cur + 1) : '#' }}" aria-label="Next">›</a>
                </li>
            </ul>
        </nav>
        @else
            <div></div>
        @endif
        @if($last > 1)
            <div class="pr-pagination-jump">
                <span>Go to</span>
                <input type="number" id="pr-page-jump" min="1" max="{{ $last }}"
                       placeholder="{{ $cur }}" aria-label="Go to page">
                <button type="button" id="pr-page-jump-btn">Go</button>
            </div>
        @else
            <div></div>
        @endif
    </div>
    @endif
</div>

</div>



@endsection

@section('scripts')
@parent
<script>
jQuery(function() {
    let _token = $('meta[name="csrf-token"]').attr('content');
    let dtButtons = jQuery.extend(true, [], jQuery.fn.dataTable.defaults.buttons);
    
    let table = jQuery('.datatable-OnlineApplication:not(.ajaxTable)').DataTable({
        buttons: dtButtons,
        order: [[9, 'desc']], // Sort by date applied column
        pageLength: 100,
        orderCellsTop: true,
        columnDefs: [
            { targets: 0, orderable: false, searchable: false, className: 'select-checkbox' },
            { targets: 9, type: 'num' }
        ],
        select: { style: 'multi', selector: 'td:first-child' },
        paging: false, 
        info: false, 
        searching: false, 
        processing: true, 
        serverSide: false,
        initComplete: function() {
            const dtWrapper  = jQuery('.pr-table-card .dataTables_wrapper');
            const dtBtnGroup = dtWrapper.find('.dt-buttons');
            const currentPage = {{ $applications->currentPage() }};
            const lastPage    = {{ $applications->lastPage() }};

            const indicator = jQuery(`
                <div class="pr-record-indicator" id="pr-record-indicator">
                    <span class="ri-dot"></span>
                    <span style="color:var(--pr-sub);font-weight:500;">Page</span>
                    <span class="ri-count" id="pr-ri-count">${currentPage}</span>
                    <span style="color:var(--pr-sub);font-weight:500;">of ${lastPage}</span>
                </div>
            `);

            const toolbar = jQuery('<div class="pr-dt-toolbar"></div>');
            const btnWrap = jQuery('<div style="display:flex;gap:5px;flex-wrap:wrap;"></div>');
            btnWrap.append(dtBtnGroup.children().detach());
            toolbar.append(btnWrap).append(indicator);
            jQuery('.pr-table-card .table-responsive').before(toolbar);

            table.on('select deselect', function() {
                const sel = table.rows({ selected: true }).count();
                if (sel > 0) {
                    jQuery('#pr-record-indicator').css({ 'background': 'rgba(6,78,59,.10)', 'border-color': 'rgba(6,78,59,.35)' });
                    jQuery('#pr-record-indicator .ri-dot').css('background', 'var(--pr-forest)');
                    jQuery('#pr-record-indicator span:last').text(`of ${lastPage} · ${sel} selected`);
                } else {
                    jQuery('#pr-record-indicator').css({ 'background': '', 'border-color': '' });
                    jQuery('#pr-record-indicator .ri-dot').css('background', '');
                    jQuery('#pr-ri-count').text(currentPage);
                    jQuery('#pr-record-indicator span:last').text(`of ${lastPage}`);
                }
            });
        }
    });

    // Page jump functionality
    jQuery(document).on('click', '#pr-page-jump-btn', function() {
        const val = parseInt(jQuery('#pr-page-jump').val());
        const max = parseInt(jQuery('#pr-page-jump').attr('max') || 1);
        if (!val || val < 1 || val > max) { 
            jQuery('#pr-page-jump').focus(); 
            return; 
        }
        const url = new URL(window.location.href);
        url.searchParams.set('page', val);
        window.location.href = url.toString();
    });
    
    jQuery('#pr-page-jump').on('keydown', function(e) {
        if (e.key === 'Enter') jQuery('#pr-page-jump-btn').trigger('click');
    });

    jQuery('a[data-toggle="tab"]').on('shown.bs.tab click', () =>
        jQuery(jQuery.fn.dataTable.tables(true)).DataTable().columns.adjust());
});
</script>
@endsection