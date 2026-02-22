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
.pr-hero-sub   { font-size: .78rem; color: rgba(255,255,255,.55); font-weight: 400; }

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
    padding: 16px 24px 14px; border-bottom: 1px solid var(--pr-border);
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

.pr-label {
    display: block; font-size: .72rem; font-weight: 700;
    letter-spacing: .05em; text-transform: uppercase; color: var(--pr-sub); margin-bottom: 5px;
}
.pr-label .req { color: var(--pr-danger); margin-left: 2px; }
.pr-hint { font-size: .70rem; color: var(--pr-sub); margin-top: 4px; display: flex; align-items: center; gap: 4px; }

.pr-input {
    width: 100%; border: 1.5px solid var(--pr-border-dark); border-radius: 8px;
    padding: 9px 13px; font-size: .83rem; font-family: 'DM Sans', sans-serif;
    color: var(--pr-text); background: var(--pr-surface); transition: border-color .2s, box-shadow .2s;
}
.pr-input:focus { outline: none; border-color: var(--pr-forest-mid); box-shadow: 0 0 0 3px rgba(6,78,59,.11); }
.pr-input::placeholder { color: var(--pr-border-dark); }
.pr-input.is-invalid { border-color: var(--pr-danger) !important; box-shadow: 0 0 0 3px rgba(239,68,68,.10) !important; }
.pr-error { font-size: .73rem; color: var(--pr-danger); margin-top: 4px; font-weight: 500; }

.pr-form-footer {
    display: flex; align-items: center; gap: 10px;
    padding: 16px 24px; border-top: 1px solid var(--pr-border); background: var(--pr-surface2);
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
            <div class="pr-hero-icon"><i class="fas fa-key"></i></div>
            <div>
                <div class="pr-hero-title">{{ trans('global.create') }} {{ trans('cruds.permission.title_singular') }}</div>
                <div class="pr-hero-sub">{{ trans('cruds.permission.fields.title_helper') }}</div>
            </div>
        </div>
        <a href="{{ route('admin.permissions.index') }}" class="pr-btn-ghost">
            <i class="fas fa-arrow-left" style="font-size:.75rem;"></i> Back to List
        </a>
    </div>
</div>

{{-- ══ FORM ══ --}}
<form method="POST" action="{{ route('admin.permissions.store') }}" enctype="multipart/form-data" id="createPermForm">
    @csrf

    <div class="pr-form-card">
        <div class="pr-section-head">
            <div class="pr-section-icon"><i class="fas fa-lock"></i></div>
            <span class="pr-section-title">Permission Details</span>
        </div>
        <div class="pr-form-body">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="pr-label" for="title">
                        {{ trans('cruds.permission.fields.title') }} <span class="req">*</span>
                    </label>
                    <input
                        type="text"
                        name="title"
                        id="title"
                        class="pr-input {{ $errors->has('title') ? 'is-invalid' : '' }}"
                        value="{{ old('title', '') }}"
                        required
                        placeholder="e.g. patient_record_create"
                        autofocus>
                    <div class="pr-hint"><i class="fas fa-info-circle"></i> Use snake_case naming (e.g. module_action).</div>
                    @error('title')
                        <div class="pr-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="pr-form-footer">
            <button type="submit" class="pr-btn-save" id="saveBtn">
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

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form    = document.getElementById('createPermForm');
    const saveBtn = document.getElementById('saveBtn');
    if (form && saveBtn) {
        form.addEventListener('submit', function () {
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving…';
        });
    }
    const firstInvalid = document.querySelector('.pr-input.is-invalid');
    if (firstInvalid) firstInvalid.focus();
});
</script>
@endsection