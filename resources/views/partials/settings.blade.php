@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">
        <h1 class="mb-4 fw-bold"><i class="fas fa-cog me-2"></i>System Settings</h1>

        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Categories</h5>
                    </div>
                    <div class="list-group list-group-flush" id="configTabs" role="tablist">
                        <a class="list-group-item list-group-item-action active" id="system-tab" data-bs-toggle="list"
                            href="#system" role="tab">System Information</a>
                        <a class="list-group-item list-group-item-action" id="display-tab" data-bs-toggle="list"
                            href="#display" role="tab">Display Preference</a>
                        <a class="list-group-item list-group-item-action" id="backup-tab" data-bs-toggle="list"
                            href="#backup" role="tab">Backup & Restore</a>
                        <a class="list-group-item list-group-item-action" id="datacontrol-tab" data-bs-toggle="list"
                            href="#datacontrol" role="tab">Data Control</a>
                        <a class="list-group-item list-group-item-action" id="version-tab" data-bs-toggle="list"
                            href="#version" role="tab">System Version</a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="tab-content" id="configTabsContent">

                            <!-- SYSTEM INFORMATION -->
                            <div class="tab-pane fade show active" id="system" role="tabpanel" aria-labelledby="system-tab">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="fw-bold">System Information</h5>
                                    <button id="editSystemBtn" class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-pen"></i> Edit
                                    </button>
                                </div>
                                <hr>
                                <form id="systemInfoForm">
                                    <div class="mb-3">
                                        <label class="form-label">System Name</label>
                                        <input type="text" class="form-control" value="WeServe: Financial Assistance System" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Organization / Office</label>
                                        <input type="text" class="form-control" value="City Social Welfare and Development Office - San Pedro" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Active Fiscal Year</label>
                                        <select class="form-select" disabled>
                                            <option selected>2025</option>
                                            <option>2024</option>
                                            <option>2023</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">System Logo</label>
                                        <input type="file" class="form-control" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Contact Email</label>
                                        <input type="email" class="form-control" value="cswd@sanpedrocity.gov.ph" disabled>
                                    </div>
                                    <button class="btn btn-success d-none" id="saveSystemBtn">Save Changes</button>
                                </form>
                            </div>

                            <!-- DISPLAY PREFERENCE -->
                            <div class="tab-pane fade" id="display" role="tabpanel" aria-labelledby="display-tab">
                                <h5 class="fw-bold">Display Preference</h5>
                                <hr>
                                <form id="themeForm">
                                    <div class="mb-3">
                                        <label class="form-label">Theme</label>
                                        <select class="form-select" id="themeSelector">
                                            <option value="light">Light Mode</option>
                                            <option value="dark">Dark Mode</option>
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-success" id="saveThemeBtn">Save Theme</button>
                                </form>
                            </div>

                            <!-- BACKUP & RESTORE -->
                            <div class="tab-pane fade" id="backup" role="tabpanel" aria-labelledby="backup-tab">
                                <h5 class="fw-bold">Backup & Restore</h5>
                                <hr>
                                <p class="text-muted">
                                    Safeguard your data by creating or restoring system backups.
                                </p>

                                <!-- Create Backup -->
                                <div class="card mb-4 border-0 shadow-sm">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0"><i class="fas fa-database me-2"></i>Create New Backup</h6>
                                    </div>
                                    <div class="card-body">
                                        <form id="createBackupForm">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Backup Type</label>
                                                    <select class="form-select" name="type" id="backupType" required>
                                                        <option value="documents">Documents Only (Files)</option>
                                                        <option value="database">Database Only (Patient Data)</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Description (Optional)</label>
                                                    <input type="text" class="form-control" name="description" placeholder="E.g., Monthly backup" maxlength="500">
                                                </div>
                                            </div>
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle me-2"></i>
                                                <span id="backupInfo">Documents backup includes all uploaded files.</span>
                                            </div>
                                            <button type="submit" class="btn btn-primary" id="createBackupBtn">
                                                <i class="fas fa-database me-2"></i>Create Backup
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Recent Backups -->
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0"><i class="fas fa-history me-2"></i>Recent Backups</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover" id="backupsTable">
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
                                                            <td>{{ $backup->filename }}</td>
                                                            <td>
                                                                <span class="badge bg-info text-capitalize">{{ $backup->type }}</span>
                                                            </td>
                                                            <td>{{ \App\Http\Controllers\Admin\SettingsController::formatBytes($backup->size ?? 0) }}</td>
                                                            <td>{{ $backup->created_at->format('M d, Y H:i') }}</td>
                                                            <td>
                                                                <div class="btn-group btn-group-sm">
                                                                    <a href="{{ route('admin.settings.backup.download', $backup->id) }}" class="btn btn-outline-primary" title="Download">
                                                                        <i class="fas fa-download"></i>
                                                                    </a>
                                                                    <button class="btn btn-outline-warning restore-backup-btn" data-id="{{ $backup->id }}" data-filename="{{ $backup->filename }}" data-type="{{ $backup->type }}" title="Restore">
                                                                        <i class="fas fa-upload"></i>
                                                                    </button>
                                                                    <button class="btn btn-outline-danger delete-backup-btn" data-id="{{ $backup->id }}" title="Delete">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center text-muted">No backups found</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- DATA CONTROL -->
                            <div class="tab-pane fade" id="datacontrol" role="tabpanel" aria-labelledby="datacontrol-tab">
                                <h5 class="fw-bold">Data Control</h5>
                                <hr>
                                <p class="text-danger">
                                    ⚠️ Warning: This will permanently delete all database records. Proceed with extreme caution.
                                </p>
                                <form method="POST" action="{{ route('admin.settings.deleteAll') }}" onsubmit="return confirm('Are you absolutely sure? This will permanently delete ALL data!');">
                                    @csrf
                                    @method('DELETE')
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="confirmDelete" required>
                                        <label class="form-check-label" for="confirmDelete">
                                            Yes, I understand. Delete all data.
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-danger">Delete All Data</button>
                                </form>
                            </div>

                            <!-- SYSTEM VERSION -->
                            <div class="tab-pane fade" id="version" role="tabpanel" aria-labelledby="version-tab">
                                <h5 class="fw-bold">System Version</h5>
                                <hr>
                                <p><strong>System Name:</strong> WeServe: Financial Assistance System</p>
                                <p><strong>Version:</strong> 1.0.0</p>
                                <p><strong>Developed by:</strong> WeServe Team</p>
                                <p><strong>Last Update:</strong> October 2025</p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
   @section('scripts')
