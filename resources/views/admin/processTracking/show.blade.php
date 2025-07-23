@extends('layouts.admin')

@section('content')
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="m-0"><i class="fas fa-tasks me-2"></i> Process Tracking</h5>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
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
                            <th>Case Worker:</th>
                            <td>{{ $patient->case_worker }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>{{ $latestStatus->status }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- ✅ VISUAL PROCESS TRACKER --}}
            @php
                $steps = ['Submitted', 'Approved', 'Budget Allocated', 'DV Submitted', 'Disbursed'];
                $currentStatus = $latestStatus->status;
                $currentIndex = array_search($currentStatus, $steps);
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
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
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
                    <div
                        class="step 
        {{ $latestStatus->status !== 'Rejected' && $index < $currentIndex ? 'completed' : '' }} 
        {{ $latestStatus->status !== 'Rejected' && $index === $currentIndex ? 'active' : '' }}">
                        <div class="circle">
                            @if ($latestStatus->status !== 'Rejected' && $index <= $currentIndex)
                                <i class="fas fa-check"></i>
                            @else
                                {{ $index + 1 }}
                            @endif
                        </div>
                        <div class="label">{{ $step }}</div>
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
        .status-disbursed {
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
                        <a href="#" data-toggle="modal" data-target="#processModal"
                           data-department="{{ strtolower(Auth::user()->department) }}"
                           title="View Action">
                            <i class="fas fa-eye text-primary"></i>
                        </a>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
@endif

<!-- MODAL -->
<div class="modal fade" id="processModal" tabindex="-1" aria-labelledby="processModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="processModalLabel">📤 Process Action</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="subject">📌 Subject</label>
                        <input type="text" class="form-control" id="subject" placeholder="Enter subject" required>
                    </div>

                    <div class="form-group">
                        <label for="action">📁 Action</label>
                        <select class="form-control" id="action" required>
                            <option value="Forward">Forward</option>
                            <option value="Return">Return</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="department">🏢 Department</label>
                        <select class="form-control" id="department" required>
                            <option value="CSWD">CSWD</option>
                            <option value="Mayor's Office">Mayor's Office</option>
                            <option value="Budget Office">Budget Office</option>
                            <option value="Accounting">Accounting</option>
                            <option value="Treasury">Treasury</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="remarks">📝 Remarks</label>
                        <textarea class="form-control" id="remarks" rows="3" placeholder="Add any remarks here..."></textarea>
                    </div>

                    <div class="form-group">
                        <label for="document">📎 Upload Document</label>
                        <input type="file" class="form-control-file" id="document" accept=".pdf,.doc,.docx,.jpg,.png,.jpeg">
                        <small class="text-muted">Accepted formats: PDF, DOCX, JPG, PNG</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>



@push('scripts')
<script>
    const departments = {
        'accounting': ['CSWD', 'Mayor\'s Office', 'Budget Office', 'Treasury'],
        'cswd': ['Accounting', 'Mayor\'s Office'],
        'mayor\'s office': ['Accounting', 'CSWD', 'Budget Office'],
        'budget office': ['CSWD', 'Accounting'],
        'treasury': ['CSWD', 'Mayor\'s Office']
    };

    $('#processModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const userDept = button.data('department');
        const options = departments[userDept] || [];
        const deptSelect = $('#department');

        deptSelect.empty();
        options.forEach(dept => {
            deptSelect.append(`<option value="${dept}">${dept}</option>`);
        });
    });
</script>
@endpush

            @php $isFinalized = in_array(optional($latestStatus)->status, ['Approved', 'Rejected']); @endphp

            @can('approve_patient')
                @if ($latestStatus->status === 'Submitted')
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
                @if ($latestStatus->status === 'Approved' && !$patient->budgetAllocation)
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-wallet me-2"></i> Budget Allocation
                        </div>
                        <div class="card-body">
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#budgetModal">
                                Allocate Budget
                            </button>

                            <div class="modal fade" id="budgetModal" tabindex="-1" role="dialog"
                                aria-labelledby="budgetModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form action="{{ route('admin.process-tracking.storeBudget', $patient->id) }}"
                                        method="POST">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title" id="budgetModalLabel">Allocate Budget</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="amount">Amount (₱)</label>
                                                    <input type="number" step="0.01" name="amount" id="amount"
                                                        class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="remarks">Remarks</label>
                                                    <textarea name="remarks" id="remarks" class="form-control" rows="3"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success">Allocate</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($patient->budgetAllocation)
                    <div class="alert alert-info mt-4">
                        <strong>Allocated Budget:</strong> ₱{{ number_format($patient->budgetAllocation->amount, 2) }}<br>
                        <strong>Remarks:</strong> {{ $patient->budgetAllocation->remarks }}
                    </div>
                @endif
            @endcan

            @can('accounting_dv_input')
                @if ($latestStatus->status === 'Budget Allocated' && !$patient->disbursementVoucher)
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-file-invoice me-2"></i> Disbursement Voucher
                        </div>
                        <div class="card-body">
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#dvModal">
                                Enter DV Details
                            </button>

                            <div class="modal fade" id="dvModal" tabindex="-1" role="dialog" aria-labelledby="dvModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form action="{{ route('admin.process-tracking.storeDV', $patient->id) }}"
                                        method="POST">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title" id="dvModalLabel">Enter Disbursement Voucher</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="dv_code">DV Code</label>
                                                    <input type="text" name="dv_code" id="dv_code" class="form-control"
                                                        required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="dv_date">DV Date</label>
                                                    <input type="date" name="dv_date" id="dv_date" class="form-control"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success">Submit DV</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif ($patient->disbursementVoucher)
                    <div class="alert alert-info mt-4">
                        <strong>DV Code:</strong> {{ $patient->disbursementVoucher->dv_code }}<br>
                        <strong>Date:</strong>
                        {{ \Carbon\Carbon::parse($patient->disbursementVoucher->dv_date)->format('F j, Y') }}
                    </div>
                @endif
            @endcan

            @can('treasury_disburse')
                @if (
                    $latestStatus->status === 'DV Submitted' &&
                        $patient->budgetAllocation &&
                        $patient->budgetAllocation->budget_status !== 'Disbursed')
                    <form action="{{ route('admin.process-tracking.disburseBudget', $patient->id) }}" method="POST"
                        class="mt-4">
                        @csrf
                        <button type="submit" class="btn btn-primary">Mark as Disbursed</button>
                    </form>
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
