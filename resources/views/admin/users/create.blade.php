@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        
        {{-- Green Header --}}
        <div class="card-header bg-success text-white d-flex align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-user-plus mr-2"></i>
                {{ trans('global.create') }} {{ trans('cruds.user.title_singular') }}
            </h5>
        </div>

        <div class="card-body bg-white">
            <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- Name --}}
                <div class="form-group">
                    <label class="required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                    <input type="text" name="name" id="name" 
                        class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        value="{{ old('name', '') }}" required>
                    @if($errors->has('name'))
                        <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                    @endif
                    <small class="form-text text-muted">{{ trans('cruds.user.fields.name_helper') }}</small>
                </div>

                {{-- Email --}}
                <div class="form-group">
                    <label class="required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                    <input type="email" name="email" id="email" 
                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        value="{{ old('email') }}" required>
                    @if($errors->has('email'))
                        <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                    @endif
                    <small class="form-text text-muted">{{ trans('cruds.user.fields.email_helper') }}</small>
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label class="required" for="password">{{ trans('cruds.user.fields.password') }}</label>
                    <input type="password" name="password" id="password"
                        class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" required>
                    @if($errors->has('password'))
                        <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                    @endif
                    <small class="form-text text-muted">{{ trans('cruds.user.fields.password_helper') }}</small>
                </div>

                {{-- Roles --}}
                <div class="form-group">
                    <label class="required" for="roles">{{ trans('cruds.user.fields.roles') }}</label>
                    <div class="mb-2">
                    <button type="button" class="btn btn-sm btn-success select-all">
                        <i class="fas fa-check-double me-1"></i> {{ trans('global.select_all') }}
                    </button>
                    <button type="button" class="btn btn-sm btn-danger deselect-all">
                        <i class="fas fa-times-circle me-1"></i> {{ trans('global.deselect_all') }}
                    </button>
                </div>
                    <select name="roles[]" id="roles" 
                        class="form-control select2 {{ $errors->has('roles') ? 'is-invalid' : '' }}" multiple required>
                        @foreach($roles as $id => $role)
                            <option value="{{ $id }}" {{ in_array($id, old('roles', [])) ? 'selected' : '' }}>
                                {{ $role }}
                            </option>
                        @endforeach
                    </select>
                    @if($errors->has('roles'))
                        <div class="invalid-feedback">{{ $errors->first('roles') }}</div>
                    @endif
                    <small class="form-text text-muted">{{ trans('cruds.user.fields.roles_helper') }}</small>
                </div>

                {{-- Buttons: Save & Back to List side by side --}}
                <div class="form-group mt-4 d-flex">
                    <button type="submit" class="btn btn-success mr-2">
                        <i class="fas fa-save mr-1"></i> {{ trans('global.save') }}
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> {{ trans('global.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection