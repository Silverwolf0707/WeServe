<aside class="main-sidebar sidebar-dark-primary elevation-4" style="min-height: 917px;">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">{{ trans('panel.site_title') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                {{-- Dashboard --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs("admin.home") ? "active" : "" }}" href="{{ route("admin.home") }}">
                        <i class="fas fa-fw fa-tachometer-alt nav-icon"></i>
                        <p>{{ trans('global.dashboard') }}</p>
                    </a>
                </li>

                {{-- User Management --}}
                @can('user_management_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/permissions*") || request()->is("admin/roles*") || request()->is("admin/users*") || request()->is("admin/audit-logs*") ? "menu-open" : "" }}">
                        <a class="nav-link {{ request()->is("admin/permissions*") || request()->is("admin/roles*") || request()->is("admin/users*") || request()->is("admin/audit-logs*") ? "active" : "" }}" href="#">
                            <i class="fa-fw nav-icon fas fa-users"></i>
                            <p>
                                {{ trans('cruds.userManagement.title') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('permission_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.permissions.index') }}" class="nav-link {{ request()->is('admin/permissions*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-unlock-alt"></i>
                                        <p>{{ trans('cruds.permission.title') }}</p>
                                    </a>
                                </li>
                            @endcan
                            @can('role_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->is('admin/roles*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-briefcase"></i>
                                        <p>{{ trans('cruds.role.title') }}</p>
                                    </a>
                                </li>
                            @endcan
                            @can('user_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-user"></i>
                                        <p>{{ trans('cruds.user.title') }}</p>
                                    </a>
                                </li>
                            @endcan
                            @can('audit_log_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.audit-logs.index') }}" class="nav-link {{ request()->is('admin/audit-logs*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-file-alt"></i>
                                        <p>{{ trans('cruds.auditLog.title') }}</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                {{-- Patient Records --}}
                @can('patient_record_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.patient-records.index') }}" class="nav-link {{ request()->is('admin/patient-records*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-cogs"></i>
                            <p>{{ trans('cruds.patientRecord.title') }}</p>
                        </a>
                    </li>
                @endcan

                {{-- Process Tracking --}}
                @can('process_tracking_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.process-tracking.index') }}" class="nav-link {{ request()->is('admin/process-tracking*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-stream"></i>
                            <p>{{ __('Process Tracking') }}</p>
                        </a>
                    </li>
                @endcan

                {{-- Document Management --}}
                @can('documents_management')
                    <li class="nav-item">
                        <a href="{{ route('admin.document-management.index') }}" class="nav-link {{ request()->is('admin/document-management*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-briefcase"></i>
                            <p>{{ __('Documents') }}</p>
                        </a>
                    </li>
                @endcan

                {{-- Analytics --}}
                @can('CSWD-ANALYTICS')
                    <li class="nav-item">
                        <a href="{{ route('admin.time-series.index') }}" class="nav-link {{ request()->is('admin/time-series*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-chart-line"></i>
                            <p>{{ __('Analytics') }}</p>
                        </a>
                    </li>
                @endcan

                {{-- Settings --}}
                @can('settings')
                    <li class="nav-item">
                        <a href="{{ route('settings.index') }}" class="nav-link {{ request()->is('settings*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-cog"></i>
                            <p>{{ __('Settings') }}</p>
                        </a>
                    </li>
                @endcan

                {{-- Change Password --}}
                @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
                    @can('profile_password_edit')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('profile/password*') ? 'active' : '' }}" href="{{ route('profile.password.edit') }}">
                                <i class="fa-fw fas fa-key nav-icon"></i>
                                <p>{{ trans('global.change_password') }}</p>
                            </a>
                        </li>
                    @endcan
                @endif

                {{-- Logout --}}
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                        <i class="fas fa-fw fa-sign-out-alt nav-icon"></i>
                        <p>{{ trans('global.logout') }}</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
