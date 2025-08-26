@extends('layouts.admin')
@section('content')

<div class="card shadow-sm border-0">
    <!-- Modernized Header -->
    <div class="card-header custom-header d-flex align-items-center bg-primary text-white" style="min-height: 80px; padding: 1.5rem;">
        <h4 class="mb-0 fw-bold d-flex align-items-center">
            <i class="fas fa-eye me-2"></i>
            {{ trans('global.show') }} {{ trans('cruds.permission.title') }}
        </h4>

        <div class="header-actions d-flex align-items-center ms-auto">
            <!-- Example action buttons can go here -->
            <!--
            @can('permission_edit')
                <a class="btn btn-add" href="{{ route('admin.permissions.edit', $permission->id) }}">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>
            @endcan
            -->
        </div>
    </div>

    <div class="card-body">
        <div class="form-group">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>{{ trans('cruds.permission.fields.id') }}</th>
                        <td>{{ $permission->id }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.permission.fields.title') }}</th>
                        <td>{{ $permission->title }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="form-group mt-3">
                <a class="btn btn-default" href="{{ route('admin.permissions.index') }}" style="background-color:  #28a745; color: black;">
                    <i class="fas fa-arrow-left me-1"></i> {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

@endsection