@extends('layouts.home')
@section('title', 'WeServe - Application Tracking')
@section('content')

    <header class="header1" role="banner">
        <div class="header-container">
            <!-- Logo -->
            <a href="#home" class="logo-title" aria-label="WeServe Home">
                <img src="{{ asset('WeServe.png') }}" alt="WeServe Logo" class="logo-full" loading="eager">
            </a>

            <!-- Nav menu -->
            <nav class="nav-links" id="navMenu" aria-label="Primary">
                <span class="close-btn" onclick="toggleMenu()">&times;</span>
                <a href="/" class="apply-link">Go to Application</a>
                <a href="#tracking-process">Process</a>
                <a href="#tracking">Track</a>
            </nav>

            <!-- Burger button -->
            <button class="burger" onclick="toggleMenu()" aria-label="Toggle Menu">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>


    <section class="tracking-process-section" id="tracking-process">
    <div class="tracking-process-container">
        <h2 class="tracking-process-title">Application Process</h2>
        <div class="tracking-process-grid">
            <div class="tracking-process-card">
                <div class="tracking-step-circle">1</div>
                <div class="tracking-process-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>CSWD</h3>
                <p>Initial review and verification of submitted application.</p>
            </div>

            <div class="tracking-process-card">
                <div class="tracking-step-circle">2</div>
                <div class="tracking-process-icon">
                    <i class="fas fa-building"></i>
                </div>
                <h3>Mayor's Office</h3>
                <p>Approval and endorsement of application.</p>
            </div>

            <div class="tracking-process-card">
                <div class="tracking-step-circle">3</div>
                <div class="tracking-process-icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <h3>Budget</h3>
                <p>Funding allocation and verification.</p>
            </div>

            <div class="tracking-process-card">
                <div class="tracking-step-circle">4</div>
                <div class="tracking-process-icon">
                    <i class="fas fa-calculator"></i>
                </div>
                <h3>Accounting</h3>
                <p>Processing of financial disbursement.</p>
            </div>

            <div class="tracking-process-card">
                <div class="tracking-step-circle">5</div>
                <div class="tracking-process-icon">
                    <i class="fas fa-coins"></i>
                </div>
                <h3>Treasury</h3>
                <p>Final release of assistance to claimant.</p>
            </div>
        </div>
    </div>
</section>

<style>

</style>
    <section class="tracking" id="tracking">
        <div class="container tracking-container">
            <div class="tracking-header text-center">
                <h1>Tracking Summary</h1>

                @php
                    // Initialize filteredLogs as empty array
                    $filteredLogs = [];
                    $currentStatus = null;

                    // Define the order of statuses for comparison
                    $statusOrder = [
                        'Processing' => 1,
                        'Submitted' => 2,
                        'Submitted[Emergency]' => 2,
                        'Approved' => 3,
                        'Budget Allocated' => 4,
                        'DV Submitted' => 5,
                        'Ready for Disbursement' => 6,
                        'Disbursed' => 7,
                        'Rejected' => 0, // Special case - always current when it's latest
];

// Check if logs exist and is not empty
if (isset($logs) && $logs && count($logs) > 0) {
    // Convert logs to array if it's a collection
                        $logsArray = is_object($logs) && method_exists($logs, 'toArray') ? $logs->toArray() : $logs;

                        // Get ALL statuses to analyze the flow
                        $allStatuses = [];
                        foreach ($logsArray as $log) {
                            $status = $log['status'] ?? null;
                            if ($status && !str_contains($status, '[ROLLED BACK]')) {
                                $allStatuses[] = $status;
                            }
                        }

                        // Determine the latest non-rejected status
                        $latestNonRejectedStatus = null;
                        $hasRejectionInHistory = false;
                        $rejectionWasLast = false;

                        // Check the statuses from latest to earliest
                        $reversedStatuses = array_reverse($allStatuses);
                        foreach ($reversedStatuses as $status) {
                            $cleanStatus = trim(str_replace('[ROLLED BACK]', '', $status));

                            if ($cleanStatus === 'Rejected') {
                                $hasRejectionInHistory = true;
                                // Check if rejection is the absolute latest status
                                if ($latestNonRejectedStatus === null) {
                                    $rejectionWasLast = true;
                                }
                                continue;
                            }

                            if ($latestNonRejectedStatus === null) {
                                $latestNonRejectedStatus = $cleanStatus;
                            }
                        }

                        // Process logs to show only relevant ones
                        $uniqueStatuses = [];
                        $processedLogs = [];

                        foreach ($logsArray as $log) {
                            $status = $log['status'] ?? null;
                            if (!$status) {
                                continue;
                            }

                            $isRollback = str_contains($status, '[ROLLED BACK]');
                            if ($isRollback) {
                                continue;
                            }

                            $cleanStatus = trim(str_replace('[ROLLED BACK]', '', $status));

                            // Skip rejected status if it's NOT the latest and there's a newer submitted status
                            if ($cleanStatus === 'Rejected' && !$rejectionWasLast) {
                                continue;
                            }

                            // Only show the most recent occurrence of each status
                            if (!in_array($cleanStatus, $uniqueStatuses)) {
                                $uniqueStatuses[] = $cleanStatus;
                                $processedLogs[] = (object) $log;
                            }
                        }

                        // Set filtered logs
                        $filteredLogs = $processedLogs;

                        // Determine current status for completion logic

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
                        'Processing' => ['label' => 'CSWD', 'icon' => 'fa-users'],
                        'Submitted' => ['label' => 'Mayor', 'icon' => 'fa-building'],
                        'Approved' => ['label' => 'Budget', 'icon' => 'fa-wallet'],
                        'Budget Allocated' => ['label' => 'Accounting', 'icon' => 'fa-calculator'],
                        'Disbursed' => ['label' => 'Treasury', 'icon' => 'fa-coins'],
                    ];

                    $currentOrder = $statusOrder[$currentStatus] ?? 0;
                @endphp

                <div class="process-stepper">
                    @foreach ($steps as $status => $data)
                        @php
                            $order = $statusOrder[$status];

                            // Determine step state
                            if ($currentStatus === 'Disbursed') {
                                $state = 'completed';
                            } else {
                                if ($order < $currentOrder) {
                                    $state = 'completed';
                                } elseif ($order === $currentOrder) {
                                    $state = 'current';
                                } else {
                                    $state = 'pending';
                                }
                            }

                            // Determine line color for the line AFTER this step
                            // The line color depends on the NEXT step's state
