@extends('layouts.admin')
@section('content')

<div class="card shadow-lg border-0 rounded-3">
    <div class="card-header bg-success text-white d-flex align-items-center">
        <i class="fas fa-edit me-2"></i>
        <h5 class="mb-0">{{ trans('global.edit') }} {{ trans('cruds.role.title_singular') }}</h5>
    </div>

    <div class="card-body p-4">
        <form method="POST" action="{{ route("admin.roles.update", [$role->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf

            {{-- Role Title --}}
            <div class="mb-4">
                <label class="form-label fw-semibold required" for="title">
                    <i class="fas fa-id-badge me-1"></i> {{ trans('cruds.role.fields.title') }}
                </label>
                <input 
                    class="form-control form-control-lg {{ $errors->has('title') ? 'is-invalid' : '' }}" 
                    type="text" 
                    name="title" 
                    id="title" 
                    placeholder="Enter role name" 
                    value="{{ old('title', $role->title) }}" 
                    required
                >
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <small class="text-muted">{{ trans('cruds.role.fields.title_helper') }}</small>
            </div>

            {{-- Permissions --}}
            <div class="mb-4">
                <label class="form-label fw-semibold required" for="permissions">
                    <i class="fas fa-key me-1"></i> {{ trans('cruds.role.fields.permissions') }}
                </label>

                <div class="mb-2">
                    <button type="button" class="btn btn-sm btn-success select-all">
                        <i class="fas fa-check-double me-1"></i> {{ trans('global.select_all') }}
                    </button>
                    <button type="button" class="btn btn-sm btn-warning deselect-all">
                        <i class="fas fa-times-circle me-1"></i> {{ trans('global.deselect_all') }}
                    </button>
                </div>

                <select 
                    class="form-select select2 {{ $errors->has('permissions') ? 'is-invalid' : '' }}" 
                    name="permissions[]" 
                    id="permissions" 
                    multiple 
                    required
                >
                    @foreach($permissions as $id => $permission)
                        <option value="{{ $id }}" 
                            {{ (in_array($id, old('permissions', [])) || $role->permissions->contains($id)) ? 'selected' : '' }}>
                            {{ $permission }}
                        </option>
                    @endforeach
                </select>

                @if($errors->has('permissions'))
                    <div class="invalid-feedback">
                        {{ $errors->first('permissions') }}
                    </div>
                @endif
                <small class="text-muted">{{ trans('cruds.role.fields.permissions_helper') }}</small>
            </div>

            {{-- Action Buttons --}}
            <div class="text-end">
                <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-arrow-left me-1"></i> {{ trans('global.cancel') }}
                </a>
                <button class="btn btn-success" type="submit">
                    <i class="fas fa-save me-1"></i> {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Inline Script for Select/Deselect --}}
@push('scripts')
<script>
    document.querySelector('.select-all').addEventListener('click', function() {
        let options = document.querySelector('#permissions').options;
        for (let i = 0; i < options.length; i++) {
            options[i].selected = true;
        }
        $('#permissions').trigger('change');
    });

    document.querySelector('.deselect-all').addEventListener('click', function() {
        let options = document.querySelector('#permissions').options;
        for (let i = 0; i < options.length; i++) {
            options[i].selected = false;
        }
        $('#permissions').trigger('change');
    });
</script>
@endpush

@endsection