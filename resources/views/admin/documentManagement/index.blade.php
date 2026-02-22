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

        /* ── adv filter bar ── */
        .pr-adv-bar {
            background: var(--pr-surface2); border-radius: var(--pr-radius);
            border: 1px solid var(--pr-border); padding: 12px 18px; margin-bottom: 16px;
            box-shadow: 0 1px 4px rgba(6,78,59,.05);
        }
        .pr-adv-bar-inner { display: flex; align-items: flex-end; flex-wrap: wrap; gap: 10px; }
        .pr-adv-group { display: flex; flex-direction: column; gap: 4px; min-width: 160px; flex: 1 1 160px; }
        .pr-adv-label { font-size: .68rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--pr-sub); white-space: nowrap; }
        .pr-adv-input {
            border: 1.5px solid var(--pr-border-dark); border-radius: 8px; padding: 6px 10px;
            font-size: .8rem; font-family: 'DM Sans', sans-serif; color: var(--pr-text); background: var(--pr-surface);
            transition: border-color .2s, box-shadow .2s; width: 100%;
        }
        .pr-adv-input:focus { outline: none; border-color: var(--pr-forest-mid); box-shadow: 0 0 0 3px rgba(6,78,59,.12); }
        .pr-adv-input::placeholder { color: var(--pr-border-dark); }
        .pr-adv-input.has-value { border-color: var(--pr-forest); background-color: var(--pr-lime-ghost); color: var(--pr-forest); font-weight: 600; }
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
        .pr-control-cell { font-size: .82rem; font-weight: 700; color: var(--pr-forest); letter-spacing: .01em; }
        .pr-name-cell    { font-size: .82rem; font-weight: 600; color: var(--pr-text); }
        .pr-sub-cell     { font-size: .78rem; color: var(--pr-sub); }
        .pr-date-cell    { font-size: .78rem; font-weight: 500; color: var(--pr-text); white-space: nowrap; }
        .pr-date-cell small { display: block; font-size: .7rem; color: var(--pr-sub); font-weight: 400; }

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
                    <div class="pr-hero-icon"><i class="fas fa-file-alt"></i></div>
                    <div>
                        <div class="pr-hero-title">{{ __('Documents List') }}</div>
                        <div class="pr-hero-meta">
                            <span class="pr-badge pr-badge-count">
                                {{ $patients->total() }} {{ $patients->total() === 1 ? 'record' : 'records' }}
                            </span>
                            @if($searchTerm)
                                <span class="pr-badge" style="background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);color:rgba(255,255,255,.8);">
                                    <i class="fas fa-search" style="font-size:.6rem;"></i>
                                    "{{ $searchTerm }}"
                                </span>
                            @endif
                            @if(request('date_from'))
                                <span class="pr-badge" style="background:rgba(245,166,35,.18);border:1px solid rgba(245,166,35,.4);color:#f5de5c;">
                                    <i class="fas fa-calendar-alt" style="font-size:.6rem;"></i>
                                    {{ \Carbon\Carbon::parse(request('date_from'))->format('M j, Y') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══ FILTER BAR ══ --}}
        @php $dateFrom = request('date_from', ''); @endphp
        <div class="pr-adv-bar">
            <form method="GET" action="{{ route('admin.document-management.index') }}" id="dmFilterForm">
                <div class="pr-adv-bar-inner">
                    <div class="pr-adv-group">
                        <span class="pr-adv-label"><i class="fas fa-calendar-alt me-1"></i>Date Processed</span>
                        <input type="date" name="date_from"
                               class="pr-adv-input {{ $dateFrom ? 'has-value' : '' }}"
                               value="{{ $dateFrom }}"
                               onchange="this.classList.toggle('has-value', this.value !== '')">
                    </div>
                    <div class="pr-adv-group" style="flex:2 1 220px;">
                        <span class="pr-adv-label"><i class="fas fa-search me-1"></i>Search</span>
                        <div style="position:relative;display:flex;align-items:center;">
                            <i class="fas fa-search" style="position:absolute;left:10px;color:var(--pr-sub);font-size:.76rem;pointer-events:none;"></i>
                            <input type="text" name="search"
                                   class="pr-adv-input {{ $searchTerm ? 'has-value' : '' }}"
                                   style="padding-left:30px;padding-right:{{ $searchTerm ? '28px' : '10px' }};"
                                   value="{{ $searchTerm }}"
                                   placeholder="Search by control #, patient, claimant…"
                                   oninput="this.classList.toggle('has-value', this.value !== '')">
                            @if($searchTerm)
                                <a href="{{ route('admin.document-management.index', array_filter(array_merge(request()->except('search','page'), []))) }}"
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
                        @if($searchTerm || $dateFrom)
                            <a href="{{ route('admin.document-management.index') }}" class="pr-adv-reset">
                                <i class="fas fa-times" style="font-size:.72rem;"></i> Clear
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        {{-- ══ TABLE ══ --}}
        <div class="pr-table-card">
            <div class="table-responsive">
                <table class="table datatable datatable-Document" style="width:100%">
                    <thead>
                        <tr>
                            <th width="10"></th>
                            <th>{{ __('Control Number') }}</th>
                            <th>{{ __('Date Processed') }}</th>
                            <th>{{ __('Patient Name') }}</th>
                            <th>{{ __('Claimant Name') }}</th>
                            <th class="text-center" width="60">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patients as $patient)
                            <tr data-entry-id="{{ $patient->id }}">
                                <td></td>
                                <td class="pr-control-cell">{{ $patient->control_number ?? 'N/A' }}</td>
                                <td class="pr-date-cell"
                                    data-order="{{ \Carbon\Carbon::parse($patient->date_processed)->timestamp }}">
                                    {{ \Carbon\Carbon::parse($patient->date_processed)->format('M j, Y') }}
                                    <small>{{ \Carbon\Carbon::parse($patient->date_processed)->format('g:i A') }}</small>
                                </td>
                                <td class="pr-name-cell">{{ $patient->patient_name ?? 'N/A' }}</td>
                                <td class="pr-sub-cell">{{ $patient->claimant_name ?? 'N/A' }}</td>
                                <td class="text-center">
                                    <div class="pr-action-wrap">
                                        <a href="{{ route('admin.document-management.show', $patient->id) }}" title="{{ __('View') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- ══ PAGINATION FOOTER ══ --}}
            @php
                $cur   = $patients->currentPage();
                $last  = $patients->lastPage();
                $perPg = $patients->perPage();
                $tot   = $patients->total();
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
                    <nav aria-label="Document management pagination">
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
                        <input type="number" id="dm-page-jump" min="1" max="{{ $last }}"
                               placeholder="{{ $cur }}" aria-label="Go to page">
                        <button type="button" id="dm-page-jump-btn">Go</button>
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
        const total = {{ $patients->total() }};
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
        let _token = $('meta[name="csrf-token"]').attr('content');

        @can('documents_management')
        dtButtons.push({
            text: '<i class="fas fa-trash me-1"></i> Delete Selected',
            className: 'btn-danger',
            action: function (e, dt) {
                const ids = $.map(dt.rows({ selected: true }).nodes(), entry => $(entry).data('entry-id'));
                if (!ids.length) { alert('No records selected'); return; }
                if (!confirm('Are you sure you want to delete the selected documents?')) return;
                $.ajax({
                    headers: { 'x-csrf-token': _token },
                    method: 'POST',
                    url: "{{ route('admin.document-management.massDestroy') }}",
                    data: { ids: ids, _method: 'DELETE' }
                }).done(function () { location.reload(); });
            }
        });
        @endcan

        const table = $('.datatable-Document:not(.ajaxTable)').DataTable({
            buttons: dtButtons,
            order: [[2, 'desc']],
            orderCellsTop: true,
            columnDefs: [
                { targets: 0, orderable: false, searchable: false, className: 'select-checkbox' },
                { targets: 5, orderable: false, searchable: false },
                { targets: 2, type: 'num' }
            ],
            select: { style: 'multi', selector: 'td:first-child' },
            paging: false, info: false, searching: false, processing: true, serverSide: false,
            initComplete: function () {
                const dtWrapper  = $('.pr-table-card .dataTables_wrapper');
                const dtBtnGroup = dtWrapper.find('.dt-buttons');

                const currentPage = {{ $patients->currentPage() }};
                const lastPage    = {{ $patients->lastPage() }};

                const indicator = $(`
                    <div class="pr-record-indicator" id="dm-indicator">
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
                        $('#dm-indicator').css({ background: 'rgba(6,78,59,.10)', borderColor: 'rgba(6,78,59,.35)' });
                        $('#dm-indicator .ri-dot').css('background', 'var(--pr-forest)');
                        $('#dm-indicator span:last').text(`of ${lastPage} · ${sel} selected`);
                    } else {
                        $('#dm-indicator').css({ background: '', borderColor: '' });
                        $('#dm-indicator .ri-dot').css('background', '');
                        $('#dm-indicator .ri-count').text(currentPage);
                        $('#dm-indicator span:last').text(`of ${lastPage}`);
                    }
                });
            }
        });

        $('#dm-page-jump-btn').on('click', function () {
            const val = parseInt($('#dm-page-jump').val());
            const max = parseInt($('#dm-page-jump').attr('max') || 1);
            if (!val || val < 1 || val > max) { $('#dm-page-jump').focus(); return; }
            const url = new URL(window.location.href);
            url.searchParams.set('page', val);
            window.location.href = url.toString();
        });
        $('#dm-page-jump').on('keydown', function (e) {
            if (e.key === 'Enter') $('#dm-page-jump-btn').trigger('click');
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab click', function () {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    });
</script>
@endsection