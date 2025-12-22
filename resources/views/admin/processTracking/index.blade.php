@extends('layouts.admin')
@section('content')
    <div class="card shadow-sm border-0">
        <!-- Modernized Header -->
        <div class="card-header custom-header d-flex align-items-center bg-primary text-white"
            style="min-height: 70px; padding: 1.5rem;">
            <h4 class="mb-0 fw-bold d-flex align-items-center">
                <i class="fas fa-tasks me-2"></i> Process Tracking
            </h4>

            <div class="header-actions d-flex align-items-center ms-auto">
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-ProcessTracking">
                <thead class="thead-dark">
                    <tr>
                        <th width="10"></th>
                        <th>{{ __('Control Number') }}</th>
                        <th>{{ __('Date Processed') }}</th>
                        <th>{{ __('Claimant Name') }}</th>
                        <th>{{ __('Case Worker') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Department Responsible') }}</th>
                        <th class="text-center" width="50">{{ __('Actions') }}</th>
                        <th style="display:none;">SortPriority</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($patients as $patient)
                        <tr data-entry-id="{{ $patient->id }}">
                            <td></td>
                            <td>{{ $patient->control_number }}</td>
                            <td>{{ \Carbon\Carbon::parse($patient->date_processed)->format('F j, Y g:i A') }}</td>
                            <td>{{ $patient->claimant_name }}</td>
                            <td>{{ $patient->case_worker }}</td>
                            <td>
                                @php
                                    $currentStatus = $patient->latestStatusLog->status ?? 'Submitted';
                                    $isRollback = str_contains($currentStatus, '[ROLLED BACK]');
                                    $baseStatus = $isRollback
                                        ? trim(str_replace('[ROLLED BACK]', '', $currentStatus))
                                        : $currentStatus;
                                @endphp


                                @php
                                    // Extract [ROLLED BACK] if it exists
                                    $isRollback = str_contains($currentStatus, '[ROLLED BACK]');
                                    $baseStatus = $isRollback
                                        ? trim(str_replace('[ROLLED BACK]', '', $currentStatus))
                                        : $currentStatus;
                                @endphp

                                @php
                                    $priorityMap = [
                                        'CSWD Office' => [
                                            'Processing' => 1,
                                            'Submitted[ROLLED BACK]' => 3,
                                            'Submitted' => 4,
                                            'Rejected' => 2,
                                        ],
                                        'Budget Office' => [
                                            'Approved' => 1,
                                            'Budget Allocated' => 2,
                                            'Approved[ROLLED BACK]' => 3,
                                        ],
                                        'Treasury Office' => [
                                            'Ready for Disbursement' => 2,
                                            'Disbursed' => 3,
                                            'DV Submitted' => 1,
                                        ],
                                        'Mayors Office' => [
                                            'Submitted' => 2,
                                            'Submitted[Emergency]' => 1,
                                            'Submitted[ROLLED BACK]' => 3,
                                            'Approved' => 4,
                                        ],
                                        'Accounting Office' => [
                                            'Budget Allocated' => 1,
                                            'Budget Allocated[ROLLED BACK]' => 2,
                                            'DV Submitted' => 3,
                                        ],
                                    ];

                                    $role = Auth::user()->roles->pluck('title')->first();
                                    $priority = $priorityMap[$role][$currentStatus] ?? 999;
                                @endphp


                                @if ($baseStatus === 'Submitted')
                                    <span class="badge d-inline-flex align-items-center"
                                        style="background-color: #007BFF; padding: 6px 12px; border-radius: 50px; color: white;">
                                        <i class="fas fa-paper-plane" style="margin-right: 5px"></i>
                                        Submitted{!! $isRollback ? ' <small>[ROLLED BACK]</small>' : '' !!}
                                    </span>
                                @elseif ($baseStatus === 'Approved')
                                    <span class="badge d-inline-flex align-items-center"
                                        style="background-color: #2e7d32; padding: 6px 12px; border-radius: 50px; color: white;">
                                        <i class="fas fa-thumbs-up" style="margin-right: 5px"></i>
                                        Approved{!! $isRollback ? ' <small>[ROLLED BACK]</small>' : '' !!}
                                    </span>
                                @elseif ($baseStatus === 'Rejected')
                                    <span class="badge d-inline-flex align-items-center"
                                        style="background-color: #c62828; padding: 6px 12px; border-radius: 50px; color: white;">
                                        <i class="fas fa-ban" style="margin-right: 5px"></i>
                                        Rejected{!! $isRollback ? ' <small>[ROLLED BACK]</small>' : '' !!}
                                    </span>
                                @elseif ($baseStatus === 'Budget Allocated')
                                    <span class="badge d-inline-flex align-items-center"
                                        style="background-color: #ffc107; padding: 6px 12px; border-radius: 50px; color: black;">
                                        <i class="fas fa-money-bill-wave" style="margin-right: 5px"></i>
                                        Budget Allocated{!! $isRollback ? ' <small>[ROLLED BACK]</small>' : '' !!}
                                    </span>
                                @elseif ($baseStatus === 'DV Submitted')
                                    <span class="badge d-inline-flex align-items-center"
                                        style="background-color: #17a2b8; padding: 6px 12px; border-radius: 50px; color: white;">
                                        <i class="fas fa-file" style="margin-right: 5px"></i>
                                        DV Submitted{!! $isRollback ? ' <small>[ROLLED BACK]</small>' : '' !!}
                                    </span>
                                @elseif ($baseStatus === 'Disbursed')
                                    <span class="badge d-inline-flex align-items-center"
                                        style="background-color: #6f42c1; padding: 6px 12px; border-radius: 50px; color: white;">
                                        <i class="fas fa-money-bill-wave" style="margin-right: 5px"></i>
                                        Disbursed{!! $isRollback ? ' <small>[ROLLED BACK]</small>' : '' !!}
                                    </span>
                                @elseif ($baseStatus === 'Ready for Disbursement')
                                    <span class="badge d-inline-flex align-items-center"
                                        style="background-color: #6f42c1; padding: 6px 12px; border-radius: 50px; color: white;">
                                        <i class="fas fa-paper-plane" style="margin-right: 5px"></i>
                                        Ready for Disbursement{!! $isRollback ? ' <small>[ROLLED BACK]</small>' : '' !!}
                                    </span>
                                @elseif ($baseStatus === 'Submitted[Emergency]')
                                    <span class="badge d-inline-flex align-items-center"
                                        style="background-color: #dc3545; padding: 6px 12px; border-radius: 50px; color: white;">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Submitted [Emergency]{!! $isRollback ? ' <small>[ROLLED BACK]</small>' : '' !!}
                                    </span>
                                @elseif ($baseStatus === 'Processing')
                                    <span class="badge d-inline-flex align-items-center"
                                        style="background-color: #5f5f5f; padding: 6px 12px; border-radius: 50px; color: white;">
                                        <i class="fas fa-spinner me-2"></i>
                                        Processing{!! $isRollback ? ' <small>[ROLLED BACK]</small>' : '' !!}
                                    </span>
                                @else
                                    <span class="badge bg-secondary d-inline-flex align-items-center"
                                        style="padding: 6px 12px; border-radius: 50px;">
                                        <i class="fas fa-question-circle" style="margin-right: 5px"></i>
                                        {{ $currentStatus }}
                                    </span>
                                @endif

                            </td>
                            <td>
                                @php
                                    $statusMap = [
                                        'Submitted' => "Mayor's Office",
                                        'Submitted[Emergency]' => "Mayor's Office",
                                        'Approved' => 'Budget Office',
                                        'Rejected' => 'CSWD Office',
                                        'Budget Allocated' => 'Accounting Office',
                                        'DV Submitted' => 'Treasury Office',
                                        'Disbursed' => 'Completed',
                                        'Ready for Disbursement' => 'Treasury Office',
                                        'Processing' => 'CSWD Office',
                                        'Draft' => 'CSWD Office',
                                    ];

                                    $cleanStatus = str_replace('[ROLLED BACK]', '', $baseStatus);
                                    $department = $statusMap[$cleanStatus] ?? 'N/A';
                                @endphp
                                {{ $department }}
                            </td>

                            <td class="text-center">
                                <a href="{{ route('admin.process-tracking.show', ['process_tracking' => $patient->id]) }}"
                                    title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                            </td>
                            <td style="display:none;">{{ $priority }}</td>
                        </tr>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mass Decision Modal -->
    <div class="modal fade" id="massDecisionModal" tabindex="-1" aria-labelledby="massDecisionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="massDecisionForm">
                @csrf
                <input type="hidden" name="action" id="massDecisionAction">

                <div class="modal-content">
                    <div class="modal-header" id="massDecisionHeader">
                        <h5 class="modal-title" id="massDecisionModalLabel">Mass Decision</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Reject Reasons (shown only if action is reject) -->
                        <div id="massDecisionRejectFields" style="display:none;">
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
                                            value="{{ $reason }}" id="massReason{{ $index }}">
                                        <label class="form-check-label"
                                            for="massReason{{ $index }}">{{ $reason }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mb-3">
                                <label for="massDecisionOtherReason" class="form-label">Other Reason (Optional)</label>
                                <input type="text" name="other_reason" id="massDecisionOtherReason" class="form-control"
                                    placeholder="Specify other reason here">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="massDecisionStatusDate" class="form-label">Decision Date</label>
                            <input type="datetime-local" class="form-control" id="massDecisionStatusDate"
                                name="status_date" value="{{ now()->toDateTimeLocalString() }}" required>
                        </div>


                        <!-- Remarks -->
                        <div class="mb-3">
                            <label for="massDecisionRemarks" class="form-label">Remarks</label>
                            <textarea name="remarks" id="massDecisionRemarks" class="form-control" rows="3"
                                placeholder="Enter remarks..."></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" id="massDecisionConfirmBtn" class="btn btn-primary">Confirm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Mass Budget Allocation Modal -->
    <div class="modal fade" id="massBudgetModal" tabindex="-1" aria-labelledby="massBudgetModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="massBudgetForm">
                @csrf
                <input type="hidden" name="ids[]" id="massBudgetIds">

                <div class="modal-content border-0 shadow-lg rounded-4" style="overflow: hidden;">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="massBudgetModalLabel">
                            <i class="fas fa-wallet me-2"></i>
                            Allocate Budget to Selected Patients
                        </h5>
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="form-group mb-4">
                            <label for="massBudgetAmount" class="form-label">Amount (₱)</label>
                            <input type="number" step="0.01" name="amount" id="massBudgetAmount"
                                class="form-control form-control-lg rounded-3 shadow-sm" required>

                            <div class="d-flex flex-wrap gap-2 mt-3">
                                @foreach ([1000, 2000, 3000, 4000, 5000, 6000, 7000, 8000, 9000, 10000] as $suggested)
                                    <button type="button"
                                        class="btn btn-outline-primary btn-sm suggested-amount rounded-pill px-3"
                                        data-value="{{ $suggested }}">₱{{ number_format($suggested) }}</button>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="massBudgetStatusDate" class="form-label">Status Date</label>
                            <input type="datetime-local" name="status_date" id="massBudgetStatusDate"
                                class="form-control form-control-lg rounded-3 shadow-sm"
                                value="{{ now()->toDateTimeLocalString() }}" required>
                        </div>

                        <div class="form-group">
                            <label for="massBudgetRemarks" class="form-label">Remarks (Optional)</label>
                            <textarea name="remarks" id="massBudgetRemarks" class="form-control rounded-3 shadow-sm" rows="4"
                                placeholder="Enter any remarks here..."></textarea>
                        </div>
                    </div>

                    <div class="modal-footer d-flex flex-column gap-2 p-4 pt-0">
                        <button type="submit" class="btn btn-success w-100 rounded-pill py-2">
                            <i class="fas fa-check-circle me-1"></i> Confirm Allocation
                        </button>
                        <button type="button" class="btn btn-secondary w-100 rounded-pill py-2"
                            data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="massDVModal" tabindex="-1" role="dialog" aria-labelledby="massDVModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="massDVForm" method="POST">
                @csrf
                <div class="modal-content border-0">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="massDVModalLabel">
                            <i class="fas fa-file-invoice me-2"></i> Mass DV Input
                        </h5>
                        <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <p class="text-muted">
                            Selected patients will be automatically assigned a <b>unique DV Code</b>.
                        </p>

                        <div class="form-group mb-3">
                            <label for="massDvDate">DV Date <span class="text-danger">*</span></label>
                            <input type="date" id="massDvDate" name="dv_date" class="form-control form-control-lg"
                                required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="massDvStatusDate">Status Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" id="massDvStatusDate" name="status_date"
                                class="form-control form-control-lg" value="{{ now()->toDateTimeLocalString() }}"
                                required>
                        </div>


                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check-circle me-1"></i> Submit DV for Selected
                            </button>
                            <button type="button" class="btn btn-secondary w-100 mt-2"
                                data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
            </form>
        </div>
    </div>
    </div>

<div class="modal fade" id="massReadyForDisbursementModal" tabindex="-1"
    aria-labelledby="massReadyForDisbursementModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="massReadyForDisbursementForm" method="POST">
            @csrf
            <input type="hidden" name="ids[]" id="massReadyForDisbursementIds">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="massReadyForDisbursementModalLabel">
                        <i class="fas fa-exclamation-circle me-2"></i> Mark as Ready for Disbursement
                    </h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        This will mark selected patients as <strong>Ready for Disbursement</strong>.<br>
                        Only patients with <strong>DV Submitted</strong> or <strong>DV Submitted[ROLLED BACK]</strong>
                        status will be processed.
                    </div>

<div class="form-group mb-3">
    <label for="massReadyStatusDate">Status Date</label>
    <input type="datetime-local" name="status_date" id="massReadyStatusDate"
        class="form-control form-control-lg" 
        value="{{ now()->format('Y-m-d\TH:i') }}"
        step="60"
        required>
</div>


                    <div class="form-group mb-3">
                        <label for="massReadyRemarks">Remarks (Optional)</label>
                        <textarea name="remarks" id="massReadyRemarks" class="form-control form-control-lg" rows="3"
                            placeholder="Enter any remarks..."></textarea>
                    </div>
                </div>
                <div class="modal-footer d-flex flex-column gap-2">
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="fas fa-exclamation-circle me-1"></i> Mark as Ready
                    </button>
                    <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
    <div class="modal fade" id="massDisburseModal" tabindex="-1" aria-labelledby="massDisburseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="massDisburseForm" method="POST" action="{{ route('admin.process-tracking.massQuickDisburse') }}">
                @csrf
                <input type="hidden" name="ids[]" id="massDisburseIds">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="massDisburseModalLabel">
                            <i class="fas fa-money-bill-wave me-2"></i> Quick Disburse Selected Patients
                        </h5>
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to mark the selected patients as <strong>Disbursed</strong>?</p>
                        <div class="mb-3">
    <label for="massDisburseDate" class="form-label">Disbursement Date</label>
    <input type="datetime-local" class="form-control" id="massDisburseDate" name="status_date"
        value="{{ now()->format('Y-m-d\TH:i') }}"
        step="60"
        required>
</div>
                        <div class="mb-3">
                            <label for="massDisburseRemarks" class="form-label">Remarks (Optional)</label>
                            <textarea class="form-control" id="massDisburseRemarks" name="remarks" rows="3"
                                placeholder="Enter any remarks..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer d-flex flex-column gap-2">
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-check-circle me-1"></i> Confirm Disbursement
                        </button>
                        <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        'use strict';
        
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
                        console.log('📢 Patient status changed:', e);
                        updatePatientTable(e);
                    });
            } else {
                console.error('Echo is not defined');
            }
        }

        function updatePatientTable(e) {
            const table = jQuery('.datatable-ProcessTracking').DataTable();
            const badge = generateBadge(e.status);
            const department = getDepartment(e.status);
            const rowSelector = `tr[data-entry-id="${e.id}"]`;
            const existingRow = jQuery(rowSelector);

            if (e.action === 'submitted' && existingRow.length === 0) {
                // Add new row
                const newRow = table.row.add([
                    '',
                    e.control_number,
                    formatDate(e.date_processed),
                    e.claimant_name,
                    e.case_worker,
                    badge,
                    department,
                    generateActionLink(e.id),
                ]).draw(false).node();

                jQuery(newRow).attr('data-entry-id', e.id);
                jQuery(newRow).addClass('table-success');
                setTimeout(() => jQuery(newRow).removeClass('table-success'), 3000);

                console.log('✅ Added new row for patient:', e.claimant_name);
            } else if (existingRow.length > 0) {
                // Update status badge
                existingRow.find('td').eq(5).html(badge);
                // Update department responsible
                existingRow.find('td').eq(6).html(department);

                console.log('✅ Updated row for patient:', e.claimant_name);
            }
        }

        // Your existing helper functions remain the same...
        function getDepartment(status) {
            const cleanStatus = status.replace('[ROLLED BACK]', '').trim();
            const statusMap = {
                'Processing': 'CSWD Office',
                'Submitted': "Mayor's Office",
                'Submitted[Emergency]': "Mayor's Office",
                'Approved': 'Budget Office',
                'Rejected': 'CSWD Office',
                'Budget Allocated': 'Accounting Office',
                'DV Submitted': 'Treasury Office',
                'Disbursed': 'Completed',
                'Ready for Disbursement': 'Treasury Office',
            };
            return statusMap[cleanStatus] || 'N/A';
        }

        function formatDate(input) {
            const date = new Date(input);
            return date.toLocaleString('en-PH', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
        }

        function generateActionLink(id) {
            return `<a href="/admin/process-tracking/${id}" title="View"><i class="fas fa-eye"></i></a>`;
        }

        function generateBadge(status) {
            const icons = {
                'Processing': 'fa-spinner',
                'Submitted': 'fa-paper-plane',
                'Submitted[Emergency]': 'fa-exclamation-triangle',
                'Approved': 'fa-thumbs-up',
                'Rejected': 'fa-ban',
                'Budget Allocated': 'fa-money-bill-wave',
                'DV Submitted': 'fa-file',
                'Disbursed': 'fa-money-bill-wave',
                'Ready for Disbursement': 'fa-paper-plane',
            };

            const colors = {
                'Submitted': '#007BFF',
                'Submitted[Emergency]': '#dc3545',
                'Approved': '#2e7d32',
                'Rejected': '#c62828',
                'Budget Allocated': '#ffc107',
                'DV Submitted': '#17a2b8',
                'Disbursed': '#6f42c1',
                'Ready for Disbursement': '#6f42c1',
            };

            const isRollback = status.includes('[ROLLED BACK]');
            const baseStatus = status.replace('[ROLLED BACK]', '').trim();

            const icon = icons[baseStatus] || 'fa-question-circle';
            const color = colors[baseStatus] || '#6c757d';

            const textColor = baseStatus === 'Budget Allocated' ? 'black' : 'white';

            return `<span class="badge d-inline-flex align-items-center"
        style="background-color: ${color}; padding: 6px 12px; border-radius: 50px; color: ${textColor};">
        <i class="fas ${icon} me-2"></i> ${baseStatus}${isRollback ? ' <small>[ROLLED BACK]</small>' : ''}
    </span>`;
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            initializeRealTimeUpdates();
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

        jQuery(function() {
            let dtButtons = jQuery.extend(true, [], jQuery.fn.dataTable.defaults.buttons);
            let _token = jQuery('meta[name="csrf-token"]').attr('content');

            jQuery.extend(true, jQuery.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 100,
            });

            let table = jQuery('.datatable-ProcessTracking:not(.ajaxTable)').DataTable({
                buttons: dtButtons,
                order: [
                    [8, 'asc'],
                    [1, 'desc']
                ],
            });

            jQuery('a[data-toggle="tab"]').on('shown.bs.tab click', function() {
                jQuery(jQuery.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });

            @can('approve_patient')
                let selectedIds = [];

                dtButtons.push({
                    text: 'Approve Selected',
                    className: 'btn-success',
                    action: function(e, dt, node, config) {
                        selectedIds = jQuery.map(dt.rows({
                            selected: true
                        }).nodes(), function(entry) {
                            return jQuery(entry).data('entry-id');
                        });

                        if (selectedIds.length === 0) {
                            alert('No records selected');
                            return;
                        }

                        jQuery('#massDecisionAction').val('approve');
                        jQuery('#massDecisionRemarks').val('');
                        jQuery('#massDecisionModal').modal('show');
                    }
                });

                dtButtons.push({
                    text: 'Reject Selected',
                    className: 'btn-danger',
                    action: function(e, dt, node, config) {
                        selectedIds = jQuery.map(dt.rows({
                            selected: true
                        }).nodes(), function(entry) {
                            return jQuery(entry).data('entry-id');
                        });

                        if (selectedIds.length === 0) {
                            alert('No records selected');
                            return;
                        }

                        jQuery('#massDecisionAction').val('reject');
                        jQuery('#massDecisionRemarks').val('');
                        jQuery('#massDecisionModal').modal('show');
                    }
                });

                jQuery('#massDecisionForm').on('submit', function(e) {
                    e.preventDefault();

                    // Get the confirm button
                    const confirmBtn = jQuery('#massDecisionConfirmBtn');
                    const originalBtnText = confirmBtn.html();
                    const action = jQuery('#massDecisionAction').val();

                    // Set loading state - disabled with spinner
                    confirmBtn.prop('disabled', true).html(
                        '<i class="fas fa-spinner fa-spin me-1"></i> Processing...'
                    );

                    // Also disable cancel button during submission
                    jQuery('#massDecisionModal').find('button[data-bs-dismiss="modal"]').prop('disabled', true);

                    let form = jQuery('<form>', {
                            method: 'POST',
                            action: "{{ route('admin.process-tracking.massDecision') }}"
                        })
                        .append(jQuery('<input>', {
                            type: 'hidden',
                            name: '_token',
                            value: _token
                        }))
                        .append(jQuery('<input>', {
                            type: 'hidden',
                            name: 'action',
                            value: action
                        }))
                        .append(jQuery('<input>', {
                            type: 'hidden',
                            name: 'remarks',
                            value: jQuery('#massDecisionRemarks').val()
                        }))
                        .append(jQuery('<input>', {
                            type: 'hidden',
                            name: 'status_date',
                            value: jQuery('#massDecisionStatusDate').val()
                        }));

                    // Append selected IDs
                    selectedIds.forEach(function(id) {
                        form.append(jQuery('<input>', {
                            type: 'hidden',
                            name: 'ids[]',
                            value: id
                        }));
                    });

                    // Handle reject reasons
                    if (action === 'reject') {
                        let reasons = [];

                        jQuery('input[name="reasons[]"]:checked').each(function() {
                            reasons.push(jQuery(this).val());
                        });

                        let otherReason = jQuery('#massDecisionOtherReason').val().trim();
                        if (otherReason) {
                            reasons.push(otherReason);
                        }

                        reasons.forEach(function(reason) {
                            form.append(jQuery('<input>', {
                                type: 'hidden',
                                name: 'reasons[]',
                                value: reason
                            }));
                        });
                    }

                    form.appendTo('body').submit();
                });

                // Reset button when modal hides
                jQuery('#massDecisionModal').on('hidden.bs.modal', function() {
                    const confirmBtn = jQuery('#massDecisionConfirmBtn');
                    const action = jQuery('#massDecisionAction').val();

                    // Reset to original text based on action
                    confirmBtn.prop('disabled', false);
                    confirmBtn.html(action === 'approve' ? 'Confirm Approve' : 'Confirm Reject');

                    // Re-enable cancel button
                    jQuery(this).find('button[data-bs-dismiss="modal"]').prop('disabled', false);
                });
            @endcan
            
            @can('accounting_dv_input')
                let selectedDvIds = [];

                dtButtons.push({
                    text: 'Mass DV Input',
                    className: 'btn-primary',
                    action: function(e, dt, node, config) {
                        selectedDvIds = jQuery.map(dt.rows({
                            selected: true
                        }).nodes(), function(entry) {
                            return jQuery(entry).data('entry-id');
                        });

                        if (!selectedDvIds.length) {
                            alert('No records selected');
                            return;
                        }

                        // Reset modal fields
                        jQuery('#massDvDate').val('');

                        // Show modal
                        jQuery('#massDVModal').modal('show');
                    }
                });

                jQuery('#massDVForm').on('submit', function(e) {
                    e.preventDefault();

                    let form = jQuery('<form>', {
                            method: 'POST',
                            action: "{{ route('admin.process-tracking.massDVInput') }}"
                        })
                        .append(jQuery('<input>', {
                            type: 'hidden',
                            name: '_token',
                            value: _token
                        }))
                        .append(jQuery('<input>', {
                            type: 'hidden',
                            name: 'dv_date',
                            value: jQuery('#massDvDate').val()
                        }))
                        .append(jQuery('<input>', {
                            type: 'hidden',
                            name: 'status_date',
                            value: jQuery('#massDvStatusDate').val()
                        }));

                    // Add each selected patient ID
                    selectedDvIds.forEach(function(id) {
                        form.append(jQuery('<input>', {
                            type: 'hidden',
                            name: 'ids[]',
                            value: id
                        }));
                    });

                    form.appendTo('body').submit();
                });
            @endcan

            @can('budget_allocate')
                let selectedBudgetIds = [];

                dtButtons.push({
                    text: 'Allocate Budget',
                    className: 'btn-success',
                    action: function(e, dt, node, config) {
                        selectedBudgetIds = jQuery.map(dt.rows({
                            selected: true
                        }).nodes(), function(entry) {
                            return jQuery(entry).data('entry-id');
                        });

                        if (!selectedBudgetIds.length) {
                            alert('No records selected');
                            return;
                        }

                        // Clear inputs
                        jQuery('#massBudgetAmount').val('');
                        jQuery('#massBudgetRemarks').val('');

                        // Show modal
                        jQuery('#massBudgetModal').modal('show');
                    }
                });

                jQuery('#massBudgetForm').on('submit', function(e) {
                    e.preventDefault();

                    let form = jQuery('<form>', {
                            method: 'POST',
                            action: "{{ route('admin.process-tracking.massBudgetAllocate') }}"
                        })
                        .append(jQuery('<input>', {
                            type: 'hidden',
                            name: '_token',
                            value: _token
                        }))
                        .append(jQuery('<input>', {
                            type: 'hidden',
                            name: 'amount',
                            value: jQuery('#massBudgetAmount').val()
                        }))
                        .append(jQuery('<input>', {
                            type: 'hidden',
                            name: 'remarks',
                            value: jQuery('#massBudgetRemarks').val()
                        }))
                        .append(jQuery('<input>', {
                            type: 'hidden',
                            name: 'status_date',
                            value: jQuery('#massBudgetStatusDate').val()
                        }));

                    selectedBudgetIds.forEach(function(id) {
                        form.append(jQuery('<input>', {
                            type: 'hidden',
                            name: 'ids[]',
                            value: id
                        }));
                    });

                    form.appendTo('body').submit();
                });
            @endcan

            @can('treasury_disburse')
                let selectedReadyForDisbursementIds = [];
                let selectedDisburseIds = [];

                function getManilaDateTime() {
                    const now = new Date();
                    // Manila is UTC+8
                    const manilaTime = new Date(now.getTime() + (8 * 60 * 60 * 1000));
                    
                    const year = manilaTime.getUTCFullYear();
                    const month = String(manilaTime.getUTCMonth() + 1).padStart(2, '0');
                    const day = String(manilaTime.getUTCDate()).padStart(2, '0');
                    const hours = String(manilaTime.getUTCHours()).padStart(2, '0');
                    const minutes = String(manilaTime.getUTCMinutes()).padStart(2, '0');
                    
                    return `${year}-${month}-${day}T${hours}:${minutes}`;
                }

                dtButtons.push({
                    text: 'Disburse',
                    className: 'btn-danger',
                    action: function(e, dt, node, config) {
                        selectedDisburseIds = jQuery.map(dt.rows({
                            selected: true
                        }).nodes(), function(entry) {
                            return jQuery(entry).data('entry-id');
                        });

                        if (!selectedDisburseIds.length) {
                            alert('No records selected');
                            return;
                        }

                        // Reset modal fields with Manila time
                        jQuery('#massDisburseDate').val(getManilaDateTime());
                        jQuery('#massDisburseRemarks').val('');

                        // Show modal
                        jQuery('#massDisburseModal').modal('show');
                    }
                });

                dtButtons.push({
                    text: 'Ready for Disbursement',
                    className: 'btn-warning',
                    action: function(e, dt, node, config) {
                        selectedReadyForDisbursementIds = jQuery.map(dt.rows({
                            selected: true
                        }).nodes(), function(entry) {
                            return jQuery(entry).data('entry-id');
                        });

                        if (!selectedReadyForDisbursementIds.length) {
                            alert('No records selected');
                            return;
                        }

                        // Reset modal fields with Manila time
                        jQuery('#massReadyStatusDate').val(getManilaDateTime());
                        jQuery('#massReadyRemarks').val('');

                        // Show modal
                        jQuery('#massReadyForDisbursementModal').modal('show');
                    }
                });
                
                // Mass Ready for Disbursement form submission
                jQuery('#massReadyForDisbursementForm').on('submit', function(e) {
                    e.preventDefault();

                    // Get the submit button
                    const submitBtn = jQuery(this).find('button[type="submit"]');

                    // Set loading state - disabled with spinner
                    submitBtn.prop('disabled', true).html(
                        '<i class="fas fa-spinner fa-spin me-1"></i> Processing...'
                    );

                    // Also disable cancel button during submission
                    jQuery('#massReadyForDisbursementModal').find('button[data-bs-dismiss="modal"]').prop('disabled', true);

                    let form = jQuery('<form>', {
                            method: 'POST',
                            action: "{{ route('admin.process-tracking.massReadyForDisbursement') }}"
                        })
                        .append(jQuery('<input>', {
                            type: 'hidden',
                            name: '_token',
                            value: _token
                        }))
                        .append(jQuery('<input>', {
                            type: 'hidden',
                            name: 'status_date',
                            value: jQuery('#massReadyStatusDate').val()
                        }))
                        .append(jQuery('<input>', {
                            type: 'hidden',
                            name: 'remarks',
                            value: jQuery('#massReadyRemarks').val()
                        }));

                    // Add each selected patient ID
                    selectedReadyForDisbursementIds.forEach(function(id) {
                        form.append(jQuery('<input>', {
                            type: 'hidden',
                            name: 'ids[]',
                            value: id
                        }));
                    });

                    form.appendTo('body').submit();
                });

                // Mass Disburse form submission
                jQuery('#massDisburseForm').on('submit', function(e) {
                    e.preventDefault();

                    // Get the submit button
                    const submitBtn = jQuery(this).find('button[type="submit"]');

                    // Set loading state - disabled with spinner
                    submitBtn.prop('disabled', true).html(
                        '<i class="fas fa-spinner fa-spin me-1"></i> Processing...'
                    );

                    // Also disable cancel button during submission
                    jQuery('#massDisburseModal').find('button[data-bs-dismiss="modal"]').prop('disabled', true);

                    let form = jQuery('<form>', {
                            method: 'POST',
                            action: "{{ route('admin.process-tracking.massQuickDisburse') }}"
                        })
                        .append(jQuery('<input>', {
                            type: 'hidden',
                            name: '_token',
                            value: _token
                        }))
                        .append(jQuery('<input>', {
                            type: 'hidden',
                            name: 'status_date',
                            value: jQuery('#massDisburseDate').val()
                        }))
                        .append(jQuery('<input>', {
                            type: 'hidden',
                            name: 'remarks',
                            value: jQuery('#massDisburseRemarks').val()
                        }));

                    // Add each selected patient ID
                    selectedDisburseIds.forEach(function(id) {
                        form.append(jQuery('<input>', {
                            type: 'hidden',
                            name: 'ids[]',
                            value: id
                        }));
                    });

                    form.appendTo('body').submit();
                });
            @endcan

            // Change modal header & toggle reject reasons based on action
            jQuery('#massDecisionModal').on('show.bs.modal', function() {
                let action = jQuery('#massDecisionAction').val();
                let header = jQuery('#massDecisionHeader');
                let confirmBtn = jQuery('#massDecisionConfirmBtn');

                if (action === 'approve') {
                    header.removeClass('bg-danger text-white').addClass('bg-success text-white');
                    jQuery('#massDecisionModalLabel').text('Approve Selected Applications');
                    confirmBtn.removeClass('btn-danger').addClass('btn-success').text('Confirm Approve');
                    jQuery('#massDecisionRejectFields').hide();
                } else {
                    header.removeClass('bg-success text-white').addClass('bg-danger text-white');
                    jQuery('#massDecisionModalLabel').text('Reject Selected Applications');
                    confirmBtn.removeClass('btn-success').addClass('btn-danger').text('Confirm Reject');
                    jQuery('#massDecisionRejectFields').show();
                }
            });
            
            jQuery(document).on('click', '.suggested-amount', function() {
                let value = jQuery(this).data('value');
                jQuery('#massBudgetAmount').val(value);
            });

        });
    </script>
@endsection
