<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'WeServe Login - Financial Aid Management System')</title>
    <link rel="icon" type="image/png+xml" href="{{ asset('home-icon (1).png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

    <meta http-equiv="Content-Security-Policy" content="default-src 'self';
                   script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net;
                   style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://fonts.googleapis.com https://cdn.jsdelivr.net;
                   font-src 'self' https://cdnjs.cloudflare.com https://fonts.gstatic.com;
                   img-src 'self' data: https:;
                   connect-src 'self';">

    <style>
        /* ═══════════════════════════════════════════════
           WeServe Login — Forest-green design system
           DM Sans / lime accents
           ═══════════════════════════════════════════════ */
        :root {
            --ws-forest:       #064e3b;
            --ws-forest-deep:  #052e22;
            --ws-forest-mid:   #065f46;
            --ws-forest-light: #0a7c5c;
            --ws-lime:         #74ff70;
            --ws-lime-dim:     #52e84e;
            --ws-lime-ghost:   rgba(116,255,112,.10);
            --ws-lime-border:  rgba(116,255,112,.28);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: var(--ws-forest-deep);
        }

        /* ── Background photo + overlay ── */
        .ws-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
        }
        .ws-bg-img {
            width: 100%; height: 100%;
            object-fit: cover;
            object-position: center;
            filter: brightness(.35) saturate(.7);
        }
        /* gradient overlay — stronger at sides, lighter in center */
        .ws-bg-overlay {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 80% at 50% 50%, rgba(5,46,34,.55) 0%, rgba(5,46,34,.92) 100%),
                linear-gradient(180deg, rgba(5,46,34,.4) 0%, transparent 40%, rgba(5,46,34,.6) 100%);
        }
        /* subtle noise texture */
        .ws-bg-overlay::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
            pointer-events: none;
        }

        /* ── Content wrapper ── */
        .ws-page {
            position: relative;
            z-index: 1;
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            gap: 20px;
        }

        /* ── Login card ── */
        .ws-card {
            width: 100%;
            max-width: 420px;
            background: rgba(255,255,255,.04);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,.10);
            border-radius: 20px;
            box-shadow:
                0 0 0 1px rgba(116,255,112,.06),
                0 24px 64px rgba(0,0,0,.5),
                inset 0 1px 0 rgba(255,255,255,.08);
            overflow: hidden;
        }

        /* top lime line */
        .ws-card::before {
            content: '';
            display: block;
            height: 2px;
            background: linear-gradient(to right, transparent 0%, var(--ws-lime) 40%, var(--ws-lime-dim) 60%, transparent 100%);
            opacity: .7;
        }

        /* ── Brand section ── */
        .ws-brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 28px 32px 20px;
            border-bottom: 1px solid rgba(255,255,255,.06);
            text-align: center;
        }
        .ws-brand-logo {
            width: 54px; height: 54px;
            border-radius: 14px;
            background: var(--ws-lime-ghost);
            border: 1.5px solid var(--ws-lime-border);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 14px;
            box-shadow: 0 0 20px rgba(116,255,112,.15);
        }
        .ws-brand-logo img { width: 34px; height: 34px; }
        .ws-brand-name {
            font-size: 1.5rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -.02em;
            line-height: 1;
            margin-bottom: 5px;
        }
        .ws-brand-name span { color: var(--ws-lime); }
        .ws-brand-sub {
            font-size: .76rem;
            font-weight: 500;
            color: rgba(255,255,255,.45);
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        /* ── Form body ── */
        .ws-form-body {
            padding: 24px 32px 28px;
        }

        /* Alerts */
        .ws-alert {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            padding: 10px 13px;
            border-radius: 10px;
            font-size: .78rem;
            font-weight: 500;
            margin-bottom: 16px;
            line-height: 1.4;
        }
        .ws-alert i { font-size: .78rem; margin-top: 1px; flex-shrink: 0; }
        .ws-alert-error   { background: rgba(239,68,68,.12);  border: 1px solid rgba(239,68,68,.3);  color: #fca5a5; }
        .ws-alert-success { background: rgba(16,185,129,.12); border: 1px solid rgba(16,185,129,.3); color: #6ee7b7; }
        .ws-alert-warning { background: rgba(245,158,11,.12); border: 1px solid rgba(245,158,11,.3); color: #fcd34d; }

        /* Form groups */
        .ws-form-group { margin-bottom: 16px; }
        .ws-form-label {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: .73rem;
            font-weight: 600;
            color: rgba(255,255,255,.55);
            letter-spacing: .04em;
            text-transform: uppercase;
            margin-bottom: 7px;
        }
        .ws-form-label i { font-size: .65rem; }

        .ws-input-wrap {
            position: relative;
        }
        .ws-input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: .72rem;
            color: rgba(255,255,255,.3);
            pointer-events: none;
            transition: color .15s;
        }
        .ws-input {
            width: 100%;
            height: 42px;
            background: rgba(255,255,255,.06);
            border: 1.5px solid rgba(255,255,255,.10);
            border-radius: 10px;
            padding: 0 40px 0 36px;
            font-family: 'DM Sans', sans-serif;
            font-size: .84rem;
            font-weight: 500;
            color: #fff;
            outline: none;
            transition: all .18s;
            -webkit-appearance: none;
        }
        .ws-input::placeholder { color: rgba(255,255,255,.25); font-weight: 400; }
        .ws-input:focus {
            background: rgba(116,255,112,.06);
            border-color: var(--ws-lime-border);
            box-shadow: 0 0 0 3px rgba(116,255,112,.08);
        }
        .ws-input:focus + .ws-input-icon,
        .ws-input-wrap:focus-within .ws-input-icon { color: var(--ws-lime); }
        /* icon is before input in DOM so use wrapper focus */
        .ws-input-wrap:focus-within .ws-input-icon { color: rgba(116,255,112,.7); }
        .ws-input.error { border-color: rgba(239,68,68,.5); background: rgba(239,68,68,.06); }

        /* Password toggle */
        .ws-pwd-toggle {
            position: absolute;
            right: 11px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255,255,255,.3);
            font-size: .72rem;
            cursor: pointer;
            padding: 4px;
            border-radius: 5px;
            transition: color .15s;
            display: flex; align-items: center; justify-content: center;
        }
        .ws-pwd-toggle:hover { color: rgba(255,255,255,.65); }

        /* Remember me */
        .ws-remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
            cursor: pointer;
        }
        .ws-remember input[type="checkbox"] {
            width: 15px; height: 15px;
            accent-color: var(--ws-lime);
            cursor: pointer;
            flex-shrink: 0;
        }
        .ws-remember label {
            font-size: .76rem;
            color: rgba(255,255,255,.45);
            cursor: pointer;
            font-weight: 500;
            user-select: none;
        }

        /* Attempts warning */
        .ws-attempts-warning {
            display: none;
            align-items: center;
            gap: 7px;
            padding: 8px 12px;
            border-radius: 9px;
            background: rgba(245,158,11,.10);
            border: 1px solid rgba(245,158,11,.25);
            color: #fcd34d;
            font-size: .74rem;
            font-weight: 500;
            margin-bottom: 14px;
        }
        .ws-attempts-warning i { font-size: .72rem; flex-shrink: 0; }

        /* Submit button */
        .ws-submit {
            width: 100%;
            height: 44px;
            background: linear-gradient(135deg, var(--ws-forest-mid) 0%, var(--ws-forest-light) 100%);
            border: 1.5px solid var(--ws-lime-border);
            border-radius: 11px;
            font-family: 'DM Sans', sans-serif;
            font-size: .88rem;
            font-weight: 700;
            color: #fff;
            cursor: pointer;
            transition: all .2s;
            position: relative;
            overflow: hidden;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            letter-spacing: .01em;
        }
        .ws-submit::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, transparent, rgba(116,255,112,.06));
            opacity: 0;
            transition: opacity .2s;
        }
        .ws-submit:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(6,78,59,.4), 0 0 0 1px rgba(116,255,112,.2);
            border-color: rgba(116,255,112,.45);
        }
        .ws-submit:hover::before { opacity: 1; }
        .ws-submit:active:not(:disabled) { transform: translateY(0); }
        .ws-submit:disabled {
            opacity: .6;
            cursor: not-allowed;
            transform: none;
        }
        .ws-btn-spinner { display: none; animation: spin .7s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Card footer ── */
        .ws-card-footer {
            padding: 14px 32px 20px;
            border-top: 1px solid rgba(255,255,255,.06);
            text-align: center;
        }
        .ws-footer-text {
            font-size: .74rem;
            color: rgba(255,255,255,.35);
        }
        .ws-support-link {
            color: rgba(116,255,112,.7);
            text-decoration: none;
            font-weight: 600;
            transition: color .15s;
        }
        .ws-support-link:hover { color: var(--ws-lime); }

        /* ── Bottom security notice ── */
        .ws-security-note {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: .68rem;
            color: rgba(255,255,255,.25);
            letter-spacing: .03em;
            text-align: center;
        }
        .ws-security-note i { color: rgba(116,255,112,.4); font-size: .65rem; }

        /* ── Toast (matches admin layout) ── */
        .custom-toast {
            font-family: 'DM Sans', sans-serif;
            border-radius: 12px;
            border: 1px solid #e8f5e9;
            box-shadow: 0 8px 24px rgba(6,78,59,.12);
            min-width: 300px;
        }
        .toast-success { border-left: 3px solid #10b981; }
        .toast-danger  { border-left: 3px solid #ef4444; }
        .toast-warning { border-left: 3px solid #f59e0b; }
        .toast-info    { border-left: 3px solid #3b82f6; }
        .toast-progress {
            height: 3px;
            background: linear-gradient(to right, #064e3b, #74ff70);
            animation: toastProgress 5s linear forwards;
        }
        @keyframes toastProgress { from { width: 100%; } to { width: 0; } }
        .toast-icon { margin-right: 7px; }

        @media (max-width: 480px) {
            .ws-brand { padding: 22px 20px 16px; }
            .ws-form-body { padding: 20px 20px 24px; }
            .ws-card-footer { padding: 12px 20px 18px; }
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- Toast --}}
    @if(session('toast'))
        @php
            $toast = session('toast');
            $bgClass = match ($toast['type']) {
                'success' => 'toast-success',
                'danger'  => 'toast-danger',
                'warning' => 'toast-warning',
                'info'    => 'toast-info',
                default   => 'bg-secondary',
            };
            $icons = [
                'success' => 'fas fa-check-circle',
                'danger'  => 'fas fa-exclamation-triangle',
                'warning' => 'fas fa-exclamation-circle',
                'info'    => 'fas fa-info-circle',
            ];
            $icon = $icons[$toast['type']] ?? 'fas fa-bell';
        @endphp
        <div class="position-fixed top-0 end-0 p-3" style="z-index:9999;">
            <div id="liveToast" class="toast custom-toast {{ $bgClass }}" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-progress"></div>
                <div class="toast-header bg-white text-dark">
                    <i class="{{ $icon }} toast-icon text-{{ $toast['type'] }}"></i>
                    <strong class="me-auto">{{ $toast['title'] ?? 'Notification' }}</strong>
                    <small class="text-muted" id="toast-timer">Just now</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">{!! session('toast')['message'] !!}</div>
            </div>
        </div>
    @endif

    {{-- Background --}}
    <div class="ws-bg">
        <img src="{{ asset('municipal.jpg') }}" alt="Municipal Building" class="ws-bg-img" />
        <div class="ws-bg-overlay"></div>
    </div>

    {{-- Page content --}}
    <div class="ws-page">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toastEl = document.getElementById('liveToast');
            if (toastEl) {
                new bootstrap.Toast(toastEl, { autohide: true, delay: 5000 }).show();
                const timerEl = document.getElementById('toast-timer');
                if (timerEl) {
                    let s = 0;
                    setInterval(() => { s++; timerEl.textContent = s < 60 ? `${s}s ago` : `${Math.floor(s/60)}m ago`; }, 1000);
                }
            }
        });
    </script>

    @stack('scripts')
</body>
</html>