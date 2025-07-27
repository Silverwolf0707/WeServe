@extends('layouts.admin')

@section('content')
<style>
    .card-header h5 i {
        color: #ffffffcc;
    }

    .form-label.required::after {
        content: " *";
        color: red;
    }

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }

    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
    }

    .card {
        border-radius: 0.5rem;
    }

    .card-header {
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }

    .btn {
        border-radius: 0.375rem;
    }
</style>

<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">
                <i class="fas fa-edit me-2"></i> {{ trans('global.edit') }} {{ trans('cruds.permission.title_singular') }}
            </h5>
            <small class="text-white-50">{{ trans('cruds.permission.fields.title_helper') }}</small>
        </div>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.permissions.update', [$permission->id]) }}" enctype="multipart/form-data">
            @method('PUT')
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
                    value="{{ old('title', $permission->title) }}" 
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