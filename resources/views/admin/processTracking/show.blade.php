@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            View Process Tracking
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped">
                <tr>
                    <th>Control Number</th>
                    <td>{{ $patient->control_number }}</td>
                </tr>
                <tr>
                    <th>Date Processed</th>
                    <td>{{ \Carbon\Carbon::parse($patient->date_processed)->format('F j, Y g:i A') }}</td>
                </tr>
                <tr>
                    <th>Claimant Name</th>
                    <td>{{ $patient->claimant_name }}</td>
                </tr>
                <tr>
                    <th>Case Worker</th>
                    <td>{{ $patient->case_worker }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ $latestStatus->status}}</td>
                </tr>

            </table>
            @if ($patient->statusLogs->count())
                <div class="mt-4">
                    <h5>Process Summary</h5>
                    <ul class="list-group">
                        @foreach ($patient->statusLogs as $log)
                            <li class="list-group-item">
                                <strong>{{ ucfirst($log->status) }}:</strong>
                                {{ $log->user->name ?? 'System' }} -
                                {{ \Carbon\Carbon::parse($log->created_at)->format('F j, Y g:i A') }}
                                <em>Remarks:</em> {{ $log->remarks ?? '-' }}
                                <br>
                            </li>
                        @endforeach
                    </ul>

                </div>
            @endif


            @php
                $isFinalized = in_array(optional($latestStatus)->status, ['Approved', 'Rejected']);
            @endphp

            @can('approve_patient') {{-- Only Mayor --}}
                @if ($latestStatus->status === 'Submitted')
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
                @elseif ($latestStatus->status === 'Rejected')
                    <div class="alert alert-danger mt-4">
                        <strong>Rejected</strong>
                    </div>
                @elseif($latestStatus->status !== 'Rejected' && $latestStatus->status === 'Approved')
                    <div class="alert alert-success mt-4">
                        <strong>Approved</strong>
                    </div>
                @endif
            @endcan


            @can('budget_allocate')
                @if ($latestStatus->status === 'Approved' && !$patient->budgetAllocation)
                    <div class="mt-4">
                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#budgetModal">
                            Allocate Budget
                        </button>
                    </div>

                    <div class="modal fade" id="budgetModal" tabindex="-1" role="dialog" aria-labelledby="budgetModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form action="{{ route('admin.process-tracking.storeBudget', $patient->id) }}" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="budgetModalLabel">Allocate Budget</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="amount">Amount (₱)</label>
                                            <input type="number" step="0.01" name="amount" id="amount" class="form-control"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="remarks">Remarks</label>
                                            <textarea name="remarks" id="remarks" class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Allocate</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                @elseif($patient->budgetAllocation)
                    <div class="alert alert-info mt-4">
                        <strong>Allocated Budget:</strong> ₱{{ number_format($patient->budgetAllocation->amount, 2) }} <br>
                        <strong>Remarks:</strong> {{ $patient->budgetAllocation->remarks }}
                    </div>
                @endif
            @endcan


            @can('accounting_dv_input')
                @if ($latestStatus->status === 'Budget Allocated' && !$patient->disbursementVoucher)

                    <button type="button" class="btn btn-info mt-3" data-toggle="modal" data-target="#dvModal">
                        Enter DV Details
                    </button>

                    <div class="modal fade" id="dvModal" tabindex="-1" role="dialog" aria-labelledby="dvModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form action="{{ route('admin.process-tracking.storeDV', $patient->id) }}" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="dvModalLabel">Enter Disbursement Voucher</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="dv_code">DV Code</label>
                                            <input type="text" name="dv_code" id="dv_code" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="dv_date">DV Date</label>
                                            <input type="date" name="dv_date" id="dv_date" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Submit DV</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @elseif ($patient->disbursementVoucher)
                    <div class="alert alert-info mt-4">
                        <strong>DV Code:</strong> {{ $patient->disbursementVoucher->dv_code }} <br>
                        <strong>Date:</strong> {{ \Carbon\Carbon::parse($patient->disbursementVoucher->dv_date)->format('F j, Y') }}
                    </div>
                @endif
            @endcan
            @can('treasury_disburse')
                @if ($latestStatus->status === 'DV Submitted' && $patient->budgetAllocation && $patient->budgetAllocation->budget_status !== 'Disbursed')
                    <div class="mt-4">
                        <form action="{{ route('admin.process-tracking.disburseBudget', $patient->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">Mark as Disbursed</button>
                        </form>
                    </div>
                @elseif ($patient->budgetAllocation && $patient->budgetAllocation->budget_status === 'Disbursed')
                    <div class="alert alert-success mt-4">
                        <strong>Status:</strong> Disbursed
                    </div>
                @endif
            @endcan



@endsection