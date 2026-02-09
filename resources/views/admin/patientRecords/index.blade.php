@extends('layouts.admin')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-header custom-header d-flex align-items-center">
            <h4 class="mb-0 fw-bold d-flex align-items-center text-white">
                <i class="fas fa-users me-2"></i> {{ trans('cruds.patientRecord.title') }}
            </h4>

            <div class="header-actions d-flex align-items-center ms-auto">
                <div class="form-check form-switch me-3">
                    <input class="form-check-input" type="checkbox" id="showDeletedToggle" {{ $showDeleted ? 'checked' : '' }}>
                    <label class="form-check-label text-white" for="showDeletedToggle">
                        <i class="fas fa-trash me-1"></i> Show Deleted
                    </label>
                </div>

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

{{-- Replace the filter section --}}
@if (!$showDeleted)
    <form method="GET" action="{{ route('admin.patient-records.index') }}" class="d-inline">
        <label for="statusFilter" class="form-label fw-bold me-2">Filter Status:</label>
        <select id="statusFilter" name="status_filter" class="form-select w-auto d-inline-block" onchange="this.form.submit()">
            <option value="">All</option>
            <option value="Submitted" {{ request('status_filter') == 'Submitted' ? 'selected' : '' }}>Submitted</option>
            <option value="Processing" {{ request('status_filter') == 'Processing' ? 'selected' : '' }}>Processing</option>
        </select>
        @if(request('show_deleted'))
            <input type="hidden" name="show_deleted" value="1">
        @endif
    </form>
