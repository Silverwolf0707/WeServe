@extends('layouts.admin')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="m-0"><i class="fas fa-plus-circle me-2"></i> Create Patient Record</h5>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.patient-records.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- Case Details --}}
                <h6 class="text-primary mb-3">Case Details</h6>
                <div class="row">
                    <div class="col-md-6">
                        {{-- Date Processed --}}
                        <div class="mb-3">
                            <label for="date_processed" class="form-label">Date Processed <span class="text-danger">*</span></label>
                            <input type="text" name="date_processed" id="date_processed"
                                class="form-control datetime {{ $errors->has('date_processed') ? 'is-invalid' : '' }}"
                                value="{{ old('date_processed', $dateProcessed) }}" required>
                            @error('date_processed') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Control Number --}}
                        <div class="mb-3">
                            <label for="control_number" class="form-label">Control Number <span class="text-danger">*</span></label>
                            <input type="text" name="control_number" id="control_number"
                                class="form-control {{ $errors->has('control_number') ? 'is-invalid' : '' }}"
                                value="{{ old('control_number', $controlNumber) }}" readonly required>
                            @error('control_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Case Type --}}
                        <div class="mb-3">
                            <label for="case_type" class="form-label">Case Type <span class="text-danger">*</span></label>
                            <select name="case_type" id="case_type"
                                class="form-control {{ $errors->has('case_type') ? 'is-invalid' : '' }}" required>
                                <option value disabled {{ old('case_type', null) === null ? 'selected' : '' }}>Please select</option>
                                @foreach(App\Models\PatientRecord::CASE_TYPE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('case_type') === (string) $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('case_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Claimant Name --}}
                        <div class="mb-3">
                            <label for="claimant_name" class="form-label">Claimant Name <span class="text-danger">*</span></label>
                            <input type="text" name="claimant_name" id="claimant_name"
                                class="form-control {{ $errors->has('claimant_name') ? 'is-invalid' : '' }}"
                                value="{{ old('claimant_name') }}" placeholder="Enter claimant name" required>
                            @error('claimant_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        {{-- Case Category --}}
                        <div class="mb-3">
                            <label for="case_category" class="form-label">Case Category <span class="text-danger">*</span></label>
                            <select name="case_category" id="case_category"
                                class="form-control {{ $errors->has('case_category') ? 'is-invalid' : '' }}" required>
                                <option value disabled {{ old('case_category', null) === null ? 'selected' : '' }}>Please select</option>
                                @foreach(App\Models\PatientRecord::CASE_CATEGORY_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('case_category') === (string) $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('case_category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Diagnosis --}}
                        <div class="mb-3">
                            <label for="diagnosis" class="form-label">Diagnosis <span class="text-danger">*</span></label>
                            <textarea name="diagnosis" id="diagnosis"
                                class="form-control {{ $errors->has('diagnosis') ? 'is-invalid' : '' }}" rows="2"
                                style="resize: none;" placeholder="Enter diagnosis..." required>{{ old('diagnosis') }}</textarea>
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
                            value="{{ old('patient_name') }}" required>
                        @error('patient_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="age" class="form-label">Age <span class="text-danger">*</span></label>
                        <input type="number" name="age" id="age"
                            class="form-control {{ $errors->has('age') ? 'is-invalid' : '' }}"
                            value="{{ old('age') }}" step="1" required>
                        @error('age') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="contact_number" class="form-label">Contact Number <span class="text-danger">*</span></label>
                        <input type="text" name="contact_number" id="contact_number"
                            class="form-control {{ $errors->has('contact_number') ? 'is-invalid' : '' }}"
                            value="{{ old('contact_number') }}" required>
                        @error('contact_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-8 mb-3">
                        <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                        <input type="text" name="address" id="address"
                            class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}"
                            value="{{ old('address') }}" required>
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="case_worker" class="form-label">Case Worker <span class="text-danger">*</span></label>
                        <input type="text" name="case_worker" id="case_worker"
                            class="form-control {{ $errors->has('case_worker') ? 'is-invalid' : '' }}"
                            value="{{ old('case_worker', auth()->user()->name) }}" readonly>
                        @error('case_worker') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Save
                    </button>
                    <a href="{{ route('admin.patient-records.index') }}" class="btn btn-secondary ms-2">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </form>
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
    });
</script>
@endsection
