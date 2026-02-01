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
             <!-- Search Form -->
            <form method="GET" action="{{ route('admin.budget-records.index') }}" class="d-flex">
                <div class="input-group">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Search budget records..." 
                           value="{{ request('search') }}"
                           aria-label="Search"
                           autocomplete="off">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.budget-records.index') }}" 
                           class="btn btn-outline-secondary" 
                           title="Clear search">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </form>
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
                    <th>Allocation Date</th> {{-- Optional: Add date column --}}
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
                        <td>
                            @php
                                $statusClass = match($record->budget_status) {
                                    'Disbursed' => 'badge bg-success',
                                    'Ready for Disbursement' => 'badge bg-warning text-dark',
                                    'Not Disbursed' => 'badge bg-info',
                                    default => 'badge bg-secondary'
                                };
                            @endphp
                            <span class="{{ $statusClass }}">{{ $record->budget_status ?? 'N/A' }}</span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($record->allocation_date)->format('M d, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Add pagination links --}}
    @if($budgetAllocations->hasPages())
        <div class="centered-pagination mb-4">
            <div>
                {{ $budgetAllocations->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
    @parent
    <script>
        $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);

            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [[7, 'desc']], // Sort by allocation date (column 7)
                pageLength: 100,
            });

            let table = $('.datatable-Budget:not(.ajaxTable)').DataTable({
                buttons: dtButtons,
                paging: false, // Disable DataTables pagination
                info: false,   // Disable DataTables info
                searching: false,
                processing: true,
                serverSide: false,
                columnDefs: [
                    {
                        targets: 0,
                        orderable: false,
                        searchable: false,
                        className: 'select-checkbox'
                    },
                    {
                        targets: 4, // Amount column
                        render: function(data, type, row) {
                            if (type === 'sort' || type === 'type') {
                                return data.replace(/[₱,]/g, '');
                            }
                            return data;
                        }
                    }
                ]
            });

            $('a[data-toggle="tab"]').on('shown.bs.tab click', function () {
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        });
    </script>
@endsection