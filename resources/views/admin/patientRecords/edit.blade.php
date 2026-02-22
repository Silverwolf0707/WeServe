@extends('layouts.admin')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

<style>
:root {
    --pr-forest:        #064e3b;
    --pr-forest-deep:   #052e22;
    --pr-forest-mid:    #065f46;
    --pr-forest-lite:   #047857;
    --pr-lime:          #74ff70;
    --pr-lime-dim:      #52e84e;
    --pr-lime-ghost:    rgba(116,255,112,.10);
    --pr-lime-border:   rgba(116,255,112,.30);
    --pr-surface:       #ffffff;
    --pr-surface2:      #f0fdf4;
    --pr-muted:         #ecfdf5;
    --pr-border:        #d1fae5;
    --pr-border-dark:   #a7f3d0;
    --pr-text:          #052e22;
    --pr-sub:           #3d7a62;
    --pr-warn:          #f59e0b;
    --pr-danger:        #ef4444;
    --pr-radius:        12px;
    --pr-radius-sm:     7px;
    --pr-shadow:        0 2px 8px rgba(6,78,59,.08), 0 8px 24px rgba(6,78,59,.06);
    --pr-shadow-lg:     0 4px 24px rgba(6,78,59,.16), 0 16px 48px rgba(6,78,59,.10);
    --pr-shadow-lime:   0 2px 12px rgba(116,255,112,.25);
}

.pr-page { font-family: 'DM Sans', sans-serif; color: var(--pr-text); padding: 0 0 2rem; }

