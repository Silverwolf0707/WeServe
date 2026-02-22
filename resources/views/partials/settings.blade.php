@extends('layouts.admin')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

    <style>
        :root {
            --pr-forest: #064e3b; --pr-forest-deep: #052e22; --pr-forest-mid: #065f46;
            --pr-lime: #74ff70; --pr-lime-dim: #52e84e;
            --pr-lime-ghost: rgba(116,255,112,.10); --pr-lime-border: rgba(116,255,112,.30);
            --pr-surface: #ffffff; --pr-surface2: #f0fdf4; --pr-muted: #ecfdf5;
            --pr-border: #d1fae5; --pr-border-dark: #a7f3d0;
            --pr-text: #052e22; --pr-sub: #3d7a62; --pr-danger: #ef4444;
            --pr-radius: 12px;
            --pr-shadow: 0 2px 8px rgba(6,78,59,.08), 0 8px 24px rgba(6,78,59,.06);
            --pr-shadow-lg: 0 4px 24px rgba(6,78,59,.16), 0 16px 48px rgba(6,78,59,.10);
        }

        .pr-page { font-family:'DM Sans',sans-serif; color:var(--pr-text); padding:0 0 2rem; }

        /* ── Hero ── */
        .pr-hero { background:linear-gradient(135deg,#052e22 0%,#064e3b 55%,#065f46 100%); border-radius:var(--pr-radius); padding:22px 28px; margin-bottom:20px; position:relative; overflow:visible; box-shadow:var(--pr-shadow-lg); }
        .pr-hero::before { content:''; position:absolute; inset:0; border-radius:var(--pr-radius); background:radial-gradient(ellipse 380px 200px at 95% 50%,rgba(116,255,112,.13) 0%,transparent 65%),radial-gradient(ellipse 180px 100px at 5% 80%,rgba(116,255,112,.07) 0%,transparent 70%); pointer-events:none; z-index:0; overflow:hidden; }
        .pr-hero::after  { content:''; position:absolute; top:0; left:28px; right:28px; height:2px; background:linear-gradient(to right,transparent,var(--pr-lime),transparent); border-radius:2px; opacity:.55; }
        .pr-hero-inner { display:flex; align-items:center; gap:16px; position:relative; z-index:1; }
        .pr-hero-icon { width:46px; height:46px; background:rgba(116,255,112,.12); border:1px solid rgba(116,255,112,.30); border-radius:11px; display:flex; align-items:center; justify-content:center; font-size:1.15rem; color:var(--pr-lime); flex-shrink:0; }
        .pr-hero-title { font-size:1.18rem; font-weight:700; color:#fff; letter-spacing:-.01em; margin:0 0 3px; }
        .pr-hero-sub { font-size:.78rem; color:rgba(255,255,255,.55); font-weight:500; }

        /* ── Layout ── */
        .ss-layout { display:grid; grid-template-columns:220px 1fr; gap:16px; align-items:start; }

        /* ── Sidebar ── */
        .ss-sidebar { background:var(--pr-surface); border-radius:var(--pr-radius); border:1px solid var(--pr-border); box-shadow:var(--pr-shadow); overflow:hidden; }
        .ss-sidebar-header { display:flex; align-items:center; gap:10px; padding:13px 16px; background:linear-gradient(135deg,#052e22 0%,#064e3b 100%); position:relative; }
        .ss-sidebar-header::after { content:''; position:absolute; bottom:0; left:16px; right:16px; height:1px; background:rgba(116,255,112,.25); }
        .ss-sidebar-header-icon { width:28px; height:28px; border-radius:7px; background:rgba(116,255,112,.12); border:1px solid rgba(116,255,112,.28); display:flex; align-items:center; justify-content:center; font-size:.76rem; color:var(--pr-lime); flex-shrink:0; }
        .ss-sidebar-header-title { font-size:.82rem; font-weight:700; color:#fff; letter-spacing:-.01em; }
        .ss-nav-item { display:flex; align-items:center; gap:10px; padding:11px 16px; font-size:.82rem; font-weight:500; color:var(--pr-sub); cursor:pointer; border:none; background:none; width:100%; text-align:left; transition:all .15s; border-left:3px solid transparent; text-decoration:none; }
        .ss-nav-item:hover { background:var(--pr-surface2); color:var(--pr-text); }
        .ss-nav-item.active { background:var(--pr-lime-ghost); color:var(--pr-forest); font-weight:700; border-left-color:var(--pr-lime-dim); }
        .ss-nav-item .nav-icon { width:20px; text-align:center; font-size:.76rem; flex-shrink:0; }
        .ss-nav-divider { height:1px; background:var(--pr-border); margin:4px 0; }

        /* ── Content card ── */
        .ss-content { background:var(--pr-surface); border-radius:var(--pr-radius); border:1px solid var(--pr-border); box-shadow:var(--pr-shadow); }
        .ss-pane { display:none; padding:22px; }
        .ss-pane.active { display:block; }
        .ss-pane-title { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; }
        .ss-pane-title-left { display:flex; align-items:center; gap:10px; }
        .ss-pane-icon { width:32px; height:32px; border-radius:9px; background:var(--pr-lime-ghost); border:1px solid var(--pr-lime-border); display:flex; align-items:center; justify-content:center; font-size:.82rem; color:var(--pr-forest); flex-shrink:0; }
        .ss-pane-heading { font-size:.95rem; font-weight:700; color:var(--pr-text); }
        .ss-divider { height:1px; background:var(--pr-border); margin-bottom:18px; }

        /* ── Fields ── */
        .pr-field { display:flex; flex-direction:column; gap:5px; margin-bottom:14px; }
        .pr-field label { font-size:.69rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--pr-sub); }
        .pr-field input, .pr-field select {
            border:1.5px solid var(--pr-border-dark); border-radius:8px; padding:9px 12px;
            font-size:.83rem; font-family:'DM Sans',sans-serif; color:var(--pr-text);
            background:var(--pr-surface); transition:border-color .2s,box-shadow .2s; width:100%;
        }
        .pr-field input:focus, .pr-field select:focus { outline:none; border-color:var(--pr-forest-mid); box-shadow:0 0 0 3px rgba(6,78,59,.10); }
        .pr-field input:disabled, .pr-field select:disabled { background:var(--pr-surface2); color:var(--pr-sub); cursor:not-allowed; }

        /* ── Buttons ── */
        .pr-btn { display:inline-flex; align-items:center; gap:7px; border-radius:9px; padding:8px 18px; font-size:.8rem; font-weight:700; font-family:'DM Sans',sans-serif; cursor:pointer; transition:all .18s; border:none; white-space:nowrap; }
        .pr-btn:hover { transform:translateY(-1px); opacity:.9; }
        .pr-btn-primary { background:var(--pr-forest); color:var(--pr-lime); box-shadow:0 2px 12px rgba(116,255,112,.22); }
        .pr-btn-ghost   { background:var(--pr-surface2); color:var(--pr-sub); border:1.5px solid var(--pr-border-dark); box-shadow:none; }
        .pr-btn-ghost:hover { background:var(--pr-border-dark); color:var(--pr-forest); opacity:1; }
        .pr-btn-danger  { background:#ef4444; color:#fff; box-shadow:0 2px 8px rgba(239,68,68,.25); }
        .pr-btn-info    { background:#0ea5e9; color:#fff; box-shadow:0 2px 8px rgba(14,165,233,.25); }
        .pr-btn-warning { background:#f59e0b; color:#fff; box-shadow:0 2px 8px rgba(245,158,11,.25); }

        /* ── Alert ── */
        .pr-alert { border-radius:8px; padding:10px 14px; font-size:.8rem; font-weight:500; border:none; display:flex; align-items:flex-start; gap:8px; margin-bottom:14px; }
        .pr-alert-info    { background:#eff6ff; color:#1e40af; }
        .pr-alert-warning { background:#fffbeb; color:#78350f; }
        .pr-alert-danger  { background:#fef2f2; color:#7f1d1d; }

        /* ── Sub-cards (backup panels) ── */
        .ss-sub-card { border-radius:10px; border:1px solid var(--pr-border); margin-bottom:14px; overflow:hidden; }
        .ss-sub-header { display:flex; align-items:center; gap:8px; padding:11px 16px; }
        .ss-sub-header-icon { width:26px; height:26px; border-radius:7px; display:flex; align-items:center; justify-content:center; font-size:.74rem; flex-shrink:0; }
        .ss-sub-title { font-size:.82rem; font-weight:700; color:#fff; }
        .ss-sub-body { padding:16px; background:var(--pr-surface); }

        /* ── Backup table ── */
        .ss-table { width:100%; border-collapse:collapse; font-family:'DM Sans',sans-serif; font-size:.8rem; }
        .ss-table thead tr { background:var(--pr-forest); }
        .ss-table thead th { padding:9px 12px; font-size:.67rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:#fff; border:none; white-space:nowrap; }
        .ss-table tbody tr { border-bottom:1px solid var(--pr-border); transition:background .15s; }
        .ss-table tbody tr:last-child { border-bottom:none; }
        .ss-table tbody tr:hover { background:var(--pr-surface2); }
        .ss-table tbody td { padding:10px 12px; border:none; vertical-align:middle; color:var(--pr-text); }
        .ss-table .empty-row td { text-align:center; color:var(--pr-sub); padding:20px; }

        /* ── Status badges ── */
        .ss-badge { display:inline-flex; align-items:center; gap:4px; border-radius:20px; font-size:.68rem; font-weight:700; padding:2px 9px; }
        .ss-badge-db  { background:rgba(59,130,246,.12); border:1px solid rgba(59,130,246,.35); color:#1e3a8a; }
        .ss-badge-doc { background:rgba(5,150,105,.12);  border:1px solid rgba(5,150,105,.35);  color:#065f46; }

        /* ── Action icon buttons ── */
        .ss-icon-btn { width:28px; height:28px; border-radius:7px; border:1px solid var(--pr-border-dark); background:var(--pr-surface); display:inline-flex; align-items:center; justify-content:center; font-size:.72rem; cursor:pointer; transition:all .15s; color:var(--pr-sub); text-decoration:none; }
        .ss-icon-btn:hover { transform:translateY(-1px); }
        .ss-icon-btn-dl:hover  { border-color:#0ea5e9; color:#0ea5e9; background:#f0f9ff; }
        .ss-icon-btn-rst:hover { border-color:#f59e0b; color:#f59e0b; background:#fffbeb; }
        .ss-icon-btn-del:hover { border-color:#ef4444; color:#ef4444; background:#fef2f2; }

        /* ── Version info ── */
        .ss-ver-row { display:flex; align-items:center; gap:14px; padding:11px 0; border-bottom:1px solid var(--pr-border); }
        .ss-ver-row:last-child { border-bottom:none; }
        .ss-ver-icon { width:32px; height:32px; border-radius:8px; background:var(--pr-lime-ghost); border:1px solid var(--pr-lime-border); display:flex; align-items:center; justify-content:center; font-size:.78rem; color:var(--pr-forest); flex-shrink:0; }
        .ss-ver-label { font-size:.67rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--pr-sub); margin-bottom:1px; }
        .ss-ver-value { font-size:.85rem; font-weight:600; color:var(--pr-text); }

        /* ── Danger zone ── */
        .ss-danger-zone { border-radius:10px; border:1.5px solid rgba(239,68,68,.3); background:rgba(239,68,68,.04); padding:18px; }
        .ss-danger-title { display:flex; align-items:center; gap:8px; font-size:.82rem; font-weight:700; color:#991b1b; margin-bottom:8px; }
        .ss-check-label { display:flex; align-items:center; gap:8px; font-size:.82rem; font-weight:500; color:var(--pr-text); cursor:pointer; margin-bottom:14px; }
        .ss-check-label input { accent-color:var(--pr-danger); width:15px; height:15px; cursor:pointer; }

        @media (max-width:768px) {
            .ss-layout { grid-template-columns:1fr; }
        }
    </style>

    <div class="pr-page">

        {{-- Hero --}}
        <div class="pr-hero">
            <div class="pr-hero-inner">
                <div class="pr-hero-icon"><i class="fas fa-cog"></i></div>
                <div>
                    <div class="pr-hero-title">System Settings</div>
                    <div class="pr-hero-sub">Configure system information, backups, and data management</div>
                </div>
            </div>
        </div>

        <div class="ss-layout">

            {{-- ── Sidebar ── --}}
            <div class="ss-sidebar">
                <div class="ss-sidebar-header">
                    <div class="ss-sidebar-header-icon"><i class="fas fa-list"></i></div>
                    <span class="ss-sidebar-header-title">Categories</span>
                </div>
                <nav>
                    <button class="ss-nav-item active" data-target="system">
                        <span class="nav-icon"><i class="fas fa-info-circle"></i></span> System Information
                    </button>
                    <button class="ss-nav-item" data-target="backup">
                        <span class="nav-icon"><i class="fas fa-database"></i></span> Backup & Restore
                    </button>
                    <div class="ss-nav-divider"></div>
                    <button class="ss-nav-item" data-target="datacontrol">
                        <span class="nav-icon"><i class="fas fa-exclamation-triangle"></i></span> Data Control
                    </button>
                    <button class="ss-nav-item" data-target="version">
                        <span class="nav-icon"><i class="fas fa-code-branch"></i></span> System Version
                    </button>
                </nav>
            </div>

            {{-- ── Content ── --}}
            <div class="ss-content">

                {{-- SYSTEM INFORMATION --}}
                <div class="ss-pane active" id="pane-system">
                    <div class="ss-pane-title">
                        <div class="ss-pane-title-left">
                            <div class="ss-pane-icon"><i class="fas fa-info-circle"></i></div>
                            <span class="ss-pane-heading">System Information</span>
                        </div>
                        <button id="editSystemBtn" class="pr-btn pr-btn-ghost" style="padding:6px 14px;font-size:.76rem;">
                            <i class="fas fa-pen" style="font-size:.68rem;"></i> Edit
                        </button>
                    </div>
                    <div class="ss-divider"></div>

                    <form id="systemInfoForm">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                            <div class="pr-field" style="grid-column:1/-1;">
                                <label>System Name</label>
                                <input type="text" value="WeServe: Financial Assistance System" disabled>
                            </div>
                            <div class="pr-field" style="grid-column:1/-1;">
                                <label>Organization / Office</label>
                                <input type="text" value="City Social Welfare and Development Office - San Pedro" disabled>
                            </div>
                            <div class="pr-field">
                                <label>Active Fiscal Year</label>
                                <select disabled>
                                    <option>2025</option>
                                    <option>2024</option>
                                    <option>2023</option>
                                </select>
                            </div>
                            <div class="pr-field">
                                <label>Contact Email</label>
                                <input type="email" value="cswd@sanpedrocity.gov.ph" disabled>
                            </div>
                            
                        </div>
                        <button class="pr-btn pr-btn-primary d-none" id="saveSystemBtn" type="button">
                            <i class="fas fa-save" style="font-size:.72rem;"></i> Save Changes
                        </button>
                    </form>
                </div>

                {{-- BACKUP & RESTORE --}}
                <div class="ss-pane" id="pane-backup">
                    <div class="ss-pane-title">
                        <div class="ss-pane-title-left">
                            <div class="ss-pane-icon"><i class="fas fa-database"></i></div>
                            <span class="ss-pane-heading">Backup & Restore</span>
                        </div>
                    </div>
                    <div class="ss-divider"></div>

                    <p style="font-size:.82rem;color:var(--pr-sub);margin-bottom:16px;">
                        Safeguard your data by creating or restoring system backups.
                    </p>

                    {{-- Create Backup --}}
                    <div class="ss-sub-card">
                        <div class="ss-sub-header" style="background:linear-gradient(135deg,#052e22 0%,#064e3b 100%);">
                            <div class="ss-sub-header-icon" style="background:rgba(116,255,112,.12);border:1px solid rgba(116,255,112,.28);color:var(--pr-lime);">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <span class="ss-sub-title">Create New Backup</span>
                        </div>
                        <div class="ss-sub-body">
                            <form id="createBackupForm">
                                @csrf
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                                    <div class="pr-field" style="margin-bottom:0;">
                                        <label>Backup Type</label>
                                        <select name="type" id="backupType" required>
                                            <option value="documents">Documents Only (Files)</option>
                                            <option value="database">Database Only (Patient Data)</option>
                                        </select>
                                    </div>
                                    <div class="pr-field" style="margin-bottom:0;">
                                        <label>Description (Optional)</label>
                                        <input type="text" name="description" placeholder="E.g., Monthly backup" maxlength="500">
                                    </div>
                                </div>
                                <div class="pr-alert pr-alert-info">
                                    <i class="fas fa-info-circle" style="flex-shrink:0;margin-top:1px;"></i>
                                    <span id="backupInfo">Documents backup includes all uploaded files.</span>
                                </div>
                                <button type="submit" class="pr-btn pr-btn-primary" id="createBackupBtn">
                                    <i class="fas fa-database" style="font-size:.72rem;"></i> Create Backup
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Recent Backups --}}
                    <div class="ss-sub-card">
                        <div class="ss-sub-header" style="background:linear-gradient(135deg,#064e3b 0%,#065f46 100%);">
                            <div class="ss-sub-header-icon" style="background:rgba(116,255,112,.12);border:1px solid rgba(116,255,112,.28);color:var(--pr-lime);">
                                <i class="fas fa-history"></i>
                            </div>
                            <span class="ss-sub-title">Recent Backups</span>
                        </div>
                        <div class="ss-sub-body" style="padding:0;">
                            <div style="overflow-x:auto;">
                                <table class="ss-table" id="backupsTable">
                                    <thead>
                                        <tr>
                                            <th>Filename</th>
                                            <th>Type</th>
                                            <th>Size</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($backups as $backup)
                                            <tr>
                                                <td style="font-size:.78rem;font-weight:600;color:var(--pr-text);max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                                    {{ $backup->filename }}
                                                </td>
                                                <td>
                                                    <span class="ss-badge {{ $backup->type === 'database' ? 'ss-badge-db' : 'ss-badge-doc' }}">
                                                        <i class="fas {{ $backup->type === 'database' ? 'fa-database' : 'fa-file' }}" style="font-size:.58rem;"></i>
                                                        {{ ucfirst($backup->type) }}
                                                    </span>
                                                </td>
                                                <td style="font-size:.78rem;color:var(--pr-sub);white-space:nowrap;">
                                                    {{ \App\Http\Controllers\Admin\SettingsController::formatBytes($backup->size ?? 0) }}
                                                </td>
                                                <td style="font-size:.76rem;color:var(--pr-sub);white-space:nowrap;">
                                                    {{ $backup->created_at->format('M d, Y H:i') }}
                                                </td>
                                                <td>
                                                    <div style="display:flex;gap:5px;">
                                                        <a href="{{ route('admin.settings.backup.download', $backup->id) }}"
                                                           class="ss-icon-btn ss-icon-btn-dl" title="Download">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                        <button class="ss-icon-btn ss-icon-btn-rst restore-backup-btn"
                                                                data-id="{{ $backup->id }}"
                                                                data-filename="{{ $backup->filename }}"
                                                                data-type="{{ $backup->type }}"
                                                                title="Restore">
                                                            <i class="fas fa-upload"></i>
                                                        </button>
                                                        <button class="ss-icon-btn ss-icon-btn-del delete-backup-btn"
                                                                data-id="{{ $backup->id }}"
                                                                title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="empty-row">
                                                <td colspan="5">
                                                    <div style="display:flex;flex-direction:column;align-items:center;gap:8px;padding:12px 0;">
                                                        <div style="width:38px;height:38px;border-radius:10px;background:var(--pr-lime-ghost);border:1px solid var(--pr-lime-border);display:flex;align-items:center;justify-content:center;">
                                                            <i class="fas fa-database" style="color:var(--pr-sub);font-size:.9rem;"></i>
                                                        </div>
                                                        <span style="font-size:.8rem;color:var(--pr-sub);font-weight:500;">No backups found</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- DATA CONTROL --}}
                <div class="ss-pane" id="pane-datacontrol">
                    <div class="ss-pane-title">
                        <div class="ss-pane-title-left">
                            <div class="ss-pane-icon" style="background:rgba(239,68,68,.10);border-color:rgba(239,68,68,.28);color:#dc2626;">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <span class="ss-pane-heading">Data Control</span>
                        </div>
                    </div>
                    <div class="ss-divider"></div>

                    <div class="pr-alert pr-alert-warning" style="margin-bottom:18px;">
                        <i class="fas fa-exclamation-triangle" style="flex-shrink:0;margin-top:1px;"></i>
                        <span>This action is <strong>irreversible</strong>. All database records will be permanently deleted. We strongly recommend creating a backup first.</span>
                    </div>

                    <div class="ss-danger-zone">
                        <div class="ss-danger-title">
                            <i class="fas fa-skull-crossbones"></i> Danger Zone — Delete All Data
                        </div>
                        <p style="font-size:.8rem;color:var(--pr-sub);margin-bottom:14px;">
                            This will permanently delete all patient records, status logs, disbursements, budgets, and tracking data from the system.
                        </p>
                        <form method="POST" action="{{ route('admin.settings.deleteAll') }}"
                              onsubmit="return confirm('Are you absolutely sure? This will permanently delete ALL data and cannot be undone!');">
                            @csrf
                            @method('DELETE')
                            <label class="ss-check-label">
                                <input type="checkbox" id="confirmDelete" required>
                                Yes, I understand — permanently delete all data.
                            </label>
                            <button type="submit" class="pr-btn pr-btn-danger">
                                <i class="fas fa-trash" style="font-size:.72rem;"></i> Delete All Data
                            </button>
                        </form>
                    </div>
                </div>

                {{-- SYSTEM VERSION --}}
                <div class="ss-pane" id="pane-version">
                    <div class="ss-pane-title">
                        <div class="ss-pane-title-left">
                            <div class="ss-pane-icon"><i class="fas fa-code-branch"></i></div>
                            <span class="ss-pane-heading">System Version</span>
                        </div>
                    </div>
                    <div class="ss-divider"></div>

                    <div>
                        <div class="ss-ver-row">
                            <div class="ss-ver-icon"><i class="fas fa-desktop"></i></div>
                            <div>
                                <div class="ss-ver-label">System Name</div>
                                <div class="ss-ver-value">WeServe: Financial Assistance System</div>
                            </div>
                        </div>
                        <div class="ss-ver-row">
                            <div class="ss-ver-icon"><i class="fas fa-code-branch"></i></div>
                            <div>
                                <div class="ss-ver-label">Version</div>
                                <div class="ss-ver-value">1.0.0</div>
                            </div>
                        </div>
                        <div class="ss-ver-row">
                            <div class="ss-ver-icon"><i class="fas fa-users"></i></div>
                            <div>
                                <div class="ss-ver-label">Developed By</div>
                                <div class="ss-ver-value">WeServe Team</div>
                            </div>
                        </div>
                        <div class="ss-ver-row">
                            <div class="ss-ver-icon"><i class="fas fa-calendar-check"></i></div>
                            <div>
                                <div class="ss-ver-label">Last Update</div>
                                <div class="ss-ver-value">October 2025</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- /ss-content --}}
        </div>{{-- /ss-layout --}}
    </div>{{-- /pr-page --}}

@endsection

@section('scripts')
<script>
    // ── Tab navigation ──
    document.querySelectorAll('.ss-nav-item').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.ss-nav-item').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.ss-pane').forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            document.getElementById('pane-' + this.dataset.target).classList.add('active');
        });
    });

    // ── Edit mode toggle ──
    document.getElementById('editSystemBtn').addEventListener('click', function() {
        const form    = document.getElementById('systemInfoForm');
        const inputs  = form.querySelectorAll('input, select');
        const saveBtn = document.getElementById('saveSystemBtn');
        const isEditing = !form.querySelector('input').disabled;

        inputs.forEach(input => input.disabled = isEditing);
        saveBtn.classList.toggle('d-none', isEditing);

        this.innerHTML = isEditing
            ? '<i class="fas fa-pen" style="font-size:.68rem;"></i> Edit'
            : '<i class="fas fa-times" style="font-size:.68rem;"></i> Cancel';
        this.className = isEditing ? 'pr-btn pr-btn-ghost' : 'pr-btn pr-btn-ghost';
    });

    // ── Backup type info ──
    const backupInfoMap = {
        'documents': 'Documents backup includes all uploaded patient files.',
        'database':  'Database backup includes patient records, status logs, disbursements, budgets, rejections, online applications, and tracking numbers.'
    };
    document.getElementById('backupType').addEventListener('change', function() {
        document.getElementById('backupInfo').textContent = backupInfoMap[this.value];
    });

    // ── Create backup ──
    document.getElementById('createBackupForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('createBackupBtn');
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size:.72rem;"></i> Creating…';
        btn.disabled = true;

        try {
            const res    = await fetch('{{ route("admin.settings.backup.create") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: new FormData(this)
            });
            const result = await res.json();
            if (result.success) {
                alert('✓ Backup created!\n\nFilename: ' + result.backup.filename + '\nSize: ' + result.backup.size + '\nType: ' + result.backup.type);
                setTimeout(() => location.reload(), 1200);
            } else throw new Error(result.message || 'Backup failed');
        } catch (err) {
            alert('✗ Backup Failed: ' + err.message);
        } finally {
            btn.innerHTML = orig;
            btn.disabled = false;
        }
    });

    // ── Restore backup ──
    document.addEventListener('click', async function(e) {
        const btn = e.target.closest('.restore-backup-btn');
        if (!btn) return;
        if (!confirm('⚠️ This will overwrite existing data.\n\nRestore from: ' + btn.dataset.filename + '?')) return;

        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btn.disabled = true;

        try {
            const fd = new FormData();
            fd.append('backup_id',    btn.dataset.id);
            fd.append('restore_type', btn.dataset.type);
            fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            const res    = await fetch('{{ route("admin.settings.backup.restore") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: fd
            });
            const result = await res.json();
            if (result.success) { alert('✓ System restored successfully!'); location.reload(); }
            else throw new Error(result.message || 'Restore failed');
        } catch (err) {
            alert('✗ Restore Failed: ' + err.message);
        } finally {
            btn.innerHTML = orig;
            btn.disabled = false;
        }
    });

    // ── Delete backup ──
    document.addEventListener('click', async function(e) {
        const btn = e.target.closest('.delete-backup-btn');
        if (!btn) return;
        if (!confirm('Delete this backup? This cannot be undone.')) return;

        try {
            const url    = '{{ route("admin.settings.backup.delete", ":id") }}'.replace(':id', btn.dataset.id);
            const res    = await fetch(url, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
            });
            const result = await res.json();
            if (result.success) { alert('✓ Backup deleted.'); btn.closest('tr').remove(); }
            else throw new Error(result.message || 'Delete failed');
        } catch (err) {
            alert('✗ Delete Failed: ' + err.message);
        }
    });
</script>
@endsection