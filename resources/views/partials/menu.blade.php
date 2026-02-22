<aside id="sidebar" class="ws-sidebar">

    {{-- ── Profile Header ── --}}
    <div class="ws-profile">
        <div class="ws-profile-avatar">
            @if(Auth::user()->currentProfileImage)
                <img src="{{ Auth::user()->currentProfileImage->image_url }}" alt="Profile">
            @else
                <div class="ws-profile-avatar-fallback">
                    <i class="fas fa-user"></i>
                </div>
            @endif
            <span class="ws-online-dot"></span>
        </div>
        <div class="ws-profile-info">
            <div class="ws-profile-name">{{ Auth::user()->name }}</div>
            <div class="ws-profile-role">{{ Auth::user()->roles->pluck('title')->first() ?? 'User' }}</div>
        </div>
        {{-- <button class="ws-profile-action" data-bs-toggle="modal" data-bs-target="#profileModal" title="Profile settings">
            <i class="fas fa-sliders-h"></i>
        </button> --}}
    </div>

    {{-- ── Navigation ── --}}
    <nav class="ws-nav">

        {{-- Dashboard --}}
        <a href="{{ route('admin.home') }}"
           class="ws-nav-item {{ request()->routeIs('admin.home') ? 'ws-active' : '' }}">
            <span class="ws-nav-icon"><i class="fas fa-th-large"></i></span>
            <span class="ws-nav-label">Dashboard</span>
            @if(request()->routeIs('admin.home'))
                <span class="ws-nav-pip"></span>
            @endif
        </a>

        {{-- ── Section: Records ── --}}
        <div class="ws-section-label">Records</div>

        @can('patient_record_access')
        <a href="{{ route('admin.patient-records.index') }}"
           class="ws-nav-item {{ request()->is('admin/patient-records*') ? 'ws-active' : '' }}">
            <span class="ws-nav-icon"><i class="fas fa-file-medical"></i></span>
            <span class="ws-nav-label">Patient Records</span>
            @if(request()->is('admin/patient-records*'))
                <span class="ws-nav-pip"></span>
            @endif
        </a>
        @endcan

        @can('online_application_access')
        <a href="{{ route('admin.online-applications.index') }}"
           class="ws-nav-item {{ request()->is('admin/online-applications*') ? 'ws-active' : '' }}">
            <span class="ws-nav-icon"><i class="fas fa-globe"></i></span>
            <span class="ws-nav-label">
                Online Records
                <span class="ws-badge ws-badge-beta">BETA</span>
            </span>
            @if(request()->is('admin/online-applications*'))
                <span class="ws-nav-pip"></span>
            @endif
        </a>
        @endcan

        @can('documents_management')
        <a href="{{ route('admin.document-management.index') }}"
           class="ws-nav-item {{ request()->is('admin/document-management*') ? 'ws-active' : '' }}">
            <span class="ws-nav-icon"><i class="fas fa-folder-open"></i></span>
            <span class="ws-nav-label">Documents</span>
            @if(request()->is('admin/document-management*'))
                <span class="ws-nav-pip"></span>
            @endif
        </a>
        @endcan

        {{-- ── Section: Workflow ── --}}
        @if(auth()->user()->can('process_tracking_access') || auth()->user()->can('budget_records'))
        <div class="ws-section-label">Workflow</div>
        @endif

        @can('process_tracking_access')
        <a href="{{ route('admin.process-tracking.index') }}"
           class="ws-nav-item {{ request()->is('admin/process-tracking*') ? 'ws-active' : '' }}">
            <span class="ws-nav-icon"><i class="fas fa-route"></i></span>
            <span class="ws-nav-label">Process Tracking</span>
            @if(request()->is('admin/process-tracking*'))
                <span class="ws-nav-pip"></span>
            @endif
        </a>
        @endcan

        @can('budget_records')
        <a href="{{ route('admin.budget-records.index') }}"
           class="ws-nav-item {{ request()->is('admin/budget-records*') ? 'ws-active' : '' }}">
            <span class="ws-nav-icon"><i class="fas fa-file-invoice-dollar"></i></span>
            <span class="ws-nav-label">Budget Records</span>
            @if(request()->is('admin/budget-records*'))
                <span class="ws-nav-pip"></span>
            @endif
        </a>
        @endcan

        {{-- ── Section: Analytics ── --}}
        @if(auth()->user()->can('CSWD-ANALYTICS') || auth()->user()->can('BUDGET-ANALYTICS') || auth()->user()->can('TREASURY-ANALYTICS') || auth()->user()->can('ACCOUNTING-ANALYTICS'))
        <div class="ws-section-label">Analytics</div>

        <div class="ws-nav-group {{ request()->is('admin/time-series*') ? 'ws-group-open' : '' }}" id="wsGroupAnalytics">
            <button class="ws-nav-item ws-nav-group-trigger" onclick="wsToggleGroup('wsGroupAnalytics')">
                <span class="ws-nav-icon"><i class="fas fa-chart-line"></i></span>
                <span class="ws-nav-label">Analytics</span>
                <span class="ws-nav-chevron"><i class="fas fa-chevron-right"></i></span>
            </button>
            <div class="ws-nav-group-children">
                @can('CSWD-ANALYTICS')
                <a href="{{ route('admin.time-series.index', ['type' => 'cswd']) }}"
                   class="ws-nav-item ws-nav-child {{ request()->fullUrlIs(route('admin.time-series.index', ['type' => 'cswd'])) ? 'ws-active' : '' }}">
                    <span class="ws-nav-icon"><i class="fas fa-chart-pie"></i></span>
                    <span class="ws-nav-label">Application</span>
                </a>
                @endcan
                @can('BUDGET-ANALYTICS')
                <a href="{{ route('admin.time-series.index', ['type' => 'budget']) }}"
                   class="ws-nav-item ws-nav-child {{ request()->fullUrlIs(route('admin.time-series.index', ['type' => 'budget'])) ? 'ws-active' : '' }}">
                    <span class="ws-nav-icon"><i class="fas fa-wallet"></i></span>
                    <span class="ws-nav-label">Budget</span>
                </a>
                @endcan
            </div>
        </div>
        @endif

        {{-- ── Section: Admin ── --}}
        @can('user_management_access')
        <div class="ws-section-label">Admin</div>

        <div class="ws-nav-group {{ request()->is('admin/permissions*') || request()->is('admin/roles*') || request()->is('admin/users*') || request()->is('admin/audit-logs*') ? 'ws-group-open' : '' }}" id="wsGroupUsers">
            <button class="ws-nav-item ws-nav-group-trigger" onclick="wsToggleGroup('wsGroupUsers')">
                <span class="ws-nav-icon"><i class="fas fa-users-cog"></i></span>
                <span class="ws-nav-label">User Management</span>
                <span class="ws-nav-chevron"><i class="fas fa-chevron-right"></i></span>
            </button>
            <div class="ws-nav-group-children">
                @can('permission_access')
                <a href="{{ route('admin.permissions.index') }}"
                   class="ws-nav-item ws-nav-child {{ request()->is('admin/permissions*') ? 'ws-active' : '' }}">
                    <span class="ws-nav-icon"><i class="fas fa-unlock-alt"></i></span>
                    <span class="ws-nav-label">Permissions</span>
                </a>
                @endcan
                @can('role_access')
                <a href="{{ route('admin.roles.index') }}"
                   class="ws-nav-item ws-nav-child {{ request()->is('admin/roles*') ? 'ws-active' : '' }}">
                    <span class="ws-nav-icon"><i class="fas fa-shield-alt"></i></span>
                    <span class="ws-nav-label">Roles</span>
                </a>
                @endcan
                @can('user_access')
                <a href="{{ route('admin.users.index') }}"
                   class="ws-nav-item ws-nav-child {{ request()->is('admin/users*') ? 'ws-active' : '' }}">
                    <span class="ws-nav-icon"><i class="fas fa-user-circle"></i></span>
                    <span class="ws-nav-label">Users</span>
                </a>
                @endcan
                @can('audit_log_access')
                <a href="{{ route('admin.audit-logs.index') }}"
                   class="ws-nav-item ws-nav-child {{ request()->is('admin/audit-logs*') ? 'ws-active' : '' }}">
                    <span class="ws-nav-icon"><i class="fas fa-clipboard-list"></i></span>
                    <span class="ws-nav-label">Audit Logs</span>
                </a>
                @endcan
            </div>
        </div>
        @endcan

        @can('settings')
        <a href="{{ route('admin.settings.index') }}"
           class="ws-nav-item {{ request()->is('admin/settings*') ? 'ws-active' : '' }}">
            <span class="ws-nav-icon"><i class="fas fa-cog"></i></span>
            <span class="ws-nav-label">Settings</span>
            @if(request()->is('admin/settings*'))
                <span class="ws-nav-pip"></span>
            @endif
        </a>
        @endcan

        @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
            @can('profile_password_edit')
            <a href="{{ route('profile.password.edit') }}"
               class="ws-nav-item {{ request()->is('profile/password*') ? 'ws-active' : '' }}">
                <span class="ws-nav-icon"><i class="fas fa-key"></i></span>
                <span class="ws-nav-label">Change Password</span>
                @if(request()->is('profile/password*'))
                    <span class="ws-nav-pip"></span>
                @endif
            </a>
            @endcan
        @endif

        {{-- ── Logout ── --}}
        <div class="ws-nav-divider"></div>

        <a href="#" class="ws-nav-item ws-nav-logout" data-bs-toggle="modal" data-bs-target="#logoutModal">
            <span class="ws-nav-icon"><i class="fas fa-sign-out-alt"></i></span>
            <span class="ws-nav-label">Logout</span>
        </a>

    </nav>
