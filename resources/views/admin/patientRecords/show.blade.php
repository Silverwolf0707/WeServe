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
                                @if(strlen($patientRecord->diagnosis) > 60)
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
                    </table>
                </div>
            </div>

            @php
                $latestStatusValue = optional($latestStatus)->status;
                $isLocked = !in_array($latestStatusValue, [null, 'Rejected', 'Processing']);
            @endphp

            @can('submit_patient_application')
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-paper-plane mr-2"></i> Submit Application
                    </div>

                    <div class="card-body">
                        <form method="POST">
                            @csrf
                            <input type="hidden" name="status" value="Submitted">

                            <div class="form-group">
                                <label for="submitted_date">Submitted Date</label>
                                <input type="datetime-local" name="submitted_date" id="submitted_date" class="form-control mb-3"
                                    value="{{ now()->toDateTimeLocalString() }}" @if($isLocked) disabled @endif>

                                <label for="remarks">Remarks</label>
                                <textarea name="remarks" id="remarks" rows="4" class="form-control" required @if($isLocked)
                                disabled @endif></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                {{-- Normal submit --}}
                                <button type="submit"
                                    formaction="{{ route('admin.patient-records.submit', $patientRecord->id) }}"
                                    class="btn btn-primary" @if($isLocked) disabled @endif>
                                    Submit
                                </button>

                                {{-- Emergency submit --}}
                                <button type="submit"
                                    formaction="{{ route('admin.patient-records.submit-emergency', $patientRecord->id) }}"
                                    class="btn btn-danger" @if($isLocked) disabled @endif>
                                    Submit [Emergency]
                                </button>
                            </div>

                            @if($isLocked)
                                <div class="alert alert-info mt-3">
                                    This application has already been submitted and is currently in process.
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            @endcan


            <div class="d-flex justify-content-between">
                <a class="btn btn-secondary" href="{{ route('admin.patient-records.index') }}">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
                <a class="btn btn-secondary" href="{{ route('admin.document-management.show', $patientRecord->id) }}">
                    <i class="fas fa-file-alt me-1"></i> View Documents
                </a>
                <a class="btn btn-info {{ !$hasProcessTracking ? 'disabled' : '' }}"
                    href="{{ $hasProcessTracking ? route('admin.process-tracking.show', $patientRecord->id) : '#' }}"
                    @if(!$hasProcessTracking) aria-disabled="true" tabindex="-1" @endif>
                    View Process Tracking
                </a>
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

                </div>
                <div class="modal-body" style="
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