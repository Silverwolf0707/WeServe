@extends('layouts.admin')

@section('content')
<div class="card shadow-sm border-0">
    <!-- Modernized Header -->
    <div class="card-header custom-header d-flex align-items-center" style="background-color: green; color: white; min-height: 80px; padding: 1.5rem;">
        <h4 class="mb-0 fw-bold d-flex align-items-center">
            <i class="fas fa-file-alt me-2"></i> {{-- You can change this icon to any you prefer --}}
            {{ trans('cruds.auditLog.title_singular') }} {{ trans('global.list') }}
        </h4>
        <div class="header-actions d-flex align-items-center ms-auto">                   
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
                            <th>{{ trans('cruds.auditLog.fields.subject_id') }}</th>
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
                                <td>{{ $auditLog->description ?? '' }}</td>
                                <td>{{ $auditLog->subject_id ?? '' }}</td>
                                <td>{{ $auditLog->subject_type ?? '' }}</td>
                                <td>{{ $auditLog->user_id ?? '' }}</td>
                                <td>{{ $auditLog->host ?? '' }}</td>
                                <td>{{ $auditLog->created_at ? \Carbon\Carbon::parse($auditLog->created_at)->format('F j, Y g:i A') : '' }}
                                </td>
                                <td class="text-center">
                                    @can('audit_log_show')
                                        <a href="{{ route('admin.audit-logs.show', $auditLog->id) }}" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);

            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [[1, 'desc']],
                pageLength: 100,
            });

            let table = $('.datatable-AuditLog:not(.ajaxTable)').DataTable({ buttons: dtButtons });

            $('a[data-toggle="tab"]').on('shown.bs.tab click', function () {
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        });
    </script>
@endsection