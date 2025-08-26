<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WeServe - Application Tracking</title>
    <link rel="stylesheet" href="{{ asset('css/onlineApplication.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="body">

    <body class="body">

        <header class="header" role="banner">
            <div class="header-container">
                <a href="#home" class="logo-title" aria-label="WeServe Home">
                    <img src="{{ asset('WeServe Logo.png') }}" alt="WeServe Logo" class="logo" loading="eager">
                    <span class="logo-text">WeServe</span>
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
                        <h3>Mayor’s Office</h3>
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
                                    $color = $badgeColors[$baseStatus] ?? '#6c757d';
                                    $icon = $icons[$baseStatus] ?? 'fa-question-circle';
                                    $textColor = $baseStatus === 'Budget Allocated' ? 'black' : 'white';
                                @endphp

                                <div class="tracking-entry">
                                    <div class="status-icon" style="background-color: {{ $color }};">
                                        <i class="fas {{ $icon }}" style="color: {{ $textColor }};"></i>
                                    </div>

                                    <div class="status-details">
                                        <p class="tracking-date">
                                            {{ \Carbon\Carbon::parse($log->status_date)->format('F j, Y g:i A') }}
                                        </p>
                                        <p class="tracking-status">
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

    </body>

</html>