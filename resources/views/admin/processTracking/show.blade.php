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
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>Case Worker:</th>
                            <td>{{ $patient->case_worker }}</td>
                        </tr>
                        <tr>
                            <th>Current Status:</th>
                            <td>
                                <span class="badge badge-info">{{ $latestStatus->status }}</span>
                            </td>
                        </tr>
                        @if (!empty($latestStatus->remarks))
                            <tr>
                                <th>Remarks:</th>
                                <td>{{ $latestStatus->remarks }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th>Updated At:</th>
                            <td>{{ $latestStatus->updated_at->format('F j, Y g:i A') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{--VISUAL PROCESS TRACKER --}}
            @php
                $steps = ['Submitted', 'Approved', 'Budget Allocated', 'DV Submitted', 'Disbursed'];

                $stepLabels = [
                    'Submitted' => 'CSWD Office',
                    'Approved' => 'Mayor\'s Office',
                    'Budget Allocated' => 'Budget Office',
                    'DV Submitted' => 'Accounting Office',
                    'Disbursed' => 'Treasury Office',
                ];

                // Normalize the latest status (strip anything after brackets like '[ROLLED BACK]')
                $rawStatus = $latestStatus->status;
                $baseStatus = trim(preg_replace('/\[.*?\]/', '', $rawStatus));

                // Get the current index in the steps
                $currentIndex = array_search($baseStatus, $steps);
            @endphp

            <div class="stepper">
                @foreach ($steps as $index => $step)
                    <div
                        class="stepper-step
                                                                                                {{ $baseStatus !== 'Rejected' && $index < $currentIndex ? 'completed' : '' }} 
                                                                                                {{ $baseStatus !== 'Rejected' && $index === $currentIndex ? 'active' : '' }}">

                        <div class="stepper-circle">
                            @if ($baseStatus !== 'Rejected' && $index <= $currentIndex)
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
                    .status-rolled-back {
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
                    <ul class="list-group" id="processSummaryList">
                        @foreach ($patient->statusLogs->where('status', '!=', 'Draft') as $log)
                            @php
                                $statusKey = strtolower(str_replace(' ', '-', $log->status));
                                $statusClass = strpos($log->status, '[ROLLED BACK]') !== false
                                    ? 'status-rolled-back'
                                    : 'status-' . $statusKey;

                                $roleTitle = $log->user
                                    ? $log->user->roles->pluck('title')->implode(', ')
                                    : 'System';
                            @endphp

                            <li class="list-group-item {{ $statusClass }}">
                                <div>
                                    <strong>{{ ucfirst($log->status) }}:</strong>
                                    {{ $log->user->name ?? 'System' }} -
                                    {{ \Carbon\Carbon::parse($log->status_date)->format('F j, Y g:i A') }} -
                                    From: {{ $roleTitle }}<br>
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
            @endphp

            @can('approve_patient')
                @if ($baseStatus === 'Submitted')
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-paper-plane me-2"></i> Mayor Approval
                        </div>
                        <div class="card-body">
                            <div class="d-flex gap-2 mt-3">
                                <!-- Trigger Approve Modal -->
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                                    Approve
                                </button>
                                <!-- Trigger Reject Modal -->
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                    Reject
                                </button>
                                @if ($latestLog && str_contains(strtolower($latestLog->remarks), 'rolled back'))
                                    <form action="{{ route('admin.process-tracking.returnToRollbacker', $patient->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-lg px-4 text-white">
                                            <i class="fas fa-share me-1"></i> Return to Rollbacker
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Approve Modal -->
                    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('admin.process-tracking.decision', $patient->id) }}">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title" id="approveModalLabel">Approve Application</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="statusDate" class="form-label">Status Date</label>
                                            <input type="datetime-local" name="status_date" id="statusDate" class="form-control"
                                                value="{{ now()->toDateTimeLocalString() }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="approveRemarks" class="form-label">Remarks</label>
                                            <textarea name="remarks" id="approveRemarks" class="form-control" rows="3"
                                                placeholder="Enter remarks..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" name="action" id="decisionAction">

                                        <button type="button" class="btn btn-success" onclick="
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
                    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('admin.process-tracking.decision', $patient->id) }}">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title" id="rejectModalLabel">Reject Application</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                                        value="{{ $reason }}" id="reason{{ $index }}" {{ collect(old('reasons'))->contains($reason) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="reason{{ $index }}">{{ $reason }}</label>
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
                                                <input type="datetime-local" name="status_date" id="statusDate" class="form-control"
                                                    value="{{ now()->toDateTimeLocalString() }}" required>
                                            </div>

                                            <label for="rejectRemarks" class="form-label">Remarks</label>
                                            <textarea name="remarks" id="rejectRemarks" class="form-control" rows="3"
                                                placeholder="Enter remarks..." required>{{ old('remarks') }}</textarea>
                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <input type="hidden" name="action" id="decisionAction">
                                        <button type="button" class="btn btn-danger" onclick="
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
                @endif
            @endcan


            @can('budget_allocate')
                @if ($baseStatus === 'Approved')
                    <div class="card shadow-sm border-0 mb-4" style="background-color: #f8f9fa;">
                        <div class="card-header bg-primary text-white d-flex align-items-center">
                            <i class="fas fa-wallet"></i>
                            <h5 class="mb-0" style="margin-left: 10px;">
                                {{ $patient->budgetAllocation ? 'Edit Budget Allocation' : 'Budget Allocation' }}
                            </h5>
                        </div>

                        <div class="card-body text-center">
                            <button type="button" class="btn btn-warning btn-lg px-4 text-white" data-bs-toggle="modal"
                                data-bs-target="#budgetModal">
                                <i class="fas fa-plus-circle me-2"></i>
                                {{ $patient->budgetAllocation ? 'Edit Budget' : 'Allocate Budget' }}
                            </button>
                            <button type="button" class="btn btn-danger btn-lg px-4 text-white" data-bs-toggle="modal"
                                data-bs-target="#rollbackModal">
                                <i class="fas fa-undo-alt me-1"></i> Rollback Process
                            </button>
                            @php
                                $latestLog = $patient->statusLogs->last();
                            @endphp

                            @if ($latestLog && str_contains(strtolower($latestLog->remarks), 'rolled back'))
                                <form action="{{ route('admin.process-tracking.returnToRollbacker', $patient->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-lg px-4 text-white">
                                        <i class="fas fa-share me-1"></i> Return to Rollbacker
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Budget Modal -->
                    <div class="modal fade" id="budgetModal" tabindex="-1" role="dialog" aria-labelledby="budgetModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <form action="{{ $patient->budgetAllocation
                        ? route('admin.process-tracking.updateBudget', $patient->id)
                        : route('admin.process-tracking.storeBudget', $patient->id) }}" method="POST">
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
                                        <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
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
                                        <button type="button" class="btn btn-success w-100 rounded-pill py-2" onclick="
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
                @endif
            @endcan

            <!-- Rollback Modal -->
            <div class="modal fade" id="rollbackModal" tabindex="-1" aria-labelledby="rollbackModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('admin.process-tracking.rollback', $patient->id) }}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title" id="rollbackModalLabel">Rollback Process</h5>
                                <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="rollback_to">Rollback to</label>
                                    <select class="form-control" name="rollback_to" id="rollback_to" required>
                                        @php
                                            $statusToOffice = [
                                                'Draft' => 'CSWD Office',
                                                'Submitted' => 'Mayor\'s Office',
                                                'Approved' => 'Budget Office',
                                                // 'Rejected'           => skip
                                                'Budget Allocated' => 'Accounting Office',
                                                'DV Submitted' => 'Treasury Office',
                                                // 'Disbursed'          => skip
                                                // 'Ready for Disbursement' => skip
                                            ];

                                            $previousStatuses = $patient->statusLogs
                                                ->pluck('status')
                                                ->unique()
                                                ->filter(function ($status) use ($latestStatus, $statusToOffice) {
                                                    return $status !== $latestStatus->status &&
                                                        isset($statusToOffice[$status]);
                                                });
                                        @endphp

                                        @foreach ($previousStatuses as $status)
                                            <option value="{{ $status }}">{{ $statusToOffice[$status] }}
                                            </option>
                                        @endforeach



                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="rollback_remarks">Remarks</label>
                                    <textarea name="rollback_remarks" class="form-control" id="rollback_remarks"
                                        rows="3"></textarea>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" onclick="
                                const form = this.closest('form');
                                this.disabled = true;
                                this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i> Rolling back...';
                                form.submit();
                            ">
                                    <i class="fas fa-undo-alt me-1"></i> Rollback
                                </button>

                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @can('accounting_dv_input')
                @if ($baseStatus === 'Budget Allocated')
                    <!-- Accounting Action Card -->
                    <div class="card shadow-sm border-0 mb-4" style="background-color: #f8f9fa;">
                        <div class="card-header bg-primary text-white d-flex align-items-center">
                            <i class="fas fa-file-invoice"></i>
                            <h5 class="mb-0 ms-2">Disbursement Voucher</h5>
                        </div>

                        <div class="card-body text-center">
                            <!-- Enter/Edit DV Button -->
                            <button type="button" class="btn btn-info btn-lg px-4 text-white" data-bs-toggle="modal"
                                data-bs-target="#dvModal">
                                <i class="fas fa-file-alt me-2"></i>
                                {{ $patient->disbursementVoucher ? 'Edit' : 'Enter' }} DV Details
                            </button>

                            <!-- Rollback Process Button -->
                            <button type="button" class="btn btn-danger btn-lg px-4 text-white" data-bs-toggle="modal"
                                data-bs-target="#rollbackModal">
                                <i class="fas fa-undo-alt me-1"></i> Rollback Process
                            </button>

                            @php
                                $latestLog = $patient->statusLogs->last();
                            @endphp

                            <!-- Return to Rollbacker Button -->
                            @if ($latestLog && str_contains(strtolower($latestLog->remarks), 'rolled back'))
                                <form action="{{ route('admin.process-tracking.returnToRollbacker', $patient->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-lg px-4 text-white mt-2">
                                        <i class="fas fa-share me-1"></i> Return to Rollbacker
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif
            @endcan

            <!-- ✅ Disbursement Voucher Modal (Adjusted to modal-lg) -->
            <div class="modal fade" id="dvModal" tabindex="-1" aria-labelledby="dvModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <form action="{{ $patient->disbursementVoucher
        ? route('admin.process-tracking.updateDV', $patient->id)
        : route('admin.process-tracking.storeDV', $patient->id) }}" method="POST">
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
                                        <input type="text" name="dv_code" id="dv_code" class="form-control form-control-lg"
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
                                <button type="button" class="btn btn-success w-100" onclick="
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


            @can('treasury_disburse')
                @if (
                        in_array($baseStatus, ['DV Submitted', 'Ready for Disbursement']) &&
                        $patient->budgetAllocation &&
                        $patient->budgetAllocation->budget_status !== 'Disbursed'
                    )
                    @if ($baseStatus === 'DV Submitted' && $patient->budgetAllocation->budget_status === 'Not Disbursed')
                        {{-- READY FOR DISBURSEMENT --}}
                        @can('disbursed_message_automation')
                            <form action="{{ route('admin.process-tracking.sendOtp', $patient->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                <button class="btn btn-warning btn-lg px-4 text-white mt-4">
                                    <i class="fas fa-exclamation-circle me-1"></i> Ready for Disbursement</button>
                            </form>
                        @endcan
                        <button type="button" class="btn btn-success btn-lg px-4 text-white mt-4" data-bs-toggle="modal"
                            data-bs-target="#quickDisburseModal">
                            <i class="fas fa-check-circle me-1"></i> Mark as Disbursed
                        </button>

                        <button type="button" class="btn btn-danger btn-lg px-4 text-white mt-4" data-bs-toggle="modal"
                            data-bs-target="#rollbackModal">
                            <i class="fas fa-undo-alt me-1"></i> Rollback Process
                        </button>


                    @elseif ($baseStatus === 'Ready for Disbursement')
                        @php
                            $otp = $patient->otpCodes()->latest()->first();
                        @endphp

                        <form action="{{ route('admin.process-tracking.verifyOtp', $patient->id) }}" method="POST" class="mt-4">
                            @csrf
                            <label for="otp_code">Enter OTP to Confirm Disbursement:</label>
                            <input type="text" name="otp_code" required class="form-control mt-2 mb-2">
                            <button class="btn btn-success">Confirm & Mark Disbursed</button>
                        </form>
                    @endif
                @elseif ($patient->budgetAllocation && $patient->budgetAllocation->budget_status === 'Disbursed')
                    <div class="alert alert-success mt-4">
                        <strong>Status:</strong> Disbursed
                    </div>
                @endif

                <div class="modal fade" id="quickDisburseModal" tabindex="-1" aria-labelledby="quickDisburseModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('admin.process-tracking.quickDisburse', $patient->id) }}" method="POST">
                            @csrf
                            <div class="modal-content border-0 shadow-lg rounded-4">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title" id="quickDisburseModalLabel">
                                        <i class="fas fa-money-bill-wave me-2"></i> Quick Disburse
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
                                        <textarea name="remarks" id="quickDisburseRemarks" class="form-control form-control-lg"
                                            rows="3" placeholder="Enter any remarks..."></textarea>
                                    </div>
                                </div>

                                <div class="modal-footer d-flex flex-column gap-2">
                                    <button type="button" class="btn btn-success w-100" onclick="
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
            @endcan
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
                                <textarea name="remarks" id="remarks" rows="4" class="form-control" @if($isLocked) disabled
                                @endif></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                {{-- Normal Submit --}}
                                <button type="button" class="btn btn-primary" @if ($isLocked) disabled @endif onclick="
                                                                        const form = this.closest('form');
                                                                        this.disabled = true;
                                                                        this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i> Submitting...';
                                                                        form.action='{{ route('admin.patient-records.submit', $patient->id) }}';
                                                                        form.submit();
                                                                    ">
                                    Submit
                                </button>

                                {{-- Emergency Submit --}}
                                <button type="button" class="btn btn-danger" @if ($isLocked) disabled @endif onclick="
                                                                        const form = this.closest('form');
                                                                        this.disabled = true;
                                                                        this.innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i> Submitting...';
                                                                        form.action='{{ route('admin.patient-records.submit-emergency', $patient->id) }}';
                                                                        form.submit();
                                                                    ">
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
@endsection
    @push('scripts')

        <script>
            document.addEventListener('DOMContentLoaded', function () {
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
            });
        </script>
        <script>
            // setTimeout(() => {
            //     Echo.channel('process-tracking')
            //         .listen('.patient.process.updated', (e) => {
            //             console.log("Status changed:", e);

            //         });



            // }, 300);


            document.addEventListener('DOMContentLoaded', function () {
                const amountInput = document.getElementById('amount');
                const buttons = document.querySelectorAll('.suggested-amount');

                buttons.forEach(btn => {
                    btn.addEventListener('click', function () {
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
    @endpush