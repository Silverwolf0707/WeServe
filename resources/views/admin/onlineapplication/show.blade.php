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

.pr-btn-success {
    display: inline-flex; align-items: center; gap: 7px;
    background: #10b981; color: white;
    border: none; border-radius: 8px; padding: 8px 18px;
    font-size: .82rem; font-weight: 700; font-family: 'DM Sans', sans-serif;
    text-decoration: none; cursor: pointer;
    transition: background .18s, transform .15s;
    white-space: nowrap; box-shadow: 0 2px 8px rgba(16,185,129,.3);
}
.pr-btn-success:hover:not(:disabled) { background: #059669; transform: translateY(-1px); }
.pr-btn-success:disabled { opacity: .45; cursor: not-allowed; transform: none; }

.pr-btn-ghost {
    display: inline-flex; align-items: center; gap: 7px;
    background: rgba(255,255,255,.08); color: rgba(255,255,255,.82);
    border: 1px solid rgba(255,255,255,.18); border-radius: 8px;
    padding: 8px 16px; font-size: .82rem; font-weight: 500; font-family: 'DM Sans', sans-serif;
    text-decoration: none; cursor: pointer; transition: all .18s; white-space: nowrap;
}
.pr-btn-ghost:hover { background: rgba(116,255,112,.12); border-color: var(--pr-lime-border); color: var(--pr-lime); }

.pr-btn-secondary {
    display: inline-flex; align-items: center; gap: 7px;
    background: var(--pr-muted); color: var(--pr-sub);
    border: 1px solid var(--pr-border-dark); border-radius: 8px;
    padding: 9px 18px; font-size: .84rem; font-weight: 600;
    font-family: 'DM Sans', sans-serif; text-decoration: none;
    cursor: pointer; transition: background .18s; white-space: nowrap;
}
.pr-btn-secondary:hover { background: var(--pr-border-dark); color: var(--pr-text); }

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

/* Tracking number chip */
.pr-tracking-chip {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--pr-lime-ghost); border: 1px solid var(--pr-lime-border);
    border-radius: 8px; padding: 8px 13px; font-size: .88rem;
    font-weight: 700; color: var(--pr-forest); letter-spacing: .03em;
}
.pr-tracking-chip i { color: var(--pr-lime-dim); font-size: .8rem; }

