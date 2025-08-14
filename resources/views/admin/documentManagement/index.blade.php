@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header bg-primary text-white font-weight-bold">
        <i class="fas fa-file-alt me-2"></i> {{ __('Documents List') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-Document">
                <thead class="thead-dark">
                    <tr>
                        <th width="10"></th>
                        <th>{{ __('Control Number') }}</th>
                        <th>{{ __('Date Processed') }}</th>
                        <th>{{ __('Patient Name') }}</th>
                        <th>{{ __('Claimant Name') }}</th>
                        <th class="text-center" width="50">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patients as $patient)
                        <tr data-entry-id="{{ $patient->id }}">
                            <td></td>
                            <td>{{ $patient->control_number ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($patient->date_processed)->format('F j, Y g:i A') }}</td>
                            <td>{{ $patient->patient_name ?? 'N/A' }}</td>
                            <td>{{ $patient->claimant_name ?? 'N/A' }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.document-management.show', $patient->id) }}" title="View">
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

        @can('documents_management')
        let deleteButton = {
            text: @json(trans('global.datatables.delete')),
            url: @json(route('admin.document-management.massDestroy')),
            className: 'btn-danger',
            action: function (e, dt, node, config) {
                let ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                    return $(entry).data('entry-id');
                });

                if (ids.length === 0) {
                    alert(@json(trans('global.datatables.zero_selected')));
                    return;
                }

                if (confirm(@json(trans('global.areYouSure')))) {
                    $.ajax({
                        headers: {
                            'x-csrf-token': _token
                        },
                        method: 'POST',
                        url: config.url,
                        data: {
                            ids: ids,
                            _method: 'DELETE'
                        }
                    }).done(function () {
                        location.reload();
                    });
                }
            }
        };
        dtButtons.push(deleteButton);
        @endcan

        $.extend(true, $.fn.dataTable.defaults, {
            orderCellsTop: true,
            order: [[1, 'desc']],
            pageLength: 100,
        });

        let table = $('.datatable-Document:not(.ajaxTable)').DataTable({ buttons: dtButtons });

        $('a[data-toggle="tab"]').on('shown.bs.tab click', function () {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    });
</script>

<style>
    /* Enhance card header */
    .card-header.bg-success {
        background-color: #28a745 !important; /* Bootstrap green */
        color: #fff !important;
        font-weight: bold;
        font-size: 1.1rem;
        text-shadow: 0 0 3px rgba(255, 255, 255, 0.6);
        display: flex;
        align-items: center;
    }
    .card-header i {
        font-size: 1.2rem;
    }
</style>
@endsection