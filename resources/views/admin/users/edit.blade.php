@extends('layouts.admin')
@section('content')

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

<style>
:root {
    --pr-forest:      #064e3b; --pr-forest-mid: #065f46;
    --pr-lime:        #74ff70; --pr-lime-dim: #52e84e;
    --pr-lime-ghost:  rgba(116,255,112,.10); --pr-lime-border: rgba(116,255,112,.30);
    --pr-surface:     #ffffff; --pr-surface2: #f0fdf4; --pr-muted: #ecfdf5;
    --pr-border:      #d1fae5; --pr-border-dark: #a7f3d0;
    --pr-text:        #052e22; --pr-sub: #3d7a62; --pr-danger: #ef4444;
    --pr-radius: 12px;
    --pr-shadow:   0 2px 8px rgba(6,78,59,.08), 0 8px 24px rgba(6,78,59,.06);
    --pr-shadow-lg: 0 4px 24px rgba(6,78,59,.16), 0 16px 48px rgba(6,78,59,.10);
    --pr-shadow-lime: 0 2px 12px rgba(116,255,112,.25);
}
.pr-page { font-family: 'DM Sans', sans-serif; color: var(--pr-text); padding: 0 0 2rem; }
.pr-hero { background: linear-gradient(135deg, #052e22 0%, #064e3b 55%, #065f46 100%); border-radius: var(--pr-radius); padding: 22px 28px; margin-bottom: 20px; position: relative; overflow: hidden; box-shadow: var(--pr-shadow-lg); }
.pr-hero::before { content: ''; position: absolute; inset: 0; border-radius: var(--pr-radius); background: radial-gradient(ellipse 380px 200px at 95% 50%, rgba(116,255,112,.13) 0%, transparent 65%), radial-gradient(ellipse 180px 100px at 5% 80%, rgba(116,255,112,.07) 0%, transparent 70%); pointer-events: none; z-index: 0; }
.pr-hero::after { content: ''; position: absolute; top: 0; left: 28px; right: 28px; height: 2px; background: linear-gradient(to right, transparent, var(--pr-lime), transparent); border-radius: 2px; opacity: .55; }
.pr-hero-inner { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; position: relative; z-index: 1; }
.pr-hero-left  { display: flex; align-items: center; gap: 16px; }
.pr-hero-icon  { width: 46px; height: 46px; background: rgba(116,255,112,.12); border: 1px solid rgba(116,255,112,.30); border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; color: var(--pr-lime); flex-shrink: 0; }
.pr-hero-title { font-size: 1.18rem; font-weight: 700; color: #fff; letter-spacing: -.01em; margin: 0 0 3px; line-height: 1.2; }
.pr-hero-badge { display: inline-flex; align-items: center; gap: 5px; background: rgba(116,255,112,.14); border: 1px solid rgba(116,255,112,.32); border-radius: 20px; padding: 2px 10px; font-size: .72rem; font-weight: 600; color: var(--pr-lime); }
.pr-hero-actions { display: flex; gap: 10px; flex-wrap: wrap; }
.pr-btn-ghost { display: inline-flex; align-items: center; gap: 7px; background: rgba(255,255,255,.08); color: rgba(255,255,255,.82); border: 1px solid rgba(255,255,255,.18); border-radius: 8px; padding: 8px 16px; font-size: .82rem; font-weight: 500; font-family: 'DM Sans', sans-serif; text-decoration: none; transition: all .18s; white-space: nowrap; }
.pr-btn-ghost:hover { background: rgba(116,255,112,.12); border-color: var(--pr-lime-border); color: var(--pr-lime); }

.pr-form-card { background: var(--pr-surface); border-radius: var(--pr-radius); border: 1px solid var(--pr-border); box-shadow: var(--pr-shadow); overflow: hidden; }
.pr-section-head { display: flex; align-items: center; gap: 10px; padding: 16px 24px 14px; border-bottom: 1px solid var(--pr-border); background: var(--pr-surface2); }
.pr-section-icon { width: 30px; height: 30px; border-radius: 8px; flex-shrink: 0; background: var(--pr-lime-ghost); border: 1px solid var(--pr-lime-border); display: flex; align-items: center; justify-content: center; color: var(--pr-forest); font-size: .78rem; }
.pr-section-title { font-size: .82rem; font-weight: 700; color: var(--pr-forest); letter-spacing: .04em; text-transform: uppercase; }
.pr-form-body { padding: 22px 24px; }
.pr-form-body + .pr-section-head { border-top: 1px solid var(--pr-border); }

.pr-label { display: block; font-size: .72rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: var(--pr-sub); margin-bottom: 5px; }
.pr-label .req { color: var(--pr-danger); margin-left: 2px; }
.pr-auto-chip { display: inline-flex; align-items: center; gap: 4px; background: var(--pr-lime-ghost); border: 1px solid var(--pr-lime-border); border-radius: 20px; padding: 1px 8px; font-size: .68rem; font-weight: 600; color: var(--pr-forest); margin-left: 6px; vertical-align: middle; }
.pr-hint { font-size: .70rem; color: var(--pr-sub); margin-top: 4px; display: flex; align-items: center; gap: 4px; }
.pr-input, .pr-select { width: 100%; border: 1.5px solid var(--pr-border-dark); border-radius: 8px; padding: 9px 13px; font-size: .83rem; font-family: 'DM Sans', sans-serif; color: var(--pr-text); background: var(--pr-surface); transition: border-color .2s, box-shadow .2s; }
.pr-input:focus, .pr-select:focus { outline: none; border-color: var(--pr-forest-mid); box-shadow: 0 0 0 3px rgba(6,78,59,.11); }
.pr-input::placeholder { color: var(--pr-border-dark); }
.pr-input.is-invalid, .pr-select.is-invalid { border-color: var(--pr-danger) !important; box-shadow: 0 0 0 3px rgba(239,68,68,.10) !important; }
.pr-error { font-size: .73rem; color: var(--pr-danger); margin-top: 4px; font-weight: 500; }
.pr-select { appearance: none; -webkit-appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%233d7a62'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; padding-right: 32px; }
.pr-input-group { display: flex; }
.pr-input-group .pr-input { border-radius: 8px 0 0 8px; border-right: none; flex: 1; }
.pr-toggle-btn { display: flex; align-items: center; justify-content: center; width: 42px; border: 1.5px solid var(--pr-border-dark); border-left: none; border-radius: 0 8px 8px 0; background: var(--pr-surface2); color: var(--pr-sub); cursor: pointer; transition: background .15s; font-size: .78rem; flex-shrink: 0; }
.pr-toggle-btn:hover { background: var(--pr-muted); color: var(--pr-forest); }

.pr-perm-toolbar { display: flex; gap: 6px; margin-bottom: 10px; flex-wrap: wrap; }
.pr-perm-btn { display: inline-flex; align-items: center; gap: 5px; border: 1.5px solid var(--pr-border-dark); border-radius: 7px; padding: 5px 13px; font-size: .75rem; font-weight: 600; font-family: 'DM Sans', sans-serif; cursor: pointer; transition: all .18s; background: var(--pr-surface); color: var(--pr-sub); }
.pr-perm-btn.select   { border-color: var(--pr-forest); color: var(--pr-forest); }
.pr-perm-btn.select:hover   { background: var(--pr-lime-ghost); }
.pr-perm-btn.deselect { border-color: var(--pr-danger); color: var(--pr-danger); }
.pr-perm-btn.deselect:hover { background: rgba(239,68,68,.06); }
.pr-perm-count { display: inline-flex; align-items: center; gap: 4px; background: var(--pr-lime-ghost); border: 1px solid var(--pr-lime-border); border-radius: 20px; padding: 2px 10px; font-size: .71rem; font-weight: 600; color: var(--pr-forest); margin-left: 8px; vertical-align: middle; }

.select2-container--default .select2-selection--multiple { border: 1.5px solid var(--pr-border-dark) !important; border-radius: 8px !important; padding: 5px 8px !important; min-height: 44px !important; font-family: 'DM Sans', sans-serif; background: var(--pr-surface) !important; }
.select2-container--default.select2-container--focus .select2-selection--multiple { border-color: var(--pr-forest-mid) !important; box-shadow: 0 0 0 3px rgba(6,78,59,.11) !important; }
.select2-container--default .select2-selection--multiple .select2-selection__choice { background: var(--pr-lime-ghost) !important; border: 1px solid var(--pr-lime-border) !important; border-radius: 6px !important; color: var(--pr-forest) !important; font-size: .74rem !important; font-weight: 600 !important; padding: 2px 8px !important; }
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove { color: var(--pr-forest) !important; margin-right: 4px !important; font-weight: 700 !important; }
.select2-dropdown { border: 1.5px solid var(--pr-border-dark) !important; border-radius: 10px !important; box-shadow: var(--pr-shadow-lg) !important; font-family: 'DM Sans', sans-serif; }
.select2-container--default .select2-results__option--highlighted { background: var(--pr-lime-ghost) !important; color: var(--pr-forest) !important; }
.select2-container--default .select2-results__option[aria-selected=true] { background: var(--pr-muted) !important; color: var(--pr-forest) !important; font-weight: 600; }
.select2-search--dropdown .select2-search__field { border: 1.5px solid var(--pr-border-dark) !important; border-radius: 7px !important; font-family: 'DM Sans', sans-serif !important; padding: 6px 10px !important; }

.pr-form-footer { display: flex; align-items: center; gap: 10px; padding: 16px 24px; border-top: 1px solid var(--pr-border); background: var(--pr-surface2); flex-wrap: wrap; }
.pr-btn-save { display: inline-flex; align-items: center; gap: 7px; background: var(--pr-forest); color: var(--pr-lime); border: none; border-radius: 8px; padding: 9px 22px; font-size: .84rem; font-weight: 700; font-family: 'DM Sans', sans-serif; cursor: pointer; transition: background .18s, transform .15s; box-shadow: 0 2px 8px rgba(6,78,59,.25); }
.pr-btn-save:hover { background: var(--pr-forest-mid); transform: translateY(-1px); }
.pr-btn-save:disabled { opacity: .6; cursor: not-allowed; transform: none; }
.pr-btn-back { display: inline-flex; align-items: center; gap: 7px; background: var(--pr-muted); color: var(--pr-sub); border: 1px solid var(--pr-border-dark); border-radius: 8px; padding: 9px 18px; font-size: .84rem; font-weight: 600; font-family: 'DM Sans', sans-serif; text-decoration: none; transition: background .18s; }
.pr-btn-back:hover { background: var(--pr-border-dark); color: var(--pr-text); }

@media (max-width: 768px) {
    .pr-hero-inner { flex-direction: column; align-items: flex-start; }
    .pr-hero { padding: 16px 18px; }
    .pr-form-body { padding: 16px; }
}
</style>

<div class="pr-page">
<div class="pr-hero">
    <div class="pr-hero-inner">
        <div class="pr-hero-left">
            <div class="pr-hero-icon"><i class="fas fa-user-edit"></i></div>
            <div>
                <div class="pr-hero-title">{{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }}</div>
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-top:3px;">
                    <span class="pr-hero-badge"><i class="fas fa-hashtag" style="font-size:.6rem;"></i> ID: {{ $user->id }}</span>
                    <span style="color:rgba(255,255,255,.4);font-size:.72rem;">·</span>
                    <span style="color:rgba(255,255,255,.55);font-size:.75rem;">{{ $user->name }}</span>
                </div>
            </div>
        </div>
        <div class="pr-hero-actions">
            <a href="{{ route('admin.users.show', $user->id) }}" class="pr-btn-ghost"><i class="fas fa-eye" style="font-size:.75rem;"></i> View</a>
            <a href="{{ route('admin.users.index') }}" class="pr-btn-ghost"><i class="fas fa-arrow-left" style="font-size:.75rem;"></i> Back to List</a>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('admin.users.update', [$user->id]) }}" enctype="multipart/form-data" id="editUserForm">
    @method('PUT')
    @csrf
    <div class="pr-form-card">

        <div class="pr-section-head">
            <div class="pr-section-icon"><i class="fas fa-user"></i></div>
            <span class="pr-section-title">Account Details</span>
        </div>
        <div class="pr-form-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="pr-label" for="name">{{ trans('cruds.user.fields.name') }} <span class="req">*</span></label>
                    <input type="text" name="name" id="name" class="pr-input {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name', $user->name) }}" required>
                    @error('name') <div class="pr-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="pr-label" for="email">{{ trans('cruds.user.fields.email') }} <span class="req">*</span></label>
                    <input type="email" name="email" id="email" class="pr-input {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email', $user->email) }}" required>
                    @error('email') <div class="pr-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="pr-label" for="password">
                        {{ trans('cruds.user.fields.password') }}
                        <span class="pr-auto-chip"><i class="fas fa-shield-alt" style="font-size:.6rem;"></i> Optional</span>
                    </label>
                    <div class="pr-input-group">
                        <input type="password" name="password" id="password" class="pr-input {{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="Leave blank to keep current password">
                        <button type="button" class="pr-toggle-btn" id="togglePasswordBtn" tabindex="-1">
                            <i class="fas fa-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                    <div class="pr-hint"><i class="fas fa-info-circle"></i> Leave blank to keep the current password.</div>
                    @error('password') <div class="pr-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                    <label class="pr-label" for="status">Status <span class="req">*</span></label>
                    <select name="status" id="status" class="pr-select {{ $errors->has('status') ? 'is-invalid' : '' }}" required>
                        <option value="active"    {{ old('status', $user->status) == 'active'    ? 'selected' : '' }}>Active</option>
                        <option value="inactive"  {{ old('status', $user->status) == 'inactive'  ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ old('status', $user->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                    @error('status') <div class="pr-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="pr-section-head">
            <div class="pr-section-icon"><i class="fas fa-user-tag"></i></div>
            <span class="pr-section-title">
                Roles
                <span class="pr-perm-count" id="roleCountBadge">0 selected</span>
            </span>
        </div>
        <div class="pr-form-body">
            <div class="pr-perm-toolbar">
                <button type="button" class="pr-perm-btn select" id="selectAllRoles"><i class="fas fa-check-double"></i> {{ trans('global.select_all') }}</button>
                <button type="button" class="pr-perm-btn deselect" id="deselectAllRoles"><i class="fas fa-times-circle"></i> {{ trans('global.deselect_all') }}</button>
            </div>
            <label class="pr-label" for="roles">{{ trans('cruds.user.fields.roles') }} <span class="req">*</span></label>
            <select name="roles[]" id="roles" class="form-select select2 {{ $errors->has('roles') ? 'is-invalid' : '' }}" multiple required>
                @foreach($roles as $id => $role)
                    <option value="{{ $id }}" {{ (in_array($id, old('roles', [])) || $user->roles->contains($id)) ? 'selected' : '' }}>{{ $role }}</option>
                @endforeach
            </select>
            @error('roles') <div class="pr-error"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div> @enderror
        </div>

        <div class="pr-form-footer">
            <button type="submit" class="pr-btn-save" id="saveBtn"><i class="fas fa-save"></i> {{ trans('global.save') }}</button>
            <a href="{{ route('admin.users.index') }}" class="pr-btn-back"><i class="fas fa-times"></i> {{ trans('global.cancel') }}</a>
            @if($errors->any())
                <span style="font-size:.75rem;color:var(--pr-danger);font-weight:600;margin-left:4px;">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    {{ $errors->count() }} field{{ $errors->count() > 1 ? 's' : '' }} need{{ $errors->count() === 1 ? 's' : '' }} attention
                </span>
            @endif
        </div>
    </div>
</form>
</div>
@endsection

@section('scripts')
@parent
<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#roles').select2({ width: '100%', placeholder: 'Search and select roles…', allowClear: true });
    function updateCount() { document.getElementById('roleCountBadge').textContent = `${$('#roles').val()?.length ?? 0} selected`; }
    $('#roles').on('change', updateCount);
    updateCount();
    document.getElementById('selectAllRoles').addEventListener('click', () => { $('#roles option').prop('selected', true); $('#roles').trigger('change'); });
    document.getElementById('deselectAllRoles').addEventListener('click', () => { $('#roles option').prop('selected', false); $('#roles').trigger('change'); });
    document.getElementById('togglePasswordBtn').addEventListener('click', function () {
        const input = document.getElementById('password');
        const icon  = document.getElementById('togglePasswordIcon');
        const isPass = input.type === 'password';
        input.type = isPass ? 'text' : 'password';
        icon.classList.toggle('fa-eye', !isPass);
        icon.classList.toggle('fa-eye-slash', isPass);
    });
    const form = document.getElementById('editUserForm');
    const btn  = document.getElementById('saveBtn');
    form.addEventListener('submit', () => { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving…'; });
    const fi = document.querySelector('.pr-input.is-invalid, .pr-select.is-invalid');
    if (fi) fi.focus();
});
</script>
@endsection