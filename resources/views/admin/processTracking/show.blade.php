@extends('layouts.admin')

@section('content')
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="m-0"><i class="fas fa-tasks me-2"></i> Process Tracking</h5>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-primary">Application Info</h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>Control Number:</th>
                            <td>{{ $patient->control_number }}</td>
                        </tr>
                        <tr>
                            <th>Date Processed:</th>
                            <td>{{ \Carbon\Carbon::parse($patient->date_processed)->format('F j, Y g:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Claimant Name:</th>
                            <td>{{ $patient->claimant_name }}</td>
                        </tr>
                        <tr>
                            <th>Case Category:</th>
                            <td>{{ $patient->case_category }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h6 class="text-primary">Process Status</h6>
                    <table class="table table-sm table-borderless" id="process-status-table">
                        <tr>
                            <th>Case Worker:</th>
                            <td>{{ $patient->case_worker }}</td>
                        </tr>
                        <tr>
                            <th>Current Status:</th>
                            <td>
                                <span class="badge {{ getStatusBadgeClass($latestStatus->status) }}"
                                    id="current-status-badge">
                                    {{ $latestStatus->status }}
                                </span>
                            </td>
                        </tr>
                        @php
                            function getStatusBadgeClass($status)
                            {
                                $statusClassMap = [
                                    'Processing' => 'badge-secondary',
                                    'Draft' => 'badge-secondary',
                                    'Submitted' => 'badge-primary',
                                    'Submitted[Emergency]' => 'badge-danger',
                                    'Approved' => 'badge-success',
                                    'Rejected' => 'badge-danger',
                                    'Budget Allocated' => 'badge-warning',
                                    'DV Submitted' => 'badge-info',
                                    'Disbursed' => 'badge-success',
                                    'Ready for Disbursement' => 'badge-warning',
                                ];

                                // Remove [ROLLED BACK] suffix if present for class mapping
                                $cleanStatus = trim(preg_replace('/\[ROLLED BACK\]/', '', $status));
                                return $statusClassMap[$cleanStatus] ?? 'badge-info';
                            }
                        @endphp

                        {{-- Budget Allocation Info --}}
                        @if ($patient->budgetAllocation)
                            <tr id="budget-allocation-row">
                                <th>Budget Allocated:</th>
                                <td id="budget-amount-display">
                                    ₱{{ number_format($patient->budgetAllocation->amount, 2) }}
                                </td>
                            </tr>
                        @else
                            <tr id="budget-allocation-row" style="display: none;">
                                <th>Budget Allocated:</th>
                                <td id="budget-amount-display"></td>
                            </tr>
                        @endif

                        {{-- DV Info --}}
                        @if ($patient->disbursementVoucher)
                            <tr id="dv-info-row">
                                <th>DV Code:</th>
                                <td id="dv-code-display">{{ $patient->disbursementVoucher->dv_code }}</td>
                            </tr>
                            <tr id="dv-date-row">
                                <th>DV Date:</th>
                                <td id="dv-date-display">
                                    {{ \Carbon\Carbon::parse($patient->disbursementVoucher->dv_date)->format('F j, Y g:i A') }}
                                </td>
                            </tr>
                        @else
                            <tr id="dv-info-row" style="display: none;">
                                <th>DV Code:</th>
                                <td id="dv-code-display"></td>
                            </tr>
                            <tr id="dv-date-row" style="display: none;">
                                <th>DV Date:</th>
                                <td id="dv-date-display"></td>
                            </tr>
                        @endif

                        @if (!empty($latestStatus->remarks))
                            <tr id="remarks-row">
                                <th>Remarks:</th>
                                <td id="current-remarks">{{ $latestStatus->remarks }}</td>
                            </tr>
                        @else
                            <tr id="remarks-row" style="display: none;">
                                <th>Remarks:</th>
                                <td id="current-remarks"></td>
                            </tr>
                        @endif
                        <tr>
                            <th>Updated At:</th>
                            <td id="status-updated-at">{{ $latestStatus->updated_at->format('F j, Y g:i A') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- VISUAL PROCESS TRACKER --}}
            @php
                $steps = ['Submitted', 'Approved', 'Budget Allocated', 'DV Submitted', 'Disbursed'];

                $stepLabels = [
                    'Submitted' => 'CSWD Office',
                    'Approved' => 'Mayor\'s Office',
                    'Budget Allocated' => 'Budget Office',
                    'DV Submitted' => 'Accounting Office',
                    'Disbursed' => 'Treasury Office',
                ];

                // Normalize status
                $rawStatus = $latestStatus->status ?? '';
                $baseStatus = trim(preg_replace('/\[.*?\]/', '', $rawStatus));

                // Find the last completed step
                $currentIndex = array_search($baseStatus, $steps);
                if ($currentIndex === false) {
                    $currentIndex = -1;
                }
            @endphp

            <div class="stepper">
                @foreach ($steps as $index => $step)
                    @php
                        $isCompleted = $index <= $currentIndex;
                        $isNext = $index === $currentIndex + 1;
                        $hasBlueLine = $index === $currentIndex; // blue line after current
                    @endphp

                    <div
                        class="stepper-step
                                    {{ $isCompleted ? 'completed' : '' }}
                                    {{ $isNext ? 'next' : '' }}
                                    {{ $hasBlueLine ? 'has-blue-line' : '' }}">

                        <div class="stepper-circle">
                            @if ($isCompleted)
                                <i class="fas fa-check"></i>
                            @else
                                {{ $index + 1 }}
                            @endif
                        </div>

                        <div class="stepper-label">{{ $stepLabels[$step] ?? $step }}</div>
                    </div>
                @endforeach
            </div>
            {{-- END PROCESS TRACKER --}}


            {{-- PROCESS SUMMARY --}}
            {{-- PROCESS SUMMARY --}}
            @if ($patient->statusLogs->count())
                <style>
                    .status-processing,
                    .status-submitted,
                    .status-approved,
                    .status-budget-allocated,
                    .status-dv-submitted,
                    .status-disbursed,
                    .status-ready-for-disbursement {
                        background-color: #b2dfb2;
                        color: #0b3e0b;
                    }

                    .status-rejected,
                    .status-rolled-back,
                    .status-submitted-emergency {
                        background-color: #f8d7da;
                        color: #721c24;
                    }

                    .list-group-item {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                    }

                    .list-group-item i {
                        cursor: pointer;
                    }
                </style>

                <div class="mb-4">
                    <h6 class="text-primary">📋 Process Summary</h6>
                    @php
                        // Define process flow sequence for "To" office
                        $processSteps = [
                            'Submitted' => 'CSWD Office',
                            'Approved' => 'Mayor\'s Office',
                            'Budget Allocated' => 'Budget Office',
                            'DV Submitted' => 'Accounting Office',
                            'Disbursed' => 'Treasury Office',
                        ];

                        $stepKeys = array_keys($processSteps);
                    @endphp

                    <ul class="list-group" id="processSummaryList">
                        @foreach ($patient->statusLogs->where('status', '!=', 'Draft') as $log)
                            @php
                                $originalStatus = $log->status;
                                $cleanStatus = trim(preg_replace('/\[.*?\]/', '', $originalStatus));
                                $statusKey = strtolower(str_replace([' ', '[', ']'], ['-', '-', ''], $cleanStatus));

                                // CSS class for coloring
                                $statusClass =
                                    strpos($originalStatus, '[ROLLED BACK]') !== false
                                        ? 'status-rolled-back'
                                        : 'status-' . $statusKey;

                                // Get user's role as "From" office
$fromOffice = $log->user ? $log->user->roles->pluck('title')->implode(', ') : 'System';

// Determine "To" office based on process flow
$toOffice = '-';
$currentIndex = array_search($cleanStatus, $stepKeys);
if ($currentIndex !== false && isset($stepKeys[$currentIndex + 1])) {
    $toOffice = $processSteps[$stepKeys[$currentIndex + 1]];
}

// Special handling for certain statuses
if (stripos($originalStatus, 'Processing') !== false) {
    $toOffice = null; // No "To" for Processing
} elseif (stripos($originalStatus, 'Rejected') !== false) {
    $toOffice = 'CSWD Office'; // Rejected goes back to CSWD
} elseif (stripos($originalStatus, 'Submitted[Emergency]') !== false) {
    $toOffice = 'Mayor\'s Office'; // Emergency still goes to Mayor
                                }

                                // For rolled back statuses, show where it's being returned to
if (strpos($originalStatus, '[ROLLED BACK]') !== false) {
    $rolledBackStatus = str_replace('[ROLLED BACK]', '', $originalStatus);
                                    $rolledBackIndex = array_search($rolledBackStatus, $stepKeys);
                                    if ($rolledBackIndex !== false && isset($stepKeys[$rolledBackIndex])) {
                                        $toOffice = $processSteps[$stepKeys[$rolledBackIndex]];
                                    }
                                }
                            @endphp

                            <li class="list-group-item {{ $statusClass }}">
                                <div>
                                    <strong>{{ ucfirst($originalStatus) }}:</strong>
                                    {{ $log->user->name ?? 'System' }}
                                    @if (!stripos($originalStatus, 'Processing'))
                                        - From: {{ $fromOffice }}
                                        @if ($toOffice)
                                            To: {{ $toOffice }}
                                        @endif
                                    @endif
                                    -- {{ \Carbon\Carbon::parse($log->status_date)->format('F j, Y g:i A') }}
                                    <br>

                                    {{-- Rejection details --}}
                                    @if (stripos($originalStatus, 'Rejected') !== false)
                                        @php
                                            $rejectionReasons = $patient->rejectionReasons->where(
                                                'patient_status_log_id',
                                                $log->id,
                                            );
                                        @endphp
                                        <em>Rejection Reason(s):</em>
                                        @if ($rejectionReasons->count() > 0)
                                            <ul class="mb-0">
                                                @foreach ($rejectionReasons as $reason)
                                                    <li>{{ $reason->reason }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            -
                                        @endif
                                        <br>
                                    @endif

                                    {{-- Budget info --}}
                                    @if ($cleanStatus == 'Budget Allocated' && $patient->budgetAllocation)
                                        <em>Budget allocated:</em>
                                        ₱{{ number_format($patient->budgetAllocation->amount, 2) }}<br>
                                    @endif

                                    <em>Remarks:</em> {{ $log->remarks ?? '-' }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                $isFinalized = in_array(optional($latestStatus)->status, ['Approved', 'Rejected']);
                $latestLog = $patient->statusLogs->last();
                $latestStatusValue = optional($latestStatus)->status;
                $isLocked = !in_array($latestStatusValue, [
                    null,
                    'Rejected',
                    'Processing',
                    'Draft',
                    'Processing[ROLLED BACK]',
                ]);

                // Get user permissions for JavaScript
                $userPermissions = auth()->user()->roles->flatMap->permissions->pluck('title')->unique();
            @endphp

            {{-- Dynamic Action Sections --}}
            <div id="dynamic-action-sections">
                {{-- Submit Patient Application Section --}}
                <div class="card mb-4 action-section" id="submit-patient-application"
                    data-permission="submit_patient_application"
                    style="display: {{ in_array($baseStatus, ['Processing', 'Draft', 'Rejected', 'Processing[ROLLED BACK]']) && auth()->user()->can('submit_patient_application') ? 'block' : 'none' }};">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-paper-plane mr-2"></i> CSWD Office - Submit Application
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            @csrf
                            <input type="hidden" name="status" value="Submitted">
                            <input type="hidden" name="redirect_to_process_tracking" value="1">

                            <div class="form-group">
                                <label for="submitted_date">Submitted Date</label>
                                <input type="datetime-local" name="submitted_date" id="submitted_date"
                                    class="form-control mb-3" value="{{ now()->toDateTimeLocalString() }}"
                                    @if ($isLocked) disabled @endif>

                                <label for="remarks">Remarks</label>
                                <textarea name="remarks" id="remarks" rows="4" class="form-control"
                                    @if ($isLocked) disabled @endif></textarea>
                            </div>

                            {{-- Check if application was previously submitted as emergency --}}
                            @php
                                $previousSubmissionStatus = null;
                                $hasEmergencySubmission = false;
                                $hasNormalSubmission = false;

                                // Find the most recent submission (before any rejection or rolled back)
                                $submissionLogs = $patient->statusLogs
                                    ->whereIn('status', ['Submitted', 'Submitted[Emergency]'])
                                    ->whereNotIn('status', function ($query) {
                                        $query
                                            ->select('status')
                                            ->from('patient_status_logs')
                                            ->where('status', 'like', '%[ROLLED BACK]%');
                                    })
                                    ->sortByDesc('status_date');

                                if ($submissionLogs->count() > 0) {
                                    $previousSubmissionStatus = $submissionLogs->first()->status;
                                    $hasEmergencySubmission = str_contains($previousSubmissionStatus, 'Emergency');
                                    $hasNormalSubmission =
                                        !$hasEmergencySubmission &&
                                        str_contains($previousSubmissionStatus, 'Submitted');
                                }

                                // Check if current status should show submission warnings
                                $showSubmissionWarnings = in_array($baseStatus, [
                                    'Rejected',
                                    'Processing[ROLLED BACK]',
                                    'Processing',
                                ]);
                            @endphp

                            <div class="d-flex justify-content-between">
                                {{-- Normal Submit --}}
                                <button type="button" class="btn btn-primary submit-btn"
                                    @if ($isLocked || $hasEmergencySubmission || ($showSubmissionWarnings && $hasEmergencySubmission)) disabled @endif
                                    onclick="submitApplication('{{ route('admin.patient-records.submit', $patient->id) }}', this, 'normal')"
                                    id="normal-submit-btn">
                                    Submit
                                </button>

                                {{-- Emergency Submit --}}
                                <button type="button" class="btn btn-danger submit-btn"
                                    @if ($isLocked || ($showSubmissionWarnings && $hasNormalSubmission)) disabled @endif
                                    onclick="submitApplication('{{ route('admin.patient-records.submit-emergency', $patient->id) }}', this, 'emergency')"
                                    id="emergency-submit-btn">
                                    Submit [Emergency]
                                </button>
                            </div>

                            @if ($hasEmergencySubmission && $showSubmissionWarnings)
                                <div class="alert alert-warning mt-3">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    This application was previously submitted as Emergency. Only emergency submissions are
                                    allowed.
                                </div>
                            @elseif ($hasNormalSubmission && $showSubmissionWarnings)
                                <div class="alert alert-info mt-3">
                                    <i class="fas fa-info-circle"></i>
                                    This application was previously submitted normally. Only normal submissions are allowed.
                                </div>
                            @endif

                            @if ($isLocked && !$showSubmissionWarnings)
                                <div class="alert alert-info mt-3">
                                    This application has already been submitted and is currently in process.
                                </div>
                            @endif
                        </form>

                        {{-- ADD THIS SECTION FOR RETURN TO ROLLBACKER BUTTON --}}
                        @php
                            $latestLog = $patient->statusLogs->last();
                        @endphp

                        {{-- In Submit Patient Application Section --}}
                        @if ($latestLog && str_contains(strtolower($latestLog->status), 'rolled back'))
                            <div class="mt-3 pt-3 border-top return-to-rollbacker-container">
                                <form action="{{ route('admin.process-tracking.returnToRollbacker', $patient->id) }}"
                                    method="POST" class="return-to-rollbacker-form">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-lg px-4 text-white w-100">
                                        <i class="fas fa-share me-1"></i> Return to Rollbacker
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="mt-3 pt-3 border-top return-to-rollbacker-container" style="display: none;">
                                <form action="{{ route('admin.process-tracking.returnToRollbacker', $patient->id) }}"
                                    method="POST" class="return-to-rollbacker-form" style="display: none;">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-lg px-4 text-white w-100">
                                        <i class="fas fa-share me-1"></i> Return to Rollbacker
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Approve Patient Section --}}
                <div class="card shadow-sm border-0 mb-4 action-section" id="approve-patient"
                    data-permission="approve_patient"
                    style="display: {{ in_array($baseStatus, ['Submitted', 'Submitted[Emergency]', 'Submitted[ROLLED BACK]']) && auth()->user()->can('approve_patient') ? 'block' : 'none' }}; background-color: #f8f9fa;">

                    <div class="card-header bg-primary text-white d-flex align-items-center">
                        <i class="fas fa-university me-2"></i>
                        <h5 class="mb-0">Mayor's Office - Approval</h5>
                    </div>

                    <div class="card-body text-center">
                        <!-- Buttons Container -->
                        <div class="d-flex flex-wrap justify-content-center gap-3">

                            <!-- Approve Button -->
                            <button type="button" class="btn btn-success btn-lg px-4 text-white" data-bs-toggle="modal"
                                data-bs-target="#approveModal">
                                <i class="fas fa-check-circle me-2"></i> Approve
                            </button>

                            <!-- Reject Button -->
                            <button type="button" class="btn btn-danger btn-lg px-4 text-white" data-bs-toggle="modal"
                                data-bs-target="#rejectModal">
                                <i class="fas fa-times-circle me-2"></i> Reject
                            </button>

                            <!-- Return to Rollbacker Button -->
                            @php
                                $latestLog = $patient->statusLogs->last();
                            @endphp

                            @if ($latestLog && str_contains(strtolower($latestLog->status), 'rolled back'))
                                <form action="{{ route('admin.process-tracking.returnToRollbacker', $patient->id) }}"
                                    method="POST" class="return-to-rollbacker-form d-inline-block">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-lg px-4 text-white">
                                        <i class="fas fa-share me-2"></i> Return to Rollbacker
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.process-tracking.returnToRollbacker', $patient->id) }}"
                                    method="POST" class="return-to-rollbacker-form" style="display: none;">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-lg px-4 text-white">
                                        <i class="fas fa-share me-2"></i> Return to Rollbacker
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Approve Modal -->
                <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('admin.process-tracking.decision', $patient->id) }}">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title" id="approveModalLabel">Approve Application</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="statusDate" class="form-label">Status Date</label>
                                        <input type="datetime-local" name="status_date" id="statusDate"
                                            class="form-control" value="{{ now()->toDateTimeLocalString() }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="approveRemarks" class="form-label">Remarks</label>
                                        <textarea name="remarks" id="approveRemarks" class="form-control" rows="3" placeholder="Enter remarks..."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="hidden" name="action" id="decisionAction">

                                    <button type="button" class="btn btn-success"
                                        onclick="
                                                            const form = this.closest('form');
                                                            form.querySelector('#decisionAction').value = 'approve';
                                                            this.disabled = true;
                                                            this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i> Processing...';
                                                            form.submit();
                                                        ">
                                        Confirm Approve
                                    </button>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Reject Modal -->
                <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('admin.process-tracking.decision', $patient->id) }}">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title" id="rejectModalLabel">Reject Application</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body">

                                    <!-- Multiple Reasons -->
                                    <div class="mb-3">
                                        <label class="form-label">Reason(s) for Rejection</label>

                                        @php
                                            $reasonsList = [
                                                'Missing ID',
                                                'No signature',
                                                'Expired documents',
                                                'Wrong name',
                                                'Missing document',
                                            ];
                                        @endphp

                                        @foreach ($reasonsList as $index => $reason)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="reasons[]"
                                                    value="{{ $reason }}" id="reason{{ $index }}"
                                                    {{ collect(old('reasons'))->contains($reason) ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="reason{{ $index }}">{{ $reason }}</label>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="mb-3">
                                        <label for="otherReason" class="form-label">Other Reason (Optional)</label>
                                        <input type="text" name="other_reason" id="otherReason" class="form-control"
                                            value="{{ old('other_reason') }}" placeholder="Specify other reason here">
                                    </div>

                                    <div class="mb-3">
                                        <div class="mb-3">
                                            <label for="statusDate" class="form-label">Status Date</label>
                                            <input type="datetime-local" name="status_date" id="statusDate"
                                                class="form-control" value="{{ now()->toDateTimeLocalString() }}"
                                                required>
                                        </div>

                                        <label for="rejectRemarks" class="form-label">Remarks</label>
                                        <textarea name="remarks" id="rejectRemarks" class="form-control" rows="3" placeholder="Enter remarks..."
                                            required>{{ old('remarks') }}</textarea>
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <input type="hidden" name="action" id="decisionAction">
                                    <button type="button" class="btn btn-danger"
                                        onclick="
                                                            const form = this.closest('form');
                                                            form.querySelector('#decisionAction').value = 'reject';
                                                            this.disabled = true;
                                                            this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i> Processing...';
                                                            form.submit();
                                                        ">
                                        Confirm Reject
                                    </button>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Budget Allocation Section --}}
            <div class="card shadow-sm border-0 mb-4 action-section" id="budget-allocate"
                data-permission="budget_allocate"
                style="display: {{ in_array($baseStatus, ['Approved', 'Approved[ROLLED BACK]']) && auth()->user()->can('budget_allocate') ? 'block' : 'none' }}; background-color: #f8f9fa;">

                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <i class="fas fa-wallet me-2"></i>
                    <h5 class="mb-0">Budget Office - Allocation</h5>
                </div>

                <div class="card-body text-center">
                    <!-- Buttons Container -->
                    <div class="d-flex flex-wrap justify-content-center gap-3">

                        <!-- Allocate/Edit Budget Button -->
                        <button type="button" class="btn btn-info btn-lg px-4 text-white" data-bs-toggle="modal"
                            data-bs-target="#budgetModal">
                            <i class="fas {{ $patient->budgetAllocation ? 'fa-edit' : 'fa-plus-circle' }} me-2"></i>
                            {{ $patient->budgetAllocation ? 'Edit Budget' : 'Allocate Budget' }}
                        </button>

                        <!-- Rollback Process Button -->
                        <button type="button" class="btn btn-danger btn-lg px-4 text-white" data-bs-toggle="modal"
                            data-bs-target="#rollbackModal">
                            <i class="fas fa-undo-alt me-2"></i> Rollback Process
                        </button>

                        <!-- Return to Rollbacker Button -->
                        @php
                            $latestLog = $patient->statusLogs->last();
                        @endphp

                        @if ($latestLog && str_contains(strtolower($latestLog->status), 'rolled back'))
                            <form action="{{ route('admin.process-tracking.returnToRollbacker', $patient->id) }}"
                                method="POST" class="return-to-rollbacker-form d-inline-block">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-lg px-4 text-white">
                                    <i class="fas fa-share me-2"></i> Return to Rollbacker
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.process-tracking.returnToRollbacker', $patient->id) }}"
                                method="POST" class="return-to-rollbacker-form" style="display: none;">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-lg px-4 text-white">
                                    <i class="fas fa-share me-2"></i> Return to Rollbacker
                                </button>
                            </form>
                        @endif
                    </div>
                </div>


                <!-- Budget Modal -->
                <div class="modal fade" id="budgetModal" tabindex="-1" role="dialog"
                    aria-labelledby="budgetModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <form
                            action="{{ $patient->budgetAllocation
                                ? route('admin.process-tracking.updateBudget', $patient->id)
                                : route('admin.process-tracking.storeBudget', $patient->id) }}"
                            method="POST">
                            @csrf
                            @if ($patient->budgetAllocation)
                                @method('PUT')
                            @endif

                            <div class="modal-content border-0 shadow-lg rounded-4" style="overflow: hidden;">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="budgetModalLabel">
                                        <i class="fas fa-wallet me-2"></i>
                                        {{ $patient->budgetAllocation ? 'Edit Budget Allocation' : 'Allocate Budget' }}
                                    </h5>
                                    <button type="button" class="close text-white" data-bs-dismiss="modal"
                                        aria-label="Close">
                                        <span aria-hidden="true" style="font-size: 1.5rem;">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body p-4">
                                    <div class="form-group mb-4">
                                        <label for="amount" class="form-label">Amount (₱)</label>
                                        <input type="number" step="0.01" name="amount" id="amount"
                                            class="form-control form-control-lg rounded-3 shadow-sm" required
                                            value="{{ old('amount', $patient->budgetAllocation->amount ?? '') }}">

                                        <div class="d-flex flex-wrap gap-2 mt-3">
                                            @foreach ([1000, 2000, 3000, 4000, 5000, 6000, 7000, 8000, 9000, 10000] as $suggested)
                                                <button type="button"
                                                    class="btn btn-outline-primary btn-sm suggested-amount rounded-pill px-3"
                                                    data-value="{{ $suggested }}">₱{{ number_format($suggested) }}</button>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="budget_status_date" class="form-label">Status Date</label>
                                        <input type="datetime-local" name="status_date" id="budget_status_date"
                                            class="form-control rounded-3 shadow-sm"
                                            value="{{ now()->toDateTimeLocalString() }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="remarks" class="form-label">Remarks</label>
                                        <textarea name="remarks" id="remarks" class="form-control rounded-3 shadow-sm" rows="4"
                                            placeholder="Enter any remarks here...">{{ old('remarks', $patient->budgetAllocation->remarks ?? '') }}</textarea>
                                    </div>
                                </div>

                                <div class="modal-footer d-flex flex-column gap-2 p-4 pt-0">
                                    <button type="button" class="btn btn-success w-100 rounded-pill py-2"
                                        onclick="
                                                            const form = this.closest('form');
                                                            this.disabled = true;
                                                            this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i> Processing...';
                                                            form.submit();
                                                        ">
                                        <i class="fas fa-check-circle me-1"></i>
                                        {{ $patient->budgetAllocation ? 'Update Allocation' : 'Confirm Allocation' }}
                                    </button>

                                    <button type="button" class="btn btn-secondary w-100 rounded-pill py-2"
                                        data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Accounting DV Input Section --}}
            <div class="card shadow-sm border-0 mb-4 action-section" id="accounting-dv-input"
                data-permission="accounting_dv_input"
                style="display: {{ in_array($baseStatus, ['Budget Allocated', 'Budget Allocated[ROLLED BACK]']) && auth()->user()->can('accounting_dv_input') ? 'block' : 'none' }}; background-color: #f8f9fa;">

                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <i class="fas fa-file-invoice me-2"></i>
                    <h5 class="mb-0">Accounting Office - Disbursement Voucher</h5>
                </div>

                <div class="card-body text-center">
                    <!-- Buttons Container -->
                    <div class="d-flex flex-wrap justify-content-center gap-3">

                        <!-- Enter/Edit DV Button -->
                        <button type="button" class="btn btn-info btn-lg px-4 text-white" data-bs-toggle="modal"
                            data-bs-target="#dvModal">
                            <i class="fas fa-file-alt me-2"></i>
                            {{ $patient->disbursementVoucher ? 'Edit' : 'Enter' }} DV Details
                        </button>

                        <!-- Rollback Process Button -->
                        <button type="button" class="btn btn-danger btn-lg px-4 text-white" data-bs-toggle="modal"
                            data-bs-target="#rollbackModal">
                            <i class="fas fa-undo-alt me-2"></i> Rollback Process
                        </button>

                        <!-- Return to Rollbacker Button -->
                        @php
                            $latestLog = $patient->statusLogs->last();
                        @endphp

                        @if ($latestLog && str_contains(strtolower($latestLog->status), 'rolled back'))
                            <form action="{{ route('admin.process-tracking.returnToRollbacker', $patient->id) }}"
                                method="POST" class="return-to-rollbacker-form d-inline-block">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-lg px-4 text-white">
                                    <i class="fas fa-share me-2"></i> Return to Rollbacker
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.process-tracking.returnToRollbacker', $patient->id) }}"
                                method="POST" class="return-to-rollbacker-form" style="display: none;">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-lg px-4 text-white">
                                    <i class="fas fa-share me-2"></i> Return to Rollbacker
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Treasury Disburse Section --}}
            <div class="card shadow-sm border-0 mb-4 action-section" id="treasury-disburse"
                data-permission="treasury_disburse"
                style="display: {{ in_array($baseStatus, ['DV Submitted', 'DV Submitted[ROLLED BACK]', 'Ready for Disbursement']) && auth()->user()->can('treasury_disburse') ? 'block' : 'none' }}; background-color: #f8f9fa;">

                {{-- DV Submitted Status (STEP 1: Mark as Ready for Disbursement) --}}
                <div class="dv-submitted-content"
                    style="{{ in_array($baseStatus, ['DV Submitted', 'DV Submitted[ROLLED BACK]']) ? '' : 'display: none;' }}">

                    <div class="card-header bg-primary text-white d-flex align-items-center">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        <h5 class="mb-0">Treasury Office - Disbursement</h5>
                    </div>

                    <div class="card-body text-center">
                        <!-- Buttons Container -->
                        <div class="d-flex flex-wrap justify-content-center gap-3">

                            {{-- READY FOR DISBURSEMENT BUTTON --}}
                            <button type="button" class="btn btn-warning btn-lg px-4 text-white" data-bs-toggle="modal"
                                data-bs-target="#readyForDisbursementModal">
                                <i class="fas fa-exclamation-circle me-2"></i> Mark as Ready for Disbursement
                            </button>

                            <button type="button" class="btn btn-danger btn-lg px-4 text-white" data-bs-toggle="modal"
                                data-bs-target="#rollbackModal">
                                <i class="fas fa-undo-alt me-2"></i> Rollback Process
                            </button>

                            {{-- Return to Rollbacker Button for Treasury Disburse Section --}}
                            @php
                                $latestLog = $patient->statusLogs->last();
                            @endphp

                            @if ($latestLog && str_contains(strtolower($latestLog->status), 'rolled back'))
                                <form action="{{ route('admin.process-tracking.returnToRollbacker', $patient->id) }}"
                                    method="POST" class="return-to-rollbacker-form d-inline-block">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-lg px-4 text-white">
                                        <i class="fas fa-share me-2"></i> Return to Rollbacker
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.process-tracking.returnToRollbacker', $patient->id) }}"
                                    method="POST" class="return-to-rollbacker-form" style="display: none;">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-lg px-4 text-white">
                                        <i class="fas fa-share me-2"></i> Return to Rollbacker
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Ready for Disbursement Status (STEP 2: Mark as Disbursed) --}}
                <div class="ready-for-disbursement-content"
                    style="{{ $baseStatus === 'Ready for Disbursement' ? '' : 'display: none;' }}">

                    <div class="card-header bg-primary text-white d-flex align-items-center">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        <h5 class="mb-0">Treasury Office - Disbursement</h5>
                    </div>

                    <div class="card-body text-center">
                        <!-- Buttons Container -->
                        <div class="d-flex flex-wrap justify-content-center gap-3">

                            <button type="button" class="btn btn-success btn-lg px-4 text-white" data-bs-toggle="modal"
                                data-bs-target="#quickDisburseModal">
                                <i class="fas fa-check-circle me-2"></i> Mark as Disbursed
                            </button>

                            <button type="button" class="btn btn-danger btn-lg px-4 text-white" data-bs-toggle="modal"
                                data-bs-target="#rollbackModal">
                                <i class="fas fa-undo-alt me-2"></i> Rollback Process
                            </button>

                            {{-- Return to Rollbacker Button for Ready for Disbursement --}}
                            @if ($latestLog && str_contains(strtolower($latestLog->status), 'rolled back'))
                                <form action="{{ route('admin.process-tracking.returnToRollbacker', $patient->id) }}"
                                    method="POST" class="return-to-rollbacker-form d-inline-block">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-lg px-4 text-white">
                                        <i class="fas fa-share me-2"></i> Return to Rollbacker
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.process-tracking.returnToRollbacker', $patient->id) }}"
                                    method="POST" class="return-to-rollbacker-form" style="display: none;">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-lg px-4 text-white">
                                        <i class="fas fa-share me-2"></i> Return to Rollbacker
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- Ready for Disbursement Modal -->
            <div class="modal fade" id="readyForDisbursementModal" tabindex="-1"
                aria-labelledby="readyForDisbursementModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('admin.process-tracking.markAsReadyForDisbursement', $patient->id) }}"
                        method="POST">
                        @csrf
                        <div class="modal-content border-0 shadow-lg rounded-4">
                            <div class="modal-header bg-warning text-white">
                                <h5 class="modal-title" id="readyForDisbursementModalLabel">
                                    <i class="fas fa-exclamation-circle me-2"></i> Mark as Ready for Disbursement
                                </h5>
                                <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    This will mark the patient as ready for disbursement. The budget allocation and DV
                                    must be completed first.
                                </div>

                                <div class="form-group mb-3">
                                    <label for="readyForDisbursementStatusDate">Status Date</label>
                                    <input type="datetime-local" name="status_date" id="readyForDisbursementStatusDate"
                                        class="form-control form-control-lg" value="{{ now()->toDateTimeLocalString() }}"
                                        required>
                                </div>

                                <div class="form-group mb-3">
                                    <label>Budget Allocated:</label>
                                    <p class="form-control bg-light">
                                        @if ($patient->budgetAllocation)
                                            ₱{{ number_format($patient->budgetAllocation->amount, 2) }}
                                        @else
                                            <span class="text-danger">No budget allocated</span>
                                        @endif
                                    </p>
                                </div>

                                <div class="form-group mb-3">
                                    <label>DV Code:</label>
                                    <p class="form-control bg-light">
                                        @if ($patient->disbursementVoucher && $patient->disbursementVoucher->dv_code)
                                            {{ $patient->disbursementVoucher->dv_code }}
                                        @else
                                            <span class="text-danger">No DV submitted</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="modal-footer d-flex flex-column gap-2">
                                <button type="button" class="btn btn-warning w-100"
                                    onclick="
                                        const btn = this;
                                        const form = btn.closest('form');
                                        btn.disabled = true;
                                        btn.innerHTML = '<i class=\'fas fa-spinner fa-spin me-1\'></i> Processing...';
                                        form.submit();
                                    ">
                                    <i class="fas fa-exclamation-circle me-1"></i> Confirm Ready for Disbursement
                                </button>

                                <button type="button" class="btn btn-secondary w-100 mt-2" data-bs-dismiss="modal">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Rollback Modal -->
            <div class="modal fade" id="rollbackModal" tabindex="-1" aria-labelledby="rollbackModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('admin.process-tracking.rollback', $patient->id) }}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header bg-warning text-white">
                                <h5 class="modal-title" id="rollbackModalLabel">Rollback Process</h5>
                                <button type="button" class="close text-white" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="rollback_to">Rollback to</label>
                                    <select class="form-control" name="rollback_to" id="rollback_to" required>
                                        <option value="">Select department to rollback to</option>
                                        @php
                                            // Define the process flow sequence
                                            $processFlow = [
                                                'Processing' => 'CSWD Office',
                                                'Submitted' => 'Mayor\'s Office', // Combine normal and emergency submissions
                                                'Approved' => 'Budget Office',
                                                'Budget Allocated' => 'Accounting Office',
                                                'DV Submitted' => 'Treasury Office',
                                            ];

                                            // Get all unique statuses from logs (clean them first)
                                            $allStatuses = $patient->statusLogs
                                                ->pluck('status')
                                                ->map(function ($status) {
                                                    // Remove [ROLLED BACK] and [EMERGENCY] tags for comparison
                                                    return trim(
                                                        str_replace(['[ROLLED BACK]', '[EMERGENCY]'], '', $status),
                                                    );
                                                })
                                                ->unique()
                                                ->filter()
                                                ->values();

                                            // Get current base status (without any tags)
                                            $currentBaseStatus = trim(
                                                str_replace(
                                                    ['[ROLLED BACK]', '[EMERGENCY]'],
                                                    '',
                                                    $latestStatus->status,
                                                ),
                                            );

                                            // Find current position in process flow
                                            $currentPosition = array_search(
                                                $currentBaseStatus,
                                                array_keys($processFlow),
                                            );

                                            $availableRollbacks = [];

                                            if ($currentPosition !== false && $currentPosition > 0) {
                                                // Only allow rollback to previous steps in the process flow
                                                for ($i = $currentPosition - 1; $i >= 0; $i--) {
                                                    $targetStatus = array_keys($processFlow)[$i];
                                                    $targetOffice = $processFlow[$targetStatus];

                                                    // Check if this status exists in patient's history
        if ($allStatuses->contains($targetStatus)) {
            $availableRollbacks[$targetStatus] = $targetOffice;
        }
    }
}