<script>
    // ===== Enable Edit Mode for System Information =====
    document.getElementById('editSystemBtn').addEventListener('click', function() {
        const form = document.getElementById('systemInfoForm');
        const inputs = form.querySelectorAll('input, select');
        const saveBtn = document.getElementById('saveSystemBtn');

        inputs.forEach(input => {
            input.disabled = !input.disabled;
        });

        saveBtn.classList.toggle('d-none');
        this.classList.toggle('btn-outline-success');
        this.classList.toggle('btn-secondary');
        this.innerHTML = form.querySelector('input').disabled ?
            '<i class="fas fa-pen"></i> Edit' :
            '<i class="fas fa-times"></i> Cancel';
    });

    // ===== Global Theme Persistence (Dark / Light) =====
    document.addEventListener('DOMContentLoaded', function() {
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.body.classList.toggle('dark-mode', savedTheme === 'dark');
    });

    // ===== Theme Switch (Dark / Light) =====
    const themeSelector = document.getElementById('themeSelector');
    const saveThemeBtn = document.getElementById('saveThemeBtn');

    // Load saved theme into the selector
    const currentTheme = localStorage.getItem('theme') || 'light';
    themeSelector.value = currentTheme;
    document.body.classList.toggle('dark-mode', currentTheme === 'dark');

    // Save and apply new theme
    saveThemeBtn.addEventListener('click', () => {
        const selectedTheme = themeSelector.value;
        localStorage.setItem('theme', selectedTheme);

        if (selectedTheme === 'dark') {
            document.body.classList.add('dark-mode');
        } else {
            document.body.classList.remove('dark-mode');
        }

        alert('Theme updated successfully!');
    });

    // ===== Backup & Restore Functionality =====
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Backup type info
        const backupInfo = {
            'documents': 'Documents backup includes all uploaded files.',
            'database': 'Database backup includes patient records, status logs, disbursements, budgets, rejections, online applications, and tracking numbers.'
        };

        document.getElementById('backupType').addEventListener('change', function() {
            document.getElementById('backupInfo').textContent = backupInfo[this.value];
        });

        // Create backup
        document.getElementById('createBackupForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const btn = document.getElementById('createBackupBtn');
            const originalText = btn.innerHTML;

            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Backup...';
            btn.disabled = true;

            const formData = new FormData(this);

            try {
                const response = await fetch('{{ route("admin.settings.backup.create") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    alert('✓ Backup created successfully!\n\nFilename: ' + result.backup.filename +
                        '\nSize: ' + result.backup.size + '\nType: ' + result.backup.type);

                    // Refresh page to show new backup
                    setTimeout(() => location.reload(), 1500);
                } else {
                    throw new Error(result.message || 'Backup failed');
                }
            } catch (error) {
                alert('✗ Backup Failed: ' + error.message);
                console.error('Backup error:', error);
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });

        // Restore backup from table row
        document.addEventListener('click', async function(e) {
            if (e.target.closest('.restore-backup-btn')) {
                const btn = e.target.closest('.restore-backup-btn');
                const backupId = btn.dataset.id;
                const filename = btn.dataset.filename;
                const backupType = btn.dataset.type;

                if (!confirm(
                        '⚠️ WARNING: This will overwrite existing data.\n\nAre you sure you want to restore from backup:\n' +
                        filename + '?')) {
                    return;
                }

                const originalText = btn.innerHTML;

                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Restoring...';
                btn.disabled = true;

                try {
                    // Create FormData object
                    const formData = new FormData();
                    formData.append('backup_id', backupId);
                    formData.append('restore_type', backupType);
                    formData.append('_token', csrfToken);

                    const response = await fetch('{{ route("admin.settings.backup.restore") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        alert('✓ System has been restored successfully!');
                        location.reload();
                    } else {
                        throw new Error(result.message || 'Restore failed');
                    }
                } catch (error) {
                    alert('✗ Restore Failed: ' + error.message);
                    console.error('Restore error:', error);
                } finally {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            }
        });

        // Delete backup from table row
        document.addEventListener('click', async function(e) {
            if (e.target.closest('.delete-backup-btn')) {
                const btn = e.target.closest('.delete-backup-btn');
                const backupId = btn.dataset.id;

                if (!confirm('Are you sure you want to delete this backup?')) {
                    return;
                }

                try {
                    const deleteUrl = '{{ route("admin.settings.backup.delete", ":id") }}'.replace(':id', backupId);

                    const response = await fetch(deleteUrl, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();

                    if (result.success) {
                        alert('✓ Backup deleted successfully!');

                        // Remove row from table
                        btn.closest('tr').remove();
                    } else {
                        throw new Error(result.message || 'Delete failed');
                    }
                } catch (error) {
                    alert('✗ Delete Failed: ' + error.message);
                    console.error('Delete error:', error);
                }
            }
        });
    });
</script>
@endsection