/* Status badge */
.pr-status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    border-radius: 20px; font-size: .75rem; font-weight: 700;
    padding: 4px 12px; letter-spacing: .02em; line-height: 1.5;
}
.status-pending { background: rgba(245,158,11,.15); border: 1px solid rgba(245,158,11,.4); color: #b45309; }
.status-transferred { background: var(--pr-lime-ghost); border: 1px solid var(--pr-lime-border); color: var(--pr-forest-mid); }

/* ── Action footer ── */
.pr-action-footer {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 10px;
    background: var(--pr-surface); border-radius: var(--pr-radius);
    border: 1px solid var(--pr-border); padding: 16px 24px;
    box-shadow: var(--pr-shadow);
    margin-top: 24px;
}
.pr-action-footer-left  { display: flex; align-items: center; gap: 8px; }
.pr-action-footer-right { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

/* ── Modals ── */
.pr-modal .modal-content { border: none; border-radius: 14px; overflow: hidden; box-shadow: var(--pr-shadow-lg); font-family: 'DM Sans', sans-serif; }
.pr-modal .modal-header {
    padding: 18px 22px 16px; border-bottom: 1px solid var(--pr-border);
}
.pr-modal .modal-header.bg-success {
    background: linear-gradient(135deg, #052e22 0%, #064e3b 100%) !important;
}
.pr-modal .modal-header.bg-success .modal-title { color: var(--pr-lime) !important; }
.pr-modal .modal-header.bg-success .btn-close { filter: invert(1); }
.pr-modal .modal-title  { font-size: .95rem; font-weight: 700; letter-spacing: -.01em; }
.pr-modal .modal-body   { padding: 20px 22px; font-size: .85rem; color: var(--pr-text); line-height: 1.6; }
.pr-modal .modal-body p { margin-bottom: 6px; }
.pr-modal .modal-body .alert { border-radius: 8px; font-size: .8rem; font-weight: 500; border: none; padding: 10px 14px; }
.pr-modal .modal-footer { padding: 14px 22px; border-top: 1px solid var(--pr-border); gap: 8px; background: var(--pr-surface2); }
.pr-modal .modal-footer .btn {
    border-radius: 8px; font-size: .8rem; font-family: 'DM Sans', sans-serif;
    font-weight: 600; padding: 7px 18px; border: none;
    transition: opacity .18s, transform .15s; background-image: none !important;
}
.pr-modal .modal-footer .btn:hover { opacity: .88; transform: translateY(-1px); }
.pr-modal .modal-footer .btn-secondary {
    background: var(--pr-muted) !important;
    color: var(--pr-sub) !important;
    border: 1px solid var(--pr-border-dark) !important;
}
.pr-modal .modal-footer .btn-success {
    background: #10b981 !important;
    color: #fff !important;
    box-shadow: 0 2px 8px rgba(16,185,129,.3) !important;
}
.pr-modal .modal-footer .btn-success:hover:not(:disabled) { background: #059669 !important; }
.pr-modal .modal-footer .btn-success:disabled { opacity: .45; cursor: not-allowed; transform: none; }

/* Transfer Modal Specific */
.transfer-info {
    background: var(--pr-surface2);
    border: 1px solid var(--pr-border);
    border-radius: 10px;
    padding: 16px;
    margin: 10px 0 5px;
}
.transfer-info-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 6px 0;
    border-bottom: 1px dashed var(--pr-border);
}
.transfer-info-item:last-child { border-bottom: none; }
.transfer-info-label {
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--pr-sub);
    min-width: 100px;
}
.transfer-info-value {
    font-size: .85rem;
    font-weight: 600;
    color: var(--pr-forest);
}
.transfer-warning {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    background: rgba(245,158,11,.08);
    border: 1px solid rgba(245,158,11,.25);
    border-radius: 8px;
    padding: 12px 14px;
    margin-top: 15px;
    color: #92400e;
    font-size: .8rem;
}
.transfer-warning i { color: #f59e0b; margin-top: 2px; }

@media (max-width: 768px) {
    .pr-hero-inner, .pr-action-footer { flex-direction: column; align-items: flex-start; }
    .pr-hero { padding: 16px 18px; }
    .pr-detail-body { padding: 16px; }
    .pr-section-head { padding: 12px 16px; }
}
</style>

<div class="pr-page">

{{-- ══ HERO ══ --}}
<div class="pr-hero">
    <div class="pr-hero-inner">
        <div class="pr-hero-left">
            <div class="pr-hero-icon"><i class="fas fa-file-medical"></i></div>
            <div>
                <div class="pr-hero-title">Online Application Details</div>
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-top:4px;">
                    <span class="pr-hero-badge">
                        <i class="fas fa-hashtag" style="font-size:.6rem;"></i>
                        {{ $application->id }}
                    </span>
                    <span style="color:rgba(255,255,255,.35);font-size:.72rem;">·</span>
                    <span style="color:rgba(255,255,255,.55);font-size:.75rem;">{{ $application->applicant_name }}</span>
                    <span style="color:rgba(255,255,255,.35);font-size:.72rem;">·</span>
                    <span class="pr-status-badge {{ $application->transferred_at ? 'status-transferred' : 'status-pending' }}">
                        <span style="width:5px;height:5px;border-radius:50%;display:inline-block;background:currentColor;opacity:.7;"></span>
                        {{ $application->transferred_at ? 'Transferred' : 'Pending' }}
                    </span>
                </div>
            </div>
        </div>
        <div class="pr-hero-actions">
            <a href="{{ route('admin.online-applications.index') }}" class="pr-btn-ghost">
                <i class="fas fa-arrow-left" style="font-size:.75rem;"></i> Back to Applications
            </a>
        </div>
    </div>
</div>

{{-- ══ DETAILS GRID ══ --}}
<div class="row g-3 mb-3">

    {{-- Applicant Information --}}
    <div class="col-lg-6">
        <div class="pr-detail-card h-100">
            <div class="pr-section-head">
                <div class="pr-section-icon"><i class="fas fa-user"></i></div>
                <span class="pr-section-title">Applicant Information</span>
            </div>
            <div class="pr-detail-body">

                <div class="pr-detail-field">
                    <div class="pr-detail-label"><i class="fas fa-user-circle"></i> Applicant Name</div>
                    <div class="pr-detail-value">{{ $application->applicant_name }}</div>
                </div>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="pr-detail-field">
                            <div class="pr-detail-label"><i class="fas fa-birthday-cake"></i> Age</div>
                            <div class="pr-detail-value">{{ $application->age }}</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="pr-detail-field">
                            <div class="pr-detail-label"><i class="fas fa-phone"></i> Contact Number</div>
                            <div class="pr-detail-value">{{ $application->contact_number }}</div>
                        </div>
                    </div>
                </div>

                <div class="pr-detail-field">
                    <div class="pr-detail-label"><i class="fas fa-map-marker-alt"></i> Address</div>
                    <div class="pr-detail-value">{{ $application->address }}</div>
                </div>

            </div>
        </div>
    </div>

    {{-- Case Information --}}
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
                            <div class="pr-detail-label"><i class="fas fa-folder"></i> Case Type</div>
                            <div class="pr-detail-value">{{ $application->case_type }}</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="pr-detail-field">
                            <div class="pr-detail-label"><i class="fas fa-tag"></i> Case Category</div>
                            <div class="pr-detail-value">{{ $application->case_category }}</div>
                        </div>
                    </div>
                </div>

                <div class="pr-detail-field">
                    <div class="pr-detail-label"><i class="fas fa-user-tie"></i> Claimant Name</div>
                    <div class="pr-detail-value">{{ $application->claimant_name }}</div>
                </div>

                <div class="pr-detail-field">
                    <div class="pr-detail-label"><i class="fas fa-barcode"></i> Tracking Number</div>
                    <div style="margin-top:2px;">
                        <span class="pr-tracking-chip">
                            <i class="fas fa-qrcode"></i>
                            {{ $application->tracking_number }}
                        </span>
                    </div>
                </div>

                <div class="pr-detail-field">
                    <div class="pr-detail-label"><i class="fas fa-calendar-alt"></i> Date Applied</div>
                    <div class="pr-detail-value">
                        {{ $application->created_at->format('F j, Y g:i A') }}
                    </div>
                </div>

                @if($application->transferred_at)
                <div class="pr-detail-field">
                    <div class="pr-detail-label"><i class="fas fa-exchange-alt"></i> Date Transferred</div>
                    <div class="pr-detail-value">
                        {{ \Carbon\Carbon::parse($application->transferred_at)->format('F j, Y g:i A') }}
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

{{-- ── Diagnosis full-width ── --}}
@if($application->diagnosis)
<div class="pr-detail-card mb-3">
    <div class="pr-section-head">
        <div class="pr-section-icon"><i class="fas fa-notes-medical"></i></div>
        <span class="pr-section-title">Diagnosis / Medical Condition</span>
    </div>
    <div class="pr-detail-body">
        <div class="pr-detail-value diagnosis-val">
            @if(strlen($application->diagnosis) > 300)
                {{ Str::limit($application->diagnosis, 300) }}
                <button type="button" style="
                    display:inline-flex;align-items:center;gap:5px;
                    background:var(--pr-lime-ghost);border:1px solid var(--pr-lime-border);
                    border-radius:6px;padding:2px 10px;font-size:.72rem;font-weight:700;
                    color:var(--pr-forest);cursor:pointer;margin-left:6px;font-family:'DM Sans',sans-serif;
                    transition:background .18s;" data-bs-toggle="modal" data-bs-target="#diagnosisModal">
                    <i class="fas fa-expand-alt" style="font-size:.65rem;"></i> Read full
                </button>
            @else
                {{ $application->diagnosis }}
            @endif
        </div>
    </div>
</div>
@endif

{{-- ══ ACTION FOOTER ══ --}}
<div class="pr-action-footer">
    <div class="pr-action-footer-left">
        <a href="{{ route('admin.online-applications.index') }}" class="pr-btn-secondary">
            <i class="fas fa-arrow-left" style="font-size:.78rem;"></i> Back to List
        </a>
    </div>
    <div class="pr-action-footer-right">
        @if(!$application->transferred_at)
            <button type="button" id="showTransferModalBtn" class="pr-btn-success">
                <i class="fas fa-exchange-alt" style="font-size:.78rem;"></i> Confirm & Transfer
            </button>
        @else
            <span class="pr-btn-secondary" style="opacity:0.7;cursor:not-allowed;">
                <i class="fas fa-check-circle" style="font-size:.78rem;"></i> Already Transferred
            </span>
        @endif
    </div>
</div>

</div>{{-- /pr-page --}}

{{-- ══ TRANSFER CONFIRMATION MODAL ══ --}}
@if(!$application->transferred_at)
<div class="modal fade pr-modal" id="transferConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title">
                    <i class="fas fa-exchange-alt me-2"></i>
                    Confirm Transfer to Patient Records
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>You are about to transfer this online application to patient records. This action will:</p>
                
                <div class="transfer-info">
                    <div class="transfer-info-item">
                        <span class="transfer-info-label">Application ID</span>
                        <span class="transfer-info-value">#{{ $application->id }}</span>
                    </div>
                    <div class="transfer-info-item">
                        <span class="transfer-info-label">Applicant</span>
                        <span class="transfer-info-value">{{ $application->applicant_name }}</span>
                    </div>
                    <div class="transfer-info-item">
                        <span class="transfer-info-label">Claimant</span>
                        <span class="transfer-info-value">{{ $application->claimant_name }}</span>
                    </div>
                    <div class="transfer-info-item">
                        <span class="transfer-info-label">Tracking #</span>
                        <span class="transfer-info-value">{{ $application->tracking_number }}</span>
                    </div>
                    <div class="transfer-info-item">
                        <span class="transfer-info-label">Case Type</span>
                        <span class="transfer-info-value">{{ $application->case_type }}</span>
                    </div>
                    <div class="transfer-info-item">
                        <span class="transfer-info-label">Case Category</span>
                        <span class="transfer-info-value">{{ $application->case_category }}</span>
                    </div>
                </div>
                
                <div class="transfer-warning">
                    <i class="fas fa-info-circle flex-shrink-0"></i>
                    <div>
                        <strong>Please confirm:</strong> A new patient record will be created with a control number, 
                        and this application will be removed from the online applications list.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <form id="confirmTransferForm" action="{{ route('admin.applications.confirm', $application->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" id="confirmTransferBtn" class="btn btn-success">
                        <i class="fas fa-exchange-alt me-1"></i> Yes, Transfer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ══ DIAGNOSIS MODAL ══ --}}
@if($application->diagnosis && strlen($application->diagnosis) > 300)
<div class="modal fade pr-modal" id="diagnosisModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title"><i class="fas fa-notes-medical me-2"></i>Full Diagnosis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">{{ $application->diagnosis }}</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
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
document.addEventListener('DOMContentLoaded', function() {
    // Transfer modal handling
    const showTransferModalBtn = document.getElementById('showTransferModalBtn');
    const transferModal = document.getElementById('transferConfirmModal');
    const confirmTransferForm = document.getElementById('confirmTransferForm');
    const confirmTransferBtn = document.getElementById('confirmTransferBtn');
    
    if (showTransferModalBtn && transferModal) {
        const modal = new bootstrap.Modal(transferModal);
        
        showTransferModalBtn.addEventListener('click', function() {
            modal.show();
        });
    }
    
    // Form submission with loading state
    if (confirmTransferForm && confirmTransferBtn) {
        confirmTransferForm.addEventListener('submit', function(e) {
            // Prevent double submission
            if (confirmTransferBtn.disabled) {
                e.preventDefault();
                e.stopPropagation();
                return;
            }
            
            // Disable button immediately
            confirmTransferBtn.disabled = true;
            confirmTransferBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Transferring...';
            
            // Also disable cancel button
            const cancelBtn = document.querySelector('#transferConfirmModal .btn-secondary');
            if (cancelBtn) {
                cancelBtn.disabled = true;
                cancelBtn.style.opacity = '0.5';
                cancelBtn.style.cursor = 'not-allowed';
            }
            
            // Show processing toast
            const toast = document.createElement('div');
            toast.className = 'position-fixed top-0 end-0 p-3';
            toast.style.zIndex = '9999';
            toast.innerHTML = `
                <div class="alert alert-info alert-dismissible fade show" role="alert" style="background:var(--pr-surface2);border:1px solid var(--pr-lime-border);color:var(--pr-forest);border-radius:10px;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-spinner fa-spin me-2" style="color:var(--pr-lime-dim);"></i>
                        <div>
                            <strong>Processing Transfer</strong><br>
                            <small style="color:var(--pr-sub);">Creating patient record and transferring application...</small>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(toast);
            
            // Remove the beforeunload event listener to prevent the warning
            window.removeEventListener('beforeunload', handleBeforeUnload);
            
            // Submit the form
            confirmTransferForm.submit();
        });
    }
    
    // Toast handling for any existing toasts
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
    
    // Function to handle beforeunload event
    function handleBeforeUnload(e) {
        e.preventDefault();
        e.returnValue = 'Transfer is in progress. Are you sure you want to leave?';
        return e.returnValue;
    }
});
</script>
@endsection