</aside>

<style>
/* ═══════════════════════════════════════════════
   WeServe Sidebar — Design System Match
   Forest-green / DM Sans / Lime accent
   ═══════════════════════════════════════════════ */

@import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap');

:root {
    --ws-forest:       #064e3b;
    --ws-forest-deep:  #052e22;
    --ws-forest-mid:   #065f46;
    --ws-forest-light: #0a7c5c;
    --ws-lime:         #74ff70;
    --ws-lime-dim:     #52e84e;
    --ws-lime-ghost:   rgba(116,255,112,.12);
    --ws-lime-border:  rgba(116,255,112,.28);
    --ws-surface:      rgba(255,255,255,.06);
    --ws-surface-hov:  rgba(255,255,255,.10);
    --ws-border:       rgba(255,255,255,.08);
    --ws-text:         rgba(255,255,255,.92);
    --ws-text-sub:     rgba(255,255,255,.50);
    --ws-text-dim:     rgba(255,255,255,.30);
    --ws-sidebar-w:    248px;
    --ws-sidebar-w-col: 62px;
    --ws-radius:       10px;
    --ws-transition:   0.22s cubic-bezier(.4,0,.2,1);
}

/* ── Sidebar Shell ── */
.ws-sidebar {
    position: fixed;
    top: 0; left: 0; bottom: 0;
    width: var(--ws-sidebar-w);
    background: linear-gradient(180deg, #052e22 0%, #064e3b 45%, #065f46 100%);
    display: flex;
    flex-direction: column;
    font-family: 'DM Sans', sans-serif;
    z-index: 1030;
    transition: width var(--ws-transition);
    overflow: hidden;
    box-shadow: 4px 0 24px rgba(0,0,0,.25);
}

/* subtle noise texture overlay */
.ws-sidebar::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.025'/%3E%3C/svg%3E");
    pointer-events: none;
    z-index: 0;
}

