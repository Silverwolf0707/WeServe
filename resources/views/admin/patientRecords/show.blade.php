@extends('layouts.admin')

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="m-0">Patient Record Details</h5>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-primary">Patient Info</h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>Patient Name:</th>
                            <td>{{ $patientRecord->patient_name }}</td>
                        </tr>
                        <tr>
                            <th>Age:</th>
                            <td>{{ $patientRecord->age }}</td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td>{{ $patientRecord->address }}</td>
                        </tr>
                        <tr>
                            <th>Contact Number:</th>
                            <td>{{ $patientRecord->contact_number }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="text-primary">Case Info</h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>Control Number:</th>
                            <td>{{ $patientRecord->control_number }}</td>
                        </tr>
                        <tr>
                            <th>Date Processed:</th>
                            <td>{{ \Carbon\Carbon::parse($patientRecord->date_processed)->format('F j, Y g:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Claimant Name:</th>
                            <td>{{ $patientRecord->claimant_name }}</td>
                        </tr>
                        <tr>
                            <th>Diagnosis:</th>
                            <td>
                                @if (strlen($patientRecord->diagnosis) > 60)
                                    {{ Str::limit($patientRecord->diagnosis, 60) }}
                                    <button class="btn btn-sm btn-outline-primary ml-2" data-bs-toggle="modal"
                                        data-bs-target="#diagnosisModal">
                                        View
                                    </button>
                                @else
                                    {{ $patientRecord->diagnosis }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Case Type:</th>
                            <td>{{ $patientRecord->case_type }}</td>
                        </tr>
                        <tr>
                            <th>Case Category:</th>
                            <td>{{ App\Models\PatientRecord::CASE_CATEGORY_SELECT[$patientRecord->case_category] ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>Case Worker:</th>
                            <td>{{ $patientRecord->case_worker }}</td>
                        </tr>
                        <tr>
                            <th>Tracking Number:</th>
                            <td>{{ $patientRecord->trackingNumber->tracking_number ?? '' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @php
                $latestStatus = $patientRecord->latestStatusLog;
                $latestStatusValue = optional($latestStatus)->status ?? 'Processing';
                $baseStatus = trim(preg_replace('/\[.*?\]/', '', $latestStatusValue));

                // Check if form should be locked
                $isLocked = !in_array($baseStatus, ['Processing', 'Rejected', 'Draft', 'Processing[ROLLED BACK]']);

                // Check if application was previously submitted as emergency
                $hasEmergencySubmission = false;
                $hasNormalSubmission = false;

                if ($patientRecord->statusLogs) {
                    // Find the most recent submission (before any rejection or rolled back)
                    $submissionLogs = $patientRecord->statusLogs
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
                            !$hasEmergencySubmission && str_contains($previousSubmissionStatus, 'Submitted');
                    }
                }
            @endphp

            @can('submit_patient_application')
                @if (in_array($baseStatus, ['Processing', 'Draft', 'Rejected', 'Processing[ROLLED BACK]']))
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-paper-plane mr-2"></i> CSWD Office - Submit Application
                        </div>

                        <div class="card-body">
                            <form method="POST" id="submitForm">
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
                                    $submissionLogs = $patientRecord->statusLogs
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
                                        onclick="submitApplication('{{ route('admin.patient-records.submit', $patientRecord->id) }}', this, 'normal')"
                                        id="normal-submit-btn">
                                        Submit
                                    </button>

                                    {{-- Emergency Submit --}}
                                    <button type="button" class="btn btn-danger submit-btn"
                                        @if ($isLocked || ($showSubmissionWarnings && $hasNormalSubmission)) disabled @endif
                                        onclick="submitApplication('{{ route('admin.patient-records.submit-emergency', $patientRecord->id) }}', this, 'emergency')"
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
                        </div>
                    </div>
                @endif
            @endcan

            <div class="d-flex justify-content-between align-items-center mt-4">
                <!-- Left Side: Back to List -->
                <a class="btn btn-secondary" href="{{ route('admin.patient-records.index') }}">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>

                <!-- Right Side: View Documents and Process Tracking -->
                <div class="d-flex gap-2">
                    <a class="btn btn-primary" href="{{ route('admin.document-management.show', $patientRecord->id) }}">
                        <i class="fas fa-file-alt me-1"></i> View Documents
                    </a>
                    @if ($patientRecord->statusLogs()->exists())
                        <a class="btn btn-info" href="{{ route('admin.process-tracking.show', $patientRecord->id) }}">
                            <i class="fas fa-tasks me-1"></i> View Process Tracking
                        </a>
                    @else
                        <button class="btn btn-info" disabled title="Process tracking is only available after submission">
                            <i class="fas fa-tasks me-1"></i> View Process Tracking
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Diagnosis Modal -->
    <div class="modal fade" id="diagnosisModal" tabindex="-1" role="dialog" aria-labelledby="diagnosisModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="diagnosisModalLabel">Full Diagnosis</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body"
                    style="
                      white-space: pre-wrap;
                      word-wrap: break-word;
                      overflow-y: auto;
                      max-height: 400px;
                      font-size: 16px;
                      line-height: 1.6;
                      padding-right: 10px;
                    ">
                    {{ $patientRecord->diagnosis }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function submitApplication(url, clickedButton, type) {
            const form = document.getElementById('submitForm');
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

            // Submit the form
            form.submit();
        }
    </script>
@endsection
