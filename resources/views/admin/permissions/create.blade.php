@extends('layouts.admin')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">
                <i class="fas fa-key me-2"></i> {{ trans('global.create') }} {{ trans('cruds.permission.title_singular') }}
            </h5>
            <small class="text-white-50">{{ trans('cruds.permission.fields.title_helper') }}</small>
        </div>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.permissions.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="title" class="form-label fw-bold required">
                    {{ trans('cruds.permission.fields.title') }}
                </label>
                <input 
                    type="text" 
                    name="title" 
                    id="title" 
                    class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" 
                    value="{{ old('title', '') }}" 
                    required
                    placeholder="Enter permission name">
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success px-4">
                    <i class="fas fa-save me-1"></i> {{ trans('global.save') }}
                </button>
                <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary px-4">
                    <i class="fas fa-arrow-left me-1"></i> {{ trans('global.back_to_list') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection