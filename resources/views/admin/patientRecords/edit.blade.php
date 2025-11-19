@extends('layouts.admin')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="m-0"><i class="fas fa-edit me-2"></i> Edit Patient Record</h5>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.patient-records.update', [$patientRecord->id]) }}"
                enctype="multipart/form-data" id="patientForm">
                @method('PUT')
                @csrf

                {{-- Case Details --}}
                <h6 class="text-primary mb-3">Case Details</h6>
                <div class="row">
                    {{-- Left Column: Date Processed, Control Number, Case Type --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="date_processed" class="form-label">Date Processed <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="date_processed" id="date_processed"
                                class="form-control datetime {{ $errors->has('date_processed') ? 'is-invalid' : '' }}"
                                value="{{ old('date_processed', $patientRecord->date_processed) }}" required>
                            @error('date_processed') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="control_number" class="form-label">Control Number <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="control_number" id="control_number"
                                class="form-control {{ $errors->has('control_number') ? 'is-invalid' : '' }}"
                                value="{{ old('control_number', $patientRecord->control_number) }}" readonly required>
                            @error('control_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                         <div class="mb-3">
                            <label for="case_type" class="form-label">Case Type<span
                                    class="text-danger">*</span></label>
                            <select name="case_type" id="case_type"
                                class="form-control {{ $errors->has('case_type') ? 'is-invalid' : '' }}" required>
                                <option value disabled {{ old('case_type', null) === null ? 'selected' : '' }}>Please
                                    select</option>
                                @foreach(App\Models\PatientRecord::CASE_TYPE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('case_type', $patientRecord->case_type) === (string) $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('case_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="claimant_name" class="form-label">Claimant Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="claimant_name" id="claimant_name"
                                class="form-control {{ $errors->has('claimant_name') ? 'is-invalid' : '' }}"
                                value="{{ old('claimant_name', $patientRecord->claimant_name) }}" required>
                            @error('claimant_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Right Column: Case Category, Diagnosis --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="case_category" class="form-label">Case Category <span
                                    class="text-danger">*</span></label>
                            <select name="case_category" id="case_category"
                                class="form-control {{ $errors->has('case_category') ? 'is-invalid' : '' }}" required>
                                <option value disabled {{ old('case_category', null) === null ? 'selected' : '' }}>Please
                                    select</option>
                                @foreach(App\Models\PatientRecord::CASE_CATEGORY_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('case_category', $patientRecord->case_category) === (string) $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('case_category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="diagnosis" class="form-label">Diagnosis <span class="text-danger">*</span></label>
                            <textarea name="diagnosis" id="diagnosis"
                                class="form-control {{ $errors->has('diagnosis') ? 'is-invalid' : '' }}" rows="2"
                                style="resize: none;" required>{{ old('diagnosis', $patientRecord->diagnosis) }}</textarea>
                            @error('diagnosis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                {{-- Patient Information --}}
                <h6 class="text-primary mb-3">Patient Information</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="patient_name" class="form-label">Patient Name <span class="text-danger">*</span></label>
                        <input type="text" name="patient_name" id="patient_name"
                            class="form-control {{ $errors->has('patient_name') ? 'is-invalid' : '' }}"
                            value="{{ old('patient_name', $patientRecord->patient_name) }}" required>
                        @error('patient_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="age" class="form-label">Age <span class="text-danger">*</span></label>
                        <input type="number" name="age" id="age"
                            class="form-control {{ $errors->has('age') ? 'is-invalid' : '' }}"
                            value="{{ old('age', $patientRecord->age) }}" step="1" required>
                        @error('age') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="contact_number" class="form-label">Contact Number <span
                                class="text-danger">*</span></label>
                        <input type="text" name="contact_number" id="contact_number"
                            class="form-control {{ $errors->has('contact_number') ? 'is-invalid' : '' }}"
                            value="{{ old('contact_number', $patientRecord->contact_number) }}" required>
                        @error('contact_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-8 mb-3">
                        <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                        <input type="text" name="address" id="address"
                            class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}"
                            value="{{ old('address', $patientRecord->address) }}" required>
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="case_worker" class="form-label">Case Worker <span class="text-danger">*</span></label>
                        <input type="text" name="case_worker" id="case_worker"
                            class="form-control {{ $errors->has('case_worker') ? 'is-invalid' : '' }}"
                            value="{{ old('case_worker', $patientRecord->case_worker) }}" readonly>
                        @error('case_worker') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="button" class="btn btn-success" id="saveButton">
                        <i class="fas fa-save me-1"></i> Save
                    </button>
                    <a href="{{ route('admin.patient-records.index') }}" class="btn btn-secondary ms-2">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="confirmationModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Confirm Changes
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="fw-bold mb-4">Are you sure you want to modify this patient record? Please review the changes below:</p>
                    
                    <div class="alert alert-info d-flex align-items-center mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <span>Only fields with changes are shown below</span>
                    </div>
                    
                    <div id="changesContainer">
                        <!-- Changes will be dynamically inserted here -->
                    </div>

                    <div id="noChangesMessage" class="alert alert-success text-center" style="display: none;">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>No changes detected.</strong> All fields remain the same.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="confirmSave">
                        <i class="fas fa-check me-1"></i> Yes, Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var toastEl = document.getElementById('liveToast');
            var timerEl = document.getElementById('toast-timer');

            if (toastEl) {
                // Show toast with 5s delay
                var toast = new bootstrap.Toast(toastEl, {
                    autohide: true,
                    delay: 5000
                });
                toast.show();

                // Countdown timer for display
                let remaining = 5;
                const interval = setInterval(() => {
                    remaining--;
                    if (timerEl) {
                        timerEl.textContent = `Closing in ${remaining}s`;
                    }
                    if (remaining <= 0) {
                        clearInterval(interval);
                    }
                }, 1000);
            }

            // Confirmation modal functionality
            const saveButton = document.getElementById('saveButton');
            const confirmSaveButton = document.getElementById('confirmSave');
            const patientForm = document.getElementById('patientForm');
            const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            const changesContainer = document.getElementById('changesContainer');
            const noChangesMessage = document.getElementById('noChangesMessage');

            // Store original values
            const originalValues = {
                date_processed: '{{ $patientRecord->date_processed }}',
                control_number: '{{ $patientRecord->control_number }}',
                case_type: '{{ $patientRecord->case_type }}',
                claimant_name: '{{ $patientRecord->claimant_name }}',
                case_category: '{{ $patientRecord->case_category }}',
                diagnosis: '{{ $patientRecord->diagnosis }}',
                patient_name: '{{ $patientRecord->patient_name }}',
                age: '{{ $patientRecord->age }}',
                contact_number: '{{ $patientRecord->contact_number }}',
                address: '{{ $patientRecord->address }}',
                case_worker: '{{ $patientRecord->case_worker }}'
            };

            saveButton.addEventListener('click', function() {
                // Update modal with comparison
                updateModalComparison();
                
                // Show confirmation modal
                confirmationModal.show();
            });

            confirmSaveButton.addEventListener('click', function() {
                // Disable the confirm button and show loading
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';
                
                // Disable the original save button and show loading
                saveButton.disabled = true;
                saveButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';
                
                // Hide modal and submit form
                confirmationModal.hide();
                
                // Submit the form
                patientForm.submit();
            });

            function updateModalComparison() {
                const fields = [
                    'date_processed', 'control_number', 'case_type', 'claimant_name', 
                    'case_category', 'diagnosis', 'patient_name', 'age', 
                    'contact_number', 'address', 'case_worker'
                ];

                let hasChanges = false;
                let changesHTML = '';

                // Group fields by section
                const caseDetailsFields = ['date_processed', 'control_number', 'case_type', 'claimant_name', 'case_category', 'diagnosis'];
                const patientInfoFields = ['patient_name', 'age', 'contact_number', 'address', 'case_worker'];

                // Check for changes in Case Details
                let caseDetailsChanges = '';
                caseDetailsFields.forEach(field => {
                    const originalValue = originalValues[field];
                    const newValue = document.getElementById(field).value;
                    
                    if (originalValue !== newValue) {
                        hasChanges = true;
                        caseDetailsChanges += `
                            <div class="mb-3 field-comparison changed">
                                <strong>${formatFieldName(field)}:</strong><br>
                                <span class="text-danger"><del>${escapeHtml(originalValue)}</del></span> 
                                <i class="fas fa-arrow-right mx-2 text-muted"></i>
                                <span class="text-success"><ins>${escapeHtml(newValue)}</ins></span>
                            </div>
                        `;
                    }
                });

                // Check for changes in Patient Information
                let patientInfoChanges = '';
                patientInfoFields.forEach(field => {
                    const originalValue = originalValues[field];
                    const newValue = document.getElementById(field).value;
                    
                    if (originalValue !== newValue) {
                        hasChanges = true;
                        patientInfoChanges += `
                            <div class="mb-3 field-comparison changed">
                                <strong>${formatFieldName(field)}:</strong><br>
                                <span class="text-danger"><del>${escapeHtml(originalValue)}</del></span> 
                                <i class="fas fa-arrow-right mx-2 text-muted"></i>
                                <span class="text-success"><ins>${escapeHtml(newValue)}</ins></span>
                            </div>
                        `;
                    }
                });

                // Build the final HTML
                if (hasChanges) {
                    changesHTML = `
                        <div class="row">
                            ${caseDetailsChanges ? `
                                <div class="col-md-6">
                                    <h6 class="text-primary border-bottom pb-2">Case Details Changes</h6>
                                    ${caseDetailsChanges}
                                </div>
                            ` : ''}
                            
                            ${patientInfoChanges ? `
                                <div class="col-md-6">
                                    <h6 class="text-primary border-bottom pb-2">Patient Information Changes</h6>
                                    ${patientInfoChanges}
                                </div>
                            ` : ''}
                        </div>
                    `;
                    
                    changesContainer.innerHTML = changesHTML;
                    noChangesMessage.style.display = 'none';
                    changesContainer.style.display = 'block';
                } else {
                    changesContainer.style.display = 'none';
                    noChangesMessage.style.display = 'block';
                }
            }

            function formatFieldName(field) {
                const names = {
                    date_processed: 'Date Processed',
                    control_number: 'Control Number',
                    case_type: 'Case Type',
                    claimant_name: 'Claimant Name',
                    case_category: 'Case Category',
                    diagnosis: 'Diagnosis',
                    patient_name: 'Patient Name',
                    age: 'Age',
                    contact_number: 'Contact Number',
                    address: 'Address',
                    case_worker: 'Case Worker'
                };
                return names[field] || field;
            }

            function escapeHtml(unsafe) {
                return unsafe
                    .toString()
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }

            // Reset button state if modal is closed without confirming
            document.getElementById('confirmationModal').addEventListener('hidden.bs.modal', function() {
                confirmSaveButton.disabled = false;
                confirmSaveButton.innerHTML = '<i class="fas fa-check me-1"></i> Yes, Save Changes';
                
                // Only reset save button if form wasn't submitted
                if (!saveButton.disabled) {
                    saveButton.innerHTML = '<i class="fas fa-save me-1"></i> Save';
                }
            });
        });
    </script>

    <style>
        .field-comparison.changed {
            background-color: #f8f9fa;
            padding: 12px;
            border-radius: 6px;
            border-left: 4px solid #28a745;
            transition: all 0.3s ease;
            margin-bottom: 12px;
        }
        del {
            text-decoration: line-through;
            color: #dc3545;
            background-color: #f8d7da;
            padding: 2px 4px;
            border-radius: 3px;
        }
        ins {
            text-decoration: none;
            color: #155724;
            background-color: #d4edda;
            padding: 2px 4px;
            border-radius: 3px;
            font-weight: 500;
        }
        .field-comparison strong {
            color: #495057;
            margin-bottom: 4px;
            display: block;
        }
        .text-primary {
            color: #2c5aa0 !important;
        }
    </style>
@endsection