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
            <a href="#process">Process</a>
            <a href="#tracking">Track</a>
        </nav>

        <!-- Burger button -->
        <button class="burger" onclick="toggleMenu()" aria-label="Toggle Menu">
            <i class="fas fa-bars"></i>
        </button>
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
                    // Initialize filteredLogs as empty array
                    $filteredLogs = [];
                    
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
                        'Rejected' => 0 // Special case - always current when it's latest
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
                            if (!$status) continue;
                            
                            $isRollback = str_contains($status, '[ROLLED BACK]');
                            if ($isRollback) continue;
                            
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
                        $currentStatus = null;
                        if (count($filteredLogs) > 0) {
                            $latestLog = end($filteredLogs);
                            if ($latestLog) {
                                $currentStatus = trim(str_replace('[ROLLED BACK]', '', $latestLog->status));
                            }
                        }
                    }
                @endphp

                @if (count($filteredLogs) > 0)
                    @foreach ($filteredLogs as $index => $log)
                        @php
                            $status = $log->status;
                            $isRollback = str_contains($status, '[ROLLED BACK]');
                            $baseStatus = $isRollback ? trim(str_replace('[ROLLED BACK]', '', $status)) : $status;
                            
                            // Determine if this is current or completed
                            $isCurrentStatus = false;
                            $isCompletedStatus = false;
                            
                            if (isset($currentStatus)) {
                                if ($baseStatus === $currentStatus) {
                                    $isCurrentStatus = true;
                                } else {
                                    // Check if this status should be marked as completed
                                    if (isset($statusOrder[$baseStatus]) && isset($statusOrder[$currentStatus])) {
                                        $isCompletedStatus = ($statusOrder[$baseStatus] < $statusOrder[$currentStatus]);
                                    }
                                }
                            }
                            
                            // Define styling based on actual status, not completion state
                            $department = '';
                            $statusText = '';
                            $color = '#6c757d';
                            $icon = 'fa-question-circle';
                            $textColor = 'white';
                            
                            switch ($baseStatus) {
                                case 'Processing':
                                    $department = 'CSWD Office';
                                    $statusText = 'Application is on-process at CSWD Office';
                                    $color = '#6c757d'; // Always gray for processing
                                    $icon = 'fa-hourglass-half';
                                    break;
                                case 'Submitted':
                                case 'Submitted[Emergency]':
                                    $department = 'Mayor\'s Office';
                                    $statusText = 'Application is on-process at Mayor\'s Office';
                                    $color = '#17a2b8'; // Blue for submitted
                                    $icon = 'fa-paper-plane';
                                    break;
                                case 'Approved':
                                    $department = 'Budget Office';
                                    $statusText = 'Application is on-process at Budget Office';
                                    $color = '#28a745'; // Green for approved
                                    $icon = 'fa-check-circle';
                                    break;
                                case 'Budget Allocated':
                                    $department = 'Accounting Office';
                                    $statusText = 'Application is on-process at Accounting Office';
                                    $color = '#d4a017'; // Yellow/gold for budget allocated
                                    $icon = 'fa-wallet';
                                    // FIX: Changed from black to white
                                    $textColor = 'white';
                                    break;
                                case 'DV Submitted':
                                    $department = 'Treasury Office';
                                    $statusText = 'Application is on-process at Treasury Office';
                                    $color = '#17a2b8'; // Blue for DV submitted
                                    $icon = 'fa-file-invoice-dollar';
                                    break;
                                case 'Ready for Disbursement':
                                    $department = 'Treasury Office';
                                    $statusText = 'Please wait for a text message to be sent via SMS';
                                    $color = '#28a745'; // Green for ready
                                    $icon = 'fa-clock';
                                    break;
                                case 'Disbursed':
                                    $department = 'Treasury Office';
                                    $statusText = 'Disbursed';
                                    $color = '#28a745'; // Green for disbursed
                                    $icon = 'fa-hand-holding-usd';
                                    // Disbursed is always completed
                                    $isCompletedStatus = true;
                                    $isCurrentStatus = false;
                                    break;
                                case 'Rejected':
                                    $department = '';
                                    $statusText = 'Discrepancy is detected on your application. Please call CSWD or Aksyon Mamamayan Center for more inquiries.';
                                    $color = '#ffc107'; // Yellow for warning
                                    $icon = 'fa-exclamation-triangle';
                                    $textColor = 'black';
                                    $isCurrentStatus = true; // Rejected is always current when shown
                                    break;
                                default:
                                    $department = '';
                                    $statusText = $log->remarks ?? 'No remarks provided';
                                    $color = '#6c757d';
                                    $icon = 'fa-question-circle';
                                    break;
                            }
                            
                            // For completed statuses, add checkmark overlay and adjust color slightly
                            if ($isCompletedStatus && $baseStatus !== 'Disbursed' && $baseStatus !== 'Rejected') {
                                $color = '#28a745'; // Make completed statuses green
                                $icon = 'fa-check-circle';
                            }
                            
                            // Convert to correct timezone
                            if (!empty($log->status_date)) {
                                $date = \Carbon\Carbon::parse($log->status_date);
                                $date->setTimezone('Asia/Manila');
                                $formattedDate = $date->format('F j, Y g:i A');
                            } else {
                                $formattedDate = now()->setTimezone('Asia/Manila')->format('F j, Y g:i A');
                            }
                            
                            // Determine badge type - FIXED LOGIC
                            $badgeType = '';
                            $badgeIcon = '';
                            $badgeText = '';
                            
                            // FIX: Disbursed should show "Completed" not "Current"
                            if ($isCompletedStatus || $baseStatus === 'Disbursed') {
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
                        @endphp

                        <div class="tracking-entry {{ $isCurrentStatus ? 'current-status' : '' }}"
                            style="background-color: {{ $color }}; border-left: 6px solid {{ $color }}; color: {{ $textColor }};">
                            <div class="status-with-badge">
                                @if($badgeType)
                                    <div class="status-badge-container">
                                        <div class="status-badge {{ $badgeType }}-badge">
                                            <i class="fas {{ $badgeIcon }}"></i>
                                            <span>{{ $badgeText }}</span>
                                        </div>
                                    </div>
                                @endif
                                <div class="status-icon-main" style="background-color: rgba(255,255,255,0.2);">
                                    <i class="fas {{ $icon }}" style="color: {{ $textColor }};"></i>
                                    @if($isCompletedStatus || $baseStatus === 'Disbursed')
                                        <div class="checkmark-overlay">
                                            <i class="fas fa-check" style="color: white; font-size: 0.7em;"></i>
                                        </div>
                                    @endif
                                </div>
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
                    {{-- Show "Please wait" message when there are no logs --}}
                    <div class="tracking-entry" 
                         style="background-color: #6c757d; border-left: 6px solid #6c757d; color: white;">
                        <div class="status-with-badge">
                            <div class="status-badge-container">
                                <div class="status-badge pending-badge">
                                    <i class="fas fa-hourglass-start"></i>
                                    <span>Pending</span>
                                </div>
                            </div>
                            <div class="status-icon-main" style="background-color: rgba(255,255,255,0.2);">
                                <i class="fas fa-hourglass-start" style="color: white;"></i>
                            </div>
                        </div>
                        <div class="status-details">
                            <p class="tracking-date" style="color: white;">
                                {{ now()->setTimezone('Asia/Manila')->format('F j, Y g:i A') }}
                            </p>
                            <p class="tracking-department" style="color: white; font-weight: bold; margin-bottom: 5px;">
                                Application Received
                            </p>
                            <p class="tracking-status" style="color: white;">
                                <b>Status:</b> Please wait for further announcement before transferring to any offices. Your application is currently being processed.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<style>
    .tracking-entry {
        display: flex;
        align-items: flex-start;
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .status-with-badge {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-right: 15px;
        min-width: 80px;
    }
    
    .status-badge-container {
        position: relative;
        width: 100%;
        margin-bottom: 8px;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: bold;
        white-space: nowrap;
        gap: 5px;
        justify-content: center;
    }
    
    .status-badge i {
        font-size: 0.7em;
    }
    
    .completed-badge {
        background-color: #28a745;
        color: white;
        border: 1px solid #218838;
    }
    
    .current-badge {
        background-color: #17a2b8;
        color: white;
        border: 1px solid #138496;
    }
    
    .rejected-badge {
        background-color: #dc3545;
        color: white;
        border: 1px solid #c82333;
    }
    
    .pending-badge {
        background-color: #6c757d;
        color: white;
        border: 1px solid #545b62;
    }
    
    .status-icon-main {
        position: relative;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .checkmark-overlay {
        position: absolute;
        bottom: -5px;
        right: -5px;
        background-color: #28a745;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
    }
    
    .status-details {
        flex: 1;
    }
    
    .tracking-date {
        font-size: 0.85rem;
        margin-bottom: 5px;
        opacity: 0.9;
    }
    
    .tracking-department {
        font-size: 1.1rem;
        margin-bottom: 8px;
    }
    
    .tracking-status {
        font-size: 0.95rem;
        line-height: 1.4;
    }
    
    .tracking-entry.current-status {
        box-shadow: 0 0 0 2px rgba(23, 162, 184, 0.5);
        transform: scale(1.01);
    }
    
    .tracking-entry.completed-status {
        opacity: 0.95;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .status-with-badge {
            min-width: 70px;
        }
        
        .status-icon-main {
            width: 50px;
            height: 50px;
            font-size: 1.2rem;
        }
        
        .status-badge {
            font-size: 0.7rem;
            padding: 3px 8px;
        }
        
        .checkmark-overlay {
            width: 20px;
            height: 20px;
        }
    }
</style>
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