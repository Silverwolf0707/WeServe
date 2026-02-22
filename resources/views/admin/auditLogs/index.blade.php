@extends('layouts.admin')
@section('content')
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --pr-forest: #064e3b;
            --pr-forest-deep: #052e22;
            --pr-forest-mid: #065f46;
            --pr-lime: #74ff70;
            --pr-lime-dim: #52e84e;
            --pr-lime-ghost: rgba(116, 255, 112, .10);
            --pr-lime-border: rgba(116, 255, 112, .30);
            --pr-surface: #ffffff;
            --pr-surface2: #f0fdf4;
            --pr-muted: #ecfdf5;
            --pr-border: #d1fae5;
            --pr-border-dark: #a7f3d0;
            --pr-text: #052e22;
            --pr-sub: #3d7a62;
            --pr-danger: #ef4444;
            --pr-radius: 12px;
            --pr-shadow: 0 2px 8px rgba(6, 78, 59, .08), 0 8px 24px rgba(6, 78, 59, .06);
            --pr-shadow-lg: 0 4px 24px rgba(6, 78, 59, .16), 0 16px 48px rgba(6, 78, 59, .10);
            --pr-shadow-lime: 0 2px 12px rgba(116, 255, 112, .25);
        }

        .pr-page {
            font-family: 'DM Sans', sans-serif;
            color: var(--pr-text);
            padding: 0 0 2rem;
        }

        /* ── hero ── */
        .pr-hero {
            background: linear-gradient(135deg, #052e22 0%, #064e3b 55%, #065f46 100%);
            border-radius: var(--pr-radius);
            padding: 22px 28px;
            margin-bottom: 16px;
            position: relative;
            overflow: hidden;
            box-shadow: var(--pr-shadow-lg);
        }

        .pr-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: var(--pr-radius);
            background:
                radial-gradient(ellipse 380px 200px at 95% 50%, rgba(116, 255, 112, .13) 0%, transparent 65%),
                radial-gradient(ellipse 180px 100px at 5% 80%, rgba(116, 255, 112, .07) 0%, transparent 70%),
                radial-gradient(ellipse 250px 120px at 50% -20%, rgba(255, 255, 255, .04) 0%, transparent 60%);
            pointer-events: none;
            z-index: 0;
        }

        .pr-hero::after {
            content: '';
            position: absolute;
            top: 0;
            left: 28px;
            right: 28px;
            height: 2px;
            background: linear-gradient(to right, transparent, var(--pr-lime), transparent);
            border-radius: 2px;
            opacity: .55;
        }

        .pr-hero-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            position: relative;
            z-index: 1;
        }

        .pr-hero-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .pr-hero-icon {
            width: 46px;
            height: 46px;
            background: rgba(116, 255, 112, .12);
            border: 1px solid rgba(116, 255, 112, .30);
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            color: var(--pr-lime);
            backdrop-filter: blur(4px);
            flex-shrink: 0;
        }

        .pr-hero-title {
            font-size: 1.18rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: -.01em;
            margin: 0 0 3px;
            line-height: 1.2;
        }

        .pr-hero-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .pr-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 600;
            padding: 2px 10px;
            letter-spacing: .03em;
            line-height: 1.6;
        }

        .pr-badge-count {
            background: rgba(116, 255, 112, .14);
            border: 1px solid rgba(116, 255, 112, .32);
            color: var(--pr-lime);
        }

        /* ── filter pills ── */
        .pr-filter-pills {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .pr-filter-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            border-radius: 20px;
            font-size: .71rem;
            font-weight: 600;
            padding: 3px 11px;
            border: 1px solid rgba(116, 255, 112, .25);
            background: rgba(116, 255, 112, .08);
            color: rgba(255,255,255,.75);
            cursor: pointer;
            transition: background .18s, border-color .18s, color .18s;
            text-decoration: none;
            white-space: nowrap;
        }

        .pr-filter-pill:hover,
        .pr-filter-pill.active {
            background: var(--pr-lime);
            border-color: var(--pr-lime);
            color: var(--pr-forest);
        }

        /* ── table card ── */
        .pr-table-card {
            background: var(--pr-surface);
            border-radius: var(--pr-radius);
            border: 1px solid var(--pr-border);
            box-shadow: var(--pr-shadow);
            overflow: hidden;
        }

        /* ── toolbar ── */
        .pr-dt-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 16px;
            border-bottom: 1px solid var(--pr-border);
            background: var(--pr-surface2);
            flex-wrap: wrap;
            gap: 8px;
        }

        .pr-dt-toolbar-left {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .pr-dt-toolbar-right {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        /* inline search */
        .pr-toolbar-search {
            position: relative;
            display: flex;
            align-items: center;
        }

        .pr-toolbar-search .si {
            position: absolute;
            left: 10px;
            color: var(--pr-sub);
            font-size: .76rem;
            pointer-events: none;
        }

        .pr-toolbar-search-input {
            border: 1.5px solid var(--pr-border-dark);
            border-radius: 8px;
            padding: 6px 32px 6px 30px;
            font-size: .78rem;
            font-family: 'DM Sans', sans-serif;
            width: 240px;
            color: var(--pr-text);
            background: var(--pr-surface);
            transition: border-color .2s, box-shadow .2s;
        }

        .pr-toolbar-search-input:focus {
            outline: none;
            border-color: var(--pr-forest-mid);
            box-shadow: 0 0 0 3px rgba(6, 78, 59, .12);
        }

        .pr-toolbar-search-input::placeholder {
            color: var(--pr-border-dark);
        }

        .pr-toolbar-search-clear {
            position: absolute;
            right: 8px;
            background: none;
            border: none;
            color: var(--pr-border-dark);
            font-size: .7rem;
            cursor: pointer;
            padding: 2px 3px;
            line-height: 1;
            transition: color .15s;
            display: none;
        }

        .pr-toolbar-search-clear.visible {
            display: flex;
            align-items: center;
        }

        .pr-toolbar-search-clear:hover {
            color: var(--pr-danger);
        }

        /* ── date filter ── */
        .pr-toolbar-date {
            position: relative;
            display: flex;
            align-items: center;
        }

        .pr-toolbar-date .di {
            position: absolute;
            left: 10px;
            color: var(--pr-sub);
            font-size: .76rem;
            pointer-events: none;
            z-index: 1;
        }

        .pr-toolbar-date-input {
            border: 1.5px solid var(--pr-border-dark);
            border-radius: 8px;
            padding: 6px 32px 6px 30px;
            font-size: .78rem;
            font-family: 'DM Sans', sans-serif;
            width: 158px;
            color: var(--pr-text);
            background: var(--pr-surface);
            transition: border-color .2s, box-shadow .2s;
            cursor: pointer;
        }

        .pr-toolbar-date-input:focus {
            outline: none;
            border-color: var(--pr-forest-mid);
            box-shadow: 0 0 0 3px rgba(6, 78, 59, .12);
        }

        .pr-toolbar-date-input::-webkit-calendar-picker-indicator {
            opacity: 0;
            position: absolute;
            right: 0;
            width: 100%;
            cursor: pointer;
        }

        .pr-toolbar-date-clear {
            position: absolute;
            right: 8px;
            background: none;
            border: none;
            color: var(--pr-border-dark);
            font-size: .7rem;
            cursor: pointer;
            padding: 2px 3px;
            line-height: 1;
            transition: color .15s;
            display: none;
            z-index: 2;
        }

        .pr-toolbar-date-clear.visible {
            display: flex;
            align-items: center;
        }

        .pr-toolbar-date-clear:hover {
            color: var(--pr-danger);
        }

        .pr-toolbar-date-input.has-value {
            border-color: var(--pr-forest-mid);
            background: var(--pr-surface2);
            padding-right: 28px;
        }

        /* record indicator */
        .pr-record-indicator {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--pr-lime-ghost);
            border: 1px solid var(--pr-lime-border);
            border-radius: 20px;
            padding: 4px 12px;
            font-size: .72rem;
            font-weight: 600;
            color: var(--pr-forest);
            white-space: nowrap;
            font-family: 'DM Sans', sans-serif;
            transition: background .2s, border-color .2s;
        }

        .pr-record-indicator .ri-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--pr-lime-dim);
            flex-shrink: 0;
            transition: background .2s;
        }

        /* table */
        .pr-table-card .table {
            margin: 0;
            font-family: 'DM Sans', sans-serif;
            font-size: .84rem;
        }

        .pr-table-card .table thead tr {
            background: var(--pr-forest);
            border-bottom: 1.5px solid var(--pr-forest-mid);
        }

        .pr-table-card .table thead th {
            font-size: .69rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: #ffffff;
            padding: 10px 14px;
            border: none;
            white-space: nowrap;
        }

        .pr-table-card .table tbody tr {
            border-bottom: 1px solid var(--pr-border);
            transition: background .15s;
        }

        .pr-table-card .table tbody tr:last-child {
            border-bottom: none;
        }

        .pr-table-card .table tbody tr:hover {
            background: var(--pr-surface2);
        }

        .pr-table-card .table tbody td {
            padding: 11px 14px;
            border: none;
            color: var(--pr-text);
            vertical-align: middle;
        }

        .pr-id-cell {
            font-size: .74rem;
            font-weight: 700;
            color: var(--pr-sub);
        }

        /* description cell */
        .pr-desc-cell {
            font-size: .82rem;
            color: var(--pr-text);
            max-width: 280px;
        }

        .pr-desc-truncate {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* subject badge */
        .pr-subject-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(6, 78, 59, .07);
            border: 1px solid var(--pr-border-dark);
            border-radius: 20px;
            padding: 2px 10px;
            font-size: .71rem;
            font-weight: 600;
            color: var(--pr-forest);
            white-space: nowrap;
        }

        /* user badge */
        .pr-user-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: var(--pr-lime-ghost);
            border: 1px solid var(--pr-lime-border);
            border-radius: 20px;
            padding: 2px 10px;
            font-size: .71rem;
            font-weight: 600;
            color: var(--pr-forest);
            white-space: nowrap;
        }

        .pr-system-badge {
            font-size: .71rem;
            color: var(--pr-sub);
            font-style: italic;
        }

        /* host cell */
        .pr-host-cell {
            font-size: .75rem;
            color: var(--pr-sub);
            font-family: 'DM Mono', monospace, 'DM Sans', sans-serif;
        }

        /* date cell */
        .pr-date-cell {
            font-size: .78rem;
            font-weight: 500;
            color: var(--pr-text);
            white-space: nowrap;
        }

        .pr-date-cell small {
            display: block;
            font-size: .7rem;
            color: var(--pr-sub);
            font-weight: 400;
        }

        /* action buttons */
        .pr-action-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pr-action-wrap a,
        .pr-action-wrap button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 7px;
            border: 1px solid var(--pr-border-dark);
            background: var(--pr-surface);
            color: var(--pr-sub);
            font-size: .78rem;
            text-decoration: none;
            cursor: pointer;
            transition: all .18s;
            flex-shrink: 0;
        }

        .pr-action-wrap a:hover {
            border-color: var(--pr-forest);
            background: var(--pr-lime-ghost);
            color: var(--pr-forest);
        }

        /* hide the default DT pagination/info */
        .pr-table-card .dataTables_wrapper .dataTables_paginate,
        .pr-table-card .dataTables_wrapper .dataTables_info {
            display: none !important;
        }

        .dataTables_wrapper .row {
            margin: 0 !important;
        }

        .pr-table-card .table-responsive {
            padding: 0 !important;
            margin: 0 !important;
        }

        /* ── branded pagination footer ── */
        .pr-pagination-wrap {
            padding: 11px 16px;
            border-top: 1px solid var(--pr-border);
            background: var(--pr-surface2);
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            gap: 10px;
        }

        .pr-pagination-info {
            font-size: .74rem;
            font-weight: 500;
            color: var(--pr-sub);
            font-family: 'DM Sans', sans-serif;
            white-space: nowrap;
        }

        .pr-pagination-info strong {
            font-weight: 700;
            color: var(--pr-forest);
        }

        .pr-pagination-wrap .pagination {
            gap: 3px;
            margin: 0;
            justify-content: center;
            flex-wrap: wrap;
        }

        .pr-pagination-wrap .page-link {
            border-radius: 7px !important;
            border: 1px solid var(--pr-border-dark) !important;
            color: var(--pr-text) !important;
            font-size: .78rem !important;
            font-family: 'DM Sans', sans-serif !important;
            padding: 5px 11px !important;
            transition: background .15s, color .15s;
            cursor: pointer;
            background: var(--pr-surface);
            user-select: none;
        }

        .pr-pagination-wrap .page-item.active .page-link {
            background: var(--pr-forest) !important;
            border-color: var(--pr-forest) !important;
            color: var(--pr-lime) !important;
            font-weight: 700;
        }

        .pr-pagination-wrap .page-item.disabled .page-link {
            opacity: .45;
            cursor: default;
            pointer-events: none;
        }

        .pr-pagination-wrap .page-link:hover:not(.active) {
            background: var(--pr-muted) !important;
            color: var(--pr-forest) !important;
        }

        .pr-pagination-jump {
            display: flex;
            align-items: center;
            gap: 6px;
            justify-content: flex-end;
            font-size: .74rem;
            font-weight: 500;
            color: var(--pr-sub);
            font-family: 'DM Sans', sans-serif;
        }

        .pr-pagination-jump input {
            width: 50px;
            border: 1.5px solid var(--pr-border-dark);
            border-radius: 7px;
            padding: 4px 7px;
            font-size: .74rem;
            font-family: 'DM Sans', sans-serif;
            color: var(--pr-text);
            text-align: center;
            background: var(--pr-surface);
            transition: border-color .2s, box-shadow .2s;
        }

        .pr-pagination-jump input:focus {
            outline: none;
            border-color: var(--pr-forest-mid);
            box-shadow: 0 0 0 3px rgba(6, 78, 59, .10);
        }

        .pr-pagination-jump button {
            background: var(--pr-forest);
            color: var(--pr-lime);
            border: none;
            border-radius: 7px;
            padding: 4px 10px;
            font-size: .72rem;
            font-weight: 700;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: background .18s;
        }

        .pr-pagination-jump button:hover {
            background: var(--pr-forest-mid);
        }

        @media (max-width: 640px) {
            .pr-pagination-wrap {
                grid-template-columns: 1fr;
                justify-items: center;
            }

            .pr-pagination-info,
            .pr-pagination-jump {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .pr-hero-inner {
                flex-direction: column;
                align-items: flex-start;
            }

            .pr-hero {
                padding: 16px 18px;
            }

            .pr-dt-toolbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .pr-dt-toolbar-right {
                width: 100%;
            }

            .pr-toolbar-search-input {
                width: 100%;
            }
        }
    </style>

    <div class="pr-page">

        {{-- ══ HERO ══ --}}
        <div class="pr-hero">
            <div class="pr-hero-inner">
                <div class="pr-hero-left">
                    <div class="pr-hero-icon"><i class="fas fa-file-alt"></i></div>
                    <div>
                        <div class="pr-hero-title">{{ trans('cruds.auditLog.title_singular') }} {{ trans('global.list') }}</div>
                        <div class="pr-hero-meta">
                            <span class="pr-badge pr-badge-count">
                                {{ $auditLogs->total() }} {{ $auditLogs->total() === 1 ? 'entry' : 'entries' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="pr-hero-actions">
                    {{-- Action type quick-filter pills --}}
                    @php
                        $pills = [
                            ''        => ['label' => 'All',        'icon' => 'fa-list'],
                            'created' => ['label' => 'Created',    'icon' => 'fa-plus-circle'],
                            'updated' => ['label' => 'Updated',    'icon' => 'fa-pencil-alt'],
                            'deleted' => ['label' => 'Deleted',    'icon' => 'fa-trash-alt'],
                            'login'   => ['label' => 'Logged In',  'icon' => 'fa-sign-in-alt'],
                            'logout'  => ['label' => 'Logged Out', 'icon' => 'fa-sign-out-alt'],
                        ];
                    @endphp
                    <div class="pr-filter-pills">
                        @foreach($pills as $pillKey => $pillMeta)
                            <a href="{{ route('admin.audit-logs.index', array_merge(request()->except('action_type', 'page'), $pillKey ? ['action_type' => $pillKey] : [])) }}"
                               class="pr-filter-pill {{ $actionType === $pillKey ? 'active' : '' }}">
                                <i class="fas {{ $pillMeta['icon'] }}" style="font-size:.65rem;"></i>
                                {{ $pillMeta['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ══ TABLE ══ --}}
        <div class="pr-table-card">
            <div class="table-responsive">
                <table class="table datatable datatable-AuditLog" style="width:100%">
                    <thead>
                        <tr>
                            <th width="10"></th>
                            <th>{{ trans('cruds.auditLog.fields.id') }}</th>
                            <th>{{ trans('cruds.auditLog.fields.description') }}</th>
                            <th>{{ trans('cruds.auditLog.fields.subject_type') }}</th>
                            <th>{{ trans('cruds.auditLog.fields.user_id') }}</th>
                            <th>{{ trans('cruds.auditLog.fields.host') }}</th>
                            <th>{{ trans('cruds.auditLog.fields.created_at') }}</th>
                            <th class="text-center" width="60">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($auditLogs as $auditLog)
                            @php
                                /* ── Classify this audit entry ── */
                                $rawSubject    = $auditLog->subject_type ?? '';
                                $rawDesc       = $auditLog->description  ?? '';
                                $props         = is_array($auditLog->properties)
                                                    ? $auditLog->properties
                                                    : (json_decode($auditLog->properties, true) ?? []);

                                // Strip the #ID suffix the Auditable trait appends: "App\Models\Activity#40" → "App\Models\Activity"
                                $subjectClass  = preg_replace('/#\d+$/', '', $rawSubject);
                                $isActivity    = str_ends_with($subjectClass, 'Activity');

                                // Determine the underlying event stored inside properties (set by the Activity model)
                                $activityEvent = $props['event'] ?? ($props['attributes']['event'] ?? '');
                                $activityDesc  = $props['description'] ?? ($props['attributes']['description'] ?? '');

                                // Build a human-readable label
                                if ($isActivity) {
                                    $eventLower = strtolower($activityEvent ?: $activityDesc);
                                    if (str_contains($eventLower, 'login') || str_contains($eventLower, 'logged in')) {
                                        $displayDesc  = 'User Logged In';
                                        $descIcon     = 'fa-sign-in-alt';
                                        $descColorVar = '--pr-forest';
                                        $entryType    = 'login';
                                    } elseif (str_contains($eventLower, 'logout') || str_contains($eventLower, 'logged out')) {
                                        $displayDesc  = 'User Logged Out';
                                        $descIcon     = 'fa-sign-out-alt';
                                        $descColorVar = '--pr-sub';
                                        $entryType    = 'logout';
                                    } else {
                                        // Generic Activity row
                                        $displayDesc  = $activityDesc ?: ucfirst(str_replace('audit:', '', $rawDesc));
                                        $descIcon     = 'fa-bolt';
                                        $descColorVar = '--pr-text';
                                        $entryType    = 'activity';
                                    }
                                } else {
                                    // Normal audit rows: created / updated / deleted
                                    $map = [
                                        'audit:created' => ['Created',  'fa-plus-circle',  '#059669'],
                                        'audit:updated' => ['Updated',  'fa-pencil-alt',   '#d97706'],
                                        'audit:deleted' => ['Deleted',  'fa-trash-alt',    '#ef4444'],
                                    ];
                                    [$displayDesc, $descIcon, $descColorVar] = $map[$rawDesc]
                                        ?? [ucfirst(str_replace('audit:', '', $rawDesc)), 'fa-circle', '--pr-text'];
                                    $entryType = strtolower(str_replace('audit:', '', $rawDesc));
                                }

                                // Clean subject label: strip namespace and #ID
                                $subjectLabel = preg_replace('/#\d+$/', '', $rawSubject);
                                $subjectLabel = class_basename($subjectLabel);

                                // Extract subject ID from "ClassName#42"
                                preg_match('/#(\d+)$/', $rawSubject, $subjectIdMatch);
                                $subjectId = $subjectIdMatch[1] ?? null;
                            @endphp
                            <tr data-entry-id="{{ $auditLog->id }}">
                                <td></td>
                                <td><span class="pr-id-cell">#{{ $auditLog->id }}</span></td>

                                {{-- ── Description ── --}}
                                <td>
                                    <div class="pr-desc-cell">
                                        <span class="pr-desc-entry" style="display:inline-flex;align-items:center;gap:6px;">
                                            <i class="fas {{ $descIcon }}"
                                               style="font-size:.75rem;color:{{ str_starts_with($descColorVar, '#') ? $descColorVar : 'var('.$descColorVar.')' }};flex-shrink:0;"></i>
                                            <span style="font-weight:600;">{{ $displayDesc }}</span>
                                        </span>
                                    </div>
                                </td>

                                {{-- ── Subject ── --}}
                                <td>
                                    @if($subjectLabel)
                                        @php
                                            $subjectIconMap = [
                                                'Activity' => 'fa-sign-in-alt',
                                                'User'     => 'fa-user',
                                                'Role'     => 'fa-user-shield',
                                                'Permission' => 'fa-lock',
                                            ];
                                            $subjectIcon = $subjectIconMap[$subjectLabel] ?? 'fa-cube';
                                        @endphp
                                        <span class="pr-subject-badge">
                                            <i class="fas {{ $subjectIcon }}" style="font-size:.62rem;opacity:.7;"></i>
                                            {{ $subjectLabel }}{{ $subjectId ? ' #'.$subjectId : '' }}
                                        </span>
                                    @endif
                                </td>

                                {{-- ── User ── --}}
                                <td>
                                    @if($auditLog->user)
                                        <span class="pr-user-badge">
                                            <i class="fas fa-user" style="font-size:.62rem;opacity:.7;"></i>
                                            {{ $auditLog->user->name ?? 'N/A' }}
                                        </span>
                                    @else
                                        <span class="pr-system-badge">System</span>
                                    @endif
                                </td>

                                {{-- ── Host ── --}}
                                <td>
                                    <span class="pr-host-cell">{{ $auditLog->host ?? '' }}</span>
                                </td>

                                {{-- ── Date ── --}}
                                <td data-order="{{ $auditLog->created_at ? $auditLog->created_at->timestamp : 0 }}">
                                    @if($auditLog->created_at)
                                        <div class="pr-date-cell">
                                            {{ $auditLog->created_at->format('M d, Y') }}
                                            <small>{{ $auditLog->created_at->format('H:i') }}</small>
                                        </div>
                                    @endif
                                </td>

                                {{-- ── Actions ── --}}
                                <td>
                                    <div class="pr-action-wrap" style="justify-content:center;">
                                        @can('audit_log_show')
                                            <a href="{{ route('admin.audit-logs.show', $auditLog->id) }}" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- ── server-side pagination footer (mirrors patient-record index) ── --}}
            @php
                $cur   = $auditLogs->currentPage();
                $last  = $auditLogs->lastPage();
                $from  = ($cur - 1) * $auditLogs->perPage() + 1;
                $to    = min($cur * $auditLogs->perPage(), $auditLogs->total());
                $tot   = $auditLogs->total();
                $pages = collect();
                for ($i = 1; $i <= $last; $i++) {
                    if ($i === 1 || $i === $last || abs($i - $cur) <= 2) $pages->push($i);
                }
                $pages  = $pages->unique()->sort()->values();
                $pageUrl = fn($p) => request()->fullUrlWithQuery(['page' => $p]);
            @endphp
            <div class="pr-pagination-wrap">
                <div class="pr-pagination-info">
                    Showing <strong>{{ $from }}–{{ $to }}</strong> of <strong>{{ $tot }}</strong> entries
                </div>
                @if($last > 1)
                <nav aria-label="Audit log pagination">
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
                        <input type="number" id="pr-audit-jump-input" min="1" max="{{ $last }}"
                               placeholder="{{ $cur }}" aria-label="Go to page">
                        <button type="button" id="pr-audit-jump-btn">Go</button>
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
            const total    = {{ $auditLogs->total() }};

            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);

            let table = $('.datatable-AuditLog:not(.ajaxTable)').DataTable({
                buttons: dtButtons,
                order: [[6, 'desc']],
                orderCellsTop: true,
                searching: true,
                paging: false,
                info: false,
                processing: true,
                columnDefs: [
                    {
                        targets: 0,
                        orderable: false,
                        searchable: false,
                        className: 'select-checkbox'
                    },
                    {
                        targets: 6,
                        type: 'num'
                    },
                    {
                        targets: 7,
                        orderable: false,
                        searchable: false
                    }
                ],
                initComplete: function () {
                    const dtWrapper = $('.pr-table-card .dataTables_wrapper');
                    const dtBtnGroup = dtWrapper.find('.dt-buttons');

                    /* ── Date filter widget ── */
                    const dateWrap = $(`
                        <div class="pr-toolbar-date">
                            <i class="fas fa-calendar-alt di"></i>
                            <input type="date" id="auditDateInput"
                                   class="pr-toolbar-date-input"
                                   title="Filter by date">
                            <button type="button" class="pr-toolbar-date-clear" id="auditDateClear" title="Clear date">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `);

                    /* ── Search widget ── */
                    const searchWrap = $(`
                        <div class="pr-toolbar-search">
                            <i class="fas fa-search si"></i>
                            <input type="text" id="auditSearchInput"
                                   class="pr-toolbar-search-input"
                                   placeholder="Search audit logs…"
                                   autocomplete="off"
                                   value="{{ addslashes($searchTerm) }}">
                            <button type="button" class="pr-toolbar-search-clear {{ $searchTerm ? 'visible' : '' }}" id="auditSearchClear" title="Clear">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `);

                    /* ── Record indicator ── */
                    const indicator = $(`
                        <div class="pr-record-indicator" id="pr-audit-indicator">
                            <span class="ri-dot"></span>
                            <span class="ri-label">${total} entr${total !== 1 ? 'ies' : 'y'}</span>
                        </div>
                    `);

                    /* ── Toolbar ── */
                    const toolbar   = $('<div class="pr-dt-toolbar"></div>');
                    const leftWrap  = $('<div class="pr-dt-toolbar-left"></div>');
                    const rightWrap = $('<div class="pr-dt-toolbar-right"></div>');
                    leftWrap.append(dtBtnGroup.children().detach());
                    rightWrap.append(dateWrap).append(searchWrap).append(indicator);
                    toolbar.append(leftWrap).append(rightWrap);
                    $('.pr-table-card .table-responsive').before(toolbar);

                    /* ── helpers ── */
                    function setLabel(text) {
                        $('#pr-audit-indicator .ri-label').text(text);
                    }

                    function resetIndicator() {
                        $('#pr-audit-indicator').css({ background: '', borderColor: '' });
                        $('#pr-audit-indicator .ri-dot').css('background', '');
                    }

                    function activeIndicator() {
                        $('#pr-audit-indicator').css({
                            background: 'rgba(6,78,59,.10)',
                            borderColor: 'rgba(6,78,59,.35)'
                        });
                        $('#pr-audit-indicator .ri-dot').css('background', 'var(--pr-forest)');
                    }

                    /* ── Custom DataTables date filter (col 6 = created_at) ── */
                    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                        if (settings.nTable !== $('.datatable-AuditLog')[0]) return true;
                        const picked = $('#auditDateInput').val(); // "YYYY-MM-DD"
                        if (!picked) return true;
                        const row  = table.row(dataIndex).node();
                        const ts   = parseInt($(row).find('td').eq(6).data('order'));
                        if (!ts) return false;
                        const d    = new Date(ts * 1000);
                        const ymd  = d.getFullYear() + '-'
                                   + String(d.getMonth() + 1).padStart(2, '0') + '-'
                                   + String(d.getDate()).padStart(2, '0');
                        return ymd === picked;
                    });

                    /* ── Live search ── */
                    $('#auditSearchInput').on('input', function () {
                        const val    = $(this).val();
                        const hasVal = val.length > 0;
                        table.search(val).draw();
                        $('#auditSearchClear').toggleClass('visible', hasVal);
                        // Update indicator
                        const info = table.page.info();
                        if (hasVal || $('#auditDateInput').val()) {
                            setLabel(`${info.recordsDisplay} of ${total} match${info.recordsDisplay !== 1 ? 'es' : ''}`);
                            activeIndicator();
                        } else {
                            setLabel(`${total} entr${total !== 1 ? 'ies' : 'y'}`);
                            resetIndicator();
                        }
                    });

                    $('#auditSearchClear').on('click', function () {
                        $('#auditSearchInput').val('').trigger('input');
                    });

                    /* ── Date filter ── */
                    $('#auditDateInput').on('change', function () {
                        const val    = $(this).val();
                        const hasVal = val.length > 0;
                        $(this).toggleClass('has-value', hasVal);
                        $('#auditDateClear').toggleClass('visible', hasVal);
                        table.draw();
                        const info = table.page.info();
                        if (hasVal || $('#auditSearchInput').val()) {
                            setLabel(`${info.recordsDisplay} of ${total} match${info.recordsDisplay !== 1 ? 'es' : ''}`);
                            activeIndicator();
                        } else {
                            setLabel(`${total} entr${total !== 1 ? 'ies' : 'y'}`);
                            resetIndicator();
                        }
                    });

                    $('#auditDateClear').on('click', function () {
                        $('#auditDateInput').val('').removeClass('has-value').trigger('change');
                    });

                    /* ── Server-side Go-to-page ── */
                    $('#pr-audit-jump-btn').on('click', function () {
                        const val = parseInt($('#pr-audit-jump-input').val());
                        const max = parseInt($('#pr-audit-jump-input').attr('max') || 1);
                        if (!val || val < 1 || val > max) { $('#pr-audit-jump-input').focus(); return; }
                        const url = new URL(window.location.href);
                        url.searchParams.set('page', val);
                        window.location.href = url.toString();
                    });
                    $('#pr-audit-jump-input').on('keydown', function (e) {
                        if (e.key === 'Enter') $('#pr-audit-jump-btn').trigger('click');
                    });
                } // /initComplete
            }); // /DataTable

            $('a[data-toggle="tab"]').on('shown.bs.tab click', function () {
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        });
    </script>
@endsection