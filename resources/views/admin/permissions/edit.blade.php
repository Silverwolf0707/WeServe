@extends('layouts.admin')
@section('content')

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

<style>
:root {
    --pr-forest:        #064e3b;
    --pr-forest-deep:   #052e22;
    --pr-forest-mid:    #065f46;
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
    --pr-danger:        #ef4444;
    --pr-radius:        12px;
    --pr-shadow:        0 2px 8px rgba(6,78,59,.08), 0 8px 24px rgba(6,78,59,.06);
    --pr-shadow-lg:     0 4px 24px rgba(6,78,59,.16), 0 16px 48px rgba(6,78,59,.10);
    --pr-shadow-lime:   0 2px 12px rgba(116,255,112,.25);
}
.pr-page { font-family: 'DM Sans', sans-serif; color: var(--pr-text); padding: 0 0 2rem; }

.pr-hero {
    background: linear-gradient(135deg, #052e22 0%, #064e3b 55%, #065f46 100%);
    border-radius: var(--pr-radius); padding: 22px 28px; margin-bottom: 20px;
    position: relative; overflow: hidden; box-shadow: var(--pr-shadow-lg);
}
.pr-hero::before {
    content: ''; position: absolute; inset: 0; border-radius: var(--pr-radius);
    background:
        radial-gradient(ellipse 380px 200px at 95% 50%, rgba(116,255,112,.13) 0%, transparent 65%),
        radial-gradient(ellipse 180px 100px at 5%  80%, rgba(116,255,112,.07) 0%, transparent 70%);
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
    width: 46px; height: 46px; background: rgba(116,255,112,.12);
    border: 1px solid rgba(116,255,112,.30); border-radius: 11px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.15rem; color: var(--pr-lime); backdrop-filter: blur(4px); flex-shrink: 0;
}
.pr-hero-title { font-size: 1.18rem; font-weight: 700; color: #fff; letter-spacing: -.01em; margin: 0 0 3px; }
.pr-hero-badge {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(116,255,112,.14); border: 1px solid rgba(116,255,112,.32);
    border-radius: 20px; padding: 2px 10px; font-size: .72rem; font-weight: 600; color: var(--pr-lime);
}
.pr-hero-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

.pr-btn-ghost {
    display: inline-flex; align-items: center; gap: 7px;
    background: rgba(255,255,255,.08); color: rgba(255,255,255,.82);
    border: 1px solid rgba(255,255,255,.18); border-radius: 8px;
    padding: 8px 16px; font-size: .82rem; font-weight: 500; font-family: 'DM Sans', sans-serif;
    text-decoration: none; cursor: pointer; transition: all .18s; white-space: nowrap;
}
.pr-btn-ghost:hover { background: rgba(116,255,112,.12); border-color: var(--pr-lime-border); color: var(--pr-lime); }

/* form card */
.pr-form-card {
    background: var(--pr-surface); border-radius: var(--pr-radius);
    border: 1px solid var(--pr-border); box-shadow: var(--pr-shadow); overflow: hidden;
}
.pr-section-head {
    display: flex; align-items: center; gap: 10px;
    padding: 16px 24px 14px; border-bottom: 1px solid var(--pr-border); background: var(--pr-surface2);
}
.pr-section-icon {
    width: 30px; height: 30px; border-radius: 8px; flex-shrink: 0;
    background: var(--pr-lime-ghost); border: 1px solid var(--pr-lime-border);
    display: flex; align-items: center; justify-content: center;
    color: var(--pr-forest); font-size: .78rem;
}
.pr-section-title { font-size: .82rem; font-weight: 700; color: var(--pr-forest); letter-spacing: .04em; text-transform: uppercase; }
.pr-form-body { padding: 22px 24px; }

.pr-label {
    display: block; font-size: .72rem; font-weight: 700;
    letter-spacing: .05em; text-transform: uppercase; color: var(--pr-sub); margin-bottom: 5px;
}
.pr-label .req { color: var(--pr-danger); margin-left: 2px; }
.pr-auto-chip {
    display: inline-flex; align-items: center; gap: 4px;
    background: var(--pr-lime-ghost); border: 1px solid var(--pr-lime-border);
    border-radius: 20px; padding: 1px 8px; font-size: .68rem; font-weight: 600;
    color: var(--pr-forest); margin-left: 6px; vertical-align: middle;
}
.pr-hint { font-size: .70rem; color: var(--pr-sub); margin-top: 4px; display: flex; align-items: center; gap: 4px; }
.pr-input {
    width: 100%; border: 1.5px solid var(--pr-border-dark); border-radius: 8px;
    padding: 9px 13px; font-size: .83rem; font-family: 'DM Sans', sans-serif;
    color: var(--pr-text); background: var(--pr-surface); transition: border-color .2s, box-shadow .2s;
}
.pr-input:focus { outline: none; border-color: var(--pr-forest-mid); box-shadow: 0 0 0 3px rgba(6,78,59,.11); }
.pr-input::placeholder { color: var(--pr-border-dark); }
.pr-input.is-invalid { border-color: var(--pr-danger) !important; box-shadow: 0 0 0 3px rgba(239,68,68,.10) !important; }
.pr-input[readonly] { background: var(--pr-surface2) !important; color: var(--pr-sub) !important; border-color: var(--pr-border) !important; cursor: default; }
.pr-error { font-size: .73rem; color: var(--pr-danger); margin-top: 4px; font-weight: 500; }

.pr-form-footer {
    display: flex; align-items: center; gap: 10px;
    padding: 16px 24px; border-top: 1px solid var(--pr-border); background: var(--pr-surface2); flex-wrap: wrap;
}
.pr-btn-save {
    display: inline-flex; align-items: center; gap: 7px;
    background: var(--pr-forest); color: var(--pr-lime); border: none;
    border-radius: 8px; padding: 9px 22px; font-size: .84rem; font-weight: 700;
    font-family: 'DM Sans', sans-serif; cursor: pointer;
    transition: background .18s, transform .15s; box-shadow: 0 2px 8px rgba(6,78,59,.25);
}
.pr-btn-save:hover { background: var(--pr-forest-mid); transform: translateY(-1px); }
.pr-btn-save:disabled { opacity: .6; cursor: not-allowed; transform: none; }
.pr-btn-back {
    display: inline-flex; align-items: center; gap: 7px;
    background: var(--pr-muted); color: var(--pr-sub);
    border: 1px solid var(--pr-border-dark); border-radius: 8px;
    padding: 9px 18px; font-size: .84rem; font-weight: 600; font-family: 'DM Sans', sans-serif;
    text-decoration: none; cursor: pointer; transition: background .18s;
}
.pr-btn-back:hover { background: var(--pr-border-dark); color: var(--pr-text); }

/* confirmation modal */
.pr-modal .modal-content { border: none; border-radius: 14px; overflow: hidden; box-shadow: var(--pr-shadow-lg); font-family: 'DM Sans', sans-serif; }
.pr-modal .modal-header {
    padding: 18px 22px 16px; border-bottom: 1px solid var(--pr-border);
    background: linear-gradient(135deg, #78350f 0%, #92400e 100%);
}
.pr-modal .modal-title { color: #fef3c7; font-size: .95rem; font-weight: 700; }
.pr-modal .modal-header .btn-close { filter: invert(1) brightness(1.5); }
.pr-modal .modal-body { padding: 20px 22px; font-size: .85rem; color: var(--pr-text); line-height: 1.6; }
.pr-modal .modal-footer { padding: 14px 22px; border-top: 1px solid var(--pr-border); gap: 8px; background: var(--pr-surface2); }
.pr-modal .modal-footer .btn { border-radius: 8px; font-size: .82rem; font-family: 'DM Sans', sans-serif; font-weight: 600; padding: 7px 18px; border: none; background-image: none !important; transition: opacity .18s, transform .15s; }
.pr-modal .modal-footer .btn:hover { opacity: .88; transform: translateY(-1px); }
.pr-modal .modal-footer .btn-secondary { background: var(--pr-muted) !important; color: var(--pr-sub) !important; border: 1px solid var(--pr-border-dark) !important; }
.pr-modal .modal-footer .btn-primary   { background: var(--pr-forest) !important; color: var(--pr-lime) !important; box-shadow: 0 2px 8px rgba(6,78,59,.25) !important; }

/* diff view */
.pr-modal-info {
    display: flex; align-items: center; gap: 8px;
    background: var(--pr-lime-ghost); border: 1px solid var(--pr-lime-border);
    border-radius: 8px; padding: 10px 14px; font-size: .8rem; color: var(--pr-forest); font-weight: 500; margin-bottom: 16px;
}
.pr-diff-item {
    background: var(--pr-surface2); border-radius: 8px; border-left: 3px solid var(--pr-forest);
    padding: 10px 14px; margin-bottom: 8px;
}
.pr-diff-field { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; color: var(--pr-sub); margin-bottom: 6px; }
.pr-diff-old { font-size: .8rem; color: var(--pr-danger); background: rgba(239,68,68,.08); border-radius: 4px; padding: 2px 7px; text-decoration: line-through; }
.pr-diff-arrow { color: var(--pr-sub); font-size: .7rem; margin: 0 6px; }
.pr-diff-new { font-size: .8rem; color: var(--pr-forest); background: var(--pr-lime-ghost); border-radius: 4px; padding: 2px 7px; font-weight: 600; }
.pr-no-changes { display: flex; flex-direction: column; align-items: center; gap: 8px; padding: 28px; text-align: center; color: var(--pr-sub); }
.pr-no-changes i { font-size: 2rem; color: var(--pr-lime-dim); }
.pr-no-changes strong { color: var(--pr-forest); font-size: .9rem; }

@media (max-width: 768px) {
    .pr-hero-inner { flex-direction: column; align-items: flex-start; }
    .pr-hero { padding: 16px 18px; }
    .pr-form-body { padding: 16px; }
}
</style>

<div class="pr-page">

{{-- ══ HERO ══ --}}
<div class="pr-hero">
    <div class="pr-hero-inner">
        <div class="pr-hero-left">
            <div class="pr-hero-icon"><i class="fas fa-edit"></i></div>
            <div>
                <div class="pr-hero-title">{{ trans('global.edit') }} {{ trans('cruds.permission.title_singular') }}</div>
                <div style="margin-top:4px;display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                    <span class="pr-hero-badge">
                        <i class="fas fa-hashtag" style="font-size:.6rem;"></i> ID: {{ $permission->id }}
                    </span>
                    <span style="color:rgba(255,255,255,.35);font-size:.72rem;">·</span>
                    <span style="color:rgba(255,255,255,.55);font-size:.75rem;">{{ $permission->title }}</span>
                </div>
            </div>
        </div>
        <div class="pr-hero-actions">
            <a href="{{ route('admin.permissions.show', $permission->id) }}" class="pr-btn-ghost">
                <i class="fas fa-eye" style="font-size:.75rem;"></i> View
            </a>
            <a href="{{ route('admin.permissions.index') }}" class="pr-btn-ghost">
                <i class="fas fa-arrow-left" style="font-size:.75rem;"></i> Back to List
            </a>
        </div>
    </div>
</div>

{{-- ══ FORM ══ --}}
<form method="POST" action="{{ route('admin.permissions.update', [$permission->id]) }}"
    enctype="multipart/form-data" id="editPermForm">
    @method('PUT')
    @csrf

    <div class="pr-form-card">
        <div class="pr-section-head">
            <div class="pr-section-icon"><i class="fas fa-lock"></i></div>
            <span class="pr-section-title">Permission Details</span>
        </div>
        <div class="pr-form-body">
            <div class="row g-3">

                {{-- ID (readonly) --}}
                <div class="col-md-2">
                    <label class="pr-label">
                        {{ trans('cruds.permission.fields.id') }}
                        <span class="pr-auto-chip"><i class="fas fa-lock" style="font-size:.6rem;"></i> Locked</span>
                    </label>
                    <input type="text" class="pr-input" value="{{ $permission->id }}" readonly>
                    <div class="pr-hint"><i class="fas fa-info-circle"></i> Auto-assigned ID.</div>
                </div>

                {{-- Title --}}
                <div class="col-md-6">
                    <label class="pr-label" for="title">
                        {{ trans('cruds.permission.fields.title') }} <span class="req">*</span>
                    </label>
                    <input
                        type="text"
                        name="title"
                        id="title"
                        class="pr-input {{ $errors->has('title') ? 'is-invalid' : '' }}"
                        value="{{ old('title', $permission->title) }}"
                        required
                        placeholder="e.g. patient_record_create">
                    <div class="pr-hint"><i class="fas fa-info-circle"></i> Use snake_case naming (e.g. module_action).</div>
                    @error('title')
                        <div class="pr-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>

        <div class="pr-form-footer">
            <button type="button" class="pr-btn-save" id="saveButton">
                <i class="fas fa-save"></i> {{ trans('global.save') }}
            </button>
            <a href="{{ route('admin.permissions.index') }}" class="pr-btn-back">
                <i class="fas fa-times"></i> {{ trans('global.back_to_list') }}
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
<div class="modal fade pr-modal" id="confirmEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Changes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="pr-modal-info">
                    <i class="fas fa-info-circle" style="flex-shrink:0;"></i>
                    Only changed fields are shown below. Review before saving.
                </div>
                <div id="permChangesContainer"></div>
                <div id="permNoChanges" class="pr-no-changes" style="display:none;">
                    <i class="fas fa-check-circle"></i>
                    <strong>No changes detected</strong>
                    <span style="font-size:.8rem;">The title is identical to the current value.</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <button type="button" class="btn btn-primary" id="confirmSaveBtn">
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

    const originalTitle = '{{ addslashes($permission->title) }}';
    const form          = document.getElementById('editPermForm');
    const saveButton    = document.getElementById('saveButton');
    const confirmBtn    = document.getElementById('confirmSaveBtn');
    const modal         = new bootstrap.Modal(document.getElementById('confirmEditModal'));
    const container     = document.getElementById('permChangesContainer');
    const noChanges     = document.getElementById('permNoChanges');

    function escHtml(s) {
        return String(s)
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function buildDiff() {
        const curr = document.getElementById('title')?.value ?? '';
        if (originalTitle === curr) return '';
        return `
            <div class="pr-diff-item">
                <div class="pr-diff-field"><i class="fas fa-key me-1"></i> Title</div>
                <span class="pr-diff-old">${escHtml(originalTitle) || '<em>empty</em>'}</span>
                <span class="pr-diff-arrow"><i class="fas fa-arrow-right"></i></span>
                <span class="pr-diff-new">${escHtml(curr) || '<em>empty</em>'}</span>
            </div>`;
    }

    saveButton.addEventListener('click', function () {
        const diff = buildDiff();
        if (!diff) {
            container.style.display  = 'none';
            noChanges.style.display  = 'flex';
        } else {
            noChanges.style.display  = 'none';
            container.style.display  = 'block';
            container.innerHTML      = diff;
        }
        modal.show();
    });

    confirmBtn.addEventListener('click', function () {
        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving…';
        saveButton.disabled = true;
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving…';
        modal.hide();
        form.submit();
    });

    document.getElementById('confirmEditModal').addEventListener('hidden.bs.modal', function () {
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = '<i class="fas fa-check me-1"></i> Yes, Save Changes';
        if (!form.dataset.submitted) {
            saveButton.disabled = false;
            saveButton.innerHTML = '<i class="fas fa-save"></i> {{ trans('global.save') }}';
        }
    });

    form.addEventListener('submit', function () { this.dataset.submitted = 'true'; });

    const firstInvalid = document.querySelector('.pr-input.is-invalid');
    if (firstInvalid) firstInvalid.focus();
});
</script>
@endsection