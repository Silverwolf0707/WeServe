<aside id="sidebar" class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Profile Section (Replaces Logo) -->
    <div class="profile-section d-flex align-items-center py-3 px-3">
        <!-- Profile Image -->
        <div class="profile-image-wrapper me-3">
            @if(Auth::user()->currentProfileImage)
                <img src="{{ Auth::user()->currentProfileImage->image_url }}" alt="Profile" 
                     class="rounded-circle profile-img" style="width: 45px; height: 45px; object-fit: cover;">
            @else
                <div class="rounded-circle profile-img-placeholder d-flex align-items-center justify-content-center"
                     style="width: 45px; height: 45px; background: linear-gradient(135deg, #4e73df, #1cc88a);">
                    <i class="fas fa-user text-white" style="font-size: 20px;"></i>
                </div>
            @endif
        </div>
        
        <!-- User Info -->
        <div class="user-info flex-grow-1">
            <div class="username fw-bold text-light" style="font-size: 1rem;">
                {{ Auth::user()->name }}
            </div>
            <div class="user-role text-light" style="font-size: 0.85rem; opacity: 0.8;">
                {{ Auth::user()->roles->pluck('title')->first() ?? 'User' }}
            </div>
        </div>
        
        <!-- Profile Dropdown Trigger -->
        <button class="btn btn-link p-0 ms-2" data-bs-toggle="modal" data-bs-target="#profileModal">
            <i class="fas fa-ellipsis-v text-light" style="font-size: 1rem;"></i>
        </button>
    </div>

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
                                        <p>Application</p>
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
.profile-section {
    background: #064e3b;
    transition: all 0.3s ease;
    position: sticky;
    top: 0;
    z-index: 100;
    border-bottom: 2px solid rgba(255, 255, 255, 0.1);
    padding: 0.75rem 1rem;
    display: flex;
    align-items: center;
    min-height: 78px; /* Fixed height to prevent shifting */
}

.profile-img {
    border: 2px solid #fff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease;
    width: 45px !important;
    height: 45px !important;
    object-fit: cover;
}

.profile-img-placeholder {
    border: 2px solid #fff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    width: 45px !important;
    height: 45px !important;
    background: linear-gradient(135deg, #4e73df, #1cc88a);
}

.user-info {
    min-width: 0; /* Allows text truncation */
    transition: opacity 0.3s ease, transform 0.3s ease;
    opacity: 1;
    transform: translateX(0);
}

.username {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    line-height: 1.2;
    font-size: 1rem;
}

.user-role {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    line-height: 1.2;
    font-size: 0.85rem;
    opacity: 0.8;
}

.online-status {
    font-size: 0.75rem;
}

/* Ellipsis button */
.btn-link .fa-ellipsis-v {
    transition: transform 0.2s ease;
}

.btn-link:hover .fa-ellipsis-v {
    transform: scale(1.2);
}

/* Collapsed sidebar styles - KEY CHANGES HERE */
body.sidebar-collapse .profile-section {
    padding: 0.75rem 0.5rem !important;
    justify-content: center !important;
}

body.sidebar-collapse .user-info,
body.sidebar-collapse .btn-link {
    display: none !important;
    opacity: 0 !important;
    width: 0 !important;
    height: 0 !important;
    overflow: hidden !important;
}

body.sidebar-collapse .profile-image-wrapper {
    margin-right: 0 !important;
    margin-left: 0 !important;
}

body.sidebar-collapse .profile-img,
body.sidebar-collapse .profile-img-placeholder {
    width: 40px !important;
    height: 40px !important;
    margin: 0 auto !important;
}

/* Show user info on hover when sidebar is collapsed */
body.sidebar-collapse .main-sidebar:hover .profile-section {
    justify-content: flex-start !important;
    padding: 0.75rem 1rem !important;
}

body.sidebar-collapse .main-sidebar:hover .user-info {
    display: block !important;
    opacity: 1 !important;
    width: auto !important;
    height: auto !important;
    margin-left: 12px !important;
}

body.sidebar-collapse .main-sidebar:hover .btn-link {
    display: block !important;
    opacity: 1 !important;
    width: auto !important;
    height: auto !important;
    margin-left: auto !important;
}

body.sidebar-collapse .main-sidebar:hover .profile-img,
body.sidebar-collapse .main-sidebar:hover .profile-img-placeholder {
    width: 45px !important;
    height: 45px !important;
    margin: 0 !important;
}

/* Beta tag styling */
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

/* Existing sidebar styles */
.nav-sidebar {
    background: #023628;
}

.nav-sidebar .nav-link {
    border-radius: 0.5rem;
    margin: 2px 0;
    color: white !important;
    transition: all 0.2s ease;
}

.nav-sidebar .nav-link.active {
    background-color: #74ff70 !important;
    color: black !important;
    font-weight: 600;
}

.nav-sidebar .nav-link:hover {
    background: rgba(60, 141, 188, 0.2);
}

.main-sidebar {
    width: 250px;
    height: 100vh;
    background: #023628;
    overflow-y: auto;
    overflow-x: hidden;
    transition: all 0.3s ease;
    scrollbar-width: none;
    -ms-overflow-style: none;
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
    min-height: calc(100vh - 78px); /* Subtract profile section height */
    padding-bottom: 20px;
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

.nav nav-pills nav-sidebar flex-column {
    min-height: 100%;
}

.nav-sidebar {
    padding-bottom: 50px;
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

/* Smooth animations for expansion */
body.sidebar-collapse .main-sidebar:hover .user-info {
    animation: fadeInSlide 0.3s ease forwards;
}

@keyframes fadeInSlide {
    from {
        opacity: 0;
        transform: translateX(-10px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
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