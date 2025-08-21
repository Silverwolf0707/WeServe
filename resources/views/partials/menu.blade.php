<aside id="sidebar" class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.home') }}"
        class="brand-link d-flex align-items-center py-3 px-3 border-bottom text-decoration-none">
        <!-- Logo -->
        <span class="brand-icon d-flex align-items-center justify-content-center">
            <img src="{{ asset('WeServe Logo.png') }}" alt="Logo" class="rounded-circle border border-2"
                style="height: 40px; width: 40px; object-fit: cover;">
        </span>
        <!-- Text -->
        <span class="brand-text fw-bold ms-3 fs-5 text-light" style="letter-spacing: 0.5px;">
            {{ trans('panel.site_title') }}
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="mt-3">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('admin.home') }}"
                        class="nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>


                <!-- User Management -->
                @can('user_management_access')
                    <li
                        class="nav-item has-treeview {{ request()->is('admin/permissions*') || request()->is('admin/roles*') || request()->is('admin/users*') || request()->is('admin/audit-logs*') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ request()->is('admin/permissions*') || request()->is('admin/roles*') || request()->is('admin/users*') || request()->is('admin/audit-logs*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>User Management <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('permission_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.permissions.index') }}"
                                        class="nav-link {{ request()->is('admin/permissions*') ? 'active' : '' }}">
                                        <i class="fas fa-unlock-alt nav-icon"></i>
                                        <p>Permissions</p>
                                    </a>
                                </li>
                            @endcan
                            @can('role_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.roles.index') }}"
                                        class="nav-link {{ request()->is('admin/roles*') ? 'active' : '' }}">
                                        <i class="fas fa-briefcase nav-icon"></i>
                                        <p>Roles</p>
                                    </a>
                                </li>
                            @endcan
                            @can('user_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.users.index') }}"
                                        class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                                        <i class="fas fa-user nav-icon"></i>
                                        <p>Users</p>
                                    </a>
                                </li>
                            @endcan
                            @can('audit_log_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.audit-logs.index') }}"
                                        class="nav-link {{ request()->is('admin/audit-logs*') ? 'active' : '' }}">
                                        <i class="fas fa-file-alt nav-icon"></i>
                                        <p>Audit Logs</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @can('patient_record_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.patient-records.index') }}"
                            class="nav-link {{ request()->is('admin/patient-records*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>Patient Records</p>
                        </a>
                    </li>
                @endcan
                @can('online_application_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.online-applications.index') }}"
                            class="nav-link {{ request()->is('admin/online-applications*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>Online Application</p>
                        </a>
                    </li>
                @endcan

                @can('process_tracking_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.process-tracking.index') }}"
                            class="nav-link {{ request()->is('admin/process-tracking*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-stream"></i>
                            <p>Process Tracking</p>
                        </a>
                    </li>
                @endcan

                @can('documents_management')
                    <li class="nav-item">
                        <a href="{{ route('admin.document-management.index') }}"
                            class="nav-link {{ request()->is('admin/document-management*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-briefcase"></i>
                            <p>Documents</p>
                        </a>
                    </li>
                @endcan

                @if(auth()->user()->can('CSWD-ANALYTICS') || auth()->user()->can('BUDGET-ANALYTICS') || auth()->user()->can('TREASURY-ANALYTICS') || auth()->user()->can('ACCOUNTING-ANALYTICS'))
                    <li class="nav-item has-treeview {{ request()->is('admin/time-series*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->is('admin/time-series*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-line"></i>
                            <p>Analytics <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('CSWD-ANALYTICS')
                                <li class="nav-item">
                                    <a href="{{ route('admin.time-series.index', ['type' => 'cswd']) }}"
                                        class="nav-link {{ request()->fullUrlIs(route('admin.time-series.index', ['type' => 'cswd'])) ? 'active' : '' }}">
                                        <i class="fas fa-chart-pie nav-icon"></i>
                                        <p>CSWD</p>
                                    </a>
                                </li>
                            @endcan
                            @can('BUDGET-ANALYTICS')
                                <li class="nav-item">
                                    <a href="{{ route('admin.time-series.index', ['type' => 'budget']) }}"
                                        class="nav-link {{ request()->fullUrlIs(route('admin.time-series.index', ['type' => 'budget'])) ? 'active' : '' }}">
                                        <i class="fas fa-wallet nav-icon"></i>
                                        <p>Budget</p>
                                    </a>
                                </li>
                            @endcan
                            @can('TREASURY-ANALYTICS')
                                <li class="nav-item">
                                    <a href="{{ route('admin.time-series.index', ['type' => 'treasury']) }}"
                                        class="nav-link {{ request()->fullUrlIs(route('admin.time-series.index', ['type' => 'treasury'])) ? 'active' : '' }}">
                                        <i class="fas fa-coins nav-icon"></i>
                                        <p>Treasury</p>
                                    </a>
                                </li>
                            @endcan
                            @can('ACCOUNTING-ANALYTICS')
                                <li class="nav-item">
                                    <a href="{{ route('admin.time-series.index', ['type' => 'accounting']) }}"
                                        class="nav-link {{ request()->fullUrlIs(route('admin.time-series.index', ['type' => 'accounting'])) ? 'active' : '' }}">
                                        <i class="fas fa-balance-scale nav-icon"></i>
                                        <p>Accounting</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif


                @can('settings')
                    <li class="nav-item">
                        <a href="{{ route('settings.index') }}"
                            class="nav-link {{ request()->is('settings*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>Settings</p>
                        </a>
                    </li>
                @endcan

                @can('budget_records')
                    <li class="nav-item">
                        <a href="{{ route('admin.budget-records.index') }}"
                            class="nav-link {{ request()->is('admin/budget-records*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-invoice-dollar"></i>
                            <p>Budget Records</p>
                        </a>
                    </li>
                @endcan



                @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
                    @can('profile_password_edit')
                        <li class="nav-item">
                            <a href="{{ route('profile.password.edit') }}"
                                class="nav-link {{ request()->is('profile/password*') ? 'active' : '' }}">
                                <i class="fas fa-key nav-icon"></i>
                                <p>Change Password</p>
                            </a>
                        </li>
                    @endcan
                @endif

                <li class="nav-item">
                    <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#profileModal">
                        <i class="fas fa-user-circle nav-icon"></i>
                        <p>Profile</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link text-danger"
                        onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                        <i class="fas fa-sign-out-alt nav-icon"></i>
                        <p>Logout</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>

