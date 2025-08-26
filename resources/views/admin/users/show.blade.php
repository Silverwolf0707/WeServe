@extends('layouts.admin')
@section('content')
    <div class="card shadow-sm border-0">
        <!-- Modernized Header -->
        <div class="card-header custom-header d-flex align-items-center bg-primary text-white"
            style="min-height: 80px; padding: 1.5rem;">
            <h4 class="mb-0 fw-bold d-flex align-items-center">
                <i class="fas fa-users me-2"></i> {{ trans('global.show') }} {{ trans('cruds.user.title') }}
            </h4>
            <div class="header-actions d-flex align-items-center ms-auto">
            </div>
        </div>
    </div>


    <div class="card-body">
        <div class="form-group">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.id') }}
                        </th>
                        <td>
                            {{ $user->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.name') }}
                        </th>
                        <td>
                            {{ $user->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.email') }}
                        </th>
                        <td>
                            {{ $user->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.email_verified_at') }}
                        </th>
                        <td>
                            {{ $user->email_verified_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.roles') }}
                        </th>
                        <td>
                            @foreach($user->roles as $key => $roles)
                                <span class="label label-info">{{ $roles->title }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.users.index') }}"
                    style="background-color: #28a745; color: white;">
                    <i class="fas fa-arrow-left me-1"></i> {{ trans('global.back_to_list') }}
                </a>
            </div>

        </div>
    </div>
    </div>



@endsection