/* top lime line */
.ws-sidebar::after {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(to right, transparent, var(--ws-lime), transparent);
    opacity: .5;
    z-index: 1;
}

.ws-sidebar > * { position: relative; z-index: 1; }

/* ── Collapsed state ── */
body.sidebar-collapse .ws-sidebar { width: var(--ws-sidebar-w-col); }
body.sidebar-collapse .ws-sidebar:hover { width: var(--ws-sidebar-w); }

/* ── Collapsed: icons only, perfectly centered ── */
body.sidebar-collapse .ws-sidebar:not(:hover) .ws-nav {
    padding: 10px 0 20px;
}

/* Each item is a full-width row, icon centered inside */
body.sidebar-collapse .ws-sidebar:not(:hover) .ws-nav-item {
    width: 100%;
    padding: 0;
    height: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0;
    border: none !important;
    border-radius: 0 !important;
    background: transparent !important;
}
body.sidebar-collapse .ws-sidebar:not(:hover) .ws-nav-item:hover {
    background: var(--ws-surface-hov) !important;
}
body.sidebar-collapse .ws-sidebar:not(:hover) .ws-nav-item.ws-active {
    background: var(--ws-lime-ghost) !important;
    border-left: 2px solid var(--ws-lime) !important;
}

/* Icon: fixed 34px, centered, no margin tricks */
body.sidebar-collapse .ws-sidebar:not(:hover) .ws-nav-icon {
    width: 34px;
    height: 34px;
    font-size: .84rem;
    flex-shrink: 0;
    margin: 0 !important;
    display: flex;
    align-items: center;
    justify-content: center;
}
body.sidebar-collapse .ws-sidebar:not(:hover) .ws-nav-item.ws-active .ws-nav-icon {
    background: rgba(116,255,112,.18);
    color: var(--ws-lime);
}
body.sidebar-collapse .ws-sidebar:not(:hover) .ws-nav-item:hover .ws-nav-icon {
    background: var(--ws-surface-hov);
    color: var(--ws-text);
}

