@extends('layouts.admin')

@section('content')
<div class="container-fluid" style="background-color: #f8f9fa; padding: 20px;">
    <div class="card shadow-sm">
        <!-- Modernized Header -->
        <div class="card-header custom-header d-flex align-items-center bg-dark text-white" style="min-height: 70; padding: 1.5rem;">
            <h4 class="mb-0 fw-bold d-flex align-items-center">
                <i class="fas fa-clipboard-list me-2"></i>
                {{ trans('global.show') }} {{ trans('cruds.auditLog.title') }}
            </h4>
            <div class="header-actions d-flex align-items-center ms-auto">
            </div>
        </div>
    </div>
</div>


        <div class="card-body bg-white">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>{{ trans('cruds.auditLog.fields.id') }}</th>
                        <td>{{ $auditLog->id }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.auditLog.fields.description') }}</th>
                        <td>{{ $auditLog->description }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.auditLog.fields.subject_id') }}</th>
                        <td>{{ $auditLog->subject_id }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.auditLog.fields.subject_type') }}</th>
                        <td>{{ $auditLog->subject_type }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.auditLog.fields.user_id') }}</th>
                        <td>{{ $auditLog->user_id }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.auditLog.fields.properties') }}</th>
                        <td>
                            <pre class="mb-0">{{ json_encode($auditLog->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                        </td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.auditLog.fields.host') }}</th>
                        <td>{{ $auditLog->host }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.auditLog.fields.created_at') }}</th>
                        <td>{{ \Carbon\Carbon::parse($auditLog->created_at)->format('F j, Y g:i A') }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-4 text-left">
                <a class="btn btn-secondary" href="{{ route('admin.audit-logs.index') }}">
                    <i class="fas fa-arrow-left mr-1"></i> {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection