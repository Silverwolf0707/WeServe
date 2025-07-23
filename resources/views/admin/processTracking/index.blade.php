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
                                @endphp

                                @if ($currentStatus === 'Submitted')
                                    <span class="badge d-inline-flex align-items-center" style="background-color: #007BFF; padding: 6px 12px; border-radius: 50px; color: white;">
                                        <i class="fas fa-paper-plane me-2"></i> Submitted
                                    </span>
                                @elseif ($currentStatus === 'Approved')
                                    <span class="badge d-inline-flex align-items-center" style="background-color: #2e7d32; padding: 6px 12px; border-radius: 50px; color: white;">
                                        <i class="fas fa-thumbs-up me-2"></i> Approved
                                    </span>
                                @elseif ($currentStatus === 'Rejected')
                                    <span class="badge d-inline-flex align-items-center" style="background-color: #c62828; padding: 6px 12px; border-radius: 50px; color: white;">
                                        <i class="fas fa-ban me-2"></i> Rejected
                                    </span>
                                @elseif ($currentStatus === 'For Review')
                                    <span class="badge d-inline-flex align-items-center" style="background-color: #ffc107; padding: 6px 12px; border-radius: 50px; color: black;">
                                        <i class="fas fa-search me-2"></i> For Review
                                    </span>
                                @elseif ($currentStatus === 'In Progress')
                                    <span class="badge d-inline-flex align-items-center" style="background-color: #17a2b8; padding: 6px 12px; border-radius: 50px; color: white;">
                                        <i class="fas fa-spinner me-2"></i> In Progress
                                    </span>
                                @elseif ($currentStatus === 'Completed')
                                    <span class="badge d-inline-flex align-items-center" style="background-color: #6f42c1; padding: 6px 12px; border-radius: 50px; color: white;">
                                        <i class="fas fa-check-double me-2"></i> Completed
                                    </span>
                                @else
                                    <span class="badge bg-secondary d-inline-flex align-items-center" style="padding: 6px 12px; border-radius: 50px;">
                                        <i class="fas fa-question-circle me-2"></i> {{ $currentStatus }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.process-tracking.show', ['process_tracking' => $patient->id]) }}" title="View">
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
            order: [[1, 'desc']],
            pageLength: 100,
        });

        let table = $('.datatable-ProcessTracking:not(.ajaxTable)').DataTable({ buttons: dtButtons });

        $('a[data-toggle="tab"]').on('shown.bs.tab click', function () {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    });
</script>
@endsection