@endif
    <form method="GET" action="{{ route('admin.patient-records.index') }}" class="float-end">
        <div class="input-group" style="width: 300px;">
            <input type="text" 
                   name="search" 
                   class="form-control" 
                   placeholder="Search records..." 
                   value="{{ request('search') }}"
                   aria-label="Search">
            @if(request('status_filter'))
                <input type="hidden" name="status_filter" value="{{ request('status_filter') }}">
            @endif
            @if(request('show_deleted'))
                <input type="hidden" name="show_deleted" value="1">
            @endif
            <button class="btn btn-outline-primary" type="submit">
                <i class="fas fa-search"></i>
            </button>
            @if(request('search'))
                <a href="{{ route('admin.patient-records.index', array_filter([
                    'status_filter' => request('status_filter'),
                    'show_deleted' => request('show_deleted')
                ])) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </div>
    </form>


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
                        @if ($showDeleted)
                            <th>Deleted At</th>
                        @endif
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($patientRecords as $patientRecord)
                        <tr data-entry-id="{{ $patientRecord->id }}" data-status="{{ $patientRecord->clean_status }}"
                            data-filter-category="{{ $patientRecord->filter_category }}"
                            @if ($showDeleted) class="table-danger" @endif>
                            <td></td>
                            <td data-sort="{{ \Carbon\Carbon::parse($patientRecord->date_processed)->timestamp }}">
                                <span>
                                    {{ \Carbon\Carbon::parse($patientRecord->date_processed)->format('F j, Y g:i A') }}
                                </span>
                            </td>
                            <td>{{ $patientRecord->case_type ?? '' }}</td>
                            <td>{{ $patientRecord->control_number ?? '' }}</td>
                            <td>{{ $patientRecord->claimant_name ?? '' }}</td>
                            <td>{{ $patientRecord->case_category ?? '' }}</td>
                            <td>{{ $patientRecord->patient_name ?? '' }}</td>
                            <td class="text-truncate" style="max-width: 200px">{{ $patientRecord->diagnosis ?? '' }}</td>
                            <td>{{ $patientRecord->age ?? '' }}</td>
                            <td>{{ $patientRecord->address ?? '' }}</td>
                            <td>{{ $patientRecord->contact_number ?? '' }}</td>
                            <td>{{ $patientRecord->case_worker ?? '' }}</td>
                            @if ($showDeleted)
                                <td data-sort="{{ \Carbon\Carbon::parse($patientRecord->deleted_at)->timestamp ?? '' }}">
                                    @if ($patientRecord->deleted_at)
                                        {{ \Carbon\Carbon::parse($patientRecord->deleted_at)->format('F j, Y g:i A') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                            @endif
                            <td>
                                <div class="d-flex align-items-center">
                                    @if ($showDeleted)
                                        {{-- Show restore and force delete for deleted records --}}
                                        <a href="#" class="mr-3 restore-single-btn"
                                            data-id="{{ $patientRecord->id }}"
                                            data-control-number="{{ $patientRecord->control_number }}" title="Restore">
                                            <i class="fas fa-undo text-success"></i>
                                        </a>
                                        <a href="#" class="mr-3 force-delete-single-btn"
                                            data-id="{{ $patientRecord->id }}"
                                            data-control-number="{{ $patientRecord->control_number }}"
                                            title="Delete Permanently">
                                            <i class="fas fa-trash-alt text-danger"></i>
                                        </a>
                                    @else
                                        {{-- Show normal actions for active records --}}
                                        @can('patient_record_show')
                                            <a href="{{ route('admin.patient-records.show', $patientRecord->id) }}"
                                                class="mr-3" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endcan
                                        @can('patient_record_edit')
                                            <a href="{{ route('admin.patient-records.edit', $patientRecord->id) }}"
                                                class="mr-3" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                        @can('patient_record_delete')
                                            <a href="#" class="mr-3 delete-single-btn" data-id="{{ $patientRecord->id }}"
                                                data-control-number="{{ $patientRecord->control_number }}" title="Delete">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </a>
                                        @endcan
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
@if($patientRecords->hasPages())
    <div class="centered-pagination mb-4">

        <div>
            {{ $patientRecords->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif
    </div>


    {{-- Single Delete Confirmation Modal --}}
    <div class="modal fade" id="singleDeleteModal" tabindex="-1" aria-labelledby="singleDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="singleDeleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this patient record?</p>
                    <p class="text-muted small">This action can be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="singleDeleteForm" method="POST" style="display: inline;">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-danger" id="confirmSingleDeleteBtn">
                            <i class="fas fa-trash-alt me-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Mass Delete Confirmation Modal --}}
    <div class="modal fade" id="massDeleteModal" tabindex="-1" aria-labelledby="massDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="massDeleteModalLabel">Confirm Mass Delete</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the selected patient records?</p>
                    <p class="text-muted small">This action cannot be undone.</p>
                    <div id="selectedRecordsCount" class="alert alert-warning mt-2"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmMassDeleteBtn">
                        <i class="fas fa-trash-alt me-1"></i> Delete Selected
                    </button>
                </div>
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

    {{-- Add new modals for restore and force delete (only shown when in deleted mode) --}}
    @if ($showDeleted)
        {{-- Restore Confirmation Modal --}}
        <div class="modal fade" id="restoreModal" tabindex="-1" aria-labelledby="restoreModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="restoreModalLabel">Confirm Restore</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="restoreMessage">Are you sure you want to restore this patient record?</p>
                        <p class="text-muted small">This will make the record active again.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form id="restoreForm" method="POST" style="display: inline;">
                            @method('PUT')
                            @csrf
                            <button type="submit" class="btn btn-success" id="confirmRestoreBtn">
                                <i class="fas fa-undo me-1"></i> Restore
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Force Delete Confirmation Modal --}}
        <div class="modal fade" id="forceDeleteModal" tabindex="-1" aria-labelledby="forceDeleteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="forceDeleteModalLabel">Confirm Permanent Delete</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="forceDeleteMessage">Are you sure you want to permanently delete this patient record?</p>
                        <p class="text-muted small">
                            <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                            This action cannot be undone. All related data will be permanently deleted.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form id="forceDeleteForm" method="POST" style="display: inline;">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-danger" id="confirmForceDeleteBtn">
                                <i class="fas fa-trash-alt me-1"></i> Delete Permanently
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mass Restore Confirmation Modal --}}
        <div class="modal fade" id="massRestoreModal" tabindex="-1" aria-labelledby="massRestoreModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="massRestoreModalLabel">Confirm Mass Restore</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to restore the selected patient records?</p>
                        <p class="text-muted small">This will make all selected records active again.</p>
                        <div id="selectedRecordsForRestore" class="alert alert-info mt-2"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success" id="confirmMassRestoreBtn">
                            <i class="fas fa-undo me-1"></i> Restore Selected
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mass Force Delete Confirmation Modal --}}
        <div class="modal fade" id="massForceDeleteModal" tabindex="-1" aria-labelledby="massForceDeleteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="massForceDeleteModalLabel">Confirm Mass Permanent Delete</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to permanently delete the selected patient records?</p>
                        <p class="text-muted small">
                            <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                            This action cannot be undone. All related data will be permanently deleted.
                        </p>
                        <div id="selectedRecordsForForceDelete" class="alert alert-warning mt-2"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmMassForceDeleteBtn">
                            <i class="fas fa-trash-alt me-1"></i> Delete Permanently
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    @parent
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
            const table = jQuery('.datatable-PatientRecord').DataTable();
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

            jQuery(newRow).attr('data-entry-id', e.id);
            jQuery(newRow).attr('data-status', e.latest_status || 'Processing');
            jQuery(newRow).addClass('table-success');
            setTimeout(() => jQuery(newRow).removeClass('table-success'), 3000);

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
            const showDeleted = @json($showDeleted ?? false);

            if (showDeleted) {
                return `
                    <div class="d-flex align-items-center">
                        <a href="#" class="mr-3 restore-single-btn" data-id="${id}" title="Restore">
                            <i class="fas fa-undo text-success"></i>
                        </a>
                        <a href="#" class="mr-3 force-delete-single-btn" data-id="${id}" title="Delete Permanently">
                            <i class="fas fa-trash-alt text-danger"></i>
                        </a>
                    </div>`;
            } else {
                return `
                    <div class="d-flex align-items-center">
                        <a href="/admin/patient-records/${id}" class="mr-3" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="/admin/patient-records/${id}/edit" class="mr-3" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="#" class="mr-3 delete-single-btn" data-id="${id}" title="Delete">
                            <i class="fas fa-trash-alt text-danger"></i>
                        </a>
                    </div>`;
            }
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

            // Toggle show deleted records
            const toggleSwitch = document.getElementById('showDeletedToggle');
            if (toggleSwitch) {
                toggleSwitch.addEventListener('change', function() {
                    const showDeleted = this.checked;
                    const url = new URL(window.location.href);

                    if (showDeleted) {
                        url.searchParams.set('show_deleted', '1');
                    } else {
                        url.searchParams.delete('show_deleted');
                    }

                    window.location.href = url.toString();
                });
            }
        });

        jQuery(function() {
            let dtButtons = jQuery.extend(true, [], jQuery.fn.dataTable.defaults.buttons)
            let _token = jQuery('meta[name="csrf-token"]').attr('content');

            @if ($showDeleted)
                dtButtons.push({
                    text: '<i class="fas fa-undo me-1"></i> Restore Selected',
                    className: 'btn-success',
                    action: function(e, dt, node, config) {
                        var ids = jQuery.map(dt.rows({
                            selected: true
                        }).nodes(), function(entry) {
                            return jQuery(entry).data('entry-id');
                        });

                        if (ids.length === 0) {
                            alert('Please select at least one record to restore');
                            return;
                        }

                        jQuery('#selectedRecordsForRestore').text(
                            `You have selected ${ids.length} record(s) for restoration.`);

                        const restoreModal = document.getElementById('massRestoreModal');
                        restoreModal.dataset.selectedIds = JSON.stringify(ids);

                        const modal = new bootstrap.Modal(restoreModal);
                        modal.show();
                    }
                });

                dtButtons.push({
                    text: '<i class="fas fa-trash me-1"></i> Delete Permanently',
                    className: 'btn-danger',
                    action: function(e, dt, node, config) {
                        var ids = jQuery.map(dt.rows({
                            selected: true
                        }).nodes(), function(entry) {
                            return jQuery(entry).data('entry-id');
                        });

                        if (ids.length === 0) {
                            alert('Please select at least one record to delete permanently');
                            return;
                        }

                        jQuery('#selectedRecordsForForceDelete').text(
                            `You have selected ${ids.length} record(s) for permanent deletion.`);

                        const forceDeleteModal = document.getElementById('massForceDeleteModal');
                        forceDeleteModal.dataset.selectedIds = JSON.stringify(ids);

                        const modal = new bootstrap.Modal(forceDeleteModal);
                        modal.show();
                    }
                });
            @else
                @can('submit_patient_application')
                    let selectedIds = [];

                    dtButtons.push({
                        text: 'Submit Selected',
                        className: 'btn-primary',
                        action: function(e, dt, node, config) {
                            selectedIds = jQuery.map(dt.rows({
                                selected: true
                            }).nodes(), function(entry) {
                                return jQuery(entry).data('entry-id');
                            });

                            if (selectedIds.length === 0) {
                                alert('No records selected');
                                return;
                            }

                            jQuery('#massSubmitRemarks').val('');
                            jQuery('#massSubmitModal').modal('show');
                        }
                    });

                    jQuery('#massSubmitForm').on('submit', function(e) {
                        e.preventDefault();

                        // Get the submit button
                        const submitBtn = jQuery(this).find('button[type="submit"]');
                        const originalBtnText = submitBtn.html();

                        // Set loading state
                        submitBtn.prop('disabled', true).html(
                            '<i class="fas fa-spinner fa-spin me-1"></i> Submitting...'
                        );

                        // Also disable cancel button during submission
                        jQuery(this).find('button[data-bs-dismiss="modal"]').prop('disabled', true);

                        let form = jQuery('<form>', {
                                method: 'POST',
                                action: "{{ route('admin.patient-records.massSubmit') }}"
                            })
                            .append(jQuery('<input>', {
                                type: 'hidden',
                                name: '_token',
                                value: _token
                            }))
                            .append(jQuery('<input>', {
                                type: 'hidden',
                                name: 'remarks',
                                value: jQuery('#massSubmitRemarks').val()
                            }))
                            .append(jQuery('<input>', {
                                type: 'hidden',
                                name: 'submitted_date',
                                value: jQuery('#massSubmitDate').val()
                            }));

                        selectedIds.forEach(function(id) {
                            form.append(jQuery('<input>', {
                                type: 'hidden',
                                name: 'ids[]',
                                value: id
                            }));
                        });

                        form.appendTo('body').submit();
                    });

                 
                    jQuery('#massSubmitModal').on('hidden.bs.modal', function() {
                        const submitBtn = jQuery('#massSubmitForm').find('button[type="submit"]');
                        submitBtn.prop('disabled', false).html('Submit Now');

                        jQuery(this).find('button[data-bs-dismiss="modal"]').prop('disabled', false);
                    });
                @endcan
             
                @can('patient_record_delete')
                    let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
                    let deleteButton = {
                        text: deleteButtonTrans,
                        className: 'btn-danger',
                        action: function(e, dt, node, config) {
                            var ids = jQuery.map(dt.rows({
                                selected: true
                            }).nodes(), function(entry) {
                                return jQuery(entry).data('entry-id');
                            });

                            if (ids.length === 0) {
                                alert('{{ trans('global.datatables.zero_selected') }}');
                                return;
                            }

                            // Update modal with selected count
                            jQuery('#selectedRecordsCount').text(
                                `You have selected ${ids.length} record(s) for deletion.`);

                            // Store IDs for later use
                            const deleteModal = document.getElementById('massDeleteModal');
                            deleteModal.dataset.selectedIds = JSON.stringify(ids);

                            // Show modal
                            const modal = new bootstrap.Modal(deleteModal);
                            modal.show();
                        }
                    };

                    dtButtons.push(deleteButton);
                @endcan
            @endif

            let table = jQuery('.datatable-PatientRecord:not(.ajaxTable)').DataTable({
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
        },
        {
            targets: 1, // date_processed column
            type: 'num' // Use numeric sorting
        }
    ],
    select: {
        style: 'multi',
        selector: 'td:first-child'
    },
   
    paging: false,
    info: false,
    searching: false, 
    processing: true,
    serverSide: false 
});

            // Custom filter for status
