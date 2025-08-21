@extends('layouts.admin')
@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-2 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1"><i class="fas fa-tasks me-2"></i> Process Tracking</h5>
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
                            <th class="text-center" width="50">{{ __('Actions') }}</th>
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
                                    @else
                                        <span class="badge bg-secondary d-inline-flex align-items-center"
                                            style="padding: 6px 12px; border-radius: 50px;">
                                            <i class="fas fa-question-circle" style="margin-right: 5px"></i>
                                            {{ $currentStatus }}
                                        </span>
                                    @endif

                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.process-tracking.show', ['process_tracking' => $patient->id]) }}"
                                        title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
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
                                            <label class="form-check-label" for="massReason{{ $index }}">{{ $reason }}</label>
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
                                <input type="datetime-local" class="form-control" id="massDecisionStatusDate" name="status_date"
                                    value="{{ now()->toDateTimeLocalString() }}" required>
                            </div>


                            <!-- Remarks -->
                            <div class="mb-3">
                                <label for="massDecisionRemarks" class="form-label">Remarks</label>
                                <textarea name="remarks" id="massDecisionRemarks" class="form-control" rows="3"
                                    placeholder="Enter remarks..." required></textarea>
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
                                <textarea name="remarks" id="massBudgetRemarks" class="form-control rounded-3 shadow-sm"
                                    rows="4" placeholder="Enter any remarks here..."></textarea>
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
                                    class="form-control form-control-lg" value="{{ now()->toDateTimeLocalString() }}" required>
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
    <div class="modal fade" id="massDisburseModal" tabindex="-1" aria-labelledby="massDisburseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="massDisburseForm" method="POST" action="{{ route('admin.process-tracking.massQuickDisburse') }}">
            @csrf
            <input type="hidden" name="ids[]" id="massDisburseIds">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="massDisburseModalLabel">
                        <i class="fas fa-money-bill-wave me-2"></i> Quick Disburse Selected Patients
                    </h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to mark the selected patients as <strong>Disbursed</strong>?</p>
                    <div class="mb-3">
                        <label for="massDisburseDate" class="form-label">Disbursement Date</label>
                        <input type="date" class="form-control" id="massDisburseDate" name="status_date" value="{{ now()->toDateString() }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="massDisburseRemarks" class="form-label">Remarks (Optional)</label>
                        <textarea class="form-control" id="massDisburseRemarks" name="remarks" rows="3" placeholder="Enter any remarks..."></textarea>
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

        $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);

            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 100,
            });

            let table = $('.datatable-ProcessTracking:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            });

            $('a[data-toggle="tab"]').on('shown.bs.tab click', function () {
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });

            @can('approve_patient')
                let selectedIds = [];

                dtButtons.push({
                    text: 'Approve Selected',
                    className: 'btn-success',
                    action: function (e, dt, node, config) {
                        selectedIds = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                            return $(entry).data('entry-id');
                        });

                        if (selectedIds.length === 0) {
                            alert('No records selected');
                            return;
                        }

                        $('#massDecisionAction').val('approve');
                        $('#massDecisionRemarks').val('');
                        $('#massDecisionModal').modal('show');
                    }
                });

                dtButtons.push({
                    text: 'Reject Selected',
                    className: 'btn-danger',
                    action: function (e, dt, node, config) {
                        selectedIds = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                            return $(entry).data('entry-id');
                        });

                        if (selectedIds.length === 0) {
                            alert('No records selected');
                            return;
                        }

                        $('#massDecisionAction').val('reject');
                        $('#massDecisionRemarks').val('');
                        $('#massDecisionModal').modal('show');
                    }
                });

                $('#massDecisionForm').on('submit', function (e) {
                    e.preventDefault();

                    let form = $('<form>', {
                        method: 'POST',
                        action: "{{ route('admin.process-tracking.massDecision') }}"
                    })
                        .append($('<input>', { type: 'hidden', name: '_token', value: _token }))
                        .append($('<input>', { type: 'hidden', name: 'action', value: $('#massDecisionAction').val() }))
                        .append($('<input>', { type: 'hidden', name: 'remarks', value: $('#massDecisionRemarks').val() }))
                        .append($('<input>', { type: 'hidden', name: 'status_date', value: $('#massDecisionStatusDate').val() }));

                    // Append selected IDs
                    selectedIds.forEach(function (id) {
                        form.append($('<input>', { type: 'hidden', name: 'ids[]', value: id }));
                    });

                    // Handle reject reasons
                    if ($('#massDecisionAction').val() === 'reject') {
                        let reasons = [];

                        $('input[name="reasons[]"]:checked').each(function () {
                            reasons.push($(this).val());
                        });

                        let otherReason = $('#massDecisionOtherReason').val().trim();
                        if (otherReason) {
                            reasons.push(otherReason);
                        }

                        reasons.forEach(function (reason) {
                            form.append($('<input>', { type: 'hidden', name: 'reasons[]', value: reason }));
                        });
                    }

                    form.appendTo('body').submit();
                });


            @endcan
                @can('accounting_dv_input')
                    let selectedDvIds = [];

                    dtButtons.push({
                        text: 'Mass DV Input',
                        className: 'btn-primary',
                        action: function (e, dt, node, config) {
                            selectedDvIds = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                                return $(entry).data('entry-id');
                            });

                            if (!selectedDvIds.length) {
                                alert('No records selected');
                                return;
                            }

                            // Reset modal fields
                            $('#massDvDate').val('');

                            // Show modal
                            $('#massDVModal').modal('show');
                        }
                    });

                    $('#massDVForm').on('submit', function (e) {
                        e.preventDefault();

                        let form = $('<form>', {
                            method: 'POST',
                            action: "{{ route('admin.process-tracking.massDVInput') }}"
                        })
                            .append($('<input>', { type: 'hidden', name: '_token', value: _token }))
                            .append($('<input>', { type: 'hidden', name: 'dv_date', value: $('#massDvDate').val() }))
                            .append($('<input>', { type: 'hidden', name: 'status_date', value: $('#massDvStatusDate').val() }));

                        // Add each selected patient ID
                        selectedDvIds.forEach(function (id) {
                            form.append($('<input>', { type: 'hidden', name: 'ids[]', value: id }));
                        });

                        form.appendTo('body').submit();
                    });
                @endcan
                
               @can('treasury_disburse')
