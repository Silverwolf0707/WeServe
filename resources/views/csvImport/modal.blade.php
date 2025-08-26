<div class="modal fade" id="csvImportModal" tabindex="-1" role="dialog" aria-labelledby="csvImportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="csvImportModalLabel">
                    <i class="fas fa-file-csv me-2"></i> {{ trans('global.app_csvImport') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form method="POST" action="{{ route($route, ['model' => $model]) }}" enctype="multipart/form-data">
                    @csrf
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

                        <input 
                            type="file" 
                            id="csv_file" 
                            name="csv_file" 
                            class="d-none @error('csv_file') is-invalid @enderror" 
                            accept=".csv" 
                            required 
                            onchange="document.getElementById('file-chosen').textContent = this.files[0]?.name || 'No file chosen';"
                        >

                        @error('csv_file')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-between">
            
                         <a class="btn btn-secondary" href="{{ route('admin.patient-records.index') }}">
                            <i class="fas fa-arrow-left me-1"></i> Back to List
                        </a>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-file-import me-1"></i> {{ trans('global.app_parse_csv') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>