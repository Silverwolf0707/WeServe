@extends('layouts.home')
@section('title', 'WeServe - Application Tracking')
@section('content')

<header class="header" role="banner">
    <div class="header-container">
        <a href="#home" class="logo-title" aria-label="WeServe Home">
            <img src="{{ asset('WeServe.png') }}" alt="WeServe Logo" class="logo-full" loading="eager">
        </a>

        <button class="burger" onclick="toggleMenu()" aria-label="Toggle Menu">
            <i class="fas fa-bars"></i>
        </button>

        <nav class="nav-links" id="navMenu" aria-label="Primary">
            <a href="#process">Process</a>
            <a href="#tracking">Track</a>
        </nav>
    </div>
</header>

<section class="process" id="process">
    <div class="container text-center">
        <h2>Application Process</h2>
        <div class="grid-5">
            <div class="process-card">
                <div class="step-circle">1</div>
                <div class="process-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>CSWD</h3>
                <p>Initial review and verification of submitted application.</p>
            </div>

            <div class="process-card">
                <div class="step-circle">2</div>
                <div class="process-icon">
                    <i class="fas fa-building"></i>
                </div>
                <h3>Mayor's Office</h3>
                <p>Approval and endorsement of application.</p>
            </div>

            <div class="process-card">
                <div class="step-circle">3</div>
                <div class="process-icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <h3>Budget</h3>
                <p>Funding allocation and verification.</p>
            </div>

            <div class="process-card">
                <div class="step-circle">4</div>
                <div class="process-icon">
                    <i class="fas fa-calculator"></i>
                </div>
                <h3>Accounting</h3>
                <p>Processing of financial disbursement.</p>
            </div>

            <div class="process-card">
                <div class="step-circle">5</div>
                <div class="process-icon">
                    <i class="fas fa-coins"></i>
                </div>
                <h3>Treasury</h3>
                <p>Final release of assistance to claimant.</p>
            </div>
        </div>
    </div>
</section>

<section class="tracking" id="tracking">
    <div class="container tracking-container">
        <div class="tracking-header text-center">
            <h1>Tracking Summary</h1>
        </div>

        <div class="tracking-log-card-wrapper">
            <div class="tracking-log-card">
                @if ($logs && count($logs) > 0)
                    @foreach ($logs as $log)
                        @php
                            $status = $log->status;
                            $isRollback = str_contains($status, '[ROLLED BACK]');
                            $baseStatus = $isRollback ? trim(str_replace('[ROLLED BACK]', '', $status)) : $status;

                            // ✅ Define the color for each status background
                            switch ($baseStatus) {
                                case 'Processing':
                                    $color = '#6c757d'; // gray
                                    $icon = 'fa-hourglass-half';
                                    break;
                                case 'Submitted':
                                    $color = '#28a745'; // green
                                    $icon = 'fa-paper-plane';
                                    break;
                                case 'Approved':
                                    $color = '#28a745'; // green
                                    $icon = 'fa-check-circle';
                                    break;
                                case 'Rejected':
                                    $color = '#dc3545'; // red
                                    $icon = 'fa-times-circle';
                                    break;
                                case 'Budget Allocated':
                                    $color = '#d4a017'; // dark yellow
                                    $icon = 'fa-wallet';
                                    break;
                                case 'DV Submitted':
                                    $color = '#17a2b8'; // light blue
                                    $icon = 'fa-file-invoice-dollar';
                                    break;
                                case 'Disbursed':
                                    $color = '#28a745'; // green
                                    $icon = 'fa-hand-holding-usd';
                                    break;
                                default:
                                    $color = '#6c757d'; // fallback gray
                                    $icon = 'fa-question-circle';
                                    break;
                            }

                            // Adjust text color for readability
                            $textColor = in_array($baseStatus, ['Budget Allocated']) ? 'black' : 'white';
                        @endphp

                        <!-- ✅ Updated: background now uses the status color -->
                        <div class="tracking-entry"
                            style="background-color: {{ $color }}; border-left: 6px solid {{ $color }}; color: {{ $textColor }};">
                            <div class="status-icon" style="background-color: rgba(255,255,255,0.2);">
                                <i class="fas {{ $icon }}" style="color: {{ $textColor }};"></i>
                            </div>

                            <div class="status-details">
                                <p class="tracking-date" style="color: {{ $textColor }};">
                                    {{ \Carbon\Carbon::parse($log->status_date)->format('F j, Y g:i A') }}
                                </p>
                                <p class="tracking-status" style="color: {{ $textColor }};">
                                    <b>{{ $baseStatus }}:</b> {{ $log->remarks }}
                                </p>
                            </div>

                            @if($isRollback)
                                <span class="rollback-badge">Rolled Back</span>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="tracking-entry empty-entry">
                        <p class="tracking-date">{{ now()->format('F j, Y g:i A') }}</p>
                        <p class="tracking-status">No tracking logs available.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<footer class="footer">
    <div class="container footer-container">
        <div>
            <h2>WeServe</h2>
            <p>Providing support when it's needed most</p>
        </div>
        <div class="socials">
            <a href="#"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
        </div>
    </div>
    <div class="copyright">&copy; 2025 WeServe. All rights reserved.</div>
</footer>

<script>
    function toggleMenu() {
        const navMenu = document.getElementById("navMenu");
        navMenu.classList.toggle("show");
    }

    document.querySelectorAll('.nav-links a').forEach(link => {
        link.addEventListener('click', () => {
            const navMenu = document.getElementById("navMenu");
            navMenu.classList.remove("show");
        });
    });
</script>

@endsection