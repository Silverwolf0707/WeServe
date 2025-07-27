@extends('layouts.admin')

@section('content')
<div class="container-fluid" style="background-color: #f8f9fa; padding: 20px;">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white d-flex align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-user-edit me-2"></i>
                {{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }}
            </h5>
        </div>

        <div class="card-body bg-white">
            <form method="POST" action="{{ route('admin.users.update', [$user->id]) }}" enctype="multipart/form-data">
                @method('PUT')
                @csrf

                {{-- Name --}}
                <div class="form-group">
                    <label class="required" for="name">
                        <i class="fas fa-user me-1 text-secondary"></i> {{ trans('cruds.user.fields.name') }}
                    </label>
                    <input type="text" name="name" id="name"
                           class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                           value="{{ old('name', $user->name) }}" required>
                    @if($errors->has('name'))
                        <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                    @endif
                    <small class="form-text text-muted">{{ trans('cruds.user.fields.name_helper') }}</small>
                </div>

                {{-- Email --}}
                <div class="form-group">
                    <label class="required" for="email">
                        <i class="fas fa-envelope me-1 text-secondary"></i> {{ trans('cruds.user.fields.email') }}
                    </label>
                    <input type="email" name="email" id="email"
                           class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                           value="{{ old('email', $user->email) }}" required>
                    @if($errors->has('email'))
                        <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                    @endif
                    <small class="form-text text-muted">{{ trans('cruds.user.fields.email_helper') }}</small>
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock me-1 text-secondary"></i> {{ trans('cruds.user.fields.password') }}
                    </label>
                    <div class="input-group">
                        <input type="password" name="password" id="password"
                               class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}">
                        <button class="btn btn-outline-secondary toggle-password" type="button" tabindex="-1">
                            <i class="fas fa-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                    @if($errors->has('password'))
                        <div class="invalid-feedback d-block">{{ $errors->first('password') }}</div>
                    @endif
                    <small class="form-text text-muted">{{ trans('cruds.user.fields.password_helper') }}</small>
                </div>

                {{-- Roles --}}
                <div class="form-group">
                    <label class="required" for="roles">
                        <i class="fas fa-user-tag me-1 text-secondary"></i> {{ trans('cruds.user.fields.roles') }}
                    </label>
                    <div class="mb-2">
                       <button type="button" class="btn btn-info select-all">{{ trans('global.select_all') }}</button>
<button type="button" class="btn btn-danger deselect-all">{{ trans('global.deselect_all') }}</button>

                    </div>
                    <select name="roles[]" id="roles" class="form-control select2 {{ $errors->has('roles') ? 'is-invalid' : '' }}" multiple required>
                        @foreach($roles as $id => $role)
                            <option value="{{ $id }}"
                                {{ (in_array($id, old('roles', [])) || $user->roles->contains($id)) ? 'selected' : '' }}>
                                {{ $role }}
                            </option>
                        @endforeach
                    </select>
                    @if($errors->has('roles'))
                        <div class="invalid-feedback">{{ $errors->first('roles') }}</div>
                    @endif
                    <small class="form-text text-muted">{{ trans('cruds.user.fields.roles_helper') }}</small>
                </div>

                {{-- Buttons --}}
                <div class="form-group mt-4 d-flex justify-content-start">
    <button type="submit" class="btn btn-success">
        <i class="fas fa-save me-2"></i> {{ trans('global.save') }}
    </button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary" style="margin-left: 12px;">
        <i class="fas fa-arrow-left me-1"></i> {{ trans('global.back_to_list') }}
    </a>
</div>

            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    // Toggle show/hide password
    document.querySelector('.toggle-password').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('togglePasswordIcon');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });

    // Select all roles
    $('.select-all').click(function () {
        let $select = $(this).closest('.form-group').find('select');
        $select.find('option').prop('selected', true).trigger('change');
    });

    // Deselect all roles
    $('.deselect-all').click(function () {
        let $select = $(this).closest('.form-group').find('select');
        $select.find('option').prop('selected', false).trigger('change');
    });
</script>
@endsection