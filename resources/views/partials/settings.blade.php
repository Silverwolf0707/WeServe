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
                                        <input type="text" class="form-control" value="WeServe: Financial Assistance System"
                                            disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Organization / Office</label>
                                        <input type="text" class="form-control"
                                            value="City Social Welfare and Development Office - San Pedro" disabled>
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
                                <div class="d-flex flex-column gap-3">
                                    <div>
                                        <label class="form-label">Create a new backup of all system data:</label><br>
                                        <button class="btn btn-primary"><i class="fas fa-database me-2"></i>Backup
                                            Now</button>
                                    </div>
                                    <div>
                                        <label class="form-label">Restore from previous backup:</label>
                                        <input type="file" class="form-control mt-2">
                                        <button class="btn btn-warning mt-2"><i class="fas fa-upload me-2"></i>Restore
                                            Backup</button>
                                    </div>
                                </div>
                            </div>

                            <!-- DATA CONTROL -->
                            <div class="tab-pane fade" id="datacontrol" role="tabpanel" aria-labelledby="datacontrol-tab">
                                <h5 class="fw-bold">Data Control</h5>
                                <hr>
                                <p class="text-danger">
                                    ⚠️ Warning: This will permanently delete all database records. Proceed with extreme
                                    caution.
                                </p>
                                <form method="POST" action="{{ route('admin.settings.deleteAll') }}"
                                    onsubmit="return confirm('Are you absolutely sure? This will permanently delete ALL data!');">
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
    document.getElementById('editSystemBtn').addEventListener('click', function () {
        const form = document.getElementById('systemInfoForm');
        const inputs = form.querySelectorAll('input, select');
        const saveBtn = document.getElementById('saveSystemBtn');

        inputs.forEach(input => {
            input.disabled = !input.disabled;
        });

        saveBtn.classList.toggle('d-none');
        this.classList.toggle('btn-outline-success');
        this.classList.toggle('btn-secondary');
        this.innerHTML = form.querySelector('input').disabled 
            ? '<i class="fas fa-pen"></i> Edit'
            : '<i class="fas fa-times"></i> Cancel';
    });

    // ===== Global Theme Persistence (Dark / Light) =====
    document.addEventListener('DOMContentLoaded', function () {
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

        // Optional: feedback toast (requires SweetAlert2)
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Theme Updated',
                text: selectedTheme === 'dark' 
                    ? 'Dark Mode has been activated across the system.' 
                    : 'Light Mode has been activated across the system.',
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
</script>
@endsection