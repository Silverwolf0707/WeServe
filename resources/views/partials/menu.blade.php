<aside id="sidebar" class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('admin.home') }}"
        class="brand-link d-flex align-items-center py-3 px-3 border-bottom text-decoration-none">

        <span class="brand-icon d-flex align-items-center justify-content-center">
            <img src="{{ asset('logo.png') }}" alt="Logo" style="height: 40px; width: 40px; object-fit: cover;">
        </span>
        <span class="brand-text fw-bold ms-3 fs-5 text-light" style="margin-left: 1rem;">
            <img src="{{ asset('WeServe1.png') }}" alt="WeServe Logo" class="logo-full" loading="eager">
        </span>
    </a>

    <div class="sidebar">
        <nav class="mt-3">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

                <li class="nav-item">
                    <a href="{{ route('admin.home') }}"
                        class="nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

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
                            <i class="nav-icon fas fa-briefcase"></i>
                            <p>Patient Records</p>
                        </a>
                    </li>
                @endcan
                @can('online_application_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.online-applications.index') }}"
                            class="nav-link {{ request()->is('admin/online-applications*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-globe"></i>
                            <p>
                                Online Records
                                <span class="beta-tag">BETA</span>
                            </p>
                        </a>
                    </li>
                @endcan
                <style>
                    .beta-tag {
                        background: #ffc107;
                        color: #000;
                        font-size: 0.6rem;
                        font-weight: bold;
                        padding: 2px 6px;
                        border-radius: 4px;
                        margin-left: 8px;
                        text-transform: uppercase;
                        position: relative;
                        top: -2px;
                    }

                    /* Or if using Bootstrap classes */
                    .beta-badge {
                        font-size: 0.6rem;
                        margin-left: 8px;
                        vertical-align: top;
                    }
                </style>

                @can('process_tracking_access')
                    <li class="nav-item">
                        <a href="{{ route('admin.process-tracking.index') }}"
                            class="nav-link {{ request()->is('admin/process-tracking*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-stream"></i>
                            <p>Process Tracking
                                {{-- <span class="badge badge-danger right"> 1</span> --}}
                            </p>
                        </a>
                    </li>
                @endcan

                @can('documents_management')
                    <li class="nav-item">
                        <a href="{{ route('admin.document-management.index') }}"
                            class="nav-link {{ request()->is('admin/document-management*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-folder"></i>
                            <p>Documents</p>
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

                @if (
                        auth()->user()->can('CSWD-ANALYTICS') ||
                        auth()->user()->can('BUDGET-ANALYTICS') ||
                        auth()->user()->can('TREASURY-ANALYTICS') ||
                        auth()->user()->can('ACCOUNTING-ANALYTICS')
                    )
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
                        <a href="{{ route('admin.settings.index') }}"
                            class="nav-link {{ request()->is('admin/settings*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>Settings</p>
                        </a>
                    </li>
                @endcan

                @if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
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
                    <a href="#" class="nav-link text-danger" data-bs-toggle="modal" data-bs-target="#logoutModal">
                        <i class="fas fa-sign-out-alt nav-icon"></i>
                        <p>Logout</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>

<style>
.brand-link {
    background: #2c3e50;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    padding: 0.75rem 1rem;
    overflow: hidden;
    border-bottom: 2px solid;
    background: white !important;
    position: sticky;
    top: 0;
    z-index: 100;
}

.brand-icon img {
    width: 45px !important;
    height: 45px !important;
    border-radius: 50%;
    border: 2px solid #fff;
    object-fit: cover;
    flex-shrink: 0;
    transition: transform 0.3s ease;
}

.logo-full {
    transform: translateX(-10px);
    height: 35px;
    width: auto;
}

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

body.sidebar-collapse .brand-text {
    opacity: 0;
    transform: translateX(-20px);
    pointer-events: none;
}

body.sidebar-collapse .main-sidebar:hover .brand-text {
    opacity: 1;
    transform: translateX(0);
    pointer-events: auto;
}

body.sidebar-collapse .main-sidebar:hover .brand-icon img {
    transform: scale(1.05);
}

.nav-sidebar {
    background: #2c3e50;
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

.main-sidebar {
    width: 250px;
    height: 100vh;
    background: #2c3e50;
    overflow-y: auto;
    overflow-x: hidden;
    transition: all 0.3s ease;
    
    /* Hide scrollbar but keep functionality */
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* IE and Edge */
}

/* Hide scrollbar for Chrome, Safari and Opera */
.main-sidebar::-webkit-scrollbar {
    width: 0px;
    background: transparent;
}

.main-sidebar::-webkit-scrollbar-thumb {
    background: transparent;
}

.main-sidebar::-webkit-scrollbar-track {
    background: transparent;
}

.main-sidebar .sidebar {
    min-height: 100vh; /* Changed from height to min-height */
    padding-bottom: 20px; /* Add some space at the bottom */
    
    /* Hide scrollbar but keep functionality */
    scrollbar-width: none;
    -ms-overflow-style: none;
}

.main-sidebar .sidebar::-webkit-scrollbar {
    width: 0px;
    background: transparent;
}

.main-sidebar .sidebar::-webkit-scrollbar-thumb {
    background: transparent;
}

.main-sidebar .sidebar::-webkit-scrollbar-track {
    background: transparent;
}

/* Make the nav container take full available height */
.nav nav-pills nav-sidebar flex-column {
    min-height: 100%;
}

/* Ensure the nav items container can expand */
.nav-sidebar {
    padding-bottom: 50px; /* Extra space for scrolling */
}

body.sidebar-collapse .main-sidebar {
    width: 60px;
}

body.sidebar-collapse .main-sidebar .sidebar {
    overflow: hidden;
}

body.sidebar-collapse .main-sidebar:hover {
    width: 250px;
}

body.sidebar-collapse .main-sidebar:hover .sidebar {
    overflow-y: auto;
    overflow-x: hidden;
}

/* Smooth scrolling */
.main-sidebar,
.main-sidebar .sidebar {
    scroll-behavior: smooth;
}

/* Force full viewport height */
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
}

.wrapper {
    min-height: 100vh;
}
</style>