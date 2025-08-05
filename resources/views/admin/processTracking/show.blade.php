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

            {{-- ✅ VISUAL PROCESS TRACKER --}}
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


            <style>
                .stepper {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin: 2rem 0;
                    padding: 1.5rem;
                    background: #fff;
                    border-radius: 10px;

                }

                .step {
                    text-align: center;
                    flex: 1;
                    position: relative;
                }

                .step:not(:last-child)::after {
                    content: "";
                    position: absolute;
                    top: 15px;
                    right: -50%;
                    width: 100%;
                    height: 4px;
                    background: #e0e0e0;
                    z-index: 0;
                }

                .step.completed::after {
                    background: #28a745;
                }

                .circle {
                    width: 30px;
                    height: 30px;
                    background: #e0e0e0;
                    border-radius: 50%;
                    margin: 0 auto;
                    line-height: 30px;
                    color: white;
                    position: relative;
                    z-index: 1;
                }

                .step.completed .circle,
                .step.active .circle {
                    background: #28a745;
                }

                .label {
                    margin-top: 0.5rem;
                    font-weight: 500;
                }
            </style>

            <div class="stepper">
                @foreach ($steps as $index => $step)
                    <div class="step 
                                        {{ $baseStatus !== 'Rejected' && $index < $currentIndex ? 'completed' : '' }} 
                                        {{ $baseStatus !== 'Rejected' && $index === $currentIndex ? 'active' : '' }}">
                        <div class="circle">
                            @if ($baseStatus !== 'Rejected' && $index <= $currentIndex)
                                <i class="fas fa-check"></i>
                            @else
                                {{ $index + 1 }}
                            @endif
                        </div>
                        <div class="label">{{ $stepLabels[$step] ?? $step }}</div>
                    </div>
                @endforeach
            </div>

            {{-- ✅ END PROCESS TRACKER --}}

            {{-- PROCESS SUMMARY --}}
            @if ($patient->statusLogs->count())
                <style>
                    .status-submitted,
                    .status-approved,
                    .status-budget-allocated,
                    .status-dv-submitted,
                    .status-disbursed,
                    .status-ready-for-disbursement {
                        background-color: #b2dfb2;
                        color: #0b3e0b;
                    }

                    .status-rejected {
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
                    <ul class="list-group">
                        @foreach ($patient->statusLogs as $log)
                            @php
                                $statusKey = strtolower(str_replace(' ', '-', $log->status));
                                $statusClass = 'status-' . $statusKey;
                            @endphp
                            <li class="list-group-item {{ $statusClass }}">
                                <div>
                                    <strong>{{ ucfirst($log->status) }}:</strong>
                                    {{ $log->user->name ?? 'System' }} -
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('F j, Y g:i A') }}<br>
                                    <em>Remarks:</em> {{ $log->remarks ?? '-' }}
                                </div>
                                <div>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#processModal" title="View Action">
                                        <i class="fas fa-eye text-primary"></i>
                                    </a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif




            @php $isFinalized = in_array(optional($latestStatus)->status, ['Approved', 'Rejected']); @endphp

            @can('approve_patient')
                @if ($baseStatus === 'Submitted')
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-paper-plane me-2"></i> Mayor Approval
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.process-tracking.decision', $patient->id) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="remarks">Remarks</label>
                                    <textarea name="remarks" id="remarks" rows="3" class="form-control" required></textarea>
                                </div>
                                <div class="d-flex gap-2 mt-3">
                                    <button name="action" value="approve" class="btn btn-success">Approve</button>
                                    <button name="action" value="reject" class="btn btn-danger">Reject</button>
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
                                @if($patient->budgetAllocation)
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

                                        <div class="form-group">
                                            <label for="remarks" class="form-label">Remarks</label>
                                            <textarea name="remarks" id="remarks" class="form-control rounded-3 shadow-sm" rows="4"
                                                placeholder="Enter any remarks here...">{{ old('remarks', $patient->budgetAllocation->remarks ?? '') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="modal-footer d-flex flex-column gap-2 p-4 pt-0">
                                        <button type="submit" class="btn btn-success w-100 rounded-pill py-2">
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
                                            $previousStatuses = $patient->statusLogs
                                                ->pluck('status')
                                                ->unique()
                                                ->filter(function ($status) use ($latestStatus) {
                                                    return $status !== $latestStatus->status;
                                                });
                                        @endphp

                                        @foreach ($previousStatuses as $status)
                                            <option value="{{ $status }}">{{ $status }}</option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="rollback_remarks">Remarks</label>
                                    <textarea name="rollback_remarks" class="form-control" id="rollback_remarks" rows="3"
                                        required></textarea>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-danger">Rollback</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @can('accounting_dv_input')
                @if ($baseStatus === 'Budget Allocated')
                    <div class="card shadow-sm border-0 mb-4" style="background-color: #f8f9fa;">
                        <div class="card-header bg-primary text-white d-flex align-items-center">
                            <i class="fas fa-file-invoice"></i>
                            <h5 class="mb-0" style="margin-left: 10px;">Disbursement Voucher</h5>
                        </div>

                        <div class="card-body text-center">
                            <button type="button" class="btn btn-info btn-lg px-4 text-white" data-bs-toggle="modal"
                                data-bs-target="#dvModal">
                                <i class="fas fa-file-alt me-2"></i> {{ $patient->disbursementVoucher ? 'Edit' : 'Enter' }} DV
                                Details
                            </button>
                            <button type="button" class="btn btn-danger btn-lg px-4 text-white" data-bs-toggle="modal"
                                data-bs-target="#rollbackModal">
                                <i class="fas fa-undo-alt me-1"></i> Rollback Process
                            </button>
                        </div>
                    </div>

                    <!-- DV Modal -->
                    <div class="modal fade" id="dvModal" tabindex="-1" role="dialog" aria-labelledby="dvModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form action="{{ $patient->disbursementVoucher
                        ? route('admin.process-tracking.updateDV', $patient->id)
                        : route('admin.process-tracking.storeDV', $patient->id) }}" method="POST">
                                @csrf
                                @if ($patient->disbursementVoucher)
                                    @method('PUT')
                                @endif

                                <div class="modal-content border-0">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="dvModalLabel"><i class="fas fa-file-invoice me-2"></i>
                                            {{ $patient->disbursementVoucher ? 'Edit' : 'Enter' }} Disbursement Voucher
                                        </h5>
                                        <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="form-group mb-3">
                                            <label for="dv_code">DV Code</label>
                                            <input type="text" name="dv_code" id="dv_code" class="form-control form-control-lg"
                                                value="{{ old('dv_code', $patient->disbursementVoucher->dv_code ?? '') }}" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="dv_date">DV Date</label>
                                            <input type="date" name="dv_date" id="dv_date" class="form-control form-control-lg"
                                                value="{{ old('dv_date', optional($patient->disbursementVoucher)->dv_date ? \Carbon\Carbon::parse($patient->disbursementVoucher->dv_date)->format('Y-m-d') : '') }}"
                                                required>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="fas fa-check-circle me-1"></i>
                                            {{ $patient->disbursementVoucher ? 'Update' : 'Submit' }} DV
                                        </button>
                                        <button type="button" class="btn btn-secondary w-100 mt-2"
                                            data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            @endcan


            @can('treasury_disburse')
                @if (
                        in_array($baseStatus, ['DV Submitted', 'Ready for Disbursement']) &&
                        $patient->budgetAllocation &&
                        $patient->budgetAllocation->budget_status !== 'Disbursed'
                    )
                    @if ($baseStatus === 'DV Submitted' && $patient->budgetAllocation->budget_status === 'Not Disbursed')
                        {{-- READY FOR DISBURSEMENT --}}
                        <form action="{{ route('admin.process-tracking.sendOtp', $patient->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-warning mt-4">Ready for Disbursement</button>
                            <button type="button" class="btn btn-danger btn-lg px-4 text-white" data-bs-toggle="modal"
                                data-bs-target="#rollbackModal">
                                <i class="fas fa-undo-alt me-1"></i> Rollback Process
                            </button>
                        </form>
                    @elseif ($baseStatus === 'Ready for Disbursement')
                        {{-- VERIFY OTP --}}
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
            @endcan




            <div class="form-group mt-4">
                <a class="btn btn-secondary" href="{{ route('admin.process-tracking.index') }}">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
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