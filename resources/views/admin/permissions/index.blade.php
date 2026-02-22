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

        .pr-hero-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .pr-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: var(--pr-lime);
            color: var(--pr-forest);
            border: none;
            border-radius: 8px;
            padding: 7px 16px;
            font-size: .8rem;
            font-weight: 700;
            font-family: 'DM Sans', sans-serif;
            text-decoration: none;
            cursor: pointer;
            transition: background .18s, transform .15s, box-shadow .18s;
            white-space: nowrap;
            box-shadow: var(--pr-shadow-lime);
        }

        .pr-btn-primary:hover {
            background: var(--pr-lime-dim);
            color: var(--pr-forest);
            transform: translateY(-1px);
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

        /* DT buttons */
        .pr-table-card .dt-buttons .btn {
            border-radius: 7px !important;
            font-size: .78rem !important;
            font-family: 'DM Sans', sans-serif !important;
            font-weight: 600 !important;
            padding: 5px 14px !important;
            transition: opacity .18s, transform .15s !important;
            background-image: none !important;
        }

        .pr-table-card .dt-buttons .btn:hover {
            opacity: .88;
            transform: translateY(-1px);
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
            width: 220px;
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

        .pr-perm-title {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: .82rem;
            font-weight: 600;
            color: var(--pr-text);
        }

        .pr-perm-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--pr-lime-dim);
            flex-shrink: 0;
        }

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

        .pr-action-wrap .del-btn:hover {
            border-color: var(--pr-danger);
            background: rgba(239, 68, 68, .08);
            color: var(--pr-danger);
        }

        /* hide the default DT pagination/info — we replace with our own footer */
        .pr-table-card .dataTables_wrapper .dataTables_paginate,
        .pr-table-card .dataTables_wrapper .dataTables_info {
            display: none !important;
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

        /* ── branded pagination footer (mirrors patient-record style) ── */
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

        /* page buttons */
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

        /* go-to-page */
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

        /* modal */
        .pr-modal .modal-content {
            border: none;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: var(--pr-shadow-lg);
            font-family: 'DM Sans', sans-serif;
        }

        .pr-modal .modal-header {
            padding: 18px 22px 16px;
            border-bottom: 1px solid var(--pr-border);
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
        }

        .pr-modal .modal-title {
            font-size: .95rem;
            font-weight: 700;
            color: #fff;
        }

        .pr-modal .modal-body {
            padding: 20px 22px;
            font-size: .85rem;
            color: var(--pr-text);
            line-height: 1.6;
        }

        .pr-modal .modal-footer {
            padding: 14px 22px;
            border-top: 1px solid var(--pr-border);
            gap: 8px;
            background: var(--pr-surface2);
        }

        .pr-modal .modal-footer .btn {
            border-radius: 8px;
            font-size: .8rem;
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            padding: 7px 18px;
            border: none;
            transition: opacity .18s, transform .15s;
            background-image: none !important;
        }

        .pr-modal .modal-footer .btn:hover {
            opacity: .88;
            transform: translateY(-1px);
        }

        .pr-modal .modal-footer .btn-secondary {
            background: var(--pr-muted) !important;
            color: var(--pr-sub) !important;
            border: 1px solid var(--pr-border-dark) !important;
        }

        .pr-modal .modal-footer .btn-danger {
            background: var(--pr-danger) !important;
            color: #fff !important;
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
                    <div class="pr-hero-icon"><i class="fas fa-lock"></i></div>
                    <div>
                        <div class="pr-hero-title">{{ trans('cruds.permission.title') }}</div>
                        <div class="pr-hero-meta">
                            <span class="pr-badge pr-badge-count">
                                {{ count($permissions) }} {{ count($permissions) === 1 ? 'permission' : 'permissions' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="pr-hero-actions">
                    @can('permission_create')
                        <a class="pr-btn-primary" href="{{ route('admin.permissions.create') }}">
                            <i class="fas fa-plus" style="font-size:.75rem;"></i>
                            {{ trans('global.add') }} {{ trans('cruds.permission.title_singular') }}
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        {{-- ══ TABLE ══ --}}
        <div class="pr-table-card">
            <div class="table-responsive">
                <table class="table datatable datatable-Permission" style="width:100%">
                    <thead>
                        <tr>
                            <th width="10"></th>
                            <th>{{ trans('cruds.permission.fields.id') }}</th>
                            <th>{{ trans('cruds.permission.fields.title') }}</th>
                            <th class="text-center">{{ trans('global.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $permission)
                            <tr data-entry-id="{{ $permission->id }}">
                                <td></td>
                                <td><span class="pr-id-cell">#{{ $permission->id }}</span></td>
                                <td>
                                    <span class="pr-perm-title">
                                        <span class="pr-perm-dot"></span>
                                        {{ $permission->title }}
                                    </span>
                                </td>
                                <td>
                                    <div class="pr-action-wrap" style="justify-content:center;">
                                        @can('permission_show')
                                            <a href="{{ route('admin.permissions.show', $permission->id) }}" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endcan
                                        @can('permission_edit')
                                            <a href="{{ route('admin.permissions.edit', $permission->id) }}" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                        @can('permission_delete')
                                            <button type="button" class="del-btn delete-perm-btn"
                                                data-id="{{ $permission->id }}" data-title="{{ $permission->title }}"
                                                title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- ── branded pagination footer (built by JS after DataTable init) ── --}}
            <div id="pr-perm-pagination" class="pr-pagination-wrap" style="display:none;">
                <div class="pr-pagination-info" id="pr-perm-page-info"></div>
                <nav aria-label="Permissions pagination">
                    <ul class="pagination" id="pr-perm-page-links"></ul>
                </nav>
                <div class="pr-pagination-jump">
                    <span>Go to</span>
                    <input type="number" id="pr-perm-jump-input" min="1" placeholder="1" aria-label="Go to page">
                    <button type="button" id="pr-perm-jump-btn">Go</button>
                </div>
            </div>
        </div>

    </div>{{-- /pr-page --}}

    {{-- ══ DELETE MODALS ══ --}}
    @can('permission_delete')
        <div class="modal fade pr-modal" id="deletePermModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-trash-alt me-2"></i>Confirm Delete</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete the permission <strong id="deletePermTitle"></strong>?</p>
                        <p style="font-size:.8rem;color:var(--pr-sub);">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form id="deletePermForm" method="POST" style="display:inline;">
                            @method('DELETE') @csrf
                            <button type="submit" class="btn btn-danger" id="confirmDeletePermBtn">
                                <i class="fas fa-trash-alt me-1"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade pr-modal" id="massDeletePermModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-layer-group me-2"></i>Confirm Mass Delete</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete the selected permissions?</p>
                        <div id="massDeletePermCount"
                            style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.25);border-radius:8px;padding:10px 14px;font-size:.8rem;font-weight:600;color:#b91c1c;margin-top:8px;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmMassDeletePermBtn">
                            <i class="fas fa-trash-alt me-1"></i> Delete Selected
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection

@section('scripts')
    @parent
    <script>
        $(function() {
            const PAGE_LEN = 15; // rows per page for the permission table
            let _token = $('meta[name="csrf-token"]').attr('content');
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);

            @can('permission_delete')
                dtButtons.push({
                    text: '<i class="fas fa-trash-alt me-1"></i> {{ trans('global.datatables.delete') }}',
                    className: 'btn-danger',
                    action: function(e, dt) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).nodes(), entry => $(entry).data('entry-id'));
                        if (!ids.length) {
                            alert('{{ trans('global.datatables.zero_selected') }}');
                            return;
                        }
                        $('#massDeletePermCount').text(
                            `You have selected ${ids.length} permission(s) for deletion.`);
                        const el = document.getElementById('massDeletePermModal');
                        el.dataset.selectedIds = JSON.stringify(ids);
                        new bootstrap.Modal(el).show();
                    }
                });
            @endcan

            let table = $('.datatable-Permission:not(.ajaxTable)').DataTable({
                buttons: dtButtons,
                order: [
                    [1, 'asc']
                ],
                pageLength: PAGE_LEN,
                lengthChange: false,
                orderCellsTop: true,
                columnDefs: [{
                        targets: 0,
                        orderable: false,
                        searchable: false,
                        className: 'select-checkbox'
                    },
                    {
                        targets: 3,
                        orderable: false,
                        searchable: false
                    }
                ],
                select: {
                    style: 'multi',
                    selector: 'td:first-child'
                },
                searching: true,
                paging: true, // DT paging stays ON — we just hide its default UI and replace it
                info: false,

                initComplete: function() {
                    const dtWrapper = $('.pr-table-card .dataTables_wrapper');
                    const dtBtnGroup = dtWrapper.find('.dt-buttons');
                    const total = table.rows().count();

                    /* ── Search widget ── */
                    const searchWrap = $(`
                    <div class="pr-toolbar-search">
                        <i class="fas fa-search si"></i>
                        <input type="text" id="permSearchInput"
                               class="pr-toolbar-search-input"
                               placeholder="Search permissions…"
                               autocomplete="off">
                        <button type="button" class="pr-toolbar-search-clear" id="permSearchClear" title="Clear">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `);

                    /* ── Record indicator ── */
                    const indicator = $(`
                    <div class="pr-record-indicator" id="pr-perm-indicator">
                        <span class="ri-dot"></span>
                        <span class="ri-label">${total} permission${total !== 1 ? 's' : ''}</span>
                    </div>
                `);

                    /* ── Toolbar ── */
                    const toolbar = $('<div class="pr-dt-toolbar"></div>');
                    const leftWrap = $('<div class="pr-dt-toolbar-left"></div>');
                    const rightWrap = $('<div class="pr-dt-toolbar-right"></div>');
                    leftWrap.append(dtBtnGroup.children().detach());
                    rightWrap.append(searchWrap).append(indicator);
                    toolbar.append(leftWrap).append(rightWrap);
                    $('.pr-table-card .table-responsive').before(toolbar);

                    /* ── helpers ── */
                    function setLabel(text) {
                        $('#pr-perm-indicator .ri-label').text(text);
                    }

                    function resetIndicator() {
                        $('#pr-perm-indicator').css({
                            background: '',
                            borderColor: ''
                        });
                        $('#pr-perm-indicator .ri-dot').css('background', '');
                    }

                    function activeIndicator() {
                        $('#pr-perm-indicator').css({
                            background: 'rgba(6,78,59,.10)',
                            borderColor: 'rgba(6,78,59,.35)'
                        });
                        $('#pr-perm-indicator .ri-dot').css('background', 'var(--pr-forest)');
                    }

                    /* ── Refresh our branded pagination footer ── */
                    function refreshPagination() {
                        const info = table.page.info();
                        const cur = info.page; // 0-based
                        const last = info.pages; // total pages
                        const totalRows = info.recordsDisplay; // after search filter
                        const from = totalRows === 0 ? 0 : info.start + 1;
                        const to = info.end;
                        const wrap = $('#pr-perm-pagination');
                        const linksEl = $('#pr-perm-page-links');
                        const infoEl = $('#pr-perm-page-info');
                        const jumpInput = $('#pr-perm-jump-input');

                        // Show/hide the whole footer
                        wrap.toggle(last > 0);
                        if (last === 0) return;

                        // Record info text
                        infoEl.html(
                            `Showing <strong>${from}–${to}</strong> of <strong>${totalRows}</strong> permissions`
                            );

                        // Jump input max
                        jumpInput.attr('max', last).attr('placeholder', cur + 1);

                        // Build page window: first, last, cur±2, with ellipsis
                        const pages = [];
                        for (let i = 0; i < last; i++) {
                            if (i === 0 || i === last - 1 || Math.abs(i - cur) <= 2) pages.push(i);
                        }

                        linksEl.empty();

                        // ‹ Prev
                        linksEl.append(`
                        <li class="page-item ${cur === 0 ? 'disabled' : ''}">
                            <span class="page-link pr-pg-btn" data-page="${cur - 1}">‹</span>
                        </li>
                    `);

                        let prev = null;
                        pages.forEach(p => {
                            // Ellipsis
                            if (prev !== null && p - prev > 1) {
                                linksEl.append(
                                    `<li class="page-item disabled"><span class="page-link" style="border:none;background:transparent;padding:5px 4px;color:var(--pr-sub);">…</span></li>`
                                    );
                            }
                            linksEl.append(`
                            <li class="page-item ${p === cur ? 'active' : ''}">
                                <span class="page-link pr-pg-btn" data-page="${p}">${p + 1}</span>
                            </li>
                        `);
                            prev = p;
                        });

                        // › Next
                        linksEl.append(`
                        <li class="page-item ${cur >= last - 1 ? 'disabled' : ''}">
                            <span class="page-link pr-pg-btn" data-page="${cur + 1}">›</span>
                        </li>
                    `);

                        // Hide footer entirely if only 1 page and no overflow
                        if (last <= 1) {
                            linksEl.closest('nav').hide();
                            jumpInput.closest('.pr-pagination-jump').hide();
                        } else {
                            linksEl.closest('nav').show();
                            jumpInput.closest('.pr-pagination-jump').show();
                        }
                    }

                    // Page button clicks (delegated — rebuilt on every draw)
                    $(document).on('click', '.pr-pg-btn', function() {
                        const p = parseInt($(this).data('page'));
                        const info = table.page.info();
                        if (isNaN(p) || p < 0 || p >= info.pages) return;
                        table.page(p).draw('page');
                    });

                    // Go-to-page
                    $('#pr-perm-jump-btn').on('click', function() {
                        const val = parseInt($('#pr-perm-jump-input').val());
                        const info = table.page.info();
                        if (!val || val < 1 || val > info.pages) {
                            $('#pr-perm-jump-input').focus();
                            return;
                        }
                        table.page(val - 1).draw('page');
                        $('#pr-perm-jump-input').val('');
                    });
                    $('#pr-perm-jump-input').on('keydown', function(e) {
                        if (e.key === 'Enter') $('#pr-perm-jump-btn').trigger('click');
                    });

                    // Re-render pagination on every DataTables draw
                    table.on('draw', function() {
                        refreshPagination();
                        // Also update record indicator when search changes
                        const searchVal = $('#permSearchInput').val();
                        const info = table.page.info();
                        if (searchVal) {
                            setLabel(
                                `${info.recordsDisplay} of ${total} match${info.recordsDisplay !== 1 ? 'es' : ''}`
                                );
                            activeIndicator();
                        } else if (table.rows({
                                selected: true
                            }).count() > 0) {
                            // selection indicator handled below
                        } else {
                            setLabel(`${total} permission${total !== 1 ? 's' : ''}`);
                            resetIndicator();
                        }
                    });

                    // Initial render
                    refreshPagination();

                    /* ── Live search ── */
                    $('#permSearchInput').on('input', function() {
                        const val = $(this).val();
                        const hasVal = val.length > 0;
                        table.search(val).draw();
                        $('#permSearchClear').toggleClass('visible', hasVal);
                    });

                    $('#permSearchClear').on('click', function() {
                        $('#permSearchInput').val('').trigger('input');
                    });

                    /* ── Row selection indicator ── */
                    table.on('select deselect', function() {
                        const sel = table.rows({
                            selected: true
                        }).count();
                        if (sel > 0) {
                            setLabel(`${sel} selected · ${total} total`);
                            activeIndicator();
                        } else {
                            const searchVal = $('#permSearchInput').val();
                            if (searchVal) {
                                const matched = table.rows({
                                    search: 'applied'
                                }).count();
                                setLabel(
                                    `${matched} of ${total} match${matched !== 1 ? 'es' : ''}`
                                    );
                                activeIndicator();
                            } else {
                                setLabel(`${total} permission${total !== 1 ? 's' : ''}`);
                                resetIndicator();
                            }
                        }
                    });
                } // /initComplete
            }); // /DataTable

            /* ── Single delete ── */
            $(document).on('click', '.delete-perm-btn', function() {
                const id = $(this).data('id');
                const title = $(this).data('title');
                $('#deletePermTitle').text(title);
                $('#deletePermForm').attr('action', `/admin/permissions/${id}`);
                new bootstrap.Modal(document.getElementById('deletePermModal')).show();
            });
            $('#confirmDeletePermBtn').on('click', function() {
                $(this).prop('disabled', true).html(
                '<i class="fas fa-spinner fa-spin me-1"></i> Deleting…');
                $('#deletePermForm').submit();
            });
            $('#deletePermModal').on('hidden.bs.modal', () =>
                $('#confirmDeletePermBtn').prop('disabled', false).html(
                    '<i class="fas fa-trash-alt me-1"></i> Delete'));

            /* ── Mass delete ── */
            $('#confirmMassDeletePermBtn').on('click', function() {
                const btn = $(this);
                const ids = JSON.parse(document.getElementById('massDeletePermModal').dataset.selectedIds ||
                    '[]');
                if (!ids.length) return;
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Deleting…');
                $.ajax({
                    headers: {
                        'x-csrf-token': _token
                    },
                    method: 'POST',
                    url: "{{ route('admin.permissions.massDestroy') }}",
                    data: {
                        ids: ids,
                        _method: 'DELETE'
                    }
                }).done(() => location.reload());
            });
            $('#massDeletePermModal').on('hidden.bs.modal', () =>
                $('#confirmMassDeletePermBtn').prop('disabled', false).html(
                    '<i class="fas fa-trash-alt me-1"></i> Delete Selected'));

            $('a[data-toggle="tab"]').on('shown.bs.tab click', () =>
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust());
        });
    </script>
@endsection
