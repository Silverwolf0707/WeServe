<aside id="sidebar" class="main-sidebar sidebar-dark-primary elevation-4" style="min-height: 917px;">
    <!-- Brand Logo -->
    <a href="#" class="brand-link d-flex justify-content-center py-3 border-bottom">
        <span class="brand-icon">
            <img src="{{ asset('WeServe Logo.png') }}" alt="Logo" style="width: 60px; height: 60px;">
        </span>
        <span class="brand-text font-weight-light fs-5">{{ trans('panel.site_title') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false"
                style="gap: 0.25rem;">

                {{-- Dashboard --}}
                <li class="nav-item">
                    <a href="{{ route('admin.home') }}"
                        class="nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}">
                        <i class="fas fa-fw fa-tachometer-alt nav-icon"></i>
                        <p>{{ trans('global.dashboard') }}</p>
                    </a>
                </li>

                {{-- User Management --}}
                @can('user_management_access')
                    <li
                        class="nav-item has-treeview {{ request()->is('admin/permissions*') || request()->is('admin/roles*') || request()->is('admin/users*') || request()->is('admin/audit-logs*') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ request()->is('admin/permissions*') || request()->is('admin/roles*') || request()->is('admin/users*') || request()->is('admin/audit-logs*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-users"></i>
                            <p>
                                {{ trans('cruds.userManagement.title') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview ps-3">
                            @can('permission_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.permissions.index') }}"
                                        class="nav-link {{ request()->is('admin/permissions*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-unlock-alt"></i>
                                        <p>{{ trans('cruds.permission.title') }}</p>
                                    </a>
                                </li>
                            @endcan

                            @can('role_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.roles.index') }}"
                                        class="nav-link {{ request()->is('admin/roles*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-briefcase"></i>
                                        <p>{{ trans('cruds.role.title') }}</p>
                                    </a>
                                </li>
                            @endcan

                            @can('user_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.users.index') }}"
                                        class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-user"></i>
                                        <p>{{ trans('cruds.user.title') }}</p>
                                    </a>
                                </li>
                            @endcan

                            @can('audit_log_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.audit-logs.index') }}"
                                        class="nav-link {{ request()->is('admin/audit-logs*') ? 'active' : '' }}">
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
                        <a href="{{ route('admin.patient-records.index') }}"
                            class="nav-link {{ request()->is('admin/patient-records*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-cogs"></i>
                            <p>{{ trans('cruds.patientRecord.title') }}</p>
                        </a>
                    </li>
                @endcan

                {{-- Process Tracking --}}
                @can('process_tracking_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.process-tracking.index') }}"
                            class="nav-link {{ request()->is('admin/process-tracking*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-stream"></i>
                            <p>{{ __('Process Tracking') }}</p>
                        </a>
                    </li>
                @endcan

                {{-- Document Management --}}
                @can('documents_management')
                    <li class="nav-item">
                        <a href="{{ route('admin.document-management.index') }}"
                            class="nav-link {{ request()->is('admin/document-management*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-briefcase"></i>
                            <p>{{ __('Documents') }}</p>
                        </a>
                    </li>
                @endcan

                {{-- Analytics (Dropdown) --}}
                @if(auth()->user()->can('CSWD-ANALYTICS') || auth()->user()->can('BUDGET-ANALYTICS') || auth()->user()->can('TREASURY-ANALYTICS') || auth()->user()->can('ACCOUNTING-ANALYTICS'))
                    <li class="nav-item has-treeview {{ request()->is('admin/time-series*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->is('admin/time-series*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-chart-line"></i>
                            <p>
                                {{ __('Analytics') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview ps-3">
                            @can('CSWD-ANALYTICS')
                                <li class="nav-item">
                                    <a href="{{ route('admin.time-series.index', ['type' => 'cswd']) }}"
                                        class="nav-link {{ request()->fullUrlIs(route('admin.time-series.index', ['type' => 'cswd'])) ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-chart-pie"></i>
                                        <p>{{ __('CSWD Analytics') }}</p>
                                    </a>
                                </li>
                            @endcan

                            @can('BUDGET-ANALYTICS')
                                <li class="nav-item">
                                    <a href="{{ route('admin.time-series.index', ['type' => 'budget']) }}"
                                        class="nav-link {{ request()->fullUrlIs(route('admin.time-series.index', ['type' => 'budget'])) ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-wallet"></i>
                                        <p>{{ __('Budget Analytics') }}</p>
                                    </a>
                                </li>
                            @endcan

                            @can('TREASURY-ANALYTICS')
                                <li class="nav-item">
                                    <a href="{{ route('admin.time-series.index', ['type' => 'treasury']) }}"
                                        class="nav-link {{ request()->fullUrlIs(route('admin.time-series.index', ['type' => 'treasury'])) ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-coins"></i>
                                        <p>{{ __('Treasury Analytics') }}</p>
                                    </a>
                                </li>
                            @endcan

                            @can('ACCOUNTING-ANALYTICS')
                                <li class="nav-item">
                                    <a href="{{ route('admin.time-series.index', ['type' => 'accounting']) }}"
                                        class="nav-link {{ request()->fullUrlIs(route('admin.time-series.index', ['type' => 'accounting'])) ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-balance-scale"></i>
                                        <p>{{ __('Accounting Analytics') }}</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif

                {{-- Settings --}}
                @can('settings')
                    <li class="nav-item">
                        <a href="{{ route('settings.index') }}"
                            class="nav-link {{ request()->is('settings*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-cog"></i>
                            <p>{{ __('Settings') }}</p>
                        </a>
                    </li>
                @endcan
                @can('budget_records')
                    <li class="nav-item">
                        <a href="{{ route('admin.budget-records.index') }}"
                            class="nav-link {{ request()->is('admin.budget-records*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-cog"></i>
                            <p>{{ __('Budget Records') }}</p>
                        </a>
                    </li>
                @endcan

                {{-- Change Password --}}
                @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
                    @can('profile_password_edit')
                        <li class="nav-item">
                            <a href="{{ route('profile.password.edit') }}"
                                class="nav-link {{ request()->is('profile/password*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-key nav-icon"></i>
                                <p>{{ trans('global.change_password') }}</p>
                            </a>
                        </li>
                    @endcan
                @endif

                {{-- Profile --}}
                <li class="nav-item">
                    <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#profileModal">
                        <i class="fas fa-fw fa-user-circle nav-icon"></i>
                        <p>{{ __('Profile') }}</p>
                    </a>
                </li>

                {{-- Logout --}}
                <li class="nav-item">
                    <a href="#" class="nav-link"
                        onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                        <i class="fas fa-fw fa-sign-out-alt nav-icon"></i>
                        <p>{{ trans('global.logout') }}</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>

<style>
    /* Hide icon by default, show text */
    .brand-icon {
        display: none;
    }

    /* When sidebar is collapsed (AdminLTE adds sidebar-collapse to body) */
    body.sidebar-collapse .brand-text {
        display: none !important;
    }

    body.sidebar-collapse .brand-icon {
        display: inline-block !important;
        color: white;
    }

    .sidebar {
        overflow-y: scroll;
        /* or auto */
        scrollbar-width: none;
        /* Firefox */
    }

    /* Chrome, Edge, Safari */
    .sidebar::-webkit-scrollbar {
        display: none;
    }
</style>