/* Label: fully removed from layout, not just hidden */
body.sidebar-collapse .ws-sidebar:not(:hover) .ws-nav-label {
    display: none !important;
}

/* Section labels: removed from flow */
body.sidebar-collapse .ws-sidebar:not(:hover) .ws-section-label {
    height: 0 !important;
    overflow: hidden !important;
    opacity: 0 !important;
    padding: 0 !important;
    margin: 0 !important;
}

/* Hide pips, badges, chevrons */
body.sidebar-collapse .ws-sidebar:not(:hover) .ws-nav-pip,
body.sidebar-collapse .ws-sidebar:not(:hover) .ws-badge,
body.sidebar-collapse .ws-sidebar:not(:hover) .ws-nav-chevron {
    display: none !important;
}

/* Logout icon */
body.sidebar-collapse .ws-sidebar:not(:hover) .ws-nav-logout .ws-nav-icon {
    color: rgba(239,68,68,.7);
}
body.sidebar-collapse .ws-sidebar:not(:hover) .ws-nav-logout:hover .ws-nav-icon {
    color: #f87171;
    background: rgba(239,68,68,.12);
}

/* Divider */
body.sidebar-collapse .ws-sidebar:not(:hover) .ws-nav-divider {
    margin: 6px 8px;
}

/* Profile: center avatar only */
body.sidebar-collapse .ws-sidebar:not(:hover) .ws-profile {
    width: 100%;
    padding: 0;
    height: 72px;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0;
}
body.sidebar-collapse .ws-sidebar:not(:hover) .ws-profile-info,
body.sidebar-collapse .ws-sidebar:not(:hover) .ws-profile-action {
    display: none !important;
}
body.sidebar-collapse .ws-sidebar:not(:hover) .ws-profile-avatar {
    margin: 0 !important;
}

/* Group children hidden */
body.sidebar-collapse .ws-sidebar:not(:hover) .ws-nav-group-children {
    display: none !important;
}

/* ── Hover restore ── */
body.sidebar-collapse .ws-sidebar:hover .ws-nav-label {
    width: auto !important;
    flex: 1 !important;
    overflow: visible !important;
    opacity: 1 !important;
    padding: 0 !important;
}
body.sidebar-collapse .ws-sidebar:hover .ws-section-label {
    height: auto !important;
    overflow: visible !important;
    opacity: 1 !important;
    padding: 14px 8px 5px !important;
    margin: 0 !important;
}
body.sidebar-collapse .ws-sidebar:hover .ws-nav-item {
    padding: 8px 10px;
    justify-content: flex-start;
    gap: 10px;
}
body.sidebar-collapse .ws-sidebar:hover .ws-nav-icon {
    width: 28px;
    height: 28px;
    font-size: .78rem;
}

/* ── Profile Header ── */
.ws-profile {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 16px 14px 14px;
    border-bottom: 1px solid var(--ws-border);
    min-height: 72px;
    background: rgba(0,0,0,.12);
    backdrop-filter: blur(4px);
    flex-shrink: 0;
}

.ws-profile-avatar {
    position: relative;
    flex-shrink: 0;
}

.ws-profile-avatar img,
.ws-profile-avatar-fallback {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    object-fit: cover;
    border: 1.5px solid var(--ws-lime-border);
    display: flex;
    align-items: center;
    justify-content: center;
}

.ws-profile-avatar-fallback {
    background: linear-gradient(135deg, var(--ws-forest-mid), var(--ws-forest-light));
    color: var(--ws-lime);
    font-size: .9rem;
}

.ws-online-dot {
    position: absolute;
    bottom: -1px; right: -1px;
    width: 9px; height: 9px;
    background: var(--ws-lime);
    border-radius: 50%;
    border: 1.5px solid var(--ws-forest-deep);
    box-shadow: 0 0 6px var(--ws-lime);
}

