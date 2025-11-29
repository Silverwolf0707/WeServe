<div class="modal fade" id="csvImportModal" tabindex="-1" role="dialog" aria-labelledby="csvImportModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="csvImportModalLabel">
                    <i class="fas fa-file-csv me-2"></i> {{ trans('global.app_csvImport') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                {{-- Directly submit to processCsvImport --}}
                <form method="POST" action="{{ route('admin.patient-records.processCsvImport') }}" enctype="multipart/form-data" id="csvImportForm">
                    @csrf
                    <input type="hidden" name="modelName" value="PatientRecord">

                    <div class="mb-3">
                        <label for="csv_file" class="form-label">
                            {{ trans('global.app_csv_file_to_import') }}
                        </label>

                        <div class="d-flex align-items-center gap-2">
                            <label for="csv_file" class="btn btn-dark mb-0">
                                <i class="fas fa-upload me-1"></i> Choose CSV
                            </label>
                            <span id="file-chosen" class="text-muted">No file chosen</span>
                        </div>

                        <input type="file" id="csv_file" name="csv_file"
                            class="d-none @error('csv_file') is-invalid @enderror" accept=".csv" required
                            onchange="document.getElementById('file-chosen').textContent = this.files[0]?.name || 'No file chosen';">

                        @error('csv_file')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="has_disbursed_date"
                            name="has_disbursed_date" value="1">
                        <label class="form-check-label" for="has_disbursed_date">
                            CSV has <strong>Disbursed Date</strong> column
                        </label>
                    </div>

                    {{-- CSV Templates --}}
                    <div class="mb-3">
                        <label class="form-label">Download CSV Templates</label>
                        <div class="d-flex flex-column gap-2">
                            <a href="{{ route('admin.csv.template', ['type' => 'basic']) }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-download me-1"></i> Template (Without Disbursed Date)
                            </a>
                            <a href="{{ route('admin.csv.template', ['type' => 'disbursed']) }}" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-download me-1"></i> Template (With Disbursed Date)
                            </a>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a class="btn btn-secondary" href="{{ route('admin.patient-records.index') }}">
                            <i class="fas fa-arrow-left me-1"></i> Back to List
                        </a>

                        <button type="submit" class="btn btn-primary" id="importButton">
                            <i class="fas fa-file-import me-1"></i> 
                            <span id="importButtonText">Import CSV</span>
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
    let isSubmitting = false;

    csvImportForm.addEventListener('submit', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return;
        }

        // Basic validation - check if file is selected
        const fileInput = document.getElementById('csv_file');
        if (!fileInput.files.length) {
            e.preventDefault();
            alert('Please select a CSV file to import.');
            return;
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
        importButtonText.textContent = 'Import CSV';
        importButtonSpinner.classList.add('d-none');
        importButton.classList.remove('btn-secondary');
        importButton.classList.add('btn-primary');
    }
});
</script>