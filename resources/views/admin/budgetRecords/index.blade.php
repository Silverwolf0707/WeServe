@extends('layouts.admin')

@section('content')

<div class="card shadow-sm border-0">
    <!-- Modernized Header -->
    <div class="card-header custom-header d-flex align-items-center bg-primary text-white"
        style="min-height: 80px; padding: 1.5rem;">
        <h4 class="mb-0 fw-bold d-flex align-items-center">
            <i class="fas fa-wallet me-2"></i> Budget Records
        </h4>
        <div class="header-actions d-flex align-items-center ms-auto">
        </div>
    </div>
</div>

<div class="card-body">
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover datatable datatable-Budget">
            <thead class="thead-dark">
                <tr>
                    <th width="10"></th>
                    <th>Patient Name</th>
                    <th>Category</th>
                    <th>Case Type</th>
                    <th>Budget Allocated</th>
                    <th>Remarks</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($budgetAllocations as $record)
                    <tr data-entry-id="{{ $record->id }}">
                        <td></td>
                        <td>{{ $record->patient->patient_name ?? 'N/A' }}</td>
                        <td>{{ $record->patient->case_category ?? 'N/A' }}</td>
                        <td>{{ $record->patient->case_type ?? 'N/A' }}</td>
                        <td class="fw-bold text-success">₱{{ number_format($record->amount, 2) }}</td>
                        <td>{{ $record->remarks ?? '-' }}</td>
                        <td>{{ $record->budget_status ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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

            let table = $('.datatable-Budget:not(.ajaxTable)').DataTable({ buttons: dtButtons });

            $('a[data-toggle="tab"]').on('shown.bs.tab click', function () {
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        });
    </script>
@endsection