.ws-profile-info {
    flex: 1;
    min-width: 0;
    transition: opacity var(--ws-transition), transform var(--ws-transition);
}

.ws-profile-name {
    font-size: .82rem;
    font-weight: 700;
    color: var(--ws-text);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.3;
}

.ws-profile-role {
    font-size: .7rem;
    font-weight: 500;
    color: var(--ws-lime);
    opacity: .75;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-top: 1px;
    letter-spacing: .03em;
    text-transform: uppercase;
}

.ws-profile-action {
    flex-shrink: 0;
    width: 28px; height: 28px;
    background: var(--ws-surface);
    border: 1px solid var(--ws-border);
    border-radius: 7px;
    color: var(--ws-text-sub);
    font-size: .72rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all var(--ws-transition);
    padding: 0;
}

.ws-profile-action:hover {
    background: var(--ws-lime-ghost);
    border-color: var(--ws-lime-border);
    color: var(--ws-lime);
    transform: rotate(90deg);
}



/* ── Navigation ── */
.ws-nav {
    flex: 1;
    overflow-y: auto;
    overflow-x: hidden;
    padding: 10px 8px 20px;
    scrollbar-width: none;
    -ms-overflow-style: none;
}
.ws-nav::-webkit-scrollbar { display: none; }

/* ── Section Labels ── */
.ws-section-label {
    font-size: .6rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--ws-text-dim);
    padding: 14px 8px 5px;
    white-space: nowrap;
    overflow: hidden;
    transition: opacity var(--ws-transition);
}

body.sidebar-collapse .ws-sidebar:not(:hover) .ws-section-label {
    opacity: 0;
}

/* ── Nav Items ── */
.ws-nav-item {
    position: relative;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 10px;
    border-radius: var(--ws-radius);
    color: var(--ws-text-sub);
    text-decoration: none;
    font-size: .82rem;
    font-weight: 500;
    font-family: 'DM Sans', sans-serif;
    cursor: pointer;
    transition: all var(--ws-transition);
    margin-bottom: 2px;
    border: none;
    background: transparent;
    width: 100%;
    text-align: left;
    white-space: nowrap;
    overflow: hidden;
}

.ws-nav-item:hover {
    background: var(--ws-surface-hov);
    color: var(--ws-text);
    text-decoration: none;
}

.ws-nav-item.ws-active {
    background: var(--ws-lime-ghost);
    color: var(--ws-lime);
    font-weight: 600;
    border: 1px solid var(--ws-lime-border);
}

.ws-nav-item.ws-active .ws-nav-icon {
    color: var(--ws-lime);
}

/* ── Nav Icon ── */
.ws-nav-icon {
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 7px;
    font-size: .78rem;
    flex-shrink: 0;
    transition: all var(--ws-transition);
    color: var(--ws-text-sub);
    background: transparent;
}

.ws-nav-item:hover .ws-nav-icon {
    color: var(--ws-text);
    background: var(--ws-surface);
}

.ws-nav-item.ws-active .ws-nav-icon {
    background: rgba(116,255,112,.18);
    color: var(--ws-lime);
}

/* ── Nav Label ── */
.ws-nav-label {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 7px;
    overflow: hidden;
    text-overflow: ellipsis;
    transition: opacity var(--ws-transition);
}

body.sidebar-collapse .ws-sidebar:not(:hover) .ws-nav-label { 
    opacity: 0; 
    width: 0;
    overflow: hidden;
}
body.sidebar-collapse .ws-sidebar:not(:hover) .ws-section-label { 
    opacity: 0;
    height: 0;
    padding: 0;
    overflow: hidden;
}

/* ── Active Pip ── */
.ws-nav-pip {
    width: 5px; height: 5px;
    border-radius: 50%;
    background: var(--ws-lime);
    box-shadow: 0 0 6px var(--ws-lime);
    flex-shrink: 0;
    margin-left: auto;
}

/* ── Badges ── */
.ws-badge {
    display: inline-flex;
    align-items: center;
    border-radius: 5px;
    font-size: .58rem;
    font-weight: 800;
    padding: 1px 5px;
    letter-spacing: .06em;
    line-height: 1.6;
}

.ws-badge-beta {
    background: rgba(245,158,11,.2);
    color: #fbbf24;
    border: 1px solid rgba(245,158,11,.35);
}

/* ── Group / Treeview ── */
.ws-nav-group { margin-bottom: 2px; }

.ws-nav-group-trigger {
    width: 100%;
}