$lineColorClass = 'line-grey'; // Default grey

if (!$loop->last) {
    // Get the next step's status
                                $nextStatus = array_keys($steps)[$loop->index + 1];
                                $nextOrder = $statusOrder[$nextStatus];

                                if ($currentStatus === 'Disbursed') {
                                    // All lines green when disbursed
                                    $lineColorClass = 'line-green';
                                } elseif ($nextOrder < $currentOrder) {
                                    // Next step is completed, line should be green
                                    $lineColorClass = 'line-green';
                                } elseif ($nextOrder === $currentOrder) {
                                    // Next step is current, line should be blue
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

            <div class="tracking-log-card-wrapper">
                <div class="tracking-log-card">


                    @if (count($filteredLogs) > 0)
                        @foreach ($filteredLogs as $index => $log)
                            @php
                                $status = $log->status;
                                $isRollback = str_contains($status, '[ROLLED BACK]');
                                $baseStatus = $isRollback ? trim(str_replace('[ROLLED BACK]', '', $status)) : $status;

                                // Determine if this is current or completed
                                $isCurrentStatus = $baseStatus === $currentStatus;
                                $isStepCompleted = false;

                                if (
                                    !$isCurrentStatus &&
                                    isset($statusOrder[$baseStatus], $statusOrder[$currentStatus])
                                ) {
                                    $isStepCompleted = $statusOrder[$baseStatus] < $statusOrder[$currentStatus];
                                }

                                // Define styling based on actual status
                                $department = '';
                                $statusText = '';
                                $color = '#6c757d';
                                $icon = 'fa-question-circle';
                                $textColor = 'white';

                                switch ($baseStatus) {
                                    case 'Processing':
                                        $department = 'CSWD Office';
                                        $statusText = 'Application is on-process at CSWD Office';
                                        $icon = 'fa-hourglass-half';
                                        break;
                                    case 'Submitted':
                                    case 'Submitted[Emergency]':
                                        $department = 'Mayor\'s Office';
                                        $statusText = 'Application is on-process at Mayor\'s Office';
                                        $icon = 'fa-paper-plane';
                                        break;
                                    case 'Approved':
                                        $department = 'Budget Office';
                                        $statusText = 'Application is on-process at Budget Office';
                                        $icon = 'fa-check-circle';
                                        break;
                                    case 'Budget Allocated':
                                        $department = 'Accounting Office';
                                        $statusText = 'Application is on-process at Accounting Office';
                                        $icon = 'fa-wallet';
                                        break;
                                    case 'DV Submitted':
                                        $department = 'Treasury Office';
                                        $statusText = 'Application is on-process at Treasury Office';
                                        $icon = 'fa-file-invoice-dollar';
                                        break;
                                    case 'Ready for Disbursement':
                                        $department = 'Treasury Office';
                                        $statusText = 'Please wait for a text message to be sent via SMS';
                                        $icon = 'fa-clock';
                                        break;
                                    case 'Disbursed':
                                        $department = 'Treasury Office';
                                        $statusText = 'Disbursed';
                                        $icon = 'fa-hand-holding-usd';
                                        $isStepCompleted = true;
                                        $isCurrentStatus = false;
                                        break;
                                    case 'Rejected':
                                        $department = '';
                                        $statusText =
                                            'Discrepancy is detected on your application. Please call CSWD or Aksyon Mamamayan Center for more inquiries.';
                                        $icon = 'fa-exclamation-triangle';
                                        $textColor = 'black';
                                        $isCurrentStatus = true;
                                        break;
                                    default:
                                        $department = '';
                                        $statusText = $log->remarks ?? 'No remarks provided';
                                        $icon = 'fa-question-circle';
                                        break;
                                }

                                // Determine badge type
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

                                // Determine background, border, and text color
                                if ($baseStatus === 'Rejected') {
                                    $entryBg = '#ffc107';
                                    $entryBorder = '#ffc107';
                                    $entryText = 'black';
                                } elseif ($isCurrentStatus) {
                                    $entryBg = '#17a2b8'; // Blue for current
                                    $entryBorder = '#17a2b8';
                                    $entryText = 'white';
                                } elseif ($isStepCompleted) {
                                    $entryBg = '#28a745'; // Green for completed
                                    $entryBorder = '#28a745';
                                    $entryText = 'white';
                                } else {
                                    $entryBg = '#6c757d'; // Gray for pending
                                    $entryBorder = '#6c757d';
                                    $entryText = 'white';
                                }

                                // Convert to correct timezone
                                if (!empty($log->status_date)) {
                                    $date = \Carbon\Carbon::parse($log->status_date);
                                    $date->setTimezone('Asia/Manila');
                                    $formattedDate = $date->format('F j, Y g:i A');
                                } else {
                                    $formattedDate = now()->setTimezone('Asia/Manila')->format('F j, Y g:i A');
                                }
                            @endphp

                            <div class="tracking-entry {{ $isCurrentStatus ? 'current-status' : '' }}"
                                style="background-color: {{ $entryBg }};
                border-left: 6px solid {{ $entryBorder }};
                color: {{ $entryText }};">

                                <div class="status-details">
                                    <p class="tracking-date" style="color: {{ $entryText }};">
                                        {{ $formattedDate }}
                                    </p>
                                    @if ($department)
                                        <p class="tracking-department"
                                            style="color: {{ $entryText }}; font-weight: bold; margin-bottom: 5px;">
                                            {{ $department }}
                                        </p>
                                    @endif
                                    <p class="tracking-status" style="color: {{ $entryText }};">
                                        <b>Status:</b> {{ $statusText }}
                                    </p>

                                    @if ($badgeType && $baseStatus !== 'Rejected')
                                        <div class="status-badge-container right">
                                            <div class="status-badge {{ $badgeType }}-badge">
                                                <i class="fas {{ $badgeIcon }}"></i>
                                                <span>{{ $badgeText }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                  @else
                      <style>
        /* Media query inline */
        @media screen and (max-width: 425px) {
            .tracking-entry.pending-entry .status-badge-container.right {
                margin-top: 15px !important;
            }
        }
    </style>
    <div class="tracking-entry pending-entry" 
         style="background-color: #6c757d; border-left: 6px solid #6c757d; color: white;">
        <div class="status-details">
            <p class="tracking-date" style="color: white;">
                {{ now()->setTimezone('Asia/Manila')->format('F j, Y g:i A') }}
            </p>
            <p class="tracking-department" style="color: white; font-weight: bold; margin-bottom: 5px;">
                Application Received
            </p>
            <p class="tracking-status" style="color: white;">
                <b>Status:</b> Please wait for further announcement.
                Your application is currently being processed.
            </p>
        </div>
        
        <div class="status-badge-container right">
            <div class="status-badge pending-badge" 
                 style="background-color: #6c757d; color: white;">
                <i class="fas fa-hourglass-start"></i>
                <span>Pending</span>
            </div>
        </div>
    </div>
@endif
                </div>
            </div>
        </div>
    </section>


    <footer class="footer">
        <div class="footer-container">
            <div class="footer-brand">
                <div class="footer-logo">
                    <img src="WeServe.png" alt="WeServe Logo" class="logo-full" loading="eager">
                </div>
                <p>Providing support when it's needed most. Dedicated to helping communities and individuals achieve
                    their best.</p>
            </div>

            <div class="footer-links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#home">Home</a></li>
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#process">Application Process</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="footer-contact">
                <h3>Contacts</h3>
                <p>Email: cswdosanpedro@gmail.com</p>
                <p>Phone: 8-8082020</p>
                <p>Address: Basement, New City Hall Bldg., Brgy. Poblacion, City of San Pedro, Laguna</p>
                <p>Office Hours: Mon - Fri, 8:00 AM - 5:00 PM</p>
            </div>

            <!-- Social Media -->
            <div class="footer-socials" aria-label="Social media">
                <h3>Follow Us</h3>
                <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="footer-left">
                &copy; 2026 WeServe. All rights reserved.
            </div>
            <div class="footer-right">
                <a href="{{ route('terms-and-conditions') }}">Terms and Conditions</a> |
                <a href="{{ route('privacy-policy') }}">Privacy Policy</a>
            </div>
        </div>
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
