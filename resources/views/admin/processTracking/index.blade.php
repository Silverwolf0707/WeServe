@extends('layouts.admin')
@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-2 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1"><i class="fas fa-tasks me-2"></i> Process Tracking</h5>
                <small class="text-white">Monitor current progress and track department submissions</small>
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
    </div>
@endsection

@section('scripts')
    @parent
    <script>
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


        });
    </script>
@endsection