//           jQuery.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
//     let selectedFilter = jQuery('#statusFilter').val();
//     if (!selectedFilter) return true;
    
//     let row = table.row(dataIndex).node();
//     let filterCategory = jQuery(row).data('filter-category');
    
//     return filterCategory === selectedFilter;
// });

//             // Trigger filter when dropdown changes
//             jQuery('#statusFilter').on('change', function() {
//                 table.draw();
//             });
// Replace the filter change listener with form submission
jQuery('#statusFilter').on('change', function() {
    // Get current URL
    const url = new URL(window.location);
    
    // Update the status_filter parameter
    if (this.value) {
        url.searchParams.set('status_filter', this.value);
    } else {
        url.searchParams.delete('status_filter');
    }
    
    // Reload the page with the new filter
    window.location.href = url.toString();
});

            // Handle single delete button clicks (only for active records)
            @if (!$showDeleted)
                jQuery(document).on('click', '.delete-single-btn', function(e) {
                    e.preventDefault();
                    const recordId = jQuery(this).data('id');
                    const controlNumber = jQuery(this).data('control-number');

                    // Set up the delete form
                    const deleteForm = jQuery('#singleDeleteForm');
                    deleteForm.attr('action', `/admin/patient-records/${recordId}`);

                    // Update modal text
                    jQuery('#singleDeleteModal .modal-body p:first').text(
                        `Are you sure you want to delete the patient record with Control Number: ${controlNumber}?`
                    );

                    // Show modal
                    const deleteModal = new bootstrap.Modal(document.getElementById('singleDeleteModal'));
                    deleteModal.show();
                });

                // Handle single delete confirmation
                jQuery('#confirmSingleDeleteBtn').on('click', function() {
                    const btn = jQuery(this);
                    const form = jQuery('#singleDeleteForm');

                    // Disable button and show loading
                    btn.prop('disabled', true).html(
                        '<i class="fas fa-spinner fa-spin me-1"></i> Deleting...');

                    // Submit the form
                    form.submit();
                });

                // Reset single delete button when modal hides
                jQuery('#singleDeleteModal').on('hidden.bs.modal', function() {
                    jQuery('#confirmSingleDeleteBtn').prop('disabled', false).html(
                        '<i class="fas fa-trash-alt me-1"></i> Delete');
                });
            @endif

            // Handle mass delete confirmation (only for active records)
            @if (!$showDeleted)
                jQuery('#confirmMassDeleteBtn').on('click', function() {
                    const btn = jQuery(this);
                    const modal = document.getElementById('massDeleteModal');
                    const ids = JSON.parse(modal.dataset.selectedIds || '[]');

                    if (ids.length === 0) {
                        alert('No records selected');
                        return;
                    }

                    // Disable button and show loading
                    btn.prop('disabled', true).html(
                        '<i class="fas fa-spinner fa-spin me-1"></i> Deleting...');

                    // Submit via standard form
                    let form = jQuery('<form>', {
                            method: 'POST',
                            action: "{{ route('admin.patient-records.massDestroy') }}"
                        })
                        .append(jQuery('<input>', {
                            type: 'hidden',
                            name: '_token',
                            value: _token
                        }))
                        .append(jQuery('<input>', {
                            type: 'hidden',
                            name: '_method',
                            value: 'DELETE'
                        }));

                    ids.forEach(function(id) {
                        form.append(jQuery('<input>', {
                            type: 'hidden',
                            name: 'ids[]',
                            value: id
                        }));
                    });

                    form.appendTo('body').submit();
                });

                // Reset mass delete button when modal hides
                jQuery('#massDeleteModal').on('hidden.bs.modal', function() {
                    jQuery('#confirmMassDeleteBtn').prop('disabled', false).html(
                        '<i class="fas fa-trash-alt me-1"></i> Delete Selected');
                });
            @endif

            // Handle single restore button clicks (only when showing deleted)
            @if ($showDeleted)
                jQuery(document).on('click', '.restore-single-btn', function(e) {
                    e.preventDefault();
                    const recordId = jQuery(this).data('id');
                    const controlNumber = jQuery(this).data('control-number');

                    const restoreForm = jQuery('#restoreForm');
                    restoreForm.attr('action', `/admin/patient-records/${recordId}/restore`);

                    jQuery('#restoreMessage').text(
                        `Are you sure you want to restore the patient record with Control Number: ${controlNumber}?`
                    );

                    const restoreModal = new bootstrap.Modal(document.getElementById('restoreModal'));
                    restoreModal.show();
                });

                // Handle single force delete button clicks (only when showing deleted)
                jQuery(document).on('click', '.force-delete-single-btn', function(e) {
                    e.preventDefault();
                    const recordId = jQuery(this).data('id');
                    const controlNumber = jQuery(this).data('control-number');

                    const forceDeleteForm = jQuery('#forceDeleteForm');
                    forceDeleteForm.attr('action', `/admin/patient-records/${recordId}/force-delete`);

                    jQuery('#forceDeleteMessage').text(
                        `Are you sure you want to permanently delete the patient record with Control Number: ${controlNumber}?`
                    );

                    const forceDeleteModal = new bootstrap.Modal(document.getElementById(
                        'forceDeleteModal'));
                    forceDeleteModal.show();
                });

                // Handle mass restore confirmation
                jQuery('#confirmMassRestoreBtn').on('click', function() {
                    const btn = jQuery(this);
                    const modal = document.getElementById('massRestoreModal');
                    const ids = JSON.parse(modal.dataset.selectedIds || '[]');

                    if (ids.length === 0) {
                        alert('No records selected');
                        return;
                    }

                    btn.prop('disabled', true).html(
                        '<i class="fas fa-spinner fa-spin me-1"></i> Restoring...');

                    let form = jQuery('<form>', {
                            method: 'POST',
                            action: "{{ route('admin.patient-records.massRestore') }}"
                        })
                        .append(jQuery('<input>', {
                            type: 'hidden',
                            name: '_token',
                            value: _token
                        }))


                    ids.forEach(function(id) {
                        form.append(jQuery('<input>', {
                            type: 'hidden',
                            name: 'ids[]',
                            value: id
                        }));
                    });

                    form.appendTo('body').submit();
                });

                // Handle mass force delete confirmation
                jQuery('#confirmMassForceDeleteBtn').on('click', function() {
                    alert('Mass force delete is currently under development.');
                    //     const btn = jQuery(this);
                    //     const modal = document.getElementById('massForceDeleteModal');
                    //     const ids = JSON.parse(modal.dataset.selectedIds || '[]');

                    //     if (ids.length === 0) {
                    //         alert('No records selected');
                    //         return;
                    //     }

                    //     btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Deleting...');

                    //     let form = jQuery('<form>', {
                    //             method: 'POST',
                    //             action: "{{ route('admin.patient-records.massForceDelete') }}"
                    //         })
                    //         .append(jQuery('<input>', {
                    //             type: 'hidden',
                    //             name: '_token',
                    //             value: _token
                    //         }))
                    //         .append(jQuery('<input>', {
                    //             type: 'hidden',
                    //             name: '_method',
                    //             value: 'DELETE'
                    //         }));

                    //     ids.forEach(function(id) {
                    //         form.append(jQuery('<input>', {
                    //             type: 'hidden',
                    //             name: 'ids[]',
                    //             value: id
                    //         }));
                    //     });

                    //     form.appendTo('body').submit();
                });
            @endif



            jQuery('a[data-toggle="tab"]').on('shown.bs.tab click', function() {
                jQuery(jQuery.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        });
    </script>
@endsection
