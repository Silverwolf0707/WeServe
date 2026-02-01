<div class="modal fade" id="csvImportModal" tabindex="-1" role="dialog" aria-labelledby="csvImportModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="csvImportModalLabel">
                    <i class="fas fa-file-import me-2"></i> {{ trans('global.app_csvImport') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form method="POST" action="{{ route('admin.patient-records.processCsvImport') }}" enctype="multipart/form-data" id="csvImportForm">
                    @csrf
                    <input type="hidden" name="modelName" value="PatientRecord">

                    <div class="mb-3">
                        <label for="csv_file" class="form-label">
                            {{ trans('global.app_csv_file_to_import') }}
                        </label>

                        <div class="d-flex align-items-center gap-2">
                            <label for="csv_file" class="btn btn-dark mb-0">
                                <i class="fas fa-upload me-1"></i> Choose File
                            </label>
                            <span id="file-chosen" class="text-muted">No file chosen</span>
                        </div>

                        <input type="file" id="csv_file" name="csv_file"
                            class="d-none @error('csv_file') is-invalid @enderror" 
                            accept=".csv,.xls,.xlsx,.ods"
                            required
                            onchange="document.getElementById('file-chosen').textContent = this.files[0]?.name || 'No file chosen';">

                        @error('csv_file')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        
                        <small class="form-text text-muted">
                            Supported formats: CSV, Excel (.xls, .xlsx), OpenDocument Spreadsheet (.ods)
                        </small>
                    </div>

                    {{-- File Type Selection --}}
                    <div class="mb-3">
                        <label class="form-label">File Type</label>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="has_disbursed_date"
                                name="has_disbursed_date" value="1" checked
                                onchange="toggleDisbursedOptions()">
                            <label class="form-check-label" for="has_disbursed_date">
                                File contains <strong>Disbursement Columns</strong> (Disbursed Date, Amount, Allocation Date)
                            </label>
                        </div>
                    </div>

                    {{-- Force Disburse Option --}}
                    <div class="mb-3" id="forceDisburseContainer" style="display: none;">
                        <div class="alert alert-warning border-warning">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="force_disburse"
                                    name="force_disburse" value="1">
                                <label class="form-check-label" for="force_disburse">
                                    <strong>Auto-Disburse All Records</strong>
                                </label>
                                <small class="form-text text-muted d-block">
                                    <i class="fas fa-info-circle"></i> 
                                    When enabled, all imported records will be marked as disbursed:
                                    <ul class="mb-0 mt-1 small">
                                        <li>Records without disbursed date will use the processed date or current date</li>
                                        <li>Records without amount will be set to 0.00</li>
                                        <li>Budget allocations will be created for all records</li>
                                        <li>Status will be set to "Disbursed" instead of "Processing"</li>
                                    </ul>
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- File Templates --}}
                    <div class="mb-3">
                        <label class="form-label">Download Templates</label>
                        <div class="d-flex flex-column gap-2">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.csv.template', ['type' => 'basic']) }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-file-csv me-1"></i> CSV Template (Basic)
                                </a>
                                <a href="{{ route('admin.csv.template', ['type' => 'disbursed']) }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-file-csv me-1"></i> CSV Template (With Disbursed)
                                </a>
                            </div>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-success btn-sm" onclick="downloadExcelTemplate('basic')">
                                    <i class="fas fa-file-excel me-1"></i> Excel Template (Basic)
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm" onclick="downloadExcelTemplate('disbursed')">
                                    <i class="fas fa-file-excel me-1"></i> Excel Template (With Disbursed)
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <small>
                            <i class="fas fa-info-circle me-1"></i>
                            <strong>Note:</strong> The first row must contain column headers. 
                            Excel dates are automatically converted. Duplicate control numbers are skipped.
                        </small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a class="btn btn-secondary" href="{{ route('admin.patient-records.index') }}">
                            <i class="fas fa-arrow-left me-1"></i> Back to List
                        </a>

                        <button type="submit" class="btn btn-primary" id="importButton">
                            <i class="fas fa-file-import me-1"></i> 
                            <span id="importButtonText">Import File</span>
                            <div id="importButtonSpinner" class="spinner-border spinner-border-sm d-none" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csvImportForm = document.getElementById('csvImportForm');
    const importButton = document.getElementById('importButton');
    const importButtonText = document.getElementById('importButtonText');
    const importButtonSpinner = document.getElementById('importButtonSpinner');
    const hasDisbursedCheckbox = document.getElementById('has_disbursed_date');
    const forceDisburseContainer = document.getElementById('forceDisburseContainer');
    const forceDisburseCheckbox = document.getElementById('force_disburse');
    
    let isSubmitting = false;

    // Toggle force disburse option based on file type selection
    function toggleDisbursedOptions() {
        if (!hasDisbursedCheckbox.checked) {
            forceDisburseContainer.style.display = 'block';
        } else {
            forceDisburseContainer.style.display = 'none';
            forceDisburseCheckbox.checked = false;
        }
    }

    // Initial state
    toggleDisbursedOptions();

    // Event listener for file type change
    hasDisbursedCheckbox.addEventListener('change', toggleDisbursedOptions);

    csvImportForm.addEventListener('submit', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return;
        }

        // Basic validation - check if file is selected
        const fileInput = document.getElementById('csv_file');
        if (!fileInput.files.length) {
            e.preventDefault();
            alert('Please select a file to import.');
            return;
        }

        // Validate file extension
        const fileName = fileInput.files[0].name;
        const extension = fileName.split('.').pop().toLowerCase();
        const allowedExtensions = ['csv', 'xls', 'xlsx', 'ods'];
        
        if (!allowedExtensions.includes(extension)) {
            e.preventDefault();
            alert('Please select a valid file type (CSV, Excel, or OpenDocument Spreadsheet).');
            return;
        }

        // Show confirmation if force disburse is enabled
        if (forceDisburseCheckbox && forceDisburseCheckbox.checked) {
            if (!confirm('You have selected "Auto-Disburse All Records". This will:\n1. Mark all records as disbursed\n2. Create budget allocations (amount 0.00 if not specified)\n3. Set disbursed date to processed date or current date\n\nContinue?')) {
                e.preventDefault();
                return;
            }
        }

        // Show loading state
        isSubmitting = true;
        importButton.disabled = true;
        importButtonText.textContent = 'Importing...';
        importButtonSpinner.classList.remove('d-none');
        
        // Change button style to indicate loading
        importButton.classList.remove('btn-primary');
        importButton.classList.add('btn-secondary');
    });

    // Re-enable button if modal is closed during submission
    const modal = document.getElementById('csvImportModal');
    modal.addEventListener('hidden.bs.modal', function() {
        resetImportButton();
    });

    function resetImportButton() {
        isSubmitting = false;
        importButton.disabled = false;
        importButtonText.textContent = 'Import File';
        importButtonSpinner.classList.add('d-none');
        importButton.classList.remove('btn-secondary');
        importButton.classList.add('btn-primary');
    }
});

function downloadExcelTemplate(type) {
    // Redirect to Excel template route with the type parameter
    window.location.href = "{{ route('admin.excel.template', ['type' => '__TYPE__']) }}".replace('__TYPE__', type);
}
</script>