<style>
    /* ==================== BRAND (Logo + Text) ==================== */
    .brand-link {
        background: #2c3e50;
        /* updated background */
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        padding: 0.75rem 1rem;
        overflow: hidden;
    }

    /* Logo (always visible) */
    .brand-icon img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid #fff;
        object-fit: cover;
        flex-shrink: 0;
        transition: transform 0.3s ease;
    }

    /* Brand text */
    .brand-text {
        margin-left: 12px;
        font-weight: bold;
        font-size: 1.1rem;
        color: #ecf0f1;
        letter-spacing: 0.5px;
        opacity: 1;
        transform: translateX(0);
        transition: opacity 0.3s ease, transform 0.3s ease;
        white-space: nowrap;
    }

    /* Collapsed → hide text only */
    body.sidebar-collapse .brand-text {
        opacity: 0;
        transform: translateX(-20px);
        pointer-events: none;
    }

    /* Hover (when collapsed) → show text */
    body.sidebar-collapse .main-sidebar:hover .brand-text {
        opacity: 1;
        transform: translateX(0);
        pointer-events: auto;
    }

    /* Logo hover subtle effect */
    body.sidebar-collapse .main-sidebar:hover .brand-icon img {
        transform: scale(1.05);
    }

    /* ==================== MENU ITEMS ==================== */
    .nav-sidebar {
        background: #2c3e50;
        /* updated sidebar background */
    }

    .nav-sidebar .nav-link {
        border-radius: 0.5rem;
        margin: 2px 0;
        color: #ecf0f1;
        transition: all 0.2s ease;
    }

    .nav-sidebar .nav-link.active {
        background-color: #3c8dbc;
        color: #fff;
        font-weight: 600;
    }

    .nav-sidebar .nav-link:hover {
        background: rgba(60, 141, 188, 0.2);
    }

    /* Menu headers */
    .nav-header {
        font-size: 0.75rem;
        font-weight: 600;
        color: #bdc3c7;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 15px 0 5px 15px;
        padding-top: 8px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* ==================== SIDEBAR WIDTH + SCROLL ==================== */
    .main-sidebar {
        width: 250px;
        height: 100vh;
        background: #2c3e50;
        /* updated background */
        overflow-y: auto;
        /* only scroll here */
        overflow-x: hidden;
        transition: all 0.3s ease;
    }

    /* Prevent inner .sidebar from creating its own scrollbar */
    .main-sidebar .sidebar {
        height: 100%;
        overflow: hidden !important;
    }

    /* Collapsed sidebar */
    body.sidebar-collapse .main-sidebar {
        width: 60px;
        overflow: hidden !important;
        /* hide scrollbar */
    }

    /* Collapsed + Hover → expand + enable scrollbar */
    body.sidebar-collapse .main-sidebar:hover {
        width: 250px;
        overflow-y: auto !important;
        /* show scrollbar again */
    }

    /* ==================== SCROLLBAR STYLE ==================== */
    .main-sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .main-sidebar::-webkit-scrollbar-thumb {
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
    }

    .main-sidebar::-webkit-scrollbar-track {
        background: transparent;
    }
</style>