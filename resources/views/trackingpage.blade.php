@extends('layouts.home')
@section('title', 'WeServe - Application Tracking')
@section('content')

    {{-- ===== HEADER (matches home page) ===== --}}
    <header class="header1" id="siteHeader" role="banner">
        <div class="header-container">
            <a href="/" class="logo-title" aria-label="WeServe Home">
                <img src="{{ asset('home-logo.png') }}" alt="WeServe Logo" class="logo-full" loading="eager">
            </a>

            <nav class="nav-links" aria-label="Primary">
                <a href="/">HOME</a>
                <a href="/#about">ABOUT</a>
                <a href="/#categories">SERVICES</a>
                <a href="#tracking-process">PROCESS</a>
                <a href="#tracking">TRACK</a>
            </nav>

            <a href="/" class="btn-neon">
                APPLY HERE!
            </a>

            <button class="burger" onclick="toggleMenu()" aria-label="Toggle Menu">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>

    <nav class="mobile-nav-overlay" id="navMenu" aria-label="Mobile navigation" aria-hidden="true">
        <button class="close-btn" onclick="toggleMenu()" aria-label="Close menu">&times;</button>
        <a href="/" onclick="toggleMenu()">HOME</a>
        <a href="/#about" onclick="toggleMenu()">ABOUT</a>
        <a href="/#categories" onclick="toggleMenu()">SERVICES</a>
        <a href="#tracking-process" onclick="toggleMenu()">PROCESS</a>
        <a href="#tracking" onclick="toggleMenu()">TRACK</a>
        <div class="mobile-menu-cta">
            <a href="/" class="btn-neon" onclick="toggleMenu()">Apply Here!</a>
        </div>
    </nav>

    <main>

        {{-- ===== HERO / PAGE TITLE STRIP ===== --}}
        <section class="track-hero">
            <div class="track-hero-inner">
                <span class="hero-eyebrow">CSWD San Pedro, Laguna</span>
                <h1>Application <em>Tracking</em></h1>
                <p>Monitor the real-time status of your financial assistance application as it moves through each department.</p>
            </div>
            {{-- wave divider --}}
            <div class="track-hero-wave">
                <svg viewBox="0 0 1440 70" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0,40 C360,80 1080,0 1440,40 L1440,70 L0,70 Z" fill="#f7faf9"/>
                </svg>
            </div>
        </section>

        {{-- ===== APPLICATION PROCESS ===== --}}
        <section class="track-process-section" id="tracking-process">
            <div class="container">
                <div class="categories-header">
                    <div class="categories-section-label">How It Works</div>
                    <h2>Application <em>Process</em></h2>
                </div>
                <div class="track-process-grid">
                    <div class="track-process-card">
                        <span class="track-card-num">01</span>
                        <div class="category-icon-circle">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>CSWD</h3>
                        <p>Initial review and verification of your submitted application by the CSWD Office.</p>
                    </div>

                    <div class="track-process-card">
                        <span class="track-card-num">02</span>
                        <div class="category-icon-circle">
                            <i class="fas fa-building"></i>
                        </div>
                        <h3>Mayor's Office</h3>
                        <p>Approval and official endorsement of your application.</p>
                    </div>

                    <div class="track-process-card">
                        <span class="track-card-num">03</span>
                        <div class="category-icon-circle">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <h3>Budget</h3>
                        <p>Funding allocation and budget verification for your assistance.</p>
                    </div>

                    <div class="track-process-card">
                        <span class="track-card-num">04</span>
                        <div class="category-icon-circle">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <h3>Accounting</h3>
                        <p>Processing of the financial disbursement documents.</p>
                    </div>

                    <div class="track-process-card">
                        <span class="track-card-num">05</span>
                        <div class="category-icon-circle">
                            <i class="fas fa-coins"></i>
                        </div>
                        <h3>Treasury</h3>
                        <p>Final release of assistance amount to the claimant.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== TRACKING RESULTS ===== --}}
        <section class="track-results-section" id="tracking">
            <div class="container">
                <div class="categories-header">
                    <div class="categories-section-label">Your Application</div>
                    <h2>Tracking <em>Summary</em></h2>
                </div>

                @php
                    $filteredLogs = [];
                    $currentStatus = null;

                    $statusOrder = [
                        'Processing'             => 1,
                        'Submitted'              => 2,
                        'Submitted[Emergency]'   => 2,
                        'Approved'               => 3,
                        'Budget Allocated'       => 4,
                        'DV Submitted'           => 5,
                        'Ready for Disbursement' => 6,
                        'Disbursed'              => 7,
                        'Rejected'               => 0,
                    ];

                    if (isset($logs) && $logs && count($logs) > 0) {
                        $logsArray = is_object($logs) && method_exists($logs, 'toArray') ? $logs->toArray() : $logs;

                        $allStatuses = [];
                        foreach ($logsArray as $log) {
                            $status = $log['status'] ?? null;
                            if ($status && !str_contains($status, '[ROLLED BACK]')) {
                                $allStatuses[] = $status;
                            }
                        }

                        $latestNonRejectedStatus = null;
                        $hasRejectionInHistory   = false;
                        $rejectionWasLast        = false;

                        $reversedStatuses = array_reverse($allStatuses);
                        foreach ($reversedStatuses as $status) {
                            $cleanStatus = trim(str_replace('[ROLLED BACK]', '', $status));
                            if ($cleanStatus === 'Rejected') {
                                $hasRejectionInHistory = true;
                                if ($latestNonRejectedStatus === null) {
                                    $rejectionWasLast = true;
                                }
                                continue;
                            }
                            if ($latestNonRejectedStatus === null) {
                                $latestNonRejectedStatus = $cleanStatus;
                            }
                        }

                        $uniqueStatuses = [];
                        $processedLogs  = [];
                        foreach ($logsArray as $log) {
                            $status = $log['status'] ?? null;
                            if (!$status) continue;
                            $isRollback = str_contains($status, '[ROLLED BACK]');
                            if ($isRollback) continue;
                            $cleanStatus = trim(str_replace('[ROLLED BACK]', '', $status));
                            if ($cleanStatus === 'Rejected' && !$rejectionWasLast) continue;
                            if (!in_array($cleanStatus, $uniqueStatuses)) {
                                $uniqueStatuses[] = $cleanStatus;
                                $processedLogs[]  = (object) $log;
                            }
                        }

                        $filteredLogs = $processedLogs;
                        if (count($filteredLogs) > 0) {
                            $latestLog = end($filteredLogs);
                            if ($latestLog) {
                                $currentStatus = trim(str_replace('[ROLLED BACK]', '', $latestLog->status));
                            }
                        }
                    }
                @endphp

                @php
                    $steps = [
                        'Processing'      => ['label' => 'CSWD',     'icon' => 'fa-users'],
                        'Submitted'       => ['label' => 'Mayor',     'icon' => 'fa-building'],
                        'Approved'        => ['label' => 'Budget',    'icon' => 'fa-wallet'],
                        'Budget Allocated'=> ['label' => 'Accounting','icon' => 'fa-calculator'],
                        'Disbursed'       => ['label' => 'Treasury',  'icon' => 'fa-coins'],
                    ];
                    $currentOrder = $statusOrder[$currentStatus] ?? 0;
                @endphp

                {{-- Progress Stepper --}}
                <div class="track-stepper-wrap">
                    <div class="process-stepper">
                        @foreach ($steps as $status => $data)
                            @php
                                $order = $statusOrder[$status];

                                if ($currentStatus === 'Disbursed') {
                                    $state = 'completed';
                                } else {
                                    if ($order < $currentOrder)      $state = 'completed';
                                    elseif ($order === $currentOrder) $state = 'current';
                                    else                              $state = 'pending';
                                }

                                $lineColorClass = 'line-grey';
                                if (!$loop->last) {
                                    $nextStatus = array_keys($steps)[$loop->index + 1];
                                    $nextOrder  = $statusOrder[$nextStatus];
                                    if ($currentStatus === 'Disbursed') {
                                        $lineColorClass = 'line-green';
                                    } elseif ($nextOrder < $currentOrder) {
                                        $lineColorClass = 'line-green';
                                    } elseif ($nextOrder === $currentOrder) {
                                        $lineColorClass = 'line-blue';
                                    }
                                }
                            @endphp

                            <div class="step {{ $state }}">
                                <div class="step-icon">
                                    <i class="fas {{ $data['icon'] }}"></i>
                                </div>
                                <span class="step-label">{{ $data['label'] }}</span>
                            </div>

                            @if (!$loop->last)
                                <div class="step-line {{ $lineColorClass }}"></div>
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- Log Cards --}}
                <div class="track-log-grid">
                    @if (count($filteredLogs) > 0)
                        @foreach ($filteredLogs as $index => $log)
                            @php
                                $status      = $log->status;
                                $isRollback  = str_contains($status, '[ROLLED BACK]');
                                $baseStatus  = $isRollback ? trim(str_replace('[ROLLED BACK]', '', $status)) : $status;

                                $isCurrentStatus = $baseStatus === $currentStatus;
                                $isStepCompleted = false;

                                if (!$isCurrentStatus && isset($statusOrder[$baseStatus], $statusOrder[$currentStatus])) {
                                    $isStepCompleted = $statusOrder[$baseStatus] < $statusOrder[$currentStatus];
                                }

                                $department = '';
                                $statusText = '';
                                $icon       = 'fa-question-circle';

                                switch ($baseStatus) {
                                    case 'Processing':
                                        $department = 'CSWD Office';
                                        $statusText = 'Application is on-process at CSWD Office';
                                        $icon       = 'fa-hourglass-half';
                                        break;
                                    case 'Submitted':
                                    case 'Submitted[Emergency]':
                                        $department = "Mayor's Office";
                                        $statusText = "Application is on-process at Mayor's Office";
                                        $icon       = 'fa-paper-plane';
                                        break;
                                    case 'Approved':
                                        $department = 'Budget Office';
                                        $statusText = 'Application is on-process at Budget Office';
                                        $icon       = 'fa-check-circle';
                                        break;
                                    case 'Budget Allocated':
                                        $department = 'Accounting Office';
                                        $statusText = 'Application is on-process at Accounting Office';
                                        $icon       = 'fa-wallet';
                                        break;
                                    case 'DV Submitted':
                                        $department = 'Treasury Office';
                                        $statusText = 'Application is on-process at Treasury Office';
                                        $icon       = 'fa-file-invoice-dollar';
                                        break;
                                    case 'Ready for Disbursement':
                                        $department = 'Treasury Office';
                                        $statusText = 'Please wait for a text message to be sent via SMS';
                                        $icon       = 'fa-clock';
                                        break;
                                    case 'Disbursed':
                                        $department      = 'Treasury Office';
                                        $statusText      = 'Disbursed';
                                        $icon            = 'fa-hand-holding-usd';
                                        $isStepCompleted = true;
                                        $isCurrentStatus = false;
                                        break;
                                    case 'Rejected':
                                        $department      = '';
                                        $statusText      = 'Discrepancy is detected on your application. Please call CSWD or Aksyon Mamamayan Center for more inquiries.';
                                        $icon            = 'fa-exclamation-triangle';
                                        $isCurrentStatus = true;
                                        break;
                                    default:
                                        $department = '';
                                        $statusText = $log->remarks ?? 'No remarks provided';
                                        $icon       = 'fa-question-circle';
                                        break;
                                }

                                // Card variant class
                                if ($baseStatus === 'Rejected') {
                                    $cardVariant = 'card-rejected';
                                } elseif ($isCurrentStatus) {
                                    $cardVariant = 'card-current';
                                } elseif ($isStepCompleted) {
                                    $cardVariant = 'card-completed';
                                } else {
                                    $cardVariant = 'card-pending';
                                }

                                $badgeType = '';
                                $badgeIcon = '';
                                $badgeText = '';
                                if ($isStepCompleted) {
                                    $badgeType = 'completed';
                                    $badgeIcon = 'fa-check-circle';
                                    $badgeText = 'Completed';
                                } elseif ($isCurrentStatus && $baseStatus !== 'Rejected') {
                                    $badgeType = 'current';
                                    $badgeIcon = 'fa-spinner fa-pulse';
                                    $badgeText = 'Current';
                                } elseif ($baseStatus === 'Rejected') {
                                    $badgeType = 'rejected';
                                    $badgeIcon = 'fa-exclamation-triangle';
                                    $badgeText = 'On Hold';
                                }

                                if (!empty($log->status_date)) {
                                    $date = \Carbon\Carbon::parse($log->status_date);
                                    $date->setTimezone('Asia/Manila');
                                    $formattedDate = $date->format('F j, Y g:i A');
                                } else {
                                    $formattedDate = now()->setTimezone('Asia/Manila')->format('F j, Y g:i A');
                                }
                            @endphp

                            <div class="track-log-card {{ $cardVariant }} {{ $isCurrentStatus ? 'is-current' : '' }}">
                                <div class="track-log-card-icon">
                                    <i class="fas {{ $icon }}"></i>
                                </div>
                                <div class="track-log-card-body">
                                    <p class="track-log-date">{{ $formattedDate }}</p>
                                    @if ($department)
                                        <p class="track-log-dept">{{ $department }}</p>
                                    @endif
                                    <p class="track-log-status"><strong>Status:</strong> {{ $statusText }}</p>
                                </div>
                                @if ($badgeType && $baseStatus !== 'Rejected')
                                    <div class="track-log-badge badge-{{ $badgeType }}">
                                        <i class="fas {{ $badgeIcon }}"></i>
                                        <span>{{ $badgeText }}</span>
                                    </div>
                                @endif
                                @if ($baseStatus === 'Rejected')
                                    <div class="track-log-badge badge-rejected">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <span>On Hold</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="track-log-card card-pending">
                            <div class="track-log-card-icon">
                                <i class="fas fa-hourglass-start"></i>
                            </div>
                            <div class="track-log-card-body">
                                <p class="track-log-date">{{ now()->setTimezone('Asia/Manila')->format('F j, Y g:i A') }}</p>
                                <p class="track-log-dept">Application Received</p>
                                <p class="track-log-status"><strong>Status:</strong> Please wait for further announcement. Your application is currently being processed.</p>
                            </div>
                            <div class="track-log-badge badge-pending">
                                <i class="fas fa-hourglass-start"></i>
                                <span>Pending</span>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Track another --}}
                <div class="track-search-wrap">
                    <div class="track-search-card">
                        <i class="fas fa-search track-search-icon"></i>
                        <h3>Track Another Application</h3>
                        <p>Enter your tracking number to check the status of a different application.</p>
                        <form action="{{ route('track.application') }}" method="GET" class="track-search-form">
                            <input type="text" name="tracking_number" class="form-control" placeholder="e.g. TRK-2026-00001" required>
                            <button type="submit" class="btn-neon">
                                <i class="fas fa-search me-2"></i>Track Now
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </section>

        {{-- ===== FOOTER (matches home page) ===== --}}
        <footer class="footer">
            <div class="footer-container">
                <div class="footer-brand">
                    <div class="footer-logo">
                        <img src="{{ asset('home-logo (1).png') }}" alt="WeServe Logo" class="logo-full" loading="eager">
                    </div>
                    <div class="footer-brand-divider"></div>
                    <p>Providing support when it's needed most. Dedicated to helping communities and individuals achieve their best.</p>
                </div>

                <div class="footer-links">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="/#about">About Us</a></li>
                        <li><a href="/#categories">Services</a></li>
                        <li><a href="/#process">Application Process</a></li>
                        <li><a href="/#contact">Contact</a></li>
                    </ul>
                </div>

                <div class="footer-contact">
                    <h3>Contact</h3>
                    <p><i class="fas fa-envelope"></i> cswdosanpedro@gmail.com</p>
                    <p><i class="fas fa-phone-alt"></i> 8-8082020</p>
                    <p><i class="fas fa-map-marker-alt"></i> Basement, New City Hall Bldg., Brgy. Poblacion, City of San Pedro, Laguna</p>
                    <p><i class="fas fa-clock"></i> Mon – Fri, 8:00 AM – 5:00 PM</p>
                </div>

                <div class="footer-socials" aria-label="Social media">
                    <h3>Follow Us</h3>
                    <div class="footer-socials-icons">
                        <a href="#" class="footer-social-link" aria-label="Facebook">
                            <span class="footer-social-icon"><i class="fab fa-facebook-f"></i></span> Facebook
                        </a>
                        <a href="#" class="footer-social-link" aria-label="Twitter">
                            <span class="footer-social-icon"><i class="fab fa-twitter"></i></span> Twitter
                        </a>
                        <a href="#" class="footer-social-link" aria-label="Instagram">
                            <span class="footer-social-icon"><i class="fab fa-instagram"></i></span> Instagram
                        </a>
                        <a href="#" class="footer-social-link" aria-label="LinkedIn">
                            <span class="footer-social-icon"><i class="fab fa-linkedin-in"></i></span> LinkedIn
                        </a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="footer-left">&copy; 2026 WeServe. All rights reserved.</div>
                <div class="footer-right">
                    <a href="{{ route('terms-and-conditions') }}">Terms and Conditions</a>
                    <span>|</span>
                    <a href="{{ route('privacy-policy') }}">Privacy Policy</a>
                </div>
            </div>
        </footer>
    </main>

    @push('styles')
    <style>
        /* =========================================
           TRACKING PAGE — STYLES
           (shares all home page CSS variables)
        ========================================= */

        /* ---------- Hero ---------- */
        .track-hero {
            background: linear-gradient(135deg, #064e3b 0%, #0a7a5e 60%, #0d9668 100%);
            padding: 7rem 1.5rem 5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .track-hero::before {
            content: "";
            position: absolute;
            top: -80px; right: -80px;
            width: 300px; height: 300px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
            pointer-events: none;
        }

        .track-hero-inner {
            position: relative;
            z-index: 1;
            max-width: 680px;
            margin: 0 auto;
        }

        .track-hero-inner .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #064e3b;
            background: #d1fae5;
            border: 1.5px solid #a7f3d0;
            padding: 0.4rem 1rem;
            border-radius: 100px;
            margin-bottom: 1.6rem;
        }

        .track-hero-inner h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 3.2rem;
            font-weight: 400;
            color: #fff;
            line-height: 1.1;
            letter-spacing: -0.02em;
            margin-bottom: 1rem;
        }

        .track-hero-inner h1 em {
            font-style: italic;
            color: #6ee7b7;
        }

        .track-hero-inner p {
            font-family: 'DM Sans', sans-serif;
            font-size: 1.05rem;
            color: rgba(255,255,255,0.75);
            line-height: 1.8;
            max-width: 500px;
            margin: 0 auto;
        }

        .track-hero-wave {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            line-height: 0;
            z-index: 2;
        }

        .track-hero-wave svg {
            width: 100%;
            height: 70px;
        }

        /* ---------- Process Section ---------- */
        .track-process-section {
            padding: 6rem 1.5rem;
            background: #f7faf9;
        }

        .track-process-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            align-items: stretch;
            gap: 0.75rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .track-process-card {
            background: white;
            border-radius: 2rem;
            border: none;
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
            cursor: default;
            position: relative;
            overflow: hidden;
            transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.35s ease;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            text-align: left;
        }

        .track-process-card::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, #064e3b, #10b981);
            border-radius: 2rem 2rem 0 0;
            opacity: 0;
            transform: scaleX(0.3);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .track-process-card:hover::before { opacity: 1; transform: scaleX(1); }
        .track-process-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(6,78,59,0.1);
        }

        .track-process-card:hover .category-icon-circle {
            background: #064e3b;
            border-color: #064e3b;
            transform: scale(1.1) rotate(-5deg);
        }

        .track-process-card:hover .category-icon-circle i { color: white; }

        .track-card-num {
            position: absolute;
            top: 1rem; right: 1.2rem;
            font-family: 'DM Serif Display', serif;
            font-size: 2.5rem;
            color: #f0fdf4;
            line-height: 1;
            pointer-events: none;
            user-select: none;
        }

        .track-process-card h3 {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 700;
            color: #0d1a14;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin: 0;
        }

        .track-process-card p {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.82rem;
            color: #6b7280;
            line-height: 1.65;
            margin: 0;
        }

        /* (connectors removed — grid handles spacing) */

        /* ---------- Results Section ---------- */
        .track-results-section {
            padding: 6rem 1.5rem;
            background: white;
        }

        /* Stepper wrapper */
        .track-stepper-wrap {
            display: flex;
            justify-content: center;
            margin-bottom: 3rem;
        }

        .process-stepper {
            display: flex;
            align-items: center;
            gap: 0;
            background: #f7faf9;
            border: 1.5px solid #e5e7eb;
            border-radius: 2rem;
            padding: 1.25rem 2rem;
            flex-wrap: wrap;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            min-width: 70px;
        }

        .step-icon {
            width: 52px; height: 52px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            border: 2px solid #e5e7eb;
            background: white;
            color: #9ca3af;
            transition: all 0.3s ease;
        }

        .step.completed .step-icon {
            background: #064e3b;
            border-color: #064e3b;
            color: white;
            box-shadow: 0 4px 12px rgba(6,78,59,0.25);
        }

        .step.current .step-icon {
            background: #0ea5e9;
            border-color: #0ea5e9;
            color: white;
            box-shadow: 0 4px 12px rgba(14,165,233,0.3);
            animation: pulse-step 2s ease-in-out infinite;
        }

        @keyframes pulse-step {
            0%,100% { box-shadow: 0 4px 12px rgba(14,165,233,0.3); }
            50%      { box-shadow: 0 4px 20px rgba(14,165,233,0.55); }
        }

        .step-label {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #9ca3af;
        }

        .step.completed .step-label { color: #064e3b; }
        .step.current   .step-label { color: #0ea5e9; }

        .step-line {
            height: 2px;
            width: 40px;
            border-radius: 2px;
            background: #e5e7eb;
            flex-shrink: 0;
            margin-bottom: 18px; /* align with icon row */
        }

        .step-line.line-green { background: #064e3b; }
        .step-line.line-blue  { background: #0ea5e9; }

        /* ---------- Log Cards ---------- */
        .track-log-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            max-width: 780px;
            margin: 0 auto 3rem;
        }

        .track-log-card {
            display: flex;
            align-items: flex-start;
            gap: 1.25rem;
            padding: 1.5rem 1.75rem;
            border-radius: 1.5rem;
            border: 1.5px solid transparent;
            position: relative;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .track-log-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.06);
        }

        /* Card variants */
        .track-log-card.card-completed {
            background: #f0fdf4;
            border-color: #bbf7d0;
        }

        .track-log-card.card-current {
            background: #eff6ff;
            border-color: #bfdbfe;
            box-shadow: 0 8px 25px rgba(14,165,233,0.1);
        }

        .track-log-card.card-rejected {
            background: #fffbeb;
            border-color: #fde68a;
        }

        .track-log-card.card-pending {
            background: #f9fafb;
            border-color: #e5e7eb;
        }

        /* Icon circle per variant */
        .track-log-card-icon {
            width: 50px; height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        .card-completed  .track-log-card-icon { background: #d1fae5; color: #064e3b; }
        .card-current    .track-log-card-icon { background: #dbeafe; color: #0ea5e9; }
        .card-rejected   .track-log-card-icon { background: #fef3c7; color: #d97706; }
        .card-pending    .track-log-card-icon { background: #f3f4f6; color: #9ca3af; }

        .track-log-card-body { flex: 1; }

        .track-log-date {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 0.3rem;
        }

        .card-completed .track-log-date { color: #059669; }
        .card-current   .track-log-date { color: #0ea5e9; }
        .card-rejected  .track-log-date { color: #d97706; }
        .card-pending   .track-log-date { color: #9ca3af; }

        .track-log-dept {
            font-family: 'DM Sans', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .card-completed .track-log-dept { color: #064e3b; }
        .card-current   .track-log-dept { color: #1e40af; }
        .card-rejected  .track-log-dept { color: #92400e; }
        .card-pending   .track-log-dept { color: #374151; }

        .track-log-status {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.88rem;
            line-height: 1.6;
            margin: 0;
        }

        .card-completed .track-log-status { color: #065f46; }
        .card-current   .track-log-status { color: #1e3a8a; }
        .card-rejected  .track-log-status { color: #78350f; }
        .card-pending   .track-log-status { color: #6b7280; }

        /* Badges */
        .track-log-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.85rem;
            border-radius: 100px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            white-space: nowrap;
            flex-shrink: 0;
            align-self: flex-start;
        }

        .badge-completed { background: #d1fae5; color: #064e3b; }
        .badge-current   { background: #dbeafe; color: #1d4ed8; }
        .badge-rejected  { background: #fef3c7; color: #92400e; }
        .badge-pending   { background: #f3f4f6; color: #6b7280; }

        /* ---------- Search Card ---------- */
        .track-search-wrap {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
        }

        .track-search-card {
            background: #f7faf9;
            border: 1.5px solid #bbf7d0;
            border-radius: 2rem;
            padding: 2.5rem 2rem;
            text-align: center;
            max-width: 520px;
            width: 100%;
        }

        .track-search-icon {
            font-size: 2rem;
            color: #064e3b;
            margin-bottom: 1rem;
            display: block;
        }

        .track-search-card h3 {
            font-family: 'DM Sans', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: #0d1a14;
            margin-bottom: 0.5rem;
        }

        .track-search-card p {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            color: #6b7280;
            margin-bottom: 1.5rem;
        }

        .track-search-form {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .track-search-form .form-control {
            flex: 1;
            min-width: 200px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.92rem;
            border: 1.5px solid #e5e7eb;
            border-radius: 0.85rem;
            padding: 0.65rem 1rem;
            background: white;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .track-search-form .form-control:focus {
            border-color: #064e3b;
            box-shadow: 0 0 0 4px rgba(6,78,59,0.08);
            outline: none;
        }

        /* ---------- Section headers reuse home styles ---------- */
        .track-results-section .categories-header,
        .track-process-section .categories-header {
            margin-bottom: 3rem;
            text-align: center;
        }

        /* =========================================
           RESPONSIVE — MOBILE FIXES
        ========================================= */

        /* --- Tablet (≤768px) --- */
        @media (max-width: 768px) {

            /* Hero */
            .track-hero { padding: 5rem 1.25rem 4rem; }
            .track-hero-inner h1 { font-size: 2.2rem; }
            .track-hero-inner p  { font-size: 0.95rem; }

            /* Process section */
            .track-process-section { padding: 4rem 1.25rem; }
            .track-process-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
            .track-process-connector { display: none; }
            .track-process-card {
                max-width: 100%;
                min-width: unset;
                flex: unset;
            }

            /* Results section */
            .track-results-section { padding: 4rem 1.25rem; }

            /* Stepper — horizontal scroll strip */
            .track-stepper-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; padding-bottom: 0.5rem; }
            .process-stepper {
                flex-wrap: nowrap;
                gap: 0;
                padding: 1rem 1.25rem;
                min-width: max-content;
                width: auto;
            }
            .step { min-width: 60px; }
            .step-icon { width: 44px; height: 44px; font-size: 0.95rem; }
            .step-label { font-size: 0.6rem; }
            .step-line { width: 28px; margin-bottom: 16px; }

            /* Log cards — stack icon + badge */
            .track-log-card {
                flex-wrap: wrap;
                gap: 0.85rem;
                padding: 1.25rem;
            }
            .track-log-card-icon {
                width: 42px; height: 42px;
                border-radius: 12px;
                font-size: 1rem;
            }
            .track-log-card-body { flex: 1; min-width: 0; }
            .track-log-badge {
                width: 100%;
                justify-content: center;
                margin-top: 0.25rem;
            }

            /* Search card */
            .track-search-card { padding: 2rem 1.25rem; border-radius: 1.5rem; }
            .track-search-form {
                flex-direction: column;
                align-items: stretch;
                gap: 0.75rem;
            }
            .track-search-form .form-control { min-width: unset; width: 100%; }
            .track-search-form .btn-neon { width: 100%; text-align: center; }
        }

        /* --- Small mobile (≤480px) --- */
        @media (max-width: 480px) {

            /* Hero */
            .track-hero { padding: 4.5rem 1rem 3.5rem; }
            .track-hero-inner h1 { font-size: 1.85rem; }
            .track-hero-inner .hero-eyebrow { font-size: 0.65rem; padding: 0.35rem 0.8rem; }

            /* Process cards — single column */
            .track-process-grid { grid-template-columns: 1fr; }
            .track-process-card { padding: 1.5rem 1.25rem; border-radius: 1.25rem; }
            .track-card-num { font-size: 2rem; }

            /* Stepper — vertical stacked */
            .track-stepper-wrap { overflow-x: visible; padding-bottom: 0; }
            .process-stepper {
                flex-direction: column;
                flex-wrap: nowrap;
                align-items: flex-start;
                min-width: unset;
                width: 100%;
                padding: 1.25rem;
                gap: 0;
                border-radius: 1.25rem;
            }
            .step {
                flex-direction: row;
                align-items: center;
                gap: 0.85rem;
                min-width: unset;
                width: 100%;
                padding: 0.25rem 0;
            }
            .step-icon {
                width: 40px; height: 40px;
                font-size: 0.9rem;
                flex-shrink: 0;
            }
            .step-label {
                font-size: 0.78rem;
                letter-spacing: 0.04em;
                text-align: left;
            }
            /* vertical connector line between steps */
            .step-line {
                width: 2px;
                height: 24px;
                margin: 0 0 0 19px; /* aligns with center of 40px icon */
                flex-shrink: 0;
                display: block;
            }

            /* Log cards */
            .track-log-card { border-radius: 1.25rem; padding: 1.1rem; }
            .track-log-dept { font-size: 0.92rem; }
            .track-log-status { font-size: 0.82rem; }
            .track-log-date { font-size: 0.68rem; }

            /* Search card */
            .track-search-card { border-radius: 1.25rem; padding: 1.5rem 1rem; }

            /* Section labels & headings */
            .track-results-section .categories-header h2,
            .track-process-section .categories-header h2 {
                font-size: 2rem;
            }
        }
    </style>
    @endpush

    <script>
        // Header scroll shadow (same as home)
        const siteHeader = document.getElementById('siteHeader');
        if (siteHeader) {
            window.addEventListener('scroll', () => {
                siteHeader.classList.toggle('scrolled', window.scrollY > 20);
            }, { passive: true });
        }

        function toggleMenu() {
            const nav = document.getElementById('navMenu');
            const isOpen = nav.classList.toggle('show');
            nav.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
            document.body.style.overflow = isOpen ? 'hidden' : '';
        }

        function closeMenu() {
            const nav = document.getElementById('navMenu');
            if (nav) {
                nav.classList.remove('show');
                nav.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            }
        }

        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeMenu(); });
    </script>

@endsection