let selectedDisburseIds = [];

dtButtons.push({
    text: 'Quick Disburse',
    className: 'btn-danger',
    action: function(e, dt, node, config) {
        selectedDisburseIds = $.map(dt.rows({ selected: true }).nodes(), function(entry) {
            return $(entry).data('entry-id');
        });

        if (!selectedDisburseIds.length) {
            alert('No records selected');
            return;
        }

        // Reset modal fields
        $('#massDisburseDate').val('{{ now()->toDateString() }}');
        $('#massDisburseRemarks').val('');

        // Show modal
        $('#massDisburseModal').modal('show');
    }
});

$('#massDisburseForm').on('submit', function(e) {
    e.preventDefault();

    let form = $('<form>', {
        method: 'POST',
        action: "{{ route('admin.process-tracking.massQuickDisburse') }}"
    })
        .append($('<input>', { type: 'hidden', name: '_token', value: _token }))
        .append($('<input>', { type: 'hidden', name: 'status_date', value: $('#massDisburseDate').val() }))
        .append($('<input>', { type: 'hidden', name: 'remarks', value: $('#massDisburseRemarks').val() }));

    // Add each selected patient ID
    selectedDisburseIds.forEach(function(id) {
        form.append($('<input>', { type: 'hidden', name: 'ids[]', value: id }));
    });

    form.appendTo('body').submit();
});
@endcan


            setTimeout(() => {
                Echo.channel('process-tracking')
                    .listen('.patient.status.changed', function (e) {
                        const table = $('.datatable-ProcessTracking').DataTable();

                        const badge = generateBadge(e.status);

                        const rowSelector = `tr[data-entry-id="${e.id}"]`;
                        const existingRow = $(rowSelector);

                        if (e.action === 'submitted' && existingRow.length === 0) {
                            // Only add a row if the action is 'submitted' and no existing row
                            const newRow = table.row.add([
                                '',
                                e.control_number,
                                formatDate(e.date_processed),
                                e.claimant_name,
                                e.case_worker,
                                badge,
                                generateActionLink(e.id),
                            ]).draw(false).node();

                            $(newRow).attr('data-entry-id', e.id);
                            $(newRow).addClass('table-success');
                            setTimeout(() => $(newRow).removeClass('table-success'), 3000);
                        } else if (existingRow.length > 0) {
                            // For updates or rollbacks: update the existing row’s status badge
                            existingRow.find('td').eq(5).html(badge);
                        }
                    });

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
                        'Submitted': 'fa-paper-plane',
                        'Approved': 'fa-thumbs-up',
                        'Rejected': 'fa-ban',
                        'Budget Allocated': 'fa-money-bill-wave',
                        'DV Submitted': 'fa-file',
                        'Disbursed': 'fa-money-bill-wave',
                        'Ready for Disbursement': 'fa-paper-plane',
                    };

                    const colors = {
                        'Submitted': '#007BFF',
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

            }, 300);

            @can('budget_allocate')
                let selectedBudgetIds = [];

                dtButtons.push({
                    text: 'Allocate Budget',
                    className: 'btn-success',
                    action: function (e, dt, node, config) {
                        selectedBudgetIds = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                            return $(entry).data('entry-id');
                        });

                        if (!selectedBudgetIds.length) {
                            alert('No records selected');
                            return;
                        }

                        // Clear inputs
                        $('#massBudgetAmount').val('');
                        $('#massBudgetRemarks').val('');

                        // Show modal
                        $('#massBudgetModal').modal('show');
                    }
                });

                $('#massBudgetForm').on('submit', function (e) {
                    e.preventDefault();

                    let form = $('<form>', {
                        method: 'POST',
                        action: "{{ route('admin.process-tracking.massBudgetAllocate') }}"
                    })
                        .append($('<input>', { type: 'hidden', name: '_token', value: _token }))
                        .append($('<input>', { type: 'hidden', name: 'amount', value: $('#massBudgetAmount').val() }))
                        .append($('<input>', { type: 'hidden', name: 'remarks', value: $('#massBudgetRemarks').val() }))
                        .append($('<input>', { type: 'hidden', name: 'status_date', value: $('#massBudgetStatusDate').val() }));

                    selectedBudgetIds.forEach(function (id) {
                        form.append($('<input>', { type: 'hidden', name: 'ids[]', value: id }));
                    });

                    form.appendTo('body').submit();
                });

            @endcan


            // Change modal header & toggle reject reasons based on action
            $('#massDecisionModal').on('show.bs.modal', function () {
                let action = $('#massDecisionAction').val();
                let header = $('#massDecisionHeader');
                let confirmBtn = $('#massDecisionConfirmBtn');

                if (action === 'approve') {
                    header.removeClass('bg-danger text-white').addClass('bg-success text-white');
                    $('#massDecisionModalLabel').text('Approve Selected Applications');
                    confirmBtn.removeClass('btn-danger').addClass('btn-success').text('Confirm Approve');
                    $('#massDecisionRejectFields').hide();
                } else {
                    header.removeClass('bg-success text-white').addClass('bg-danger text-white');
                    $('#massDecisionModalLabel').text('Reject Selected Applications');
                    confirmBtn.removeClass('btn-success').addClass('btn-danger').text('Confirm Reject');
                    $('#massDecisionRejectFields').show();
                }
            });
            $(document).on('click', '.suggested-amount', function () {
                let value = $(this).data('value');
                $('#massBudgetAmount').val(value);
            });


        });
    </script>
@endsection