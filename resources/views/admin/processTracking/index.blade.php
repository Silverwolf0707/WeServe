@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('Process Tracking') }}
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
                                    $statusColor = match ($currentStatus) {
                                        'Submitted' => '#0000FF',
                                        'Approved' => '#90EE90',
                                        'Rejected' => '#FF0000',
                                        default => '#D3D3D3',
                                    };
                                @endphp
                                <span class="badge" style="background-color: {{ $statusColor }}; padding: 5px 10px; border-radius: 20px; color: white;">
                                    {{ $currentStatus }}
                                </span>
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
