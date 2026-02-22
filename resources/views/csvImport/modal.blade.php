<div class="modal fade pr-modal" id="csvImportModal" tabindex="-1" role="dialog" aria-labelledby="csvImportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">

            {{-- Header --}}
            <div class="modal-header" style="background: linear-gradient(135deg, #1a2235 0%, #1c2640 100%); border-bottom: 1px solid rgba(255,255,255,.10);">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:34px;height:34px;background:rgba(59,110,248,.25);border:1px solid rgba(59,110,248,.4);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#93b4fd;font-size:.85rem;">
                        <i class="fas fa-file-import"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0" id="csvImportModalLabel" style="color:#fff;font-family:'DM Sans',sans-serif;font-size:.95rem;font-weight:700;letter-spacing:-.01em;">
                            {{ trans('global.app_csvImport') }}
                        </h5>
                        <div style="font-size:.7rem;color:rgba(255,255,255,.45);font-family:'DM Sans',sans-serif;margin-top:1px;">CSV · Excel · ODS supported</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body" style="padding:20px 22px;font-family:'DM Sans',sans-serif;">
                <form method="POST" action="{{ route('admin.patient-records.processCsvImport') }}" enctype="multipart/form-data" id="csvImportForm">
                    @csrf
                    <input type="hidden" name="modelName" value="PatientRecord">

                    {{-- File Upload --}}
                    <div style="margin-bottom:16px;">
                        <label style="font-size:.72rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#6b7897;display:block;margin-bottom:6px;">
                            <i class="fas fa-upload me-1"></i> File to Import
                        </label>

                        <label for="csv_file" id="fileDropZone" style="display:flex;align-items:center;gap:12px;padding:14px 16px;border:1.5px dashed #e4e8f0;border-radius:10px;cursor:pointer;transition:border-color .2s,background .2s;background:#f9fafc;">
                            <div style="width:36px;height:36px;background:#eff4ff;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fas fa-file-spreadsheet" style="color:#3b6ef8;font-size:.85rem;"></i>
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div id="file-chosen" style="font-size:.82rem;font-weight:500;color:#6b7897;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    Click to choose a file…
                                </div>
                                <div style="font-size:.7rem;color:#b8c0d4;margin-top:2px;">CSV, Excel (.xls .xlsx), OpenDocument (.ods)</div>
                            </div>
                            <span style="font-size:.73rem;font-weight:600;color:#3b6ef8;background:#eff4ff;border:1px solid #c7d8fd;border-radius:6px;padding:3px 10px;white-space:nowrap;">Browse</span>
                        </label>

                        <input type="file" id="csv_file" name="csv_file"
                            class="d-none @error('csv_file') is-invalid @enderror"
                            accept=".csv,.xls,.xlsx,.ods"
                            required
                            onchange="
                                const name = this.files[0]?.name || 'Click to choose a file…';
                                document.getElementById('file-chosen').textContent = name;
                                const zone = document.getElementById('fileDropZone');
                                zone.style.borderColor = this.files[0] ? '#3b6ef8' : '#e4e8f0';
                                zone.style.background  = this.files[0] ? '#f0f4ff' : '#f9fafc';
                            ">

                        @error('csv_file')
                            <div style="font-size:.76rem;color:#ef4444;margin-top:5px;"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Divider --}}
                    <div style="height:1px;background:#f0f2f7;margin:0 0 14px;"></div>

                    {{-- File Type Toggle --}}
                    <div style="margin-bottom:12px;">
                        <label style="font-size:.72rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#6b7897;display:block;margin-bottom:8px;">
                            <i class="fas fa-table me-1"></i> File Type
                        </label>
                        <label style="display:flex;align-items:flex-start;gap:10px;padding:11px 14px;border:1.5px solid #e4e8f0;border-radius:9px;cursor:pointer;transition:border-color .2s,background .2s;" id="disbursedLabel">
                            <div style="position:relative;display:inline-flex;align-items:center;margin-top:1px;">
                                <input type="checkbox" id="has_disbursed_date" name="has_disbursed_date" value="1" checked
                                    style="width:15px;height:15px;accent-color:#3b6ef8;cursor:pointer;"
                                    onchange="toggleDisbursedOptions()">
                            </div>
                            <div>
                                <div style="font-size:.82rem;font-weight:600;color:#1a2035;">File contains Disbursement Columns</div>
                                <div style="font-size:.72rem;color:#6b7897;margin-top:2px;">Disbursed Date · Amount · Allocation Date</div>
                            </div>
                        </label>
                    </div>

                    {{-- Force Disburse --}}
                    <div id="forceDisburseContainer" style="display:none;margin-bottom:12px;">
                        <label style="display:flex;align-items:flex-start;gap:10px;padding:11px 14px;border:1.5px solid #f5a623;border-radius:9px;cursor:pointer;background:#fffbf0;" id="forceDisburseLabel">
                            <div style="position:relative;display:inline-flex;align-items:center;margin-top:1px;">
                                <input type="checkbox" id="force_disburse" name="force_disburse" value="1"
                                    style="width:15px;height:15px;accent-color:#f5a623;cursor:pointer;">
                            </div>
                            <div>
                                <div style="font-size:.82rem;font-weight:700;color:#92400e;"><i class="fas fa-bolt me-1" style="color:#f5a623;"></i>Auto-Disburse All Records</div>
                                <div style="font-size:.71rem;color:#b45309;margin-top:3px;line-height:1.55;">
                                    All imported records will be marked as <strong>Disbursed</strong>. Missing dates use processed date; missing amounts default to <code>0.00</code>.
                                </div>
                            </div>
                        </label>
                    </div>

                    {{-- Divider --}}
                    <div style="height:1px;background:#f0f2f7;margin:0 0 14px;"></div>

                    {{-- Templates --}}
                    <div style="margin-bottom:16px;">
                        <label style="font-size:.72rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#6b7897;display:block;margin-bottom:8px;">
                            <i class="fas fa-download me-1"></i> Download Templates
                        </label>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">
                            <a href="{{ route('admin.csv.template', ['type' => 'basic']) }}"
                               style="display:flex;align-items:center;gap:7px;padding:8px 12px;border:1.5px solid #e4e8f0;border-radius:8px;font-size:.76rem;font-weight:600;color:#6b7897;text-decoration:none;transition:all .18s;font-family:'DM Sans',sans-serif;"
                               onmouseover="this.style.borderColor='#3b6ef8';this.style.color='#3b6ef8';this.style.background='#f0f4ff'"
                               onmouseout="this.style.borderColor='#e4e8f0';this.style.color='#6b7897';this.style.background='transparent'">
                                <i class="fas fa-file-csv" style="color:#22c77a;font-size:.8rem;"></i> CSV Basic
                            </a>
                            <a href="{{ route('admin.csv.template', ['type' => 'disbursed']) }}"
                               style="display:flex;align-items:center;gap:7px;padding:8px 12px;border:1.5px solid #e4e8f0;border-radius:8px;font-size:.76rem;font-weight:600;color:#6b7897;text-decoration:none;transition:all .18s;font-family:'DM Sans',sans-serif;"
                               onmouseover="this.style.borderColor='#3b6ef8';this.style.color='#3b6ef8';this.style.background='#f0f4ff'"
                               onmouseout="this.style.borderColor='#e4e8f0';this.style.color='#6b7897';this.style.background='transparent'">
                                <i class="fas fa-file-csv" style="color:#22c77a;font-size:.8rem;"></i> CSV Disbursed
                            </a>
                            <button type="button" onclick="downloadExcelTemplate('basic')"
                               style="display:flex;align-items:center;gap:7px;padding:8px 12px;border:1.5px solid #e4e8f0;border-radius:8px;font-size:.76rem;font-weight:600;color:#6b7897;background:transparent;cursor:pointer;transition:all .18s;font-family:'DM Sans',sans-serif;"
                               onmouseover="this.style.borderColor='#22c77a';this.style.color='#22c77a';this.style.background='#f0fdf4'"
                               onmouseout="this.style.borderColor='#e4e8f0';this.style.color='#6b7897';this.style.background='transparent'">
                                <i class="fas fa-file-excel" style="color:#22c77a;font-size:.8rem;"></i> Excel Basic
                            </button>
                            <button type="button" onclick="downloadExcelTemplate('disbursed')"
                               style="display:flex;align-items:center;gap:7px;padding:8px 12px;border:1.5px solid #e4e8f0;border-radius:8px;font-size:.76rem;font-weight:600;color:#6b7897;background:transparent;cursor:pointer;transition:all .18s;font-family:'DM Sans',sans-serif;"
                               onmouseover="this.style.borderColor='#22c77a';this.style.color='#22c77a';this.style.background='#f0fdf4'"
                               onmouseout="this.style.borderColor='#e4e8f0';this.style.color='#6b7897';this.style.background='transparent'">
                                <i class="fas fa-file-excel" style="color:#22c77a;font-size:.8rem;"></i> Excel Disbursed
                            </button>
                        </div>
                    </div>

                    {{-- Info note --}}
                    <div style="display:flex;align-items:flex-start;gap:9px;padding:10px 13px;background:#f0f4ff;border:1px solid #c7d8fd;border-radius:8px;margin-bottom:18px;">
                        <i class="fas fa-info-circle" style="color:#3b6ef8;font-size:.8rem;margin-top:2px;flex-shrink:0;"></i>
                        <span style="font-size:.74rem;color:#374693;line-height:1.55;">
                            First row must contain column headers. Excel dates are auto-converted. Duplicate control numbers are skipped.
                        </span>
                    </div>

                    {{-- Footer actions --}}
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;">
                        <a href="{{ route('admin.patient-records.index') }}"
                           style="display:inline-flex;align-items:center;gap:6px;background:#f0f2f7;color:#6b7897;border:none;border-radius:8px;padding:8px 16px;font-size:.8rem;font-weight:600;font-family:'DM Sans',sans-serif;text-decoration:none;transition:background .18s;"
                           onmouseover="this.style.background='#e4e8f0'"
                           onmouseout="this.style.background='#f0f2f7'">
                            <i class="fas fa-arrow-left" style="font-size:.72rem;"></i> Back
                        </a>

                        <button type="submit" id="importButton"
                            style="display:inline-flex;align-items:center;gap:7px;background:#3b6ef8;color:#fff;border:none;border-radius:8px;padding:8px 20px;font-size:.8rem;font-weight:700;font-family:'DM Sans',sans-serif;cursor:pointer;transition:background .18s,transform .15s;box-shadow:0 2px 8px rgba(59,110,248,.35);"
                            onmouseover="this.style.background='#2a5be8';this.style.transform='translateY(-1px)'"
                            onmouseout="this.style.background='#3b6ef8';this.style.transform='translateY(0)'">
                            <i class="fas fa-file-import" style="font-size:.75rem;"></i>
                            <span id="importButtonText">Import File</span>
                            <div id="importButtonSpinner" class="spinner-border spinner-border-sm d-none" role="status" style="width:13px;height:13px;border-width:2px;">
                                <span class="visually-hidden">Loading…</span>
                            </div>
                        </button>
                    </div>

                </form>
            </div>{{-- /modal-body --}}

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csvImportForm      = document.getElementById('csvImportForm');
    const importButton       = document.getElementById('importButton');
    const importButtonText   = document.getElementById('importButtonText');
    const importButtonSpinner = document.getElementById('importButtonSpinner');
    const hasDisbursedCheckbox  = document.getElementById('has_disbursed_date');
    const forceDisburseContainer = document.getElementById('forceDisburseContainer');
    const forceDisburseCheckbox  = document.getElementById('force_disburse');
    let isSubmitting = false;

    function toggleDisbursedOptions() {
        if (!hasDisbursedCheckbox.checked) {
            forceDisburseContainer.style.display = 'block';
        } else {
            forceDisburseContainer.style.display = 'none';
            forceDisburseCheckbox.checked = false;
        }
    }
    toggleDisbursedOptions();
    hasDisbursedCheckbox.addEventListener('change', toggleDisbursedOptions);

    csvImportForm.addEventListener('submit', function(e) {
        if (isSubmitting) { e.preventDefault(); return; }

        const fileInput = document.getElementById('csv_file');
        if (!fileInput.files.length) {
            e.preventDefault();
            alert('Please select a file to import.');
            return;
        }

        const ext = fileInput.files[0].name.split('.').pop().toLowerCase();
        if (!['csv','xls','xlsx','ods'].includes(ext)) {
            e.preventDefault();
            alert('Please select a valid file type (CSV, Excel, or OpenDocument Spreadsheet).');
            return;
        }

        if (forceDisburseCheckbox && forceDisburseCheckbox.checked) {
            if (!confirm('You have selected "Auto-Disburse All Records". This will:\n1. Mark all records as disbursed\n2. Create budget allocations (amount 0.00 if not specified)\n3. Set disbursed date to processed date or current date\n\nContinue?')) {
                e.preventDefault();
                return;
            }
        }

        isSubmitting = true;
        importButton.disabled = true;
        importButton.style.background = '#6b7897';
        importButton.style.transform  = 'none';
        importButton.style.boxShadow  = 'none';
        importButtonText.textContent  = 'Importing…';
        importButtonSpinner.classList.remove('d-none');
    });

    document.getElementById('csvImportModal').addEventListener('hidden.bs.modal', function() {
        isSubmitting = false;
        importButton.disabled = false;
        importButton.style.background = '#3b6ef8';
        importButton.style.boxShadow  = '0 2px 8px rgba(59,110,248,.35)';
        importButtonText.textContent  = 'Import File';
        importButtonSpinner.classList.add('d-none');
    });
});

function downloadExcelTemplate(type) {
    window.location.href = "{{ route('admin.excel.template', ['type' => '__TYPE__']) }}".replace('__TYPE__', type);
}
</script>