@extends('layouts.admin')

@section('content')
    <div class="card shadow-sm border-0">
        <!-- Modernized Header -->
        <div class="card-header custom-header d-flex align-items-center">
            <h4 class="mb-0 fw-bold d-flex align-items-center text-white">
                <i class="fas fa-users me-2"></i> {{ trans('cruds.patientRecord.title') }}
            </h4>

            <div class="header-actions d-flex align-items-center ms-auto">
                @can('patient_record_create')
                    <a class="btn btn-add me-2" href="{{ route('admin.patient-records.create') }}">
                        <i class="fas fa-plus me-1"></i>
                        {{ trans('global.add') }} {{ trans('cruds.patientRecord.title_singular') }}
                    </a>
                    <button class="btn btn-import" data-bs-toggle="modal" data-bs-target="#csvImportModal">
                        <i class="fas fa-file-csv me-1"></i> {{ trans('global.app_csvImport') }}
                    </button>
                @endcan
            </div>
        </div>
    </div>

    @can('patient_record_create')
        @include('csvImport.modal', [
            'model' => 'PatientRecord',
            'route' => 'admin.patient-records.parseCsvImport',
        ])
    @endcan

    <div class="card-body">
        <div class="mb-3">
            <label for="statusFilter" class="form-label fw-bold">Filter Status:</label>
            <select id="statusFilter" class="form-select w-auto d-inline-block">
                <option value="">All</option>
                <option value="Submitted">Submitted</option>
                <option value="Processing">Processing</option>
            </select>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-PatientRecord">
                <thead class="thead-dark">
                    <tr>
                        <th width="10"></th>
                        <th>{{ trans('cruds.patientRecord.fields.date_processed') }}</th>
                        <th>{{ trans('cruds.patientRecord.fields.case_type') }}</th>
                        <th>{{ trans('cruds.patientRecord.fields.control_number') }}</th>
                        <th>{{ trans('cruds.patientRecord.fields.claimant_name') }}</th>
                        <th>{{ trans('cruds.patientRecord.fields.case_category') }}</th>
                        <th>{{ trans('cruds.patientRecord.fields.patient_name') }}</th>
                        <th>{{ trans('cruds.patientRecord.fields.diagnosis') }}</th>
                        <th>{{ trans('cruds.patientRecord.fields.age') }}</th>
                        <th>{{ trans('cruds.patientRecord.fields.address') }}</th>
                        <th>{{ trans('cruds.patientRecord.fields.contact_number') }}</th>
                        <th>{{ trans('cruds.patientRecord.fields.case_worker') }}</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($patientRecords as $patientRecord)
                        @php
                            $latestStatus = $patientRecord->latestStatusLog; // Make sure you eager load this in the controller
                            $statusValue = $latestStatus->status ?? 'Processing';
                        @endphp
                        <tr data-entry-id="{{ $patientRecord->id }}" data-status="{{ $statusValue }}">
                            <td></td>
                            <td>
                                <span data-order="{{ \Carbon\Carbon::parse($patientRecord->date_processed)->timestamp }}">
                                    {{ \Carbon\Carbon::parse($patientRecord->date_processed)->format('F j, Y g:i A') }}
                                </span>
                            </td>

                            <td>{{ $patientRecord->case_type ?? '' }}</td>
                            <td>{{ $patientRecord->control_number ?? '' }}</td>
                            <td>{{ $patientRecord->claimant_name ?? '' }}</td>
                            <td>{{ $patientRecord->case_category ?? '' }}
                            </td>
                            <td>{{ $patientRecord->patient_name ?? '' }}</td>
                            <td class="text-truncate" style="max-width: 200px">{{ $patientRecord->diagnosis ?? '' }}</td>
                            <td>{{ $patientRecord->age ?? '' }}</td>
                            <td>{{ $patientRecord->address ?? '' }}</td>
                            <td>{{ $patientRecord->contact_number ?? '' }}</td>
                            <td>{{ $patientRecord->case_worker ?? '' }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @can('patient_record_show')
                                        <a href="{{ route('admin.patient-records.show', $patientRecord->id) }}" class="mr-3"
                                            title="View"><i class="fas fa-eye"></i></a>
                                    @endcan
                                    @can('patient_record_edit')
                                        <a href="{{ route('admin.patient-records.edit', $patientRecord->id) }}" class="mr-3"
                                            title="Edit"><i class="fas fa-edit"></i></a>
                                    @endcan
                                    @can('patient_record_delete')
                                        <form action="{{ route('admin.patient-records.destroy', $patientRecord->id) }}"
                                            method="POST" class="m-0 p-0 delete-form" style="display: inline;">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn p-0 border-0 bg-transparent mr-3 delete-button"
                                                title="Delete"><i class="fas fa-trash-alt text-danger"></i></button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div>

    {{-- Mass Submit Modal --}}
    <div class="modal fade" id="massSubmitModal" tabindex="-1" role="dialog" aria-labelledby="massSubmitModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="massSubmitForm">
                @csrf
                <input type="hidden" name="ids" id="massSubmitIds">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Submit Selected Patients</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Submitted Date:</p>
                        <input type="datetime-local" class="form-control mb-3" name="submitted_date" id="massSubmitDate"
                            value="{{ now()->toDateTimeLocalString() }}">
                        <p>Remarks (optional):</p>
                        <textarea class="form-control" name="remarks" id="massSubmitRemarks" rows="3" placeholder="Enter remarks..."></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit Now</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function initializeRealTimeUpdates() {
            console.log('📡 Initializing real-time updates for patient records...');
            
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

            // Listen for patient record changes
            if (window.Echo) {
                window.Echo.channel('patients')
                    .listen('.patientRecord.changed', function(e) {
                        console.log('📢 Patient Record added:', e);
                        updatePatientTable(e);
                    });
            } else {
                console.error('Echo is not defined');
            }
        }

        function updatePatientTable(e) {
            const table = $('.datatable-PatientRecord').DataTable();
            const rowData = [
                '', 
                `<span data-order="${new Date(e.date_processed).getTime()}">${safeValue(formatDate(e.date_processed))}</span>`,
                safeValue(e.case_type),
                safeValue(e.control_number),
                safeValue(e.claimant_name),
                safeValue(caseCategoryLabel(e.case_category)),
                safeValue(e.patient_name),
                `<span class="text-truncate" style="max-width:200px;">${safeValue(e.diagnosis)}</span>`,
                safeValue(e.age),
                safeValue(e.address),
                safeValue(e.contact_number),
                safeValue(e.case_worker),
                generateActions(e.id)
            ];

            const expectedCols = table.columns().count();
            while (rowData.length < expectedCols) rowData.unshift('');
            while (rowData.length > expectedCols) rowData.pop();

            console.log('Final rowData count:', rowData.length, 'Expected:', expectedCols);

            const newRow = table.row.add(rowData).draw(false).node();

            table.order([1, 'desc']).draw();

            $(newRow).attr('data-entry-id', e.id);
            $(newRow).attr('data-status', e.latest_status || 'Processing');
            $(newRow).addClass('table-success');
            setTimeout(() => $(newRow).removeClass('table-success'), 3000);

            console.log('✅ Added new row for patient:', e.patient_name);
        }

        function safeValue(v) {
            if (v === null || v === undefined) return '';
            if (typeof v === 'object') return JSON.stringify(v);
            return String(v);
        }

        function formatDate(input) {
            if (!input) return '';

            // Handle "YYYY-MM-DD HH:mm:ss" manually
            if (/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/.test(input)) {
                const [datePart, timePart] = input.split(' ');
                const [y, m, d] = datePart.split('-').map(Number);
                const [hh, mm, ss] = timePart.split(':').map(Number);
                const date = new Date(y, m - 1, d, hh, mm, ss);

                return date.toLocaleString('en-PH', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                });
            }

            const parsed = new Date(input);
            if (isNaN(parsed)) return input;

            return parsed.toLocaleString('en-PH', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
        }

        function caseCategoryLabel(code) {
            const categoryMap = @json(App\Models\PatientRecord::CASE_CATEGORY_SELECT);
            return categoryMap && typeof categoryMap === 'object' ?
                categoryMap[code] ?? code :
                code;
        }

        function generateActions(id) {
            return `
                <div class="d-flex align-items-center">
                    <a href="/admin/patient-records/${id}" class="mr-3" title="View"><i class="fas fa-eye"></i></a>
                    <a href="/admin/patient-records/${id}/edit" class="mr-3" title="Edit"><i class="fas fa-edit"></i></a>
                    <form method="POST" action="/admin/patient-records/${id}" class="m-0 p-0 delete-form" style="display:inline;">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="${_token}">
                        <button type="submit" class="btn p-0 border-0 bg-transparent mr-3 delete-button" title="Delete">
                            <i class="fas fa-trash-alt text-danger"></i>
                        </button>
                    </form>
                </div>`;
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

        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

            let table = $('.datatable-PatientRecord:not(.ajaxTable)').DataTable({
                buttons: dtButtons,
                order: [
                    [1, 'desc']
                ],
                pageLength: 100,
                orderCellsTop: true,
                columnDefs: [{
                    targets: 0,
                    orderable: false,
                    searchable: false,
                    className: 'select-checkbox'
                }],
                select: {
                    style: 'multi',
                    selector: 'td:first-child'
                }
            });

            // Custom filter for status
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                let selectedStatus = $('#statusFilter').val(); // value from dropdown
                let rowStatus = $(table.row(dataIndex).node()).data('status'); // read data-status

                // If nothing selected, show all
                if (!selectedStatus) return true;

                return rowStatus === selectedStatus;
            });

            // Trigger filter when dropdown changes
            $('#statusFilter').on('change', function() {
                table.draw();
            });

            @can('patient_record_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
                let deleteButton = {
                    text: deleteButtonTrans,
                    className: 'btn-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).nodes(), function(entry) {
                            return $(entry).data('entry-id');
                        });

                        if (ids.length === 0) {
                            alert('{{ trans('global.datatables.zero_selected') }}');
                            return;
                        }

                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You are about to delete selected records. This action cannot be undone.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, delete them!',
                            cancelButtonText: 'Cancel',
                            customClass: {
                                confirmButton: 'btn btn-danger',
                                cancelButton: 'btn btn-secondary'
                            },
                            buttonsStyling: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Submit via standard form to allow redirect
                                let form = $('<form>', {
                                        method: 'POST',
                                        action: "{{ route('admin.patient-records.massDestroy') }}"
                                    })
                                    .append($('<input>', {
                                        type: 'hidden',
                                        name: '_token',
                                        value: _token
                                    }))
                                    .append($('<input>', {
                                        type: 'hidden',
                                        name: '_method',
                                        value: 'DELETE'
                                    }));

                                ids.forEach(function(id) {
                                    form.append($('<input>', {
                                        type: 'hidden',
                                        name: 'ids[]',
                                        value: id
                                    }));
                                });

                                form.appendTo('body').submit();
                            }
                        });
                    }
                };

                dtButtons.push(deleteButton);
            @endcan

            @can('submit_patient_application')
                let selectedIds = [];

                dtButtons.push({
                    text: 'Submit Selected',
                    className: 'btn-primary',
                    action: function(e, dt, node, config) {
                        selectedIds = $.map(dt.rows({
                            selected: true
                        }).nodes(), function(entry) {
                            return $(entry).data('entry-id');
                        });

                        if (selectedIds.length === 0) {
                            alert('No records selected');
                            return;
                        }

                        $('#massSubmitRemarks').val('');
                        $('#massSubmitModal').modal('show');
                    }
                });

                $('#massSubmitForm').on('submit', function(e) {
                    e.preventDefault();

                    let form = $('<form>', {
                            method: 'POST',
                            action: "{{ route('admin.patient-records.massSubmit') }}"
                        })
                        .append($('<input>', {
                            type: 'hidden',
                            name: '_token',
                            value: _token
                        }))
                        .append($('<input>', {
                            type: 'hidden',
                            name: 'remarks',
                            value: $('#massSubmitRemarks').val()
                        }))
                        .append($('<input>', {
                            type: 'hidden',
                            name: 'submitted_date',
                            value: $('#massSubmitDate').val()
                        }));

                    // Add each selected patient ID
                    selectedIds.forEach(function(id) {
                        form.append($('<input>', {
                            type: 'hidden',
                            name: 'ids[]',
                            value: id
                        }));
                    });

                    form.appendTo('body').submit();
                });
            @endcan

            $('a[data-toggle="tab"]').on('shown.bs.tab click', function() {
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        });
    </script>
@endsection