.ws-nav-chevron {
    margin-left: auto;
    font-size: .62rem;
    color: var(--ws-text-dim);
    transition: transform var(--ws-transition);
    flex-shrink: 0;
}

.ws-group-open > .ws-nav-group-trigger .ws-nav-chevron {
    transform: rotate(90deg);
}

.ws-nav-group-children {
    display: none;
    padding-left: 8px;
    margin-top: 2px;
    border-left: 1.5px solid var(--ws-border);
    margin-left: 23px;
}

.ws-group-open > .ws-nav-group-children {
    display: block;
    animation: wsGroupOpen .18s ease forwards;
}

@keyframes wsGroupOpen {
    from { opacity: 0; transform: translateY(-4px); }
    to   { opacity: 1; transform: translateY(0); }
}

.ws-nav-child {
    padding: 6px 10px;
    font-size: .78rem;
}

.ws-nav-child .ws-nav-icon {
    width: 22px; height: 22px;
    font-size: .7rem;
}

/* ── Divider ── */
.ws-nav-divider {
    height: 1px;
    background: var(--ws-border);
    margin: 10px 0;
}

/* ── Logout ── */
.ws-nav-logout {
    color: rgba(239,68,68,.7);
}

.ws-nav-logout:hover {
    background: rgba(239,68,68,.10);
    color: #f87171;
    border: 1px solid rgba(239,68,68,.2);
}

.ws-nav-logout .ws-nav-icon {
    color: rgba(239,68,68,.6);
}

.ws-nav-logout:hover .ws-nav-icon {
    color: #f87171;
    background: rgba(239,68,68,.12);
}

/* ── Content wrapper offset ── */
.content-wrapper {
    margin-left: var(--ws-sidebar-w) !important;
    transition: margin-left var(--ws-transition);
}

body.sidebar-collapse .content-wrapper {
    margin-left: var(--ws-sidebar-w-col) !important;
}

.main-header.navbar {
    margin-left: var(--ws-sidebar-w) !important;
    transition: margin-left var(--ws-transition);
}

body.sidebar-collapse .main-header.navbar {
    margin-left: var(--ws-sidebar-w-col) !important;
}

/* ── Hide old AdminLTE sidebar if still rendered ── */
.main-sidebar:not(.ws-sidebar) { display: none !important; }
</style>

<script>
function wsToggleGroup(id) {
    const group = document.getElementById(id);
    if (!group) return;
    group.classList.toggle('ws-group-open');
}

// Expand groups that contain the active item on load
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.ws-nav-group').forEach(group => {
        if (group.querySelector('.ws-active')) {
            group.classList.add('ws-group-open');
        }
    });
});
</script>

<script>
// Collapse all groups when sidebar collapses, restore active group on expand
(function () {
    const sidebar = document.getElementById('sidebar');
    if (!sidebar) return;

    // Store which groups were open before collapsing
    let openGroupsBeforeCollapse = [];

    // Watch for sidebar-collapse class on body
    const observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            if (mutation.attributeName !== 'class') return;
            const isCollapsed = document.body.classList.contains('sidebar-collapse');
            if (isCollapsed) {
                // Save open groups and collapse them all
                openGroupsBeforeCollapse = [];
                document.querySelectorAll('.ws-nav-group.ws-group-open').forEach(g => {
                    openGroupsBeforeCollapse.push(g.id);
                    g.classList.remove('ws-group-open');
                });
            } else {
                // Restore previously open groups (or the one with active item)
                if (openGroupsBeforeCollapse.length > 0) {
                    openGroupsBeforeCollapse.forEach(id => {
                        const g = document.getElementById(id);
                        if (g) g.classList.add('ws-group-open');
                    });
                } else {
                    // Fallback: re-open any group containing the active page
                    document.querySelectorAll('.ws-nav-group').forEach(g => {
                        if (g.querySelector('.ws-active')) g.classList.add('ws-group-open');
                    });
                }
            }

            // Recalculate DataTables column widths after sidebar transition ends
            const transitionDuration = 240; // matches --ws-transition 0.22s + buffer
            setTimeout(function () {
                if (typeof $.fn !== 'undefined' && $.fn.dataTable) {
                    $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
                }
                // Also fire a window resize so any other layout-dependent components recalculate
                window.dispatchEvent(new Event('resize'));
            }, transitionDuration);
        });
    });

    observer.observe(document.body, { attributes: true });
})();
</script>