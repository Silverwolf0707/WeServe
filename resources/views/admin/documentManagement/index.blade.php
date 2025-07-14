@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Documents List
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-Document">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>Control Number</th>
                        <th>Date Processed</th>
                        <th>Patient Name</th>
                        <th>Claimant Name</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patients as $patient)
                    <tr data-entry-id="{{ $patient->id }}">
                        <td></td>
                        <td>{{ $patient->control_number ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($patient->date_processed)->format('F j, Y') }}</td>
                        <td>{{ $patient->patient_name ?? 'N/A' }}</td>
                        <td>{{ $patient->claimant_name ?? 'N/A' }}</td>
                        <td>
                            <a class="btn btn-sm btn-primary" href="{{ route('admin.document-management.show', $patient->id) }}">
                                View
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
    text: 'Delete selected',
    url: "{{ route('admin.document-management.massDestroy') }}",  // ✅ Correct route name!
    className: 'btn-danger',
    action: function (e, dt, node, config) {
        let ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
            return $(entry).data('entry-id');
        });

        if (ids.length === 0) {
            alert('No documents selected');
            return;
        }

        if (confirm('Are you sure you want to delete selected documents?')) {
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

        $('.datatable-Document:not(.ajaxTable)').DataTable({
            buttons: dtButtons,
            select: true,
            order: [[1, 'desc']],
            pageLength: 25,
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab click', function () {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    });
</script>
@endsection
