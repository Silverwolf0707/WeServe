@extends('layouts.home')
@section('title', 'WeServe - Application Tracking')
@section('content')

<header class="header" role="banner">
    <div class="header-container">
       <a href="#home" class="logo-title block w-50 lg:w-38 md:w-34 sm:w-30" aria-label="WeServe Home">
                <img src="{{ asset('WeServe.png') }}" alt="WeServe Logo" class="logo-full w-full h-auto" loading="eager">
            </a>

        <button class="burger" onclick="toggleMenu()" aria-label="Toggle Menu">
            <i class="fas fa-bars"></i>
        </button>

        <nav class="nav-links" id="navMenu" aria-label="Primary">
            <a href="/" class="apply-link">Go to Application</a>
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
                @php
                    // Group logs by status and get only the latest of each status
                    $filteredLogs = [];
                    
                    // Process logs in reverse to get the most recent of each status
                    $uniqueStatuses = [];
                    foreach (array_reverse($logs->toArray()) as $log) {
                        $status = $log['status'];
                        $isRollback = str_contains($status, '[ROLLED BACK]');
                        
                        // Skip rollback entries from display
                        if ($isRollback) {
                            continue;
                        }
                        
                        // If we haven't seen this status before, add it
                        if (!in_array($status, $uniqueStatuses)) {
                            $uniqueStatuses[] = $status;
                            $filteredLogs[] = (object) $log;
                        }
                    }
                    
                    // Reverse back to chronological order
                    $filteredLogs = array_reverse($filteredLogs);
                @endphp

                @if (count($filteredLogs) > 0)
                    @foreach ($filteredLogs as $log)
                        @php
                            $status = $log->status;
                            $isRollback = str_contains($status, '[ROLLED BACK]');
                            $baseStatus = $isRollback ? trim(str_replace('[ROLLED BACK]', '', $status)) : $status;

                            // Define department, status text, color and icon
                            $department = '';
                            $statusText = '';
                            $color = '#6c757d';
                            $icon = 'fa-question-circle';
                            $textColor = 'white';

                            switch ($baseStatus) {
                                case 'Processing':
                                    $department = 'CSWD Office';
                                    $statusText = 'Application is on-process at CSWD Office';
                                    $color = '#6c757d'; // gray
                                    $icon = 'fa-hourglass-half';
                                    break;
                                case 'Submitted':
                                    $department = 'Mayor\'s Office';
                                    $statusText = 'Application is on-process at Mayor\'s Office';
                                    $color = '#28a745'; // green
                                    $icon = 'fa-paper-plane';
                                    break;
                                case 'Approved':
                                    $department = 'Budget Office';
                                    $statusText = 'Application is on-process at Budget Office';
                                    $color = '#28a745'; // green
                                    $icon = 'fa-check-circle';
                                    break;
                                case 'Budget Allocated':
                                    $department = 'Accounting Office';
                                    $statusText = 'Application is on-process at Accounting Office';
                                    $color = '#d4a017'; // dark yellow
                                    $icon = 'fa-wallet';
                                    $textColor = 'black';
                                    break;
                                case 'DV Submitted':
                                    $department = 'Treasury Office';
                                    $statusText = 'Application is on-process at Treasury Office';
                                    $color = '#17a2b8'; // light blue
                                    $icon = 'fa-file-invoice-dollar';
                                    break;
                                case 'Ready for Disbursement':
                                    $department = 'Treasury Office';
                                    $statusText = 'Please wait for a text message to be sent via SMS';
                                    $color = '#28a745'; // green
                                    $icon = 'fa-clock';
                                    break;
                                case 'Disbursed':
                                    $department = 'Treasury Office';
                                    $statusText = 'Disbursed';
                                    $color = '#28a745'; // green
                                    $icon = 'fa-hand-holding-usd';
                                    break;
                                case 'Rejected':
                                    $department = '';
                                    $statusText = 'Application is On-Hold, Discrepancy is detected on your application please call CSWD or Aksyon mamamayan center for more inquiries';
                                    $color = '#dc3545'; // red
                                    $icon = 'fa-times-circle';
                                    break;
                                default:
                                    $department = '';
                                    $statusText = $log->remarks ?: 'No remarks provided';
                                    break;
                            }

                            // For rejected status, override with custom text
                            if ($baseStatus === 'Rejected' && !empty($log->remarks)) {
                                $statusText = $log->remarks;
                            }
                            
                            // Convert to correct timezone - FIXED THIS PART
                            $date = \Carbon\Carbon::parse($log->status_date);
                            
                            // Set to Asia/Manila timezone (Philippines)
                            $date->setTimezone('Asia/Manila');
                            
                            // Format the date correctly
                            $formattedDate = $date->format('F j, Y g:i A');
                        @endphp

                        <div class="tracking-entry"
                            style="background-color: {{ $color }}; border-left: 6px solid {{ $color }}; color: {{ $textColor }};">
                            <div class="status-icon" style="background-color: rgba(255,255,255,0.2);">
                                <i class="fas {{ $icon }}" style="color: {{ $textColor }};"></i>
                            </div>

                            <div class="status-details">
                                <p class="tracking-date" style="color: {{ $textColor }};">
                                    {{ $formattedDate }}
                                </p>
                                @if($department)
                                    <p class="tracking-department" style="color: {{ $textColor }}; font-weight: bold; margin-bottom: 5px;">
                                        {{ $department }}
                                    </p>
                                @endif
                                <p class="tracking-status" style="color: {{ $textColor }};">
                                    <b>Status:</b> {{ $statusText }}
                                </p>
                            </div>
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