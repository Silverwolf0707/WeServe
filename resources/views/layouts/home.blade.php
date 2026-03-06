<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'WeServe - Financial Assistance')</title>
    <link rel="icon" type="image/png+xml" href="{{ asset('home-icon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/onlineApplication.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/about.css') }}">
    <link rel="stylesheet" href="{{ asset('css/services.css') }}">
    <link rel="stylesheet" href="{{ asset('css/process.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/faq.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('styles')
    @yield('styles')
</head>
@if(session('toast'))
        @php
            $toast = session('toast');
            $bgClass = match ($toast['type']) {
                'success' => 'toast-success',
                'danger' => 'toast-danger',
                'warning' => 'toast-warning',
                'info' => 'toast-info',
                default => 'bg-secondary',
            };

            $icons = [
                'success' => 'fas fa-check-circle',
                'danger' => 'fas fa-exclamation-triangle',
                'warning' => 'fas fa-exclamation-circle',
                'info' => 'fas fa-info-circle'
            ];
            $icon = $icons[$toast['type']] ?? 'fas fa-bell';
        @endphp

        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1055">
            <div id="liveToast" class="toast custom-toast {{ $bgClass }}" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-progress"></div>
                <div class="toast-header bg-white text-dark">
                    <i class="{{ $icon }} toast-icon text-{{ $toast['type'] }}"></i>
                    <strong class="me-auto">{{ $toast['title'] ?? 'Notification' }}</strong>
                    <small class="text-muted" id="toast-timer">Just now</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    {!! session('toast')['message'] !!}
                </div>
            </div>
        </div>
    @endif

<body class="body">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @yield('content')
    <script>
        // Initialize toast if exists
        document.addEventListener('DOMContentLoaded', function () {
            const toastEl = document.getElementById('liveToast');
            if (toastEl) {
                const toast = new bootstrap.Toast(toastEl, {
                    autohide: true,
                    delay: 5000
                });
                toast.show();

                // Update timer every second
                const timerElement = document.getElementById('toast-timer');
                if (timerElement) {
                    let seconds = 0;
                    setInterval(() => {
                        seconds++;
                        if (seconds < 60) {
                            timerElement.textContent = `${seconds}s ago`;
                        } else {
                            timerElement.textContent = `${Math.floor(seconds / 60)}m ago`;
                        }
                    }, 1000);
                }
            }
        });
    </script>
   
    @stack('scripts')
</body>

</html>