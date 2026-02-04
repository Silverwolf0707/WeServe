@extends('layouts.admin')

@section('content')
<div class="card shadow-sm border-0">
    <!-- Modernized Header -->
    <div class="card-header custom-header d-flex align-items-center" style="background-color: green; color: white; min-height: 80px; padding: 1.5rem;">
        <h4 class="mb-0 fw-bold d-flex align-items-center">
            <i class="fas fa-file-alt me-2"></i>
            {{ trans('cruds.auditLog.title_singular') }} {{ trans('global.list') }}
        </h4>
        <div class="header-actions d-flex align-items-center ms-auto">
            <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="d-flex">
                <div class="input-group">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Search audit logs..." 
                           value="{{ request('search') }}"
                           aria-label="Search"
                           autocomplete="off">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.audit-logs.index') }}" 
                           class="btn btn-outline-secondary" 
                           title="Clear search">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card-body">

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover datatable datatable-AuditLog">
            <thead class="thead-dark">
                <tr>
                    <th width="10"></th>
                    <th>{{ trans('cruds.auditLog.fields.id') }}</th>
                    <th>{{ trans('cruds.auditLog.fields.description') }}</th>
                    <th>{{ trans('cruds.auditLog.fields.subject_type') }}</th>
                    <th>{{ trans('cruds.auditLog.fields.user_id') }}</th>
                    <th>{{ trans('cruds.auditLog.fields.host') }}</th>
                    <th>{{ trans('cruds.auditLog.fields.created_at') }}</th>
                    <th class="text-center" width="50">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($auditLogs as $auditLog)
                    <tr data-entry-id="{{ $auditLog->id }}">
                        <td></td>
                        <td>{{ $auditLog->id ?? '' }}</td>
                        <td>
                            @php
                                $description = $auditLog->description ?? '';
                                // Truncate long descriptions
                                if (strlen($description) > 80) {
                                    $description = substr($description, 0, 80) . '...';
                                }
                                echo $description;
                            @endphp
                        </td>
                        <td>
                            <span class="badge bg-info">
                                {{ str_replace('App\Models\\', '', $auditLog->subject_type) ?? '' }}
                            </span>
                        </td>
                        <td>
                            @if($auditLog->user)
                                <span class="badge bg-secondary">
                                    {{ $auditLog->user->name ?? 'N/A' }}
                                </span>
                            @else
                                <span class="text-muted">System</span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">{{ $auditLog->host ?? '' }}</small>
                        </td>
                        <td data-order="{{ $auditLog->created_at->timestamp }}">
                            {{ $auditLog->created_at ? $auditLog->created_at->format('M d, Y H:i') : '' }}
                        </td>
                        <td class="text-center">
                            @can('audit_log_show')
                                <a href="{{ route('admin.audit-logs.show', $auditLog->id) }}" title="View" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Add pagination links --}}
    @if($auditLogs->hasPages())
        <div class="centered-pagination mb-4">
            <div>
                {{ $auditLogs->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
    @parent
    <script>
        $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);

            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [[6, 'desc']], // Sort by created_at (column 6)
                pageLength: 100,
            });

            let table = $('.datatable-AuditLog:not(.ajaxTable)').DataTable({
                buttons: dtButtons,
                paging: false, 
                info: false,   
                searching: false,
                processing: true,
                serverSide: false,
                columnDefs: [
                    {
                        targets: 0,
                        orderable: false,
                        searchable: false,
                        className: 'select-checkbox'
                    },
                    {
                        targets: 6, // created_at column for proper sorting
                        type: 'date' // Use date type for proper sorting
                    }
                ],
                language: {
                    search: "Search:",
                    
                }
            });

            $('a[data-toggle="tab"]').on('shown.bs.tab click', function () {
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        });
    </script>
@endsection