/* ── Hero ── */
.pr-hero {
    background: linear-gradient(135deg, #052e22 0%, #064e3b 55%, #065f46 100%);
    border-radius: var(--pr-radius);
    padding: 22px 28px; margin-bottom: 20px;
    position: relative; overflow: hidden;
    box-shadow: var(--pr-shadow-lg);
}
.pr-hero::before {
    content: ''; position: absolute; inset: 0; border-radius: var(--pr-radius);
    background:
        radial-gradient(ellipse 380px 200px at 95% 50%, rgba(116,255,112,.13) 0%, transparent 65%),
        radial-gradient(ellipse 180px 100px at 5% 80%,  rgba(116,255,112,.07) 0%, transparent 70%);
    pointer-events: none; z-index: 0;
}
.pr-hero::after {
    content: ''; position: absolute; top: 0; left: 28px; right: 28px; height: 2px;
    background: linear-gradient(to right, transparent, var(--pr-lime), transparent);
    border-radius: 2px; opacity: .55;
}
.pr-hero-inner { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; position: relative; z-index: 1; }
.pr-hero-left  { display: flex; align-items: center; gap: 16px; }
.pr-hero-icon  {
    width: 46px; height: 46px;
    background: rgba(116,255,112,.12); border: 1px solid rgba(116,255,112,.30);
    border-radius: 11px; display: flex; align-items: center; justify-content: center;
    font-size: 1.15rem; color: var(--pr-lime); backdrop-filter: blur(4px); flex-shrink: 0;
}
.pr-hero-title { font-size: 1.18rem; font-weight: 700; color: #fff; letter-spacing: -.01em; margin: 0 0 3px; line-height: 1.2; }
.pr-hero-sub   { font-size: .78rem; color: rgba(255,255,255,.55); font-weight: 400; }
.pr-hero-badge {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(116,255,112,.14); border: 1px solid rgba(116,255,112,.32);
    border-radius: 20px; padding: 2px 10px; font-size: .72rem; font-weight: 600;
    color: var(--pr-lime); letter-spacing: .03em;
}

.pr-btn-ghost {
    display: inline-flex; align-items: center; gap: 7px;
    background: rgba(255,255,255,.08); color: rgba(255,255,255,.82);
    border: 1px solid rgba(255,255,255,.18); border-radius: 8px;
    padding: 8px 16px; font-size: .82rem; font-weight: 500; font-family: 'DM Sans', sans-serif;
    text-decoration: none; cursor: pointer; transition: all .18s; white-space: nowrap;
}
.pr-btn-ghost:hover { background: rgba(116,255,112,.12); border-color: var(--pr-lime-border); color: var(--pr-lime); }

/* ── Form card ── */
.pr-form-card {
    background: var(--pr-surface); border-radius: var(--pr-radius);
    border: 1px solid var(--pr-border); box-shadow: var(--pr-shadow); overflow: hidden;
}

.pr-section-head {
    display: flex; align-items: center; gap: 10px;
    padding: 16px 24px 14px;
    border-bottom: 1px solid var(--pr-border);
    background: var(--pr-surface2);
}
.pr-section-icon {
    width: 30px; height: 30px; border-radius: 8px; flex-shrink: 0;
    background: var(--pr-lime-ghost); border: 1px solid var(--pr-lime-border);
    display: flex; align-items: center; justify-content: center;
    color: var(--pr-forest); font-size: .78rem;
}
.pr-section-title { font-size: .82rem; font-weight: 700; color: var(--pr-forest); letter-spacing: .04em; text-transform: uppercase; }

.pr-form-body { padding: 22px 24px; }
.pr-form-body + .pr-section-head { border-top: 1px solid var(--pr-border); }

/* Labels & fields */
.pr-label {
    display: block; font-size: .72rem; font-weight: 700;
    letter-spacing: .05em; text-transform: uppercase;
    color: var(--pr-sub); margin-bottom: 5px;
}
.pr-label .req { color: var(--pr-danger); margin-left: 2px; }

.pr-input, .pr-select, .pr-textarea {
    width: 100%; border: 1.5px solid var(--pr-border-dark);
    border-radius: 8px; padding: 9px 13px;
    font-size: .83rem; font-family: 'DM Sans', sans-serif;
    color: var(--pr-text); background: var(--pr-surface);
    transition: border-color .2s, box-shadow .2s;
}
.pr-input:focus, .pr-select:focus, .pr-textarea:focus {
    outline: none; border-color: var(--pr-forest-mid);
    box-shadow: 0 0 0 3px rgba(6,78,59,.11);
}
.pr-input::placeholder, .pr-textarea::placeholder { color: var(--pr-border-dark); }
.pr-input.is-invalid, .pr-select.is-invalid, .pr-textarea.is-invalid {
    border-color: var(--pr-danger) !important;
    box-shadow: 0 0 0 3px rgba(239,68,68,.10) !important;
}
.pr-input[readonly] {
    background: var(--pr-surface2) !important;
    color: var(--pr-sub) !important;
    border-color: var(--pr-border) !important;
    cursor: default;
}
.pr-select {
    appearance: none; -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%233d7a62'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 12px center; padding-right: 32px;
}
.pr-select:focus {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%23064e3b'/%3E%3C/svg%3E");
}
.pr-textarea { resize: vertical; min-height: 80px; }
.pr-error { font-size: .73rem; color: var(--pr-danger); margin-top: 4px; font-weight: 500; }
.pr-field { margin-bottom: 16px; }
.pr-field:last-child { margin-bottom: 0; }
.pr-hint { font-size: .70rem; color: var(--pr-sub); margin-top: 4px; display: flex; align-items: center; gap: 4px; }
.pr-auto-chip {
    display: inline-flex; align-items: center; gap: 4px;
    background: var(--pr-lime-ghost); border: 1px solid var(--pr-lime-border);
    border-radius: 20px; padding: 1px 8px; font-size: .68rem;
    font-weight: 600; color: var(--pr-forest); margin-left: 6px; vertical-align: middle;
}

/* Footer */
.pr-form-footer {
    display: flex; align-items: center; gap: 10px;
    padding: 16px 24px; border-top: 1px solid var(--pr-border);
    background: var(--pr-surface2); flex-wrap: wrap;
}
.pr-btn-save {
    display: inline-flex; align-items: center; gap: 7px;
    background: var(--pr-forest); color: var(--pr-lime);
    border: none; border-radius: 8px; padding: 9px 22px;
    font-size: .84rem; font-weight: 700; font-family: 'DM Sans', sans-serif;
    cursor: pointer; transition: background .18s, transform .15s;
    box-shadow: 0 2px 8px rgba(6,78,59,.25); white-space: nowrap;
}
.pr-btn-save:hover { background: var(--pr-forest-mid); transform: translateY(-1px); }
.pr-btn-save:disabled { opacity: .6; cursor: not-allowed; transform: none; }
.pr-btn-back {
    display: inline-flex; align-items: center; gap: 7px;
    background: var(--pr-muted); color: var(--pr-sub);
    border: 1px solid var(--pr-border-dark); border-radius: 8px;
    padding: 9px 18px; font-size: .84rem; font-weight: 600;
    font-family: 'DM Sans', sans-serif; text-decoration: none;
    cursor: pointer; transition: background .18s; white-space: nowrap;
}
.pr-btn-back:hover { background: var(--pr-border-dark); color: var(--pr-text); }

/* ── Confirmation modal ── */
.pr-modal .modal-content { border: none; border-radius: 14px; overflow: hidden; box-shadow: var(--pr-shadow-lg); font-family: 'DM Sans', sans-serif; }
.pr-modal .modal-header {
    padding: 18px 22px 16px; border-bottom: 1px solid var(--pr-border);
    background: linear-gradient(135deg, #78350f 0%, #92400e 100%);
}
.pr-modal .modal-header .modal-title { color: #fef3c7; font-size: .95rem; font-weight: 700; letter-spacing: -.01em; }
.pr-modal .modal-header .btn-close { filter: invert(1) brightness(1.5); }
.pr-modal .modal-body { padding: 20px 22px; font-size: .85rem; color: var(--pr-text); line-height: 1.6; }
.pr-modal .modal-footer { padding: 14px 22px; border-top: 1px solid var(--pr-border); gap: 8px; background: var(--pr-surface2); }
.pr-modal .modal-footer .btn { border-radius: 8px; font-size: .82rem; font-family: 'DM Sans', sans-serif; font-weight: 600; padding: 7px 18px; border: none; transition: opacity .18s, transform .15s; background-image: none !important; }
.pr-modal .modal-footer .btn:hover { opacity: .88; transform: translateY(-1px); }
.pr-modal .modal-footer .btn-secondary { background: var(--pr-muted) !important; color: var(--pr-sub) !important; border: 1px solid var(--pr-border-dark) !important; }
.pr-modal .modal-footer .btn-primary   { background: var(--pr-forest) !important; color: var(--pr-lime) !important; box-shadow: 0 2px 8px rgba(6,78,59,.25) !important; }

/* Diff view inside modal */
.pr-diff-section-title {
    font-size: .72rem; font-weight: 700; letter-spacing: .06em;
    text-transform: uppercase; color: var(--pr-sub);
    border-bottom: 1px solid var(--pr-border); padding-bottom: 8px; margin-bottom: 12px;
}
.pr-diff-item {
    background: var(--pr-surface2); border-radius: 8px;
    border-left: 3px solid var(--pr-forest); padding: 10px 14px; margin-bottom: 10px;
}
.pr-diff-field { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; color: var(--pr-sub); margin-bottom: 6px; }
.pr-diff-old { font-size: .8rem; color: var(--pr-danger); background: rgba(239,68,68,.08); border-radius: 4px; padding: 2px 6px; text-decoration: line-through; }
.pr-diff-arrow { color: var(--pr-sub); font-size: .7rem; margin: 0 6px; }
.pr-diff-new { font-size: .8rem; color: var(--pr-forest); background: var(--pr-lime-ghost); border-radius: 4px; padding: 2px 6px; font-weight: 600; }
.pr-no-changes {
    display: flex; flex-direction: column; align-items: center; gap: 8px;
    padding: 28px; text-align: center; color: var(--pr-sub);
}
.pr-no-changes i { font-size: 2rem; color: var(--pr-lime-dim); }
.pr-no-changes strong { color: var(--pr-forest); font-size: .9rem; }

.pr-modal-info {
    display: flex; align-items: center; gap: 8px;
    background: var(--pr-lime-ghost); border: 1px solid var(--pr-lime-border);
    border-radius: 8px; padding: 10px 14px; font-size: .8rem;
    color: var(--pr-forest); font-weight: 500; margin-bottom: 18px;
}

@media (max-width: 768px) {
    .pr-hero-inner { flex-direction: column; align-items: flex-start; }
    .pr-hero { padding: 16px 18px; }
    .pr-form-body { padding: 16px; }
    .pr-section-head { padding: 12px 16px; }
    .pr-form-footer { padding: 14px 16px; }
}
</style>

<div class="pr-page">

{{-- ══ HERO ══ --}}
<div class="pr-hero">
    <div class="pr-hero-inner">
        <div class="pr-hero-left">
            <div class="pr-hero-icon"><i class="fas fa-user-edit"></i></div>
            <div>
                <div class="pr-hero-title">Edit Patient Record</div>
                <div class="pr-hero-sub" style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-top:3px;">
                    <span class="pr-hero-badge">
                        <i class="fas fa-hashtag" style="font-size:.6rem;"></i>
                        {{ $patientRecord->control_number }}
                    </span>
                    <span style="color:rgba(255,255,255,.4);font-size:.72rem;">·</span>
                    <span style="color:rgba(255,255,255,.55);font-size:.75rem;">
                        {{ $patientRecord->patient_name }}
                    </span>
                </div>
            </div>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="{{ route('admin.patient-records.show', $patientRecord->id) }}" class="pr-btn-ghost">
                <i class="fas fa-eye" style="font-size:.75rem;"></i> View Record
            </a>
            <a href="{{ route('admin.patient-records.index') }}" class="pr-btn-ghost">
                <i class="fas fa-arrow-left" style="font-size:.75rem;"></i> Back to Records
            </a>
        </div>
    </div>
</div>

{{-- ══ FORM ══ --}}
<form method="POST" action="{{ route('admin.patient-records.update', [$patientRecord->id]) }}"
    enctype="multipart/form-data" id="patientForm">
    @method('PUT')
    @csrf

    <div class="pr-form-card">

        {{-- ── Case Details ── --}}
        <div class="pr-section-head">
            <div class="pr-section-icon"><i class="fas fa-folder-open"></i></div>
            <span class="pr-section-title">Case Details</span>
        </div>

        <div class="pr-form-body">
            <div class="row g-3">

                <div class="col-md-6">
                    <div class="pr-field">
                        <label class="pr-label" for="date_processed">Date Processed <span class="req">*</span></label>
                        <input type="text" name="date_processed" id="date_processed"
                            class="pr-input datetime {{ $errors->has('date_processed') ? 'is-invalid' : '' }}"
                            value="{{ old('date_processed', $patientRecord->date_processed) }}" required>
                        @error('date_processed') <div class="pr-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="pr-field">
                        <label class="pr-label" for="control_number">
                            Control Number <span class="req">*</span>
                            <span class="pr-auto-chip"><i class="fas fa-lock" style="font-size:.6rem;"></i> Locked</span>
                        </label>
                        <input type="text" name="control_number" id="control_number"
                            class="pr-input {{ $errors->has('control_number') ? 'is-invalid' : '' }}"
                            value="{{ old('control_number', $patientRecord->control_number) }}" readonly required>
                        <div class="pr-hint"><i class="fas fa-info-circle"></i> Control number cannot be changed.</div>
                        @error('control_number') <div class="pr-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="pr-field">
                        <label class="pr-label" for="case_type">Case Type <span class="req">*</span></label>
                        <select name="case_type" id="case_type"
                            class="pr-select {{ $errors->has('case_type') ? 'is-invalid' : '' }}" required>
                            <option value="" disabled>Select case type…</option>
                            @foreach(App\Models\PatientRecord::CASE_TYPE_SELECT as $key => $label)
                                <option value="{{ $key }}" {{ old('case_type', $patientRecord->case_type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('case_type') <div class="pr-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="pr-field">
                        <label class="pr-label" for="case_category">Case Category <span class="req">*</span></label>
                        <select name="case_category" id="case_category"
                            class="pr-select {{ $errors->has('case_category') ? 'is-invalid' : '' }}" required>
                            <option value="" disabled>Select category…</option>
                            @foreach(App\Models\PatientRecord::CASE_CATEGORY_SELECT as $key => $label)
                                <option value="{{ $key }}" {{ old('case_category', $patientRecord->case_category) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('case_category') <div class="pr-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="pr-field">
                        <label class="pr-label" for="claimant_name">Claimant Name <span class="req">*</span></label>
                        <input type="text" name="claimant_name" id="claimant_name"
                            class="pr-input {{ $errors->has('claimant_name') ? 'is-invalid' : '' }}"
                            value="{{ old('claimant_name', $patientRecord->claimant_name) }}" required>
                        @error('claimant_name') <div class="pr-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="pr-field">
                        <label class="pr-label" for="diagnosis">Diagnosis</label>
                        <textarea name="diagnosis" id="diagnosis"
                            class="pr-textarea {{ $errors->has('diagnosis') ? 'is-invalid' : '' }}"
                            rows="3" placeholder="Enter diagnosis or medical condition…">{{ old('diagnosis', $patientRecord->diagnosis) }}</textarea>
                        @error('diagnosis') <div class="pr-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
                    </div>
                </div>

            </div>
        </div>

        {{-- ── Patient Information ── --}}
        <div class="pr-section-head">
            <div class="pr-section-icon"><i class="fas fa-user"></i></div>
            <span class="pr-section-title">Patient Information</span>
        </div>

        <div class="pr-form-body">
            <div class="row g-3">

                <div class="col-md-6">
                    <div class="pr-field">
                        <label class="pr-label" for="patient_name">Patient Name <span class="req">*</span></label>
                        <input type="text" name="patient_name" id="patient_name"
                            class="pr-input {{ $errors->has('patient_name') ? 'is-invalid' : '' }}"
                            value="{{ old('patient_name', $patientRecord->patient_name) }}" required>
                        @error('patient_name') <div class="pr-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="pr-field">
                        <label class="pr-label" for="age">Age <span class="req">*</span></label>
                        <input type="number" name="age" id="age"
                            class="pr-input {{ $errors->has('age') ? 'is-invalid' : '' }}"
                            value="{{ old('age', $patientRecord->age) }}" min="0" max="130" step="1" required>
                        @error('age') <div class="pr-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="pr-field">
                        <label class="pr-label" for="contact_number">Contact Number <span class="req">*</span></label>
                        <input type="tel" name="contact_number" id="contact_number"
                            class="pr-input {{ $errors->has('contact_number') ? 'is-invalid' : '' }}"
                            value="{{ old('contact_number', $patientRecord->contact_number) }}"
                            maxlength="11" placeholder="09XXXXXXXXX"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);" required>
                        @error('contact_number') <div class="pr-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="pr-field">
                        <label class="pr-label" for="address">Address <span class="req">*</span></label>
                        <input type="text" name="address" id="address"
                            class="pr-input {{ $errors->has('address') ? 'is-invalid' : '' }}"
                            value="{{ old('address', $patientRecord->address) }}"
                            placeholder="Street, Barangay, City/Municipality" required>
                        @error('address') <div class="pr-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="pr-field">
                        <label class="pr-label" for="case_worker">
                            Case Worker
                            <span class="pr-auto-chip"><i class="fas fa-lock" style="font-size:.6rem;"></i> Locked</span>
                        </label>
                        <input type="text" name="case_worker" id="case_worker"
                            class="pr-input {{ $errors->has('case_worker') ? 'is-invalid' : '' }}"
                            value="{{ old('case_worker', $patientRecord->case_worker) }}" readonly>
                        <div class="pr-hint"><i class="fas fa-info-circle"></i> Assigned on record creation.</div>
                        @error('case_worker') <div class="pr-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
                    </div>
                </div>

            </div>
        </div>

        {{-- ── Footer Actions ── --}}
        <div class="pr-form-footer">
            <button type="button" class="pr-btn-save" id="saveButton">
                <i class="fas fa-save"></i> Save Changes
            </button>
            <a href="{{ route('admin.patient-records.index') }}" class="pr-btn-back">
                <i class="fas fa-times"></i> Cancel
            </a>
            @if($errors->any())
                <span style="font-size:.75rem;color:var(--pr-danger);font-weight:600;margin-left:4px;">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    {{ $errors->count() }} field{{ $errors->count() > 1 ? 's' : '' }} need{{ $errors->count() === 1 ? 's' : '' }} attention
                </span>
            @endif
        </div>

    </div>
</form>

</div>{{-- /pr-page --}}


{{-- ══ CONFIRMATION MODAL ══ --}}
<div class="modal fade pr-modal" id="confirmationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i> Confirm Changes
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="pr-modal-info">
                    <i class="fas fa-info-circle" style="flex-shrink:0;"></i>
                    Only fields with changes are shown below. Please review before confirming.
                </div>
                <div id="changesContainer"></div>
                <div id="noChangesMessage" class="pr-no-changes" style="display:none;">
                    <i class="fas fa-check-circle"></i>
                    <strong>No changes detected</strong>
                    <span style="font-size:.8rem;">All fields are identical to the original record.</span>
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

    // Toast
    var toastEl = document.getElementById('liveToast');
    var timerEl = document.getElementById('toast-timer');
    if (toastEl) {
        new bootstrap.Toast(toastEl, { autohide: true, delay: 5000 }).show();
        let rem = 5;
        const iv = setInterval(() => { rem--; if (timerEl) timerEl.textContent = `Closing in ${rem}s`; if (rem <= 0) clearInterval(iv); }, 1000);
    }

    // Focus first invalid field on load
    const firstInvalid = document.querySelector('.pr-input.is-invalid, .pr-select.is-invalid, .pr-textarea.is-invalid');
    if (firstInvalid) firstInvalid.focus();

    // Original values snapshot (server-rendered)
    const originalValues = {
        date_processed: '{{ addslashes($patientRecord->date_processed) }}',
        control_number: '{{ addslashes($patientRecord->control_number) }}',
        case_type:      '{{ addslashes($patientRecord->case_type) }}',
        claimant_name:  '{{ addslashes($patientRecord->claimant_name) }}',
        case_category:  '{{ addslashes($patientRecord->case_category) }}',
        diagnosis:      '{{ addslashes($patientRecord->diagnosis) }}',
        patient_name:   '{{ addslashes($patientRecord->patient_name) }}',
        age:            '{{ addslashes($patientRecord->age) }}',
        contact_number: '{{ addslashes($patientRecord->contact_number) }}',
        address:        '{{ addslashes($patientRecord->address) }}',
        case_worker:    '{{ addslashes($patientRecord->case_worker) }}'
    };

    const fieldLabels = {
        date_processed: 'Date Processed', control_number: 'Control Number',
        case_type: 'Case Type', claimant_name: 'Claimant Name',
        case_category: 'Case Category', diagnosis: 'Diagnosis',
        patient_name: 'Patient Name', age: 'Age',
        contact_number: 'Contact Number', address: 'Address', case_worker: 'Case Worker'
    };

    const caseFields    = ['date_processed', 'control_number', 'case_type', 'claimant_name', 'case_category', 'diagnosis'];
    const patientFields = ['patient_name', 'age', 'contact_number', 'address', 'case_worker'];

    const saveButton   = document.getElementById('saveButton');
    const confirmBtn   = document.getElementById('confirmSave');
    const patientForm  = document.getElementById('patientForm');
    const modal        = new bootstrap.Modal(document.getElementById('confirmationModal'));
    const changesCont  = document.getElementById('changesContainer');
    const noChangesMsg = document.getElementById('noChangesMessage');

    function escapeHtml(s) {
        return String(s)
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');
    }

    function buildDiffBlock(fields) {
        let html = '';
        fields.forEach(f => {
            const orig = originalValues[f];
            const curr = document.getElementById(f)?.value ?? '';
            if (orig !== curr) {
                html += `
                    <div class="pr-diff-item">
                        <div class="pr-diff-field">${fieldLabels[f]}</div>
                        <span class="pr-diff-old">${escapeHtml(orig) || '<em>empty</em>'}</span>
                        <span class="pr-diff-arrow"><i class="fas fa-arrow-right"></i></span>
                        <span class="pr-diff-new">${escapeHtml(curr) || '<em>empty</em>'}</span>
                    </div>`;
            }
        });
        return html;
    }

    function updateModal() {
        const caseDiff    = buildDiffBlock(caseFields);
        const patientDiff = buildDiffBlock(patientFields);

        if (!caseDiff && !patientDiff) {
            changesCont.style.display  = 'none';
            noChangesMsg.style.display = 'flex';
            return;
        }

        noChangesMsg.style.display = 'none';
        changesCont.style.display  = 'block';

        let html = '<div class="row g-4">';
        if (caseDiff)    html += `<div class="col-md-6"><div class="pr-diff-section-title"><i class="fas fa-folder-open me-1"></i> Case Details</div>${caseDiff}</div>`;
        if (patientDiff) html += `<div class="col-md-6"><div class="pr-diff-section-title"><i class="fas fa-user me-1"></i> Patient Information</div>${patientDiff}</div>`;
        html += '</div>';
        changesCont.innerHTML = html;
    }

    saveButton.addEventListener('click', function () {
        updateModal();
        modal.show();
    });

    confirmBtn.addEventListener('click', function () {
        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving…';
        saveButton.disabled = true;
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving…';
        modal.hide();
        patientForm.submit();
    });

    document.getElementById('confirmationModal').addEventListener('hidden.bs.modal', function () {
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = '<i class="fas fa-check me-1"></i> Yes, Save Changes';
        if (!patientForm.dataset.submitted) {
            saveButton.disabled = false;
            saveButton.innerHTML = '<i class="fas fa-save"></i> Save Changes';
        }
    });

    patientForm.addEventListener('submit', function () {
        this.dataset.submitted = 'true';
    });

});
</script>
@endsection