// Special case: if current status is Submitted[Emergency], allow rollback to Processing
if (
    $currentBaseStatus === 'Submitted' &&
    strpos($latestStatus->status, '[EMERGENCY]') !== false
) {
    if ($allStatuses->contains('Processing')) {
        $availableRollbacks['Processing'] = 'CSWD Office';
                                                }
                                            }
                                        @endphp

                                        @foreach ($availableRollbacks as $status => $office)
                                            <option value="{{ $status }}">{{ $office }}</option>
                                        @endforeach
                                    </select>

                                    @if (empty($availableRollbacks))
                                        <div class="alert alert-info mt-2">
                                            No valid rollback targets available. You can only rollback to previous
                                            departments in the process flow.
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="rollback_remarks">Remarks</label>
                                    <textarea name="rollback_remarks" class="form-control" id="rollback_remarks" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-warning"
                                    onclick="
                                        const form = this.closest('form');
                                        this.disabled = true;
                                        this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i> Rolling back...';
                                        form.submit();
                                    "
                                    {{ empty($availableRollbacks) ? 'disabled' : '' }}>
                                    <i class="fas fa-undo-alt me-1"></i> Rollback
                                </button>

                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal fade" id="dvModal" tabindex="-1" aria-labelledby="dvModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <form
                        action="{{ $patient->disbursementVoucher
                            ? route('admin.process-tracking.updateDV', $patient->id)
                            : route('admin.process-tracking.storeDV', $patient->id) }}"
                        method="POST">
                        @csrf
                        @if ($patient->disbursementVoucher)
                            @method('PUT')
                        @endif

                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="dvModalLabel">
                                    <i class="fas fa-file-invoice me-2"></i>
                                    {{ $patient->disbursementVoucher ? 'Edit' : 'Enter' }} Disbursement Voucher
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="dv_code" class="form-label fw-bold">DV Code</label>
                                        <input type="text" name="dv_code" id="dv_code"
                                            class="form-control form-control-lg"
                                            value="{{ old('dv_code', $patient->disbursementVoucher->dv_code ?? '') }}"
                                            placeholder="Enter DV Code">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="dv_date" class="form-label fw-bold">DV Date</label>
                                        <input type="datetime-local" name="dv_date" id="dv_date"
                                            class="form-control form-control-lg"
                                            value="{{ old('dv_date', optional($patient->disbursementVoucher)->dv_date ? \Carbon\Carbon::parse($patient->disbursementVoucher->dv_date)->format('Y-m-d\TH:i') : '') }}">
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="status_date" class="form-label fw-bold">Status Date</label>
                                    <input type="datetime-local" name="status_date" id="status_date"
                                        class="form-control form-control-lg"
                                        value="{{ old('status_date', now()->toDateTimeLocalString()) }}" required>
                                </div>
                            </div>

                            <div class="modal-footer d-flex flex-column gap-2">
                                <button type="button" class="btn btn-success w-100"
                                    onclick="
                                                    const btn = this;
                                                    const form = btn.closest('form');
                                                    btn.disabled = true;
                                                    btn.innerHTML = '<i class=\'fas fa-spinner fa-spin me-1\'></i> Processing...';
                                                    form.submit();
                                                ">
                                    <i class="fas fa-check-circle me-1"></i>
                                    {{ $patient->disbursementVoucher ? 'Update' : 'Submit' }} DV
                                </button>

                                <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal fade" id="quickDisburseModal" tabindex="-1" aria-labelledby="quickDisburseModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('admin.process-tracking.quickDisburse', $patient->id) }}" method="POST">
                        @csrf
                        <div class="modal-content border-0 shadow-lg rounded-4">
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title" id="quickDisburseModalLabel">
                                    <i class="fas fa-money-bill-wave me-2"></i> Disburse
                                </h5>
                                <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <div class="form-group mb-3">
                                    <label for="quickDisburseStatusDate">Status Date</label>
                                    <input type="datetime-local" name="status_date" id="quickDisburseStatusDate"
                                        class="form-control form-control-lg" value="{{ now()->toDateTimeLocalString() }}"
                                        required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="quickDisburseRemarks">Remarks (Optional)</label>
                                    <textarea name="remarks" id="quickDisburseRemarks" class="form-control form-control-lg" rows="3"
                                        placeholder="Enter any remarks..."></textarea>
                                </div>
                            </div>

                            <div class="modal-footer d-flex flex-column gap-2">
                                <button type="button" class="btn btn-success w-100"
                                    onclick="
                                                    const btn = this;
                                                    const form = btn.closest('form');
                                                    btn.disabled = true;
                                                    btn.innerHTML = '<i class=\'fas fa-spinner fa-spin me-1\'></i> Processing...';
                                                    form.submit();
                                                ">
                                    <i class="fas fa-check-circle me-1"></i> Confirm Disbursement
                                </button>

                                <button type="button" class="btn btn-secondary w-100 mt-2" data-bs-dismiss="modal">
                                    Cancel
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            @if ($patient->budgetAllocation && $patient->budgetAllocation->budget_status === 'Disbursed')
                <div class="alert alert-success mt-4">
                    <strong>Status:</strong> Disbursed
                </div>
            @endif

            <div class="form-group mt-4">
                <div class="left-buttons">
                    <a class="btn btn-secondary" href="{{ route('admin.process-tracking.index') }}">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>

                <div class="right-buttons">
                    <a class="btn btn-primary" href="{{ route('admin.document-management.show', $patient->id) }}">
                        <i class="fas fa-file-alt me-1"></i> View Document
                    </a>
                    <a class="btn btn-success" href="{{ route('admin.patient-records.show', $patient->id) }}">
                        <i class="fas fa-file-medical me-1"></i> View Record
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .action-section {
            transition: all 0.3s ease-in-out;
        }

        /* Ensure proper spacing when sections are hidden/shown */
        .card.mb-4 {
            margin-bottom: 1rem !important;
        }

        /* Status Colors for Process Summary */
        .status-processing {
            background-color: #dbeafe !important;
            /* Light blue */
            color: #1e40af !important;
            border-left: 4px solid #3b82f6 !important;
        }

        .status-submitted {
            background-color: #dbeafe !important;
            /* Light blue */
            color: #1e40af !important;
            border-left: 4px solid #3b82f6 !important;
        }

        .status-submitted-emergency {
            background-color: #fef3c7 !important;
            /* Light yellow/orange */
            color: #92400e !important;
            border-left: 4px solid #f59e0b !important;
            position: relative;
        }

        /* Add emergency indicator for better visibility */
        .status-submitted-emergency::before {
            content: "🚨";
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
        }

        .status-approved {
            background-color: #d1fae5 !important;
            /* Light green */
            color: #065f46 !important;
            border-left: 4px solid #10b981 !important;
        }

        .status-rejected {
            background-color: #fecaca !important;
            /* Light red */
            color: #991b1b !important;
            border-left: 4px solid #ef4444 !important;
        }

        .status-budget-allocated {
            background-color: #fef3c7 !important;
            /* Light yellow */
            color: #92400e !important;
            border-left: 4px solid #f59e0b !important;
        }

        .status-dv-submitted {
            background-color: #dbeafe !important;
            /* Light blue */
            color: #1e40af !important;
            border-left: 4px solid #3b82f6 !important;
        }

        .status-disbursed {
            background-color: #d1fae5 !important;
            /* Light green */
            color: #065f46 !important;
            border-left: 4px solid #10b981 !important;
        }

        .status-ready-for-disbursement {
            background-color: #fef3c7 !important;
            /* Light yellow */
            color: #92400e !important;
            border-left: 4px solid #f59e0b !important;
        }

        .status-rolled-back {
            background-color: #fef3c7 !important;
            /* Light yellow */
            color: #92400e !important;
            border-left: 4px solid #f59e0b !important;
            position: relative;
        }

        /* Add rollback indicator */
        .status-rolled-back::before {
            content: "↶";
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-weight: bold;
            color: #f59e0b;
            font-size: 16px;
        }

        .status-default {
            background-color: #f8f9fa !important;
            color: #212529 !important;
            border-left: 4px solid #6c757d !important;
        }

        /* Ensure list group items have proper spacing and borders */
        .list-group-item {
            border: 1px solid rgba(0, 0, 0, .125) !important;
            margin-bottom: 5px !important;
            border-radius: 5px !important;
            padding-left: 50px !important;
            /* Make space for icons */
            position: relative;
            transition: all 0.2s ease-in-out;
        }

        .list-group-item:hover {
            transform: translateX(2px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection

@section('scripts')
    <script>
        const userPermissions = @json($userPermissions);

        function initializeRealTimeUpdates() {
            console.log('📡 Initializing real-time updates...');

            // Connection status handling
            if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
                window.Echo.connector.pusher.connection.bind('connected', function() {
                    console.log('✅ Connected to Pusher');
                });

                window.Echo.connector.pusher.connection.bind('disconnected', function() {
                    console.log('❌ Disconnected from Pusher');
                });

                window.Echo.connector.pusher.connection.bind('error', function(error) {
                    console.error('Pusher error:', error);
                });
            }

            // Listen for patient status changes
            if (window.Echo) {
                window.Echo.channel('process-tracking')
                    .listen('.patient.status.changed', function(e) {


                        // Update process status section
                        updateProcessStatus(e);

                        // Update process summary in real-time
                        updateProcessSummary(e);

                        // Update action buttons based on status and permissions
                        updateActionButtons(e);

                        // Update process tracker visualization
                        updateProcessTracker(e);

                        updateFormLockState(e);

                        updateReturnToRollbackerButton(e);
                    });
            } else {
                console.error('Echo is not defined');
            }
        }

        function updateProcessStatus(eventData) {
            // Update current status badge
            const statusBadge = document.getElementById('current-status-badge');
            if (statusBadge) {
                statusBadge.textContent = eventData.status;

                // Update badge color based on status
                statusBadge.className = 'badge badge-' + getStatusBadgeClass(eventData.status);
            }

            // Update remarks
            const remarksRow = document.getElementById('remarks-row');
            const currentRemarks = document.getElementById('current-remarks');
            if (remarksRow && currentRemarks) {
                if (eventData.remarks && eventData.remarks.trim() !== '') {
                    currentRemarks.textContent = eventData.remarks;
                    remarksRow.style.display = '';
                } else {
                    remarksRow.style.display = 'none';
                }
            }

            // Update budget allocation info
            const budgetRow = document.getElementById('budget-allocation-row');
            const budgetAmount = document.getElementById('budget-amount-display');
            if (budgetRow && budgetAmount) {
                if (eventData.budget_amount !== undefined && eventData.budget_amount !== null) {
                    budgetAmount.textContent = '₱' + parseFloat(eventData.budget_amount).toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                    budgetRow.style.display = '';
                } else {
                    budgetRow.style.display = 'none';
                }
            }

            // Update DV info
            const dvInfoRow = document.getElementById('dv-info-row');
            const dvDateRow = document.getElementById('dv-date-row');
            const dvCodeDisplay = document.getElementById('dv-code-display');
            const dvDateDisplay = document.getElementById('dv-date-display');

            if (dvInfoRow && dvCodeDisplay) {
                if (eventData.dv_code !== undefined && eventData.dv_code !== null && eventData.dv_code !== '') {
                    dvCodeDisplay.textContent = eventData.dv_code;
                    dvInfoRow.style.display = '';
                } else {
                    dvInfoRow.style.display = 'none';
                }
            }

            if (dvDateRow && dvDateDisplay) {
                if (eventData.dv_date !== undefined && eventData.dv_date !== null) {
                    const dvDate = new Date(eventData.dv_date);
                    dvDateDisplay.textContent = dvDate.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true
                    });
                    dvDateRow.style.display = '';
                } else {
                    dvDateRow.style.display = 'none';
                }
            }

            // Update timestamp
            const updatedAt = document.getElementById('status-updated-at');
            if (updatedAt) {
                const now = new Date();
                updatedAt.textContent = now.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                });
            }
        }

        function getStatusBadgeClass(status) {
            const statusClassMap = {
                'Processing': 'secondary',
                'Submitted': 'primary',
                'Submitted[Emergency]': 'danger',
                'Approved': 'success',
                'Rejected': 'danger',
                'Budget Allocated': 'warning',
                'DV Submitted': 'info',
                'Disbursed': 'success',
                'Ready for Disbursement': 'warning'
            };

            // Remove [ROLLED BACK] suffix if present for class mapping
            const cleanStatus = status.replace('[ROLLED BACK]', '').trim();
            return statusClassMap[cleanStatus] || 'info';
        }

        function updateProcessSummary(eventData) {
            const processSummaryList = document.getElementById('processSummaryList');
            if (!processSummaryList) return;

            // Create new list item for the latest status
            const newLogItem = createProcessSummaryItem(eventData);

            // Prepend the new item (most recent first)
            processSummaryList.insertBefore(newLogItem, processSummaryList.lastChild);
        }

        function createProcessSummaryItem(eventData) {
            const li = document.createElement('li');

            // Get the correct status class
            const statusClass = getStatusClass(eventData.status);
            li.className = `list-group-item status-${statusClass}`;

            // Format date to match your desired format: "November 22, 2025 6:03 PM"
            const formattedDate = formatDateTime(eventData.status_date);

            // Get the formatted From/To text
            const fromToText = formatProcessSummaryText(eventData);

            let content = `
                    <div>
                        <strong>${eventData.status}:</strong>
                        ${eventData.user_name} - ${fromToText} -- ${formattedDate}
                        <br>
                `;

            // Add rejection reasons if present
            if (eventData.action === 'rejected' && eventData.rejection_reasons) {
                content += `<em>Rejection Reason(s):</em>`;
                if (eventData.rejection_reasons.length > 0) {
                    content += `<ul class="mb-0">`;
                    eventData.rejection_reasons.forEach(reason => {
                        content += `<li>${reason}</li>`;
                    });
                    content += `</ul>`;
                } else {
                    content += `-`;
                }
                content += `<br>`;
            }

            // Add budget info if present
            if (eventData.status === 'Budget Allocated' && eventData.budget_amount !== undefined) {
                content +=
                    `<em>Budget allocated:</em> ₱${eventData.budget_amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}<br>`;
            }

            // Add DV info if present
            if (eventData.status === 'DV Submitted' && eventData.dv_code !== undefined) {
                content += `<em>DV Code:</em> ${eventData.dv_code || 'N/A'}<br>`;
                if (eventData.dv_date) {
                    content += `<em>DV Date:</em> ${formatDateTime(eventData.dv_date)}<br>`;
                }
            }

            content += `<em>Remarks:</em> ${eventData.remarks || '-'}`;
            content += `</div>`;

            li.innerHTML = content;
            return li;
        }

        // Helper function to format date as "November 22, 2025 6:03 PM"
        function formatDateTime(dateString) {
            const date = new Date(dateString);

            // Format: "Month Day, Year Hour:Minute AM/PM"
            return date.toLocaleString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
        }

        function getStatusClass(status) {


            // First check for rolled back status - this should take priority
            if (status.includes('[ROLLED BACK]')) {

                return 'rolled-back';
            }

            // Then check for other status types
            const statusMap = {
                'Processing': 'processing',
                'Draft': 'processing',
                'Submitted': 'submitted',
                'Submitted[Emergency]': 'submitted-emergency',
                'Approved': 'approved',
                'Rejected': 'rejected',
                'Budget Allocated': 'budget-allocated',
                'DV Submitted': 'dv-submitted',
                'Disbursed': 'disbursed',
                'Ready for Disbursement': 'ready-for-disbursement'
            };

            // Clean the status by removing any brackets and trim
            const cleanStatus = status.replace(/\[.*?\]/g, '').trim();
            const result = statusMap[cleanStatus] || 'default';


            return result;
        }

        function formatProcessSummaryText(eventData) {
            const processSteps = {
                'Processing': null,
                'Draft': null,
                'Rejected': 'CSWD Office',
                'Submitted': 'Mayor\'s Office',
                'Submitted[Emergency]': 'Mayor\'s Office',
                'Approved': 'Budget Office',
                'Budget Allocated': 'Accounting Office',
                'DV Submitted': 'Treasury Office',
                'Disbursed': null,
                'Ready for Disbursement': null
            };

            // For Processing and Draft status, don't show From/To
            if (eventData.status === 'Processing' || eventData.status === 'Draft') {
                return '';
            }

            let text = '';

            // Show "From" as the actual user's role/office - use user_role if available, otherwise fallback
            if (eventData.user_role) {
                text += `From: ${eventData.user_role}`;
            } else {
                // Fallback: Use the user's name or a default if role is not available in real-time data
                text += `From: ${eventData.user_name || 'System'}`;
            }

            // Show "To" based on process flow
            const toOffice = processSteps[eventData.status];
            if (toOffice) {
                text += ` To: ${toOffice}`;
            }

            return text;
        }

        function updateActionButtons(eventData) {


            const baseStatus = eventData.status.replace('[ROLLED BACK]', '').trim();

            // Hide all action sections first
            hideAllActionSections();

            // Show appropriate action section based on status AND user permissions
            let sectionToShow = null;

            switch (baseStatus) {
                case 'Processing[ROLLED BACK]':
                case 'Rejected':
                case 'Processing':
                case 'Draft':
                    if (userPermissions.includes('submit_patient_application')) {
                        sectionToShow = 'submit-patient-application';
                    }
                    break;
                case 'Submitted':
                case 'Submitted[Emergency]':
                case 'Submitted[ROLLED BACK]':
                    if (userPermissions.includes('approve_patient')) {
                        sectionToShow = 'approve-patient';
                    }
                    break;
                case 'Approved':
                case 'Approved[ROLLED BACK]':
                    if (userPermissions.includes('budget_allocate')) {
                        sectionToShow = 'budget-allocate';
                    }
                    break;
                case 'Budget Allocated':
                case 'Budget Allocated[ROLLED BACK]':
                    if (userPermissions.includes('accounting_dv_input')) {
                        sectionToShow = 'accounting-dv-input';
                    }
                    break;
                case 'DV Submitted':
                case 'DV Submitted[ROLLED BACK]':
                case 'Ready for Disbursement':
                    if (userPermissions.includes('treasury_disburse')) {
                        sectionToShow = 'treasury-disburse';


                        // FORCE show the Treasury section regardless of server-side conditions
                        const treasurySection = document.getElementById('treasury-disburse');
                        if (treasurySection) {
                            treasurySection.style.display = 'block';


                            // Also update the internal content visibility based on status
                            updateTreasurySectionContent(eventData);
                        }
                    }
                    break;
                case 'Disbursed':
                    // No actions needed for disbursed status
                    break;
            }

            if (sectionToShow && sectionToShow !== 'treasury-disburse') {
                showActionSection(sectionToShow);

            }
            updateRollbackDropdown(eventData);

            // Update rollbacker button AFTER action sections are shown
            updateReturnToRollbackerButton(eventData);
        }

        // New function to handle Treasury section content updates
        function updateTreasurySectionContent(eventData) {
            const baseStatus = eventData.status.replace('[ROLLED BACK]', '').trim();
            const treasurySection = document.getElementById('treasury-disburse');

            if (!treasurySection) return;



            // Hide all content divs first
            const dvSubmittedContent = treasurySection.querySelector('.dv-submitted-content');
            const readyForDisbursementContent = treasurySection.querySelector('.ready-for-disbursement-content');

            if (dvSubmittedContent) dvSubmittedContent.style.display = 'none';
            if (readyForDisbursementContent) readyForDisbursementContent.style.display = 'none';

            // Show appropriate content based on status
            if (baseStatus === 'DV Submitted' || baseStatus === 'DV Submitted[ROLLED BACK]') {
                if (dvSubmittedContent) {
                    dvSubmittedContent.style.display = 'block';

                }
            } else if (baseStatus === 'Ready for Disbursement') {
                if (readyForDisbursementContent) {
                    readyForDisbursementContent.style.display = 'block';

                }
            }
        }

        function hideAllActionSections() {
            const sections = [
                'submit-patient-application',
                'approve-patient',
                'budget-allocate',
                'accounting-dv-input',
                'treasury-disburse'
            ];

            sections.forEach(sectionId => {
                const section = document.getElementById(sectionId);
                if (section) {
                    section.style.display = 'none';
                }
            });
        }

        function showActionSection(sectionId) {
            const section = document.getElementById(sectionId);
            if (section) {
                section.style.display = 'block';
            }
        }

        function updateProcessTracker(eventData) {
            const steps = ['Submitted', 'Approved', 'Budget Allocated', 'DV Submitted', 'Disbursed'];
            const baseStatus = eventData.status.replace('[ROLLED BACK]', '').trim();

            const currentIndex = steps.indexOf(baseStatus);
            if (currentIndex === -1) return;

            // Update stepper visualization
            const stepperSteps = document.querySelectorAll('.stepper-step');
            stepperSteps.forEach((step, index) => {
                step.classList.remove('completed', 'next', 'has-blue-line');

                if (index <= currentIndex) {
                    step.classList.add('completed');
                }
                if (index === currentIndex + 1) {
                    step.classList.add('next');
                }
                if (index === currentIndex) {
                    step.classList.add('has-blue-line');
                }
            });
        }

        // Helper function for form submission
        function submitForm(url, button) {
            const form = button.closest('form');
            form.action = url;
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            form.submit();
        }

        function updateFormLockState(eventData) {
            const baseStatus = eventData.status.replace('[ROLLED BACK]', '').trim();

            // Define which statuses should be locked
            const lockedStatuses = ['Submitted', 'Submitted[Emergency]', 'Approved', 'Budget Allocated', 'DV Submitted',
                'Disbursed', 'Ready for Disbursement'
            ];
            const shouldBeLocked = lockedStatuses.includes(baseStatus);

            // Update the submit form elements
            const submittedDate = document.getElementById('submitted_date');
            const remarksTextarea = document.getElementById('remarks');
            const submitButtons = document.querySelectorAll('#submit-patient-application button[type="button"]');
            const lockedAlert = document.querySelector('#submit-patient-application .alert');

            if (submittedDate) {
                submittedDate.disabled = shouldBeLocked;
            }

            if (remarksTextarea) {
                remarksTextarea.disabled = shouldBeLocked;
            }

            if (submitButtons) {
                submitButtons.forEach(button => {
                    button.disabled = shouldBeLocked;
                });
            }

            // Show/hide the locked alert
            if (lockedAlert) {
                lockedAlert.style.display = shouldBeLocked ? 'block' : 'none';
            }

        }

        function updateReturnToRollbackerButton(eventData) {


            // Use the specific class to find all Return to Rollbacker buttons
            const rolledBackForms = document.querySelectorAll('.return-to-rollbacker-form');
            const rolledBackContainers = document.querySelectorAll('.return-to-rollbacker-container');



            // FIXED LOGIC: Show button ONLY if the current status has [ROLLED BACK] tag
            const isRolledBack = eventData.status && eventData.status.includes('[ROLLED BACK]');
            const shouldShowButton = isRolledBack;


            // Update all forms
            rolledBackForms.forEach((form, index) => {
                if (shouldShowButton) {
                    form.style.display = 'inline-block';

                } else {
                    form.style.display = 'none';

                }
            });

            // Update all containers
            rolledBackContainers.forEach((container, index) => {
                if (shouldShowButton) {
                    container.style.display = 'block';

                } else {
                    container.style.display = 'none';

                }
            });


        }

        function submitApplication(url, clickedButton, type) {
            const form = clickedButton.closest('form');
            form.action = url;

            // Disable both submit buttons
            const submitButtons = document.querySelectorAll('.submit-btn');
            submitButtons.forEach(button => {
                button.disabled = true;
                if (button === clickedButton) {
                    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                } else {
                    button.innerHTML = '<i class="fas fa-clock"></i> Please wait...';
                }
            });

            form.submit();
        }

        function updateRollbackDropdown(eventData) {
            console.log('🔄 Updating rollback dropdown for status:', eventData.status);

            const baseStatus = eventData.status.replace('[ROLLED BACK]', '').trim();
            const rollbackSelect = document.getElementById('rollback_to');

            if (!rollbackSelect) return;

            // Define the process flow sequence
            const processFlow = {
                'Processing': 'CSWD Office',
                'Submitted': 'Mayor\'s Office',
                'Approved': 'Budget Office',
                'Budget Allocated': 'Accounting Office',
                'DV Submitted': 'Treasury Office'
            };

            // Get current position in process flow
            const processSteps = Object.keys(processFlow);
            const currentPosition = processSteps.indexOf(baseStatus);

            let availableRollbacks = [];

            if (currentPosition !== -1 && currentPosition > 0) {
                // Only allow rollback to previous steps
                for (let i = currentPosition - 1; i >= 0; i--) {
                    const targetStatus = processSteps[i];
                    const targetOffice = processFlow[targetStatus];
                    availableRollbacks.push({
                        status: targetStatus,
                        office: targetOffice
                    });
                }
            }

            // Special case for emergency submissions
            if (baseStatus === 'Submitted' && eventData.status.includes('[EMERGENCY]')) {
                if (!availableRollbacks.some(item => item.status === 'Processing')) {
                    availableRollbacks.push({
                        status: 'Processing',
                        office: 'CSWD Office'
                    });
                }
            }

            // Clear existing options except the first one
            while (rollbackSelect.options.length > 1) {
                rollbackSelect.remove(1);
            }

            // Add new options
            availableRollbacks.forEach(rollback => {
                const option = document.createElement('option');
                option.value = rollback.status;
                option.textContent = rollback.office;
                rollbackSelect.appendChild(option);
            });

            // Update the rollback button state
            const rollbackButton = document.querySelector('#rollbackModal button[type="button"].btn-warning');
            if (rollbackButton) {
                rollbackButton.disabled = availableRollbacks.length === 0;

                if (availableRollbacks.length === 0) {
                    // Add info message if not already present
                    if (!document.querySelector('#rollbackModal .alert-info')) {
                        const infoAlert = document.createElement('div');
                        infoAlert.className = 'alert alert-info mt-2';
                        infoAlert.innerHTML =
                            'No valid rollback targets available. You can only rollback to previous departments in the process flow.';
                        rollbackSelect.parentNode.appendChild(infoAlert);
                    }
                } else {
                    // Remove info message if present
                    const existingAlert = document.querySelector('#rollbackModal .alert-info');
                    if (existingAlert) {
                        existingAlert.remove();
                    }
                }
            }

            console.log('✅ Updated rollback dropdown with options:', availableRollbacks);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const statusBadge = document.getElementById('current-status-badge');
            if (statusBadge) {
                const initialStatus = statusBadge.textContent;
                updateReturnToRollbackerButton({
                    status: initialStatus
                });
            }
            initializeRealTimeUpdates();

            // Existing toast and amount button code
            var toastEl = document.getElementById('liveToast');
            var timerEl = document.getElementById('toast-timer');

            if (toastEl) {
                var toast = new bootstrap.Toast(toastEl, {
                    autohide: true,
                    delay: 5000
                });
                toast.show();

                let remaining = 5;
                const interval = setInterval(() => {
                    remaining--;
                    if (timerEl) {
                        timerEl.textContent = `Closing in ${remaining}s`;
                    }
                    if (remaining <= 0) {
                        clearInterval(interval);
                    }
                }, 1000);
            }

            const amountInput = document.getElementById('amount');
            const buttons = document.querySelectorAll('.suggested-amount');

            buttons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const value = this.dataset.value;
                    amountInput.value = value;
                    amountInput.focus();
                    amountInput.classList.add('bg-success', 'text-white');
                    setTimeout(() => {
                        amountInput.classList.remove('bg-success', 'text-white');
                    }, 500);
                });
            });
        });
    </script>
@endsection
