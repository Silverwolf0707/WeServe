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
        radial-gradient(ellipse 180px 100px at 5% 80%,  rgba(116,255,112,.07) 0%, transparent 70%),
        radial-gradient(ellipse 250px 120px at 50% -20%, rgba(255,255,255,.04) 0%, transparent 60%);
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
.pr-hero-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

/* Buttons */
.pr-btn-primary {
    display: inline-flex; align-items: center; gap: 7px;
    background: var(--pr-lime); color: var(--pr-forest);
    border: none; border-radius: 8px; padding: 8px 18px;
    font-size: .82rem; font-weight: 700; font-family: 'DM Sans', sans-serif;
    text-decoration: none; cursor: pointer;
    transition: background .18s, transform .15s, box-shadow .18s;
    white-space: nowrap; box-shadow: var(--pr-shadow-lime);
}
.pr-btn-primary:hover { background: var(--pr-lime-dim); color: var(--pr-forest); transform: translateY(-1px); box-shadow: 0 4px 18px rgba(116,255,112,.40); }

.pr-btn-ghost {
    display: inline-flex; align-items: center; gap: 7px;
    background: rgba(255,255,255,.08); color: rgba(255,255,255,.82);
    border: 1px solid rgba(255,255,255,.18); border-radius: 8px;
    padding: 8px 16px; font-size: .82rem; font-weight: 500; font-family: 'DM Sans', sans-serif;
    text-decoration: none; cursor: pointer; transition: all .18s; white-space: nowrap;
}
.pr-btn-ghost:hover { background: rgba(116,255,112,.12); border-color: var(--pr-lime-border); color: var(--pr-lime); }

