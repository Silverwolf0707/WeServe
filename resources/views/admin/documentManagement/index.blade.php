@extends('layouts.admin')
@section('content')

    <div class="card shadow-sm border-0">
        <!-- Modernized Header -->
        <div class="card-header custom-header d-flex align-items-center bg-primary text-white"
            style="min-height: 80px; padding: 1.5rem;">
            <h4 class="mb-0 fw-bold d-flex align-items-center">
                <i class="fas fa-file-alt me-2"></i> {{ __('Documents List') }}
            </h4>
            <div class="header-actions d-flex align-items-center ms-auto">
                  <form method="GET" action="{{ route('admin.document-management.index') }}" class="float-end">
                    <div class="input-group">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Search documents..." 
                               value="{{ request('search') }}"
                               aria-label="Search documents">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin.document-management.index') }}" 
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

        {{-- Add pagination links --}}
        @if($patients->hasPages())
            <div class="centered-pagination mb-4">
                <div>
                    {{ $patients->links('pagination::bootstrap-5') }}
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
        let _token = $('meta[name="csrf-token"]').attr('content');

        @can('documents_management')
        let deleteButton = {
            text: 'Delete Selected',
            className: 'btn-danger',
            action: function (e, dt, node, config) {
                let ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                    return $(entry).data('entry-id');
                });

                if (ids.length === 0) {
                    alert('No records selected');
                    return;
                }

                if (confirm('Are you sure you want to delete the selected documents?')) {
                    $.ajax({
                        headers: {
                            'x-csrf-token': _token
                        },
                        method: 'POST',
                        url: "{{ route('admin.document-management.massDestroy') }}",
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

        let table = $('.datatable-Document:not(.ajaxTable)').DataTable({
            buttons: dtButtons,
            paging: false, 
            info: false,   
            searching: false,
            processing: true,
            serverSide: false
        });

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