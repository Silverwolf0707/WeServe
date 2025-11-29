@extends('layouts.login')

@section('title', 'WeServe Login - Financial Aid Management System')

@section('content')
    <div class="login-card">
        <div class="brand-section-inside">
            <div class="logo-circle">
                <img src="{{ asset('logo.png') }}?v=1.0" alt="WeServe Logo" width="42" height="42" />
            </div>
            <h1 class="brand-title">WeServe</h1>
            <p class="brand-subtitle">Financial Aid Management System</p>
        </div>

        <!-- Alert Messages (Fallback if JavaScript is disabled) -->
        @if($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        @if(session('status'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if(session('success') && !session('toast'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('warning') && !session('toast'))
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf
            
            <!-- Honeypot Field for Bot Detection -->
            <div style="opacity: 0; position: absolute; left: -10000px;">
                <input type="text" name="username" autocomplete="off" tabindex="-1">
            </div>

            <div class="form-group">
                <label class="form-label" for="email">
                    <i class="fas fa-envelope" style="margin-right: 5px;"></i>
                    Email Address
                </label>
                <div class="input-wrapper">
                    <i class="fa fa-envelope input-icon"></i>
                    <input type="email" id="email" name="email" class="form-input @error('email') error @enderror" 
                           placeholder="admin@example.com" required autofocus
                           value="{{ old('email') }}"
                           autocomplete="email"
                           maxlength="255">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">
                    <i class="fas fa-lock" style="margin-right: 5px;"></i>
                    Password
                </label>
                <div class="input-wrapper">
                    <i class="fa fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" class="form-input @error('password') error @enderror" 
                           placeholder="Enter your password" required
                           autocomplete="current-password"
                           minlength="8"
                           maxlength="100">
                </div>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="remember-me">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Remember me for 30 days</label>
            </div>

            <div class="forgot-password">
                <a href="{{ route('password.request') }}" class="support-link">
                    <i class="fas fa-key" style="margin-right: 5px;"></i>
                    Forgot your password?
                </a>
            </div>

            <!-- Attempts Warning -->
            <div class="attempts-warning" id="attemptsWarning">
                <i class="fas fa-exclamation-triangle"></i>
                Multiple failed attempts may temporarily lock your account
            </div>

            <button class="submit-button" type="submit" id="submitBtn">
                <div class="button-content">
                    <span id="btnText">Sign In to Dashboard</span>
                    <div id="btnSpinner" class="button-spinner">
                        <i class="fas fa-spinner"></i>
                    </div>
                </div>
            </button>
        </form>

        <div class="card-footer">
            <p class="footer-text">
                Need assistance? 
                <a href="mailto:it-support@weserve.gov" class="support-link">
                    <i class="fas fa-envelope" style="margin-right: 5px;"></i>
                    Contact IT Support
                </a>
            </p>
        </div>
    </div>

    <p class="bottom-text">
        <i class="fas fa-shield-alt" style="margin-right: 5px;"></i>
        Secure government portal • Authorized access only • All activities are monitored
    </p>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elements
        const loginForm = document.getElementById('loginForm');
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const btnSpinner = document.getElementById('btnSpinner');
        const attemptsWarning = document.getElementById('attemptsWarning');
        const emailField = document.getElementById('email');
        
        // Track form submission state
        let formSubmitted = false;
        let failedAttempts = 0;

        // Email input sanitization
        emailField.addEventListener('input', function(e) {
            // Remove potentially dangerous characters
            this.value = this.value.replace(/[<>]/g, '');
            
            // Validate email format in real-time
            if (this.value && !this.validity.valid) {
                this.classList.add('error');
            } else {
                this.classList.remove('error');
            }
        });

        // Show attempts warning after first failed attempt
        function showAttemptsWarning() {
            failedAttempts++;
            if (failedAttempts >= 1) {
                attemptsWarning.style.display = 'block';
            }
        }

        // Form submission handler
        loginForm.addEventListener('submit', function(e) {
            // Prevent multiple submissions
            if (formSubmitted) {
                e.preventDefault();
                return false;
            }

            // Basic validation
            const email = emailField.value.trim();
            const password = passwordField.value.trim();

            if (!email || !password) {
                e.preventDefault();
                showAttemptsWarning();
                return false;
            }

            // Honeypot validation
            const honeypot = document.querySelector('input[name="username"]').value;
            if (honeypot) {
                e.preventDefault();
                console.warn('Potential bot detected');
                return false;
            }

            // Show loading state
            formSubmitted = true;
            submitBtn.disabled = true;
            btnText.style.display = 'none';
            btnSpinner.style.display = 'block';

            // Add slight delay for better UX
            setTimeout(() => {
                if (!loginForm.checkValidity()) {
                    formSubmitted = false;
                    submitBtn.disabled = false;
                    btnText.style.display = 'block';
                    btnSpinner.style.display = 'none';
                    showAttemptsWarning();
                }
            }, 100);
        });

        // Input event to reset form submission state
        ['input', 'change'].forEach(event => {
            loginForm.addEventListener(event, function() {
                if (formSubmitted) {
                    formSubmitted = false;
                    submitBtn.disabled = false;
                    btnText.style.display = 'block';
                    btnSpinner.style.display = 'none';
                }
            });
        });

        // Enter key submission
        document.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !submitBtn.disabled) {
                const activeElement = document.activeElement;
                if (activeElement === emailField || activeElement === passwordField) {
                    loginForm.requestSubmit();
                }
            }
        });

        // Focus management for better accessibility
        emailField.focus();

        // Session timeout warning (optional)
        let inactivityTimer;
        function resetInactivityTimer() {
            clearTimeout(inactivityTimer);
            inactivityTimer = setTimeout(() => {
                if (document.visibilityState === 'visible') {
                    // Show warning if page is visible
                    console.log('Session will expire soon due to inactivity');
                }
            }, 25 * 60 * 1000); // 25 minutes
        }

        // Reset timer on user activity
        ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart'].forEach(event => {
            document.addEventListener(event, resetInactivityTimer, false);
        });

        resetInactivityTimer();
    });

    // Handle page visibility changes
    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'visible') {
            // Page became visible again
            console.log('Page is now visible');
        }
    });
</script>
@endpush