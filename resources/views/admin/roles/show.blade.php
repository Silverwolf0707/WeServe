@extends('layouts.admin')
@section('content')

<div class="card shadow-lg border-0 rounded-3">
    <!-- Modernized Header -->
    <div class="card-header custom-header d-flex align-items-center bg-success text-white" style="min-height: 80px; padding: 1.5rem;">
        <h4 class="mb-0 fw-bold d-flex align-items-center">
            <i class="fas fa-eye me-2"></i>
            {{ trans('global.show') }} {{ trans('cruds.role.title') }}
        </h4>
        <div class="header-actions d-flex align-items-center ms-auto">
        </div>
    </div>
</div>


    <div class="card-body p-4">

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <tbody>
                    <tr>
                        <th class="fw-semibold text-secondary" style="width: 200px;">
                            <i class="fas fa-hashtag me-1 text-muted"></i> {{ trans('cruds.role.fields.id') }}
                        </th>
                        <td>{{ $role->id }}</td>
                    </tr>
                    <tr>
                        <th class="fw-semibold text-secondary">
                            <i class="fas fa-id-badge me-1 text-muted"></i> {{ trans('cruds.role.fields.title') }}
                        </th>
                        <td>{{ $role->title }}</td>
                    </tr>
                    <tr>
                        <th class="fw-semibold text-secondary align-top">
                            <i class="fas fa-key me-1 text-muted"></i> {{ trans('cruds.role.fields.permissions') }}
                        </th>
                        <td>
                            <div class="d-flex flex-wrap gap-2">
                                @forelse($role->permissions as $permissions)
                                    <span class="badge bg-info text-dark px-3 py-2 rounded-pill">
                                        {{ $permissions->title }}
                                    </span>
                                @empty
                                    <span class="text-muted">No permissions assigned</span>
                                @endforelse
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Back to List Button at Bottom --}}
        <div class="mt-4 text-start">
            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary"
                style="background-color: #28a745; color: white;">
                <i class="fas fa-arrow-left me-1"></i> {{ trans('global.back_to_list') }}
            </a>
        </div>

    </div>
</div>

@endsection