/* ── Detail card ── */
.pr-detail-card {
    background: var(--pr-surface); border-radius: var(--pr-radius);
    border: 1px solid var(--pr-border); box-shadow: var(--pr-shadow); overflow: hidden;
    margin-bottom: 16px;
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

.pr-detail-body { padding: 22px 24px; }

/* Detail field */
.pr-detail-field { margin-bottom: 18px; }
.pr-detail-field:last-child { margin-bottom: 0; }
.pr-detail-label {
    font-size: .68rem; font-weight: 700; letter-spacing: .07em;
    text-transform: uppercase; color: var(--pr-sub); margin-bottom: 4px;
    display: flex; align-items: center; gap: 5px;
}
.pr-detail-value {
    font-size: .88rem; font-weight: 500; color: var(--pr-text);
    background: var(--pr-surface2); border: 1px solid var(--pr-border);
    border-radius: 8px; padding: 9px 13px; line-height: 1.5;
    word-break: break-word;
}
.pr-detail-value.empty { color: var(--pr-border-dark); font-style: italic; font-weight: 400; }
.pr-detail-value.diagnosis-val { min-height: 64px; white-space: pre-wrap; }

/* Status badge */
.pr-status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    border-radius: 20px; font-size: .75rem; font-weight: 700;
    padding: 4px 12px; letter-spacing: .02em; line-height: 1.5;
}
.status-processing { background: rgba(245,158,11,.15); border: 1px solid rgba(245,158,11,.4); color: #b45309; }
.status-submitted  { background: var(--pr-lime-ghost);   border: 1px solid var(--pr-lime-border);   color: var(--pr-forest-mid); }
.status-approved   { background: rgba(16,185,129,.12);   border: 1px solid rgba(16,185,129,.4);     color: #065f46; }
.status-rejected   { background: rgba(239,68,68,.12);    border: 1px solid rgba(239,68,68,.35);     color: #b91c1c; }
.status-default    { background: var(--pr-muted);        border: 1px solid var(--pr-border-dark);   color: var(--pr-sub); }

/* Tracking number chip */
.pr-tracking-chip {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--pr-lime-ghost); border: 1px solid var(--pr-lime-border);
    border-radius: 8px; padding: 8px 13px; font-size: .88rem;
    font-weight: 700; color: var(--pr-forest); letter-spacing: .03em;
}
.pr-tracking-chip i { color: var(--pr-lime-dim); font-size: .8rem; }
.pr-tracking-empty { color: var(--pr-border-dark); font-style: italic; font-weight: 400; font-size: .85rem; }

/* Divider */
.pr-divider { height: 1px; background: var(--pr-border); margin: 0; }

/* ── Submit form card ── */
.pr-submit-card {
    background: var(--pr-surface); border-radius: var(--pr-radius);
    border: 1px solid var(--pr-border); box-shadow: var(--pr-shadow); overflow: hidden;
    margin-bottom: 16px;
}
.pr-submit-head {
    display: flex; align-items: center; gap: 10px;
    padding: 16px 24px 14px;
    border-bottom: 1px solid var(--pr-border);
    background: linear-gradient(135deg, #052e22 0%, #064e3b 100%);
}
.pr-submit-head .pr-section-icon { background: rgba(116,255,112,.14); border-color: rgba(116,255,112,.30); color: var(--pr-lime); }
.pr-submit-head .pr-section-title { color: var(--pr-lime); }

.pr-submit-body { padding: 22px 24px; }

.pr-field-label {
    display: block; font-size: .72rem; font-weight: 700;
    letter-spacing: .05em; text-transform: uppercase;
    color: var(--pr-sub); margin-bottom: 5px;
}
.pr-input, .pr-textarea {
    width: 100%; border: 1.5px solid var(--pr-border-dark);
    border-radius: 8px; padding: 9px 13px;
    font-size: .83rem; font-family: 'DM Sans', sans-serif;
    color: var(--pr-text); background: var(--pr-surface);
    transition: border-color .2s, box-shadow .2s;
}
.pr-input:focus, .pr-textarea:focus { outline: none; border-color: var(--pr-forest-mid); box-shadow: 0 0 0 3px rgba(6,78,59,.11); }
.pr-input:disabled, .pr-textarea:disabled { background: var(--pr-surface2); color: var(--pr-sub); border-color: var(--pr-border); cursor: not-allowed; }
.pr-input::placeholder, .pr-textarea::placeholder { color: var(--pr-border-dark); }
.pr-textarea { resize: vertical; min-height: 90px; }

.pr-submit-actions { display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; margin-top: 18px; }

.pr-btn-submit {
    display: inline-flex; align-items: center; gap: 7px;
    background: var(--pr-forest); color: var(--pr-lime);
    border: none; border-radius: 8px; padding: 9px 22px;
    font-size: .84rem; font-weight: 700; font-family: 'DM Sans', sans-serif;
    cursor: pointer; transition: background .18s, transform .15s;
    box-shadow: 0 2px 8px rgba(6,78,59,.25); white-space: nowrap;
}
.pr-btn-submit:hover:not(:disabled) { background: var(--pr-forest-mid); transform: translateY(-1px); }
.pr-btn-submit:disabled { opacity: .45; cursor: not-allowed; transform: none; }

.pr-btn-emergency {
    display: inline-flex; align-items: center; gap: 7px;
    background: var(--pr-danger); color: #fff;
    border: none; border-radius: 8px; padding: 9px 22px;
    font-size: .84rem; font-weight: 700; font-family: 'DM Sans', sans-serif;
    cursor: pointer; transition: background .18s, transform .15s;
    box-shadow: 0 2px 8px rgba(239,68,68,.30); white-space: nowrap;
}
.pr-btn-emergency:hover:not(:disabled) { background: #dc2626; transform: translateY(-1px); }
.pr-btn-emergency:disabled { opacity: .45; cursor: not-allowed; transform: none; }

.pr-alert {
    display: flex; align-items: flex-start; gap: 10px;
    border-radius: 9px; padding: 12px 14px; font-size: .8rem;
    font-weight: 500; margin-top: 14px; line-height: 1.5;
}
.pr-alert-warn    { background: rgba(245,158,11,.10); border: 1px solid rgba(245,158,11,.3); color: #92400e; }
.pr-alert-info    { background: var(--pr-lime-ghost); border: 1px solid var(--pr-lime-border); color: var(--pr-forest); }
.pr-alert-danger  { background: rgba(239,68,68,.08);  border: 1px solid rgba(239,68,68,.25);  color: #b91c1c; }
.pr-alert i { margin-top: 1px; flex-shrink: 0; }

/* ── Action footer ── */
.pr-action-footer {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 10px;
    background: var(--pr-surface); border-radius: var(--pr-radius);
    border: 1px solid var(--pr-border); padding: 16px 24px;
    box-shadow: var(--pr-shadow);
}
.pr-action-footer-left  { display: flex; align-items: center; gap: 8px; }
.pr-action-footer-right { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

.pr-btn-secondary {
    display: inline-flex; align-items: center; gap: 7px;
    background: var(--pr-muted); color: var(--pr-sub);
    border: 1px solid var(--pr-border-dark); border-radius: 8px;
    padding: 9px 18px; font-size: .84rem; font-weight: 600;
    font-family: 'DM Sans', sans-serif; text-decoration: none;
    cursor: pointer; transition: background .18s; white-space: nowrap;
}
.pr-btn-secondary:hover { background: var(--pr-border-dark); color: var(--pr-text); }

.pr-btn-info {
    display: inline-flex; align-items: center; gap: 7px;
    background: rgba(6,95,70,.09); color: var(--pr-forest-mid);
    border: 1px solid rgba(6,95,70,.25); border-radius: 8px;
    padding: 9px 18px; font-size: .84rem; font-weight: 600;
    font-family: 'DM Sans', sans-serif; text-decoration: none;
    cursor: pointer; transition: all .18s; white-space: nowrap;
}
.pr-btn-info:hover { background: rgba(6,95,70,.16); color: var(--pr-forest); border-color: rgba(6,95,70,.4); }
.pr-btn-info.disabled-btn { opacity: .45; cursor: not-allowed; pointer-events: none; }

/* ── Diagnosis modal ── */
.pr-modal .modal-content { border: none; border-radius: 14px; overflow: hidden; box-shadow: var(--pr-shadow-lg); font-family: 'DM Sans', sans-serif; }
.pr-modal .modal-header {
    padding: 18px 22px 16px; border-bottom: 1px solid var(--pr-border);
    background: linear-gradient(135deg, #052e22 0%, #064e3b 100%);
}
.pr-modal .modal-title { color: var(--pr-lime); font-size: .95rem; font-weight: 700; letter-spacing: -.01em; }
.pr-modal .modal-header .btn-close { filter: invert(1) brightness(1.5); }
.pr-modal .modal-body { padding: 20px 22px; font-size: .87rem; color: var(--pr-text); line-height: 1.7; white-space: pre-wrap; word-wrap: break-word; overflow-y: auto; max-height: 420px; }
.pr-modal .modal-footer { padding: 14px 22px; border-top: 1px solid var(--pr-border); background: var(--pr-surface2); }
.pr-modal .modal-footer .btn {
    border-radius: 8px; font-size: .82rem; font-family: 'DM Sans', sans-serif;
    font-weight: 600; padding: 7px 18px; border: none; background-image: none !important;
    background: var(--pr-muted); color: var(--pr-sub); border: 1px solid var(--pr-border-dark);
}
.pr-modal .modal-footer .btn:hover { background: var(--pr-border-dark); color: var(--pr-text); }

/* ── bottom nav bar ── */
        .pr-nav-bar {
            background: var(--pr-surface2); border: 1px solid var(--pr-border);
            border-radius: var(--pr-radius); padding: 12px 18px;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 10px; box-shadow: var(--pr-shadow);
        }
        .pr-nav-bar-left  { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .pr-nav-bar-right { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

        .pr-nav-btn {
            display: inline-flex; align-items: center; gap: 6px;
            border-radius: 8px; padding: 7px 14px; font-size: .8rem; font-weight: 600;
            font-family: 'DM Sans', sans-serif; cursor: pointer; transition: all .18s;
            white-space: nowrap; text-decoration: none;
        }
        .pr-nav-btn-back {
            background: var(--pr-surface); color: var(--pr-sub);
            border: 1.5px solid var(--pr-border-dark);
        }
        .pr-nav-btn-back:hover { background: var(--pr-muted); color: var(--pr-forest); border-color: var(--pr-forest); }
        .pr-nav-btn-upload {
            background: var(--pr-forest); color: var(--pr-lime);
            border: 1.5px solid var(--pr-forest); box-shadow: 0 2px 8px rgba(6,78,59,.25);
        }
        .pr-nav-btn-upload:hover { background: var(--pr-forest-mid); color: var(--pr-lime); }
        .pr-nav-btn-info {
            background: var(--pr-surface); color: var(--pr-sub);
            border: 1.5px solid var(--pr-border-dark);
        }
        .pr-nav-btn-info:hover { background: var(--pr-lime-ghost); border-color: var(--pr-forest); color: var(--pr-forest); }

@media (max-width: 768px) {
    .pr-hero-inner, .pr-action-footer { flex-direction: column; align-items: flex-start; }
    .pr-hero { padding: 16px 18px; }
    .pr-detail-body, .pr-submit-body { padding: 16px; }
    .pr-section-head, .pr-submit-head { padding: 12px 16px; }
    .pr-action-footer-right { width: 100%; }
}
</style>

<div class="pr-page">

{{-- ══ HERO ══ --}}
<div class="pr-hero">
    <div class="pr-hero-inner">
        <div class="pr-hero-left">
            <div class="pr-hero-icon"><i class="fas fa-user-circle"></i></div>
            <div>
                <div class="pr-hero-title">Patient Record Details</div>
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-top:4px;">
                    <span class="pr-hero-badge">
                        <i class="fas fa-hashtag" style="font-size:.6rem;"></i>
                        {{ $patientRecord->control_number }}
                    </span>
                    <span style="color:rgba(255,255,255,.35);font-size:.72rem;">·</span>
                    <span style="color:rgba(255,255,255,.55);font-size:.75rem;">{{ $patientRecord->patient_name }}</span>
                    @php
                        $latestStatus = $patientRecord->latestStatusLog;
                        $latestStatusValue = optional($latestStatus)->status ?? 'Processing';
                        $baseStatus = trim(preg_replace('/\[.*?\]/', '', $latestStatusValue));
                        $statusClass = match($baseStatus) {
                            'Processing' => 'status-processing',
                            'Submitted'  => 'status-submitted',
                            'Approved'   => 'status-approved',
                            'Rejected'   => 'status-rejected',
                            default      => 'status-default',
                        };
                    @endphp
                    <span style="color:rgba(255,255,255,.35);font-size:.72rem;">·</span>
                    <span class="pr-status-badge {{ $statusClass }}">
                        <span style="width:5px;height:5px;border-radius:50%;display:inline-block;background:currentColor;opacity:.7;"></span>
                        {{ $latestStatusValue }}
                    </span>
                </div>
            </div>
        </div>
        <div class="pr-hero-actions">
            @can('patient_record_edit')
                <a href="{{ route('admin.patient-records.edit', $patientRecord->id) }}" class="pr-btn-primary">
                    <i class="fas fa-edit" style="font-size:.75rem;"></i> Edit Record
                </a>
            @endcan
            <a href="{{ route('admin.patient-records.index') }}" class="pr-btn-ghost">
                <i class="fas fa-arrow-left" style="font-size:.75rem;"></i> Back to Records
            </a>
        </div>
    </div>
</div>


{{-- ══ DETAILS GRID ══ --}}
<div class="row g-3 mb-3">

    {{-- Patient Info --}}
    <div class="col-lg-6">
        <div class="pr-detail-card h-100">
            <div class="pr-section-head">
                <div class="pr-section-icon"><i class="fas fa-user"></i></div>
                <span class="pr-section-title">Patient Information</span>
            </div>
            <div class="pr-detail-body">

                <div class="pr-detail-field">
                    <div class="pr-detail-label"><i class="fas fa-user-circle"></i> Patient Name</div>
                    <div class="pr-detail-value">{{ $patientRecord->patient_name ?: '' }}
                        @if(!$patientRecord->patient_name)<span class="empty">—</span>@endif
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="pr-detail-field">
                            <div class="pr-detail-label"><i class="fas fa-birthday-cake"></i> Age</div>
                            <div class="pr-detail-value">{{ $patientRecord->age ?? '' }}
                                @if(!$patientRecord->age)<span class="empty">—</span>@endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="pr-detail-field">
                            <div class="pr-detail-label"><i class="fas fa-phone"></i> Contact Number</div>
                            <div class="pr-detail-value">{{ $patientRecord->contact_number ?? '' }}
                                @if(!$patientRecord->contact_number)<span class="empty">—</span>@endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pr-detail-field">
                    <div class="pr-detail-label"><i class="fas fa-map-marker-alt"></i> Address</div>
                    <div class="pr-detail-value">{{ $patientRecord->address ?? '' }}
                        @if(!$patientRecord->address)<span class="empty">—</span>@endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Case Info --}}
    <div class="col-lg-6">
        <div class="pr-detail-card h-100">
            <div class="pr-section-head">
                <div class="pr-section-icon"><i class="fas fa-folder-open"></i></div>
                <span class="pr-section-title">Case Information</span>
            </div>
            <div class="pr-detail-body">

                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="pr-detail-field">
                            <div class="pr-detail-label"><i class="fas fa-hashtag"></i> Control Number</div>
                            <div class="pr-detail-value" style="font-weight:700;letter-spacing:.02em;">
                                {{ $patientRecord->control_number ?? '' }}
                                @if(!$patientRecord->control_number)<span class="empty">—</span>@endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="pr-detail-field">
                            <div class="pr-detail-label"><i class="fas fa-calendar-alt"></i> Date Processed</div>
                            <div class="pr-detail-value">
                                {{ \Carbon\Carbon::parse($patientRecord->date_processed)->format('F j, Y g:i A') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="pr-detail-field">
                            <div class="pr-detail-label"><i class="fas fa-folder"></i> Case Type</div>
                            <div class="pr-detail-value">{{ $patientRecord->case_type ?? '' }}
                                @if(!$patientRecord->case_type)<span class="empty">—</span>@endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="pr-detail-field">
                            <div class="pr-detail-label"><i class="fas fa-tag"></i> Case Category</div>
                            <div class="pr-detail-value">
                                {{ App\Models\PatientRecord::CASE_CATEGORY_SELECT[$patientRecord->case_category] ?? ($patientRecord->case_category ?? '') }}
                                @if(!$patientRecord->case_category)<span class="empty">—</span>@endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="pr-detail-field">
                            <div class="pr-detail-label"><i class="fas fa-user-tie"></i> Claimant Name</div>
                            <div class="pr-detail-value">{{ $patientRecord->claimant_name ?? '' }}
                                @if(!$patientRecord->claimant_name)<span class="empty">—</span>@endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="pr-detail-field">
                            <div class="pr-detail-label"><i class="fas fa-user-md"></i> Case Worker</div>
                            <div class="pr-detail-value">{{ $patientRecord->case_worker ?? '' }}
                                @if(!$patientRecord->case_worker)<span class="empty">—</span>@endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pr-detail-field">
                    <div class="pr-detail-label"><i class="fas fa-barcode"></i> Tracking Number</div>
                    @if($patientRecord->trackingNumber->tracking_number ?? null)
                        <div style="margin-top:2px;">
                            <span class="pr-tracking-chip">
                                <i class="fas fa-qrcode"></i>
                                {{ $patientRecord->trackingNumber->tracking_number }}
                            </span>
                        </div>
                    @else
                        <div class="pr-detail-value"><span class="empty">Not yet assigned</span></div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

{{-- ── Diagnosis full-width ── --}}
@if($patientRecord->diagnosis)
<div class="pr-detail-card mb-3">
    <div class="pr-section-head">
        <div class="pr-section-icon"><i class="fas fa-notes-medical"></i></div>
        <span class="pr-section-title">Diagnosis / Medical Condition</span>
    </div>
    <div class="pr-detail-body">
        <div class="pr-detail-value diagnosis-val">
            @if(strlen($patientRecord->diagnosis) > 300)
                {{ Str::limit($patientRecord->diagnosis, 300) }}
                <button type="button" style="
                    display:inline-flex;align-items:center;gap:5px;
                    background:var(--pr-lime-ghost);border:1px solid var(--pr-lime-border);
                    border-radius:6px;padding:2px 10px;font-size:.72rem;font-weight:700;
                    color:var(--pr-forest);cursor:pointer;margin-left:6px;font-family:'DM Sans',sans-serif;
                    transition:background .18s;" data-bs-toggle="modal" data-bs-target="#diagnosisModal">
                    <i class="fas fa-expand-alt" style="font-size:.65rem;"></i> Read full
                </button>
            @else
                {{ $patientRecord->diagnosis }}
            @endif
        </div>
    </div>
</div>
@endif


{{-- ══ SUBMIT APPLICATION ══ --}}
@can('submit_patient_application')
    @php
        $isLocked = !in_array($baseStatus, ['Processing', 'Rejected', 'Draft', 'Processing[ROLLED BACK]']);

        $hasEmergencySubmission = false;
        $hasNormalSubmission = false;

        if ($patientRecord->statusLogs) {
            $submissionLogs = $patientRecord->statusLogs
                ->whereIn('status', ['Submitted', 'Submitted[Emergency]'])
                ->sortByDesc('status_date');

            if ($submissionLogs->count() > 0) {
                $previousSubmissionStatus = $submissionLogs->first()->status;
                $hasEmergencySubmission = str_contains($previousSubmissionStatus, 'Emergency');
                $hasNormalSubmission = !$hasEmergencySubmission && str_contains($previousSubmissionStatus, 'Submitted');
            }
        }
        $showSubmissionWarnings = in_array($baseStatus, ['Rejected', 'Processing[ROLLED BACK]', 'Processing']);
    @endphp

    @if(in_array($baseStatus, ['Processing', 'Draft', 'Rejected', 'Processing[ROLLED BACK]']))
    <div class="pr-submit-card">
        <div class="pr-submit-head">
            <div class="pr-section-icon"><i class="fas fa-paper-plane"></i></div>
            <span class="pr-section-title">Submit Application — CSWD Office</span>
        </div>
        <div class="pr-submit-body">
            <form method="POST" id="submitForm">
                @csrf
                <input type="hidden" name="status" value="Submitted">
                <input type="hidden" name="redirect_to_process_tracking" value="1">

                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="pr-field-label" for="submitted_date">
                            <i class="fas fa-calendar-alt me-1"></i> Submitted Date
                        </label>
                        <input type="datetime-local" name="submitted_date" id="submitted_date"
                            class="pr-input"
                            value="{{ now()->toDateTimeLocalString() }}"
                            @if($isLocked) disabled @endif>
                    </div>
                    <div class="col-md-7">
                        <label class="pr-field-label" for="remarks">
                            <i class="fas fa-comment-alt me-1"></i> Remarks
                            <span style="font-weight:400;color:var(--pr-border-dark);text-transform:none;letter-spacing:0;">(optional)</span>
                        </label>
                        <textarea name="remarks" id="remarks" class="pr-textarea"
                            placeholder="Add any remarks or notes…"
                            @if($isLocked) disabled @endif></textarea>
                    </div>
                </div>

                <div class="pr-submit-actions">
                    <button type="button" class="pr-btn-submit submit-btn"
                        @if($isLocked || $hasEmergencySubmission || ($showSubmissionWarnings && $hasEmergencySubmission)) disabled @endif
                        onclick="submitApplication('{{ route('admin.patient-records.submit', $patientRecord->id) }}', this, 'normal')"
                        id="normal-submit-btn">
                        <i class="fas fa-paper-plane" style="font-size:.78rem;"></i> Submit
                    </button>
                    <button type="button" class="pr-btn-emergency submit-btn"
                        @if($isLocked || ($showSubmissionWarnings && $hasNormalSubmission)) disabled @endif
                        onclick="submitApplication('{{ route('admin.patient-records.submit-emergency', $patientRecord->id) }}', this, 'emergency')"
                        id="emergency-submit-btn">
                        <i class="fas fa-bolt" style="font-size:.78rem;"></i> Submit [Emergency]
                    </button>
                </div>

                @if($hasEmergencySubmission && $showSubmissionWarnings)
                    <div class="pr-alert pr-alert-warn">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>This application was previously submitted as <strong>Emergency</strong>. Only emergency re-submissions are allowed.</div>
                    </div>
                @elseif($hasNormalSubmission && $showSubmissionWarnings)
                    <div class="pr-alert pr-alert-info">
                        <i class="fas fa-info-circle"></i>
                        <div>This application was previously submitted <strong>normally</strong>. Only normal re-submissions are allowed.</div>
                    </div>
                @endif

                @if($isLocked && !$showSubmissionWarnings)
                    <div class="pr-alert pr-alert-info">
                        <i class="fas fa-lock"></i>
                        <div>This application has already been submitted and is currently <strong>in process</strong>.</div>
                    </div>
                @endif

            </form>
        </div>
    </div>
    @endif
@endcan


<div class="pr-nav-bar">
            <div class="pr-nav-bar-left">
                <a href="{{ route('admin.patient-records.index') }}" class="pr-nav-btn pr-nav-btn-back">
                    <i class="fas fa-arrow-left" style="font-size:.72rem;"></i> Back to List
                </a>
            </div>
            <div class="pr-nav-bar-right">
                <a href="{{ route('admin.document-management.show', $patientRecord->id) }}" class="pr-nav-btn pr-nav-btn-info">
                    <i class="fas fa-file-alt" style="font-size:.72rem;"></i> View Document
                </a>
                <a href="{{ route('admin.process-tracking.show', $patientRecord->id) }}" class="pr-nav-btn pr-nav-btn-info">
                    <i class="fas fa-history" style="font-size:.72rem;"></i> Process Tracking
                </a>
            </div>
        </div>

</div>{{-- /pr-page --}}


{{-- ══ DIAGNOSIS MODAL ══ --}}
@if($patientRecord->diagnosis && strlen($patientRecord->diagnosis) > 300)
<div class="modal fade pr-modal" id="diagnosisModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-notes-medical me-2"></i>Full Diagnosis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">{{ $patientRecord->diagnosis }}</div>
            <div class="modal-footer">
                <button type="button" class="btn" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('scripts')
<script>
function submitApplication(url, clickedButton, type) {
    const form = document.getElementById('submitForm');
    form.action = url;

    const submitButtons = document.querySelectorAll('.submit-btn');
    submitButtons.forEach(button => {
        button.disabled = true;
        if (button === clickedButton) {
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Processing…';
        } else {
            button.innerHTML = '<i class="fas fa-clock me-1"></i> Please wait…';
        }
    });

    form.submit();
}

document.addEventListener('DOMContentLoaded', function() {
    var toastEl = document.getElementById('liveToast');
    var timerEl = document.getElementById('toast-timer');
    if (toastEl) {
        new bootstrap.Toast(toastEl, { autohide: true, delay: 5000 }).show();
        let rem = 5;
        const iv = setInterval(() => {
            rem--;
            if (timerEl) timerEl.textContent = `Closing in ${rem}s`;
            if (rem <= 0) clearInterval(iv);
        }, 1000);
    }
});
</script>
@endsection