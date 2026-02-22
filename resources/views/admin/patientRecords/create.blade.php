@extends('layouts.admin')

@section('content')
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --pr-forest: #064e3b;
            --pr-forest-deep: #052e22;
            --pr-forest-mid: #065f46;
            --pr-forest-lite: #047857;
            --pr-lime: #74ff70;
            --pr-lime-dim: #52e84e;
            --pr-lime-ghost: rgba(116, 255, 112, .10);
            --pr-lime-border: rgba(116, 255, 112, .30);
            --pr-surface: #ffffff;
            --pr-surface2: #f0fdf4;
            --pr-muted: #ecfdf5;
            --pr-border: #d1fae5;
            --pr-border-dark: #a7f3d0;
            --pr-text: #052e22;
            --pr-sub: #3d7a62;
            --pr-warn: #f59e0b;
            --pr-danger: #ef4444;
            --pr-radius: 12px;
            --pr-radius-sm: 7px;
            --pr-shadow: 0 2px 8px rgba(6, 78, 59, .08), 0 8px 24px rgba(6, 78, 59, .06);
            --pr-shadow-lg: 0 4px 24px rgba(6, 78, 59, .16), 0 16px 48px rgba(6, 78, 59, .10);
            --pr-shadow-lime: 0 2px 12px rgba(116, 255, 112, .25);
        }

        .pr-page {
            font-family: 'DM Sans', sans-serif;
            color: var(--pr-text);
            padding: 0 0 2rem;
        }

        /* ── Hero ── */
        .pr-hero {
            background: linear-gradient(135deg, #052e22 0%, #064e3b 55%, #065f46 100%);
            border-radius: var(--pr-radius);
            padding: 22px 28px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
            box-shadow: var(--pr-shadow-lg);
        }

        .pr-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: var(--pr-radius);
            background:
                radial-gradient(ellipse 380px 200px at 95% 50%, rgba(116, 255, 112, .13) 0%, transparent 65%),
                radial-gradient(ellipse 180px 100px at 5% 80%, rgba(116, 255, 112, .07) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .pr-hero::after {
            content: '';
            position: absolute;
            top: 0;
            left: 28px;
            right: 28px;
            height: 2px;
            background: linear-gradient(to right, transparent, var(--pr-lime), transparent);
            border-radius: 2px;
            opacity: .55;
        }

        .pr-hero-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            position: relative;
            z-index: 1;
        }

        .pr-hero-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .pr-hero-icon {
            width: 46px;
            height: 46px;
            background: rgba(116, 255, 112, .12);
            border: 1px solid rgba(116, 255, 112, .30);
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            color: var(--pr-lime);
            backdrop-filter: blur(4px);
            flex-shrink: 0;
        }

        .pr-hero-title {
            font-size: 1.18rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: -.01em;
            margin: 0 0 3px;
            line-height: 1.2;
        }

        .pr-hero-sub {
            font-size: .78rem;
            color: rgba(255, 255, 255, .55);
            font-weight: 400;
        }

        .pr-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: var(--pr-lime);
            color: var(--pr-forest);
            border: none;
            border-radius: 8px;
            padding: 8px 18px;
            font-size: .82rem;
            font-weight: 700;
            font-family: 'DM Sans', sans-serif;
            text-decoration: none;
            cursor: pointer;
            transition: background .18s, transform .15s, box-shadow .18s;
            white-space: nowrap;
            box-shadow: var(--pr-shadow-lime);
        }

        .pr-btn-primary:hover {
            background: var(--pr-lime-dim);
            color: var(--pr-forest);
            transform: translateY(-1px);
            box-shadow: 0 4px 18px rgba(116, 255, 112, .40);
        }

        .pr-btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(255, 255, 255, .08);
            color: rgba(255, 255, 255, .82);
            border: 1px solid rgba(255, 255, 255, .18);
            border-radius: 8px;
            padding: 8px 16px;
            font-size: .82rem;
            font-weight: 500;
            font-family: 'DM Sans', sans-serif;
            text-decoration: none;
            cursor: pointer;
            transition: all .18s;
            white-space: nowrap;
        }

        .pr-btn-ghost:hover {
            background: rgba(116, 255, 112, .12);
            border-color: var(--pr-lime-border);
            color: var(--pr-lime);
        }

        /* ── Form card ── */
        .pr-form-card {
            background: var(--pr-surface);
            border-radius: var(--pr-radius);
            border: 1px solid var(--pr-border);
            box-shadow: var(--pr-shadow);
            overflow: hidden;
        }

        /* Section header inside card */
        .pr-section-head {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 16px 24px 14px;
            border-bottom: 1px solid var(--pr-border);
            background: var(--pr-surface2);
        }

        .pr-section-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            flex-shrink: 0;
            background: var(--pr-lime-ghost);
            border: 1px solid var(--pr-lime-border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--pr-forest);
            font-size: .78rem;
        }

        .pr-section-title {
            font-size: .82rem;
            font-weight: 700;
            color: var(--pr-forest);
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        /* Form body */
        .pr-form-body {
            padding: 22px 24px;
        }

        .pr-form-body+.pr-section-head {
            border-top: 1px solid var(--pr-border);
        }

        /* Field label */
        .pr-label {
            display: block;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
            color: var(--pr-sub);
            margin-bottom: 5px;
        }

        .pr-label .req {
            color: var(--pr-danger);
            margin-left: 2px;
        }

        /* Inputs */
        .pr-input,
        .pr-select,
        .pr-textarea {
            width: 100%;
            border: 1.5px solid var(--pr-border-dark);
            border-radius: 8px;
            padding: 9px 13px;
            font-size: .83rem;
            font-family: 'DM Sans', sans-serif;
            color: var(--pr-text);
            background: var(--pr-surface);
            transition: border-color .2s, box-shadow .2s;
        }

        .pr-input:focus,
        .pr-select:focus,
        .pr-textarea:focus {
            outline: none;
            border-color: var(--pr-forest-mid);
            box-shadow: 0 0 0 3px rgba(6, 78, 59, .11);
        }

        .pr-input::placeholder,
        .pr-textarea::placeholder {
            color: var(--pr-border-dark);
        }

        .pr-input.is-invalid,
        .pr-select.is-invalid,
        .pr-textarea.is-invalid {
            border-color: var(--pr-danger) !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, .10) !important;
        }

        /* Readonly / auto-filled */
        .pr-input[readonly],
        .pr-input[disabled] {
            background: var(--pr-surface2) !important;
            color: var(--pr-sub) !important;
            border-color: var(--pr-border) !important;
            cursor: default;
        }

        /* Select chevron */
        .pr-select {
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%233d7a62'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 32px;
        }

        .pr-select:focus {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%23064e3b'/%3E%3C/svg%3E");
        }

        .pr-textarea {
            resize: vertical;
            min-height: 80px;
        }

        /* Error message */
        .pr-error {
            font-size: .73rem;
            color: var(--pr-danger);
            margin-top: 4px;
            font-weight: 500;
        }

        /* Field group */
        .pr-field {
            margin-bottom: 16px;
        }

        .pr-field:last-child {
            margin-bottom: 0;
        }

        /* Hint text under a field */
        .pr-hint {
            font-size: .70rem;
            color: var(--pr-sub);
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Auto-filled chip indicator */
        .pr-auto-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: var(--pr-lime-ghost);
            border: 1px solid var(--pr-lime-border);
            border-radius: 20px;
            padding: 1px 8px;
            font-size: .68rem;
            font-weight: 600;
            color: var(--pr-forest);
            margin-left: 6px;
            vertical-align: middle;
        }

        /* Form footer */
        .pr-form-footer {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 16px 24px;
            border-top: 1px solid var(--pr-border);
            background: var(--pr-surface2);
        }

        .pr-btn-save {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: var(--pr-forest);
            color: var(--pr-lime);
            border: none;
            border-radius: 8px;
            padding: 9px 22px;
            font-size: .84rem;
            font-weight: 700;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: background .18s, transform .15s;
            box-shadow: 0 2px 8px rgba(6, 78, 59, .25);
            white-space: nowrap;
        }

        .pr-btn-save:hover {
            background: var(--pr-forest-mid);
            transform: translateY(-1px);
        }

        .pr-btn-save:disabled {
            opacity: .6;
            cursor: not-allowed;
            transform: none;
        }

        .pr-btn-back {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: var(--pr-muted);
            color: var(--pr-sub);
            border: 1px solid var(--pr-border-dark);
            border-radius: 8px;
            padding: 9px 18px;
            font-size: .84rem;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            text-decoration: none;
            cursor: pointer;
            transition: background .18s;
            white-space: nowrap;
        }

        .pr-btn-back:hover {
            background: var(--pr-border-dark);
            color: var(--pr-text);
        }

        @media (max-width: 768px) {
            .pr-hero-inner {
                flex-direction: column;
                align-items: flex-start;
            }

            .pr-hero {
                padding: 16px 18px;
            }

            .pr-form-body {
                padding: 16px;
            }

            .pr-section-head {
                padding: 12px 16px;
            }

            .pr-form-footer {
                padding: 14px 16px;
            }
        }
    </style>

    <div class="pr-page">

        {{-- ══ HERO ══ --}}
        <div class="pr-hero">
            <div class="pr-hero-inner">
                <div class="pr-hero-left">
                    <div class="pr-hero-icon"><i class="fas fa-user-plus"></i></div>
                    <div>
                        <div class="pr-hero-title">Create Patient Record</div>
                        <div class="pr-hero-sub">Fill in the details below to add a new patient record to the system.</div>
                    </div>
                </div>
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <a href="{{ route('admin.patient-records.index') }}" class="pr-btn-ghost">
                        <i class="fas fa-arrow-left" style="font-size:.75rem;"></i> Back to Records
                    </a>
                </div>
            </div>
        </div>

        {{-- ══ FORM ══ --}}
        <form method="POST" action="{{ route('admin.patient-records.store') }}" enctype="multipart/form-data"
            id="createPatientForm">
            @csrf

            <div class="pr-form-card">

                {{-- ── Case Details ── --}}
                <div class="pr-section-head">
                    <div class="pr-section-icon"><i class="fas fa-folder-open"></i></div>
                    <span class="pr-section-title">Case Details</span>
                </div>

                <div class="pr-form-body">
                    <div class="row g-3">

                        {{-- Date Processed --}}
                        <div class="col-md-6">
                            <div class="pr-field">
                                <label class="pr-label" for="date_processed">Date Processed <span
                                        class="req">*</span></label>
                                <input type="text" name="date_processed" id="date_processed"
                                    class="pr-input datetime {{ $errors->has('date_processed') ? 'is-invalid' : '' }}"
                                    value="{{ old('date_processed', $dateProcessed) }}" required>
                                @error('date_processed')
                                    <div class="pr-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- Control Number --}}
                        <div class="col-md-6">
                            <div class="pr-field">
                                <label class="pr-label" for="control_number">
                                    Control Number <span class="req">*</span>
                                    <span class="pr-auto-chip"><i class="fas fa-magic" style="font-size:.6rem;"></i>
                                        Auto</span>
                                </label>
                                <input type="text" name="control_number" id="control_number"
                                    class="pr-input {{ $errors->has('control_number') ? 'is-invalid' : '' }}"
                                    value="{{ old('control_number', $controlNumber) }}" readonly required>
                                <div class="pr-hint"><i class="fas fa-info-circle"></i> Automatically generated — cannot be
                                    edited.</div>
                                @error('control_number')
                                    <div class="pr-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- Case Type --}}
                        <div class="col-md-6">
                            <div class="pr-field">
                                <label class="pr-label" for="case_type">Case Type <span class="req">*</span></label>
                                <select name="case_type" id="case_type"
                                    class="pr-select {{ $errors->has('case_type') ? 'is-invalid' : '' }}" required>
                                    <option value="" disabled {{ old('case_type', null) === null ? 'selected' : '' }}>
                                        Select case type…</option>
                                    @foreach (App\Models\PatientRecord::CASE_TYPE_SELECT as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('case_type') === (string) $key ? 'selected' : '' }}>{{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('case_type')
                                    <div class="pr-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- Case Category --}}
                        <div class="col-md-6">
                            <div class="pr-field">
                                <label class="pr-label" for="case_category">Case Category <span
                                        class="req">*</span></label>
                                <select name="case_category" id="case_category"
                                    class="pr-select {{ $errors->has('case_category') ? 'is-invalid' : '' }}" required>
                                    <option value="" disabled
                                        {{ old('case_category', null) === null ? 'selected' : '' }}>Select category…
                                    </option>
                                    @foreach (App\Models\PatientRecord::CASE_CATEGORY_SELECT as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('case_category') === (string) $key ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('case_category')
                                    <div class="pr-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- Claimant Name --}}
                        <div class="col-md-6">
                            <div class="pr-field">
                                <label class="pr-label" for="claimant_name">Claimant Name <span
                                        class="req">*</span></label>
                                <input type="text" name="claimant_name" id="claimant_name"
                                    class="pr-input {{ $errors->has('claimant_name') ? 'is-invalid' : '' }}"
                                    value="{{ old('claimant_name') }}" placeholder="Enter claimant full name" required>
                                @error('claimant_name')
                                    <div class="pr-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- Diagnosis --}}
                        <div class="col-md-6">
                            <div class="pr-field">
                                <label class="pr-label" for="diagnosis">Diagnosis</label>
                                <textarea name="diagnosis" id="diagnosis" class="pr-textarea {{ $errors->has('diagnosis') ? 'is-invalid' : '' }}"
                                    rows="3" placeholder="Enter diagnosis or medical condition…">{{ old('diagnosis') }}</textarea>
                                @error('diagnosis')
                                    <div class="pr-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
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

                        {{-- Patient Name --}}
                        <div class="col-md-6">
                            <div class="pr-field">
                                <label class="pr-label" for="patient_name">Patient Name <span
                                        class="req">*</span></label>
                                <input type="text" name="patient_name" id="patient_name"
                                    class="pr-input {{ $errors->has('patient_name') ? 'is-invalid' : '' }}"
                                    value="{{ old('patient_name') }}" placeholder="Enter patient full name" required>
                                @error('patient_name')
                                    <div class="pr-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- Age --}}
                        <div class="col-md-3">
                            <div class="pr-field">
                                <label class="pr-label" for="age">Age <span class="req">*</span></label>
                                <input type="number" name="age" id="age"
                                    class="pr-input {{ $errors->has('age') ? 'is-invalid' : '' }}"
                                    value="{{ old('age') }}" min="0" max="130" step="1"
                                    placeholder="0" required>
                                @error('age')
                                    <div class="pr-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- Contact Number --}}
                        <div class="col-md-3">
                            <div class="pr-field">
                                <label class="pr-label" for="contact_number">Contact Number <span
                                        class="req">*</span></label>
                                <input type="tel" name="contact_number" id="contact_number"
                                    class="pr-input {{ $errors->has('contact_number') ? 'is-invalid' : '' }}"
                                    value="{{ old('contact_number') }}" maxlength="11" placeholder="09XXXXXXXXX"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);" required>
                                @error('contact_number')
                                    <div class="pr-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="col-md-8">
                            <div class="pr-field">
                                <label class="pr-label" for="address">Address <span class="req">*</span></label>
                                <input type="text" name="address" id="address"
                                    class="pr-input {{ $errors->has('address') ? 'is-invalid' : '' }}"
                                    value="{{ old('address') }}" placeholder="Street, Barangay, City/Municipality"
                                    required>
                                @error('address')
                                    <div class="pr-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- Case Worker --}}
                        <div class="col-md-4">
                            <div class="pr-field">
                                <label class="pr-label" for="case_worker">
                                    Case Worker
                                    <span class="pr-auto-chip"><i class="fas fa-user-check" style="font-size:.6rem;"></i>
                                        You</span>
                                </label>
                                <input type="text" name="case_worker" id="case_worker"
                                    class="pr-input {{ $errors->has('case_worker') ? 'is-invalid' : '' }}"
                                    value="{{ old('case_worker', auth()->user()->name) }}" readonly>
                                <div class="pr-hint"><i class="fas fa-info-circle"></i> Set to your account name
                                    automatically.</div>
                                @error('case_worker')
                                    <div class="pr-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ── Footer Actions ── --}}
                <div class="pr-form-footer">
                    <button type="submit" class="pr-btn-save" id="saveBtn">
                        <i class="fas fa-save"></i> Save Record
                    </button>
                    <a href="{{ route('admin.patient-records.index') }}" class="pr-btn-back">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    @if ($errors->any())
                        <span style="font-size:.75rem;color:var(--pr-danger);font-weight:600;margin-left:4px;">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            {{ $errors->count() }} field{{ $errors->count() > 1 ? 's' : '' }}
                            need{{ $errors->count() === 1 ? 's' : '' }} attention
                        </span>
                    @endif
                </div>

            </div>
        </form>

    </div>{{-- /pr-page --}}
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Toast
            var toastEl = document.getElementById('liveToast');
            var timerEl = document.getElementById('toast-timer');
            if (toastEl) {
                new bootstrap.Toast(toastEl, {
                    autohide: true,
                    delay: 5000
                }).show();
                let rem = 5;
                const iv = setInterval(() => {
                    rem--;
                    if (timerEl) timerEl.textContent = `Closing in ${rem}s`;
                    if (rem <= 0) clearInterval(iv);
                }, 1000);
            }

            // Submit loading state
            const form = document.getElementById('createPatientForm');
            const saveBtn = document.getElementById('saveBtn');
            if (form && saveBtn) {
                form.addEventListener('submit', function() {
                    saveBtn.disabled = true;
                    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving…';
                });
            }

            // Highlight first invalid field
            const firstInvalid = document.querySelector(
                '.pr-input.is-invalid, .pr-select.is-invalid, .pr-textarea.is-invalid');
            if (firstInvalid) firstInvalid.focus();

        });
    </script>
@endsection
