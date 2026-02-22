@extends('layouts.login')

@section('title', 'WeServe Login - Financial Aid Management System')

@section('content')

<div class="ws-card">

    {{-- Brand --}}
    <div class="ws-brand">
        <img src="{{ asset('home-logo (1).png') }}" alt="WeServe" style="height:44px;width:auto;margin-bottom:10px;">
        <div class="ws-brand-sub">Financial Aid Management System</div>
    </div>

    {{-- Form body --}}
    <div class="ws-form-body">

        {{-- Alerts --}}
        @if($errors->any())
            <div class="ws-alert ws-alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        @if(session('status'))
            <div class="ws-alert ws-alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if(session('success') && !session('toast'))
            <div class="ws-alert ws-alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('warning') && !session('toast'))
            <div class="ws-alert ws-alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            {{-- Honeypot --}}
            <div style="opacity:0;position:absolute;left:-10000px;">
                <input type="text" name="username" autocomplete="off" tabindex="-1">
            </div>

            {{-- Email --}}
            <div class="ws-form-group">
                <label class="ws-form-label" for="email">
                    <i class="fas fa-envelope"></i> Email Address
                </label>
                <div class="ws-input-wrap">
                    <i class="fas fa-envelope ws-input-icon"></i>
                    <input type="email" id="email" name="email"
                           class="ws-input @error('email') error @enderror"
                           placeholder="admin@example.com"
                           value="{{ old('email') }}"
                           required autofocus
                           autocomplete="email"
                           maxlength="255">
                </div>
            </div>

            {{-- Password --}}
            <div class="ws-form-group">
                <label class="ws-form-label" for="password">
                    <i class="fas fa-lock"></i> Password
                </label>
                <div class="ws-input-wrap">
                    <i class="fas fa-lock ws-input-icon"></i>
                    <input type="password" id="password" name="password"
                           class="ws-input @error('password') error @enderror"
                           placeholder="Enter your password"
                           required
                           autocomplete="current-password"
                           minlength="8"
                           maxlength="100">

                </div>
            </div>

            {{-- Remember --}}
            <div class="ws-remember">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Remember me for 30 days</label>
            </div>

            {{-- Attempts warning --}}
            <div class="ws-attempts-warning" id="attemptsWarning">
                <i class="fas fa-exclamation-triangle"></i>
                Multiple failed attempts may temporarily lock your account
            </div>

            {{-- Submit --}}
            <button class="ws-submit" type="submit" id="submitBtn">
                <i class="fas fa-sign-in-alt" id="btnIcon"></i>
                <span id="btnText">Sign In to Dashboard</span>
                <i class="fas fa-spinner ws-btn-spinner" id="btnSpinner"></i>
            </button>
        </form>
    </div>

    {{-- Footer --}}
    <div class="ws-card-footer">
        <p class="ws-footer-text">
            Need assistance?
            <a href="mailto:it-support@weserve.gov" class="ws-support-link">
                <i class="fas fa-envelope" style="margin-right:3px;font-size:.65rem;"></i>Contact IT Support
            </a>
        </p>
    </div>

</div>

{{-- Security notice --}}
<div class="ws-security-note">
    <i class="fas fa-shield-alt"></i>
    Secure government portal &nbsp;·&nbsp; Authorized access only &nbsp;·&nbsp; All activities are monitored
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const loginForm      = document.getElementById('loginForm');
    const passwordField  = document.getElementById('password');
    const emailField     = document.getElementById('email');
    const submitBtn      = document.getElementById('submitBtn');
    const btnText        = document.getElementById('btnText');
    const btnIcon        = document.getElementById('btnIcon');
    const btnSpinner     = document.getElementById('btnSpinner');
    const attemptsWarning = document.getElementById('attemptsWarning');
    let formSubmitted = false;
    let failedAttempts = 0;

    // ── Email sanitisation ──
    emailField.addEventListener('input', function () {
        this.value = this.value.replace(/[<>]/g, '');
        this.classList.toggle('error', this.value && !this.validity.valid);
    });

    function showAttemptsWarning() {
        failedAttempts++;
        if (failedAttempts >= 1) attemptsWarning.style.display = 'flex';
    }

    // ── Form submit ──
    loginForm.addEventListener('submit', function (e) {
        if (formSubmitted) { e.preventDefault(); return false; }

        const email    = emailField.value.trim();
        const password = passwordField.value.trim();
        if (!email || !password) { e.preventDefault(); showAttemptsWarning(); return false; }

        const honeypot = document.querySelector('input[name="username"]').value;
        if (honeypot) { e.preventDefault(); return false; }

        // Loading state
        formSubmitted = true;
        submitBtn.disabled = true;
        btnIcon.style.display    = 'none';
        btnText.textContent      = 'Signing in…';
        btnSpinner.style.display = 'inline-block';

        setTimeout(() => {
            if (!loginForm.checkValidity()) {
                formSubmitted = false;
                submitBtn.disabled = false;
                btnIcon.style.display    = 'inline-block';
                btnText.textContent      = 'Sign In to Dashboard';
                btnSpinner.style.display = 'none';
                showAttemptsWarning();
            }
        }, 100);
    });

    // Reset on input change
    ['input', 'change'].forEach(ev => {
        loginForm.addEventListener(ev, function () {
            if (formSubmitted) {
                formSubmitted = false;
                submitBtn.disabled = false;
                btnIcon.style.display    = 'inline-block';
                btnText.textContent      = 'Sign In to Dashboard';
                btnSpinner.style.display = 'none';
            }
        });
    });

    // Enter key
    document.addEventListener('keypress', function (e) {
        if (e.key === 'Enter' && !submitBtn.disabled) {
            if (document.activeElement === emailField || document.activeElement === passwordField) {
                loginForm.requestSubmit();
            }
        }
    });

    emailField.focus();
});
</script>
@endpush