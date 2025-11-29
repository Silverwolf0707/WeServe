@extends('layouts.admin')
@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-header custom-header d-flex align-items-center bg-primary text-white"
            style="min-height: 80px; padding: 1.5rem;">
            <h4 class="mb-0 fw-bold d-flex align-items-center">
                <i class="fas fa-users me-2"></i> {{ trans('global.show') }} {{ trans('cruds.user.title') }}
            </h4>
            <div class="header-actions d-flex align-items-center ms-auto">
            </div>
        </div>

        <div class="card-body">
            <div class="form-group">
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th width="200">{{ trans('cruds.user.fields.id') }}</th>
                            <td>{{ $user->id }}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.user.fields.name') }}</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.user.fields.email') }}</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span
                                    class="badge badge-{{ $user->status === 'active' ? 'success' : ($user->status === 'inactive' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Last Login</th>
                            <td>
                                @if ($user->last_login_at)
                                    @php
                                        $lastLogin = $user->last_login_at;
                                        if (is_string($lastLogin)) {
                                            $lastLogin = \Carbon\Carbon::parse($lastLogin);
                                        }
                                    @endphp
                                    {{ $lastLogin->format('M j, Y g:i A') }}
                                    <br>
                                    <small class="text-muted">{{ $lastLogin->diffForHumans() }}</small>
                                @else
                                    <span class="text-muted">Never logged in</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Last Login IP</th>
                            <td>
                                @if ($user->last_login_ip)
                                    <code>{{ $user->last_login_ip }}</code>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.user.fields.email_verified_at') }}</th>
                            <td>
                                @if($user->email_verified_at)
                                    @php
                                        $emailVerified = $user->email_verified_at;
                                        if (is_string($emailVerified)) {
                                            $emailVerified = \Carbon\Carbon::parse($emailVerified);
                                        }
                                    @endphp
                                    {{ $emailVerified->format('M j, Y g:i A') }}
                                    <br>
                                    <small class="text-muted">{{ $emailVerified->diffForHumans() }}</small>
                                @else
                                    <span class="badge badge-warning">Not Verified</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.user.fields.roles') }}</th>
                            <td>
                                @foreach ($user->roles as $key => $roles)
                                    <span class="badge badge-info">{{ $roles->title }}</span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>Account Created</th>
                            <td>
                                @if($user->created_at)
                                    @php
                                        $createdAt = $user->created_at;
                                        if (is_string($createdAt)) {
                                            $createdAt = \Carbon\Carbon::parse($createdAt);
                                        }
                                    @endphp
                                    {{ $createdAt->format('M j, Y g:i A') }}
                                    <br>
                                    <small class="text-muted">{{ $createdAt->diffForHumans() }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Last Updated</th>
                            <td>
                                @if($user->updated_at)
                                    @php
                                        $updatedAt = $user->updated_at;
                                        if (is_string($updatedAt)) {
                                            $updatedAt = \Carbon\Carbon::parse($updatedAt);
                                        }
                                    @endphp
                                    {{ $updatedAt->format('M j, Y g:i A') }}
                                    <br>
                                    <small class="text-muted">{{ $updatedAt->diffForHumans() }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="form-group mt-4">
                    <a class="btn btn-success" href="{{ route('admin.users.index') }}">
                        <i class="fas fa-arrow-left me-1"></i> {{ trans('global.back_to_list') }}
                    </a>

                    @can('user_edit')
                        <a class="btn btn-info ml-2" href="{{ route('admin.users.edit', $user->id) }}">
                            <i class="fas fa-edit me-1"></i> Edit User
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection