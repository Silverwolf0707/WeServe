@extends('layouts.admin')

@section('content')
    @can('patient_record_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success me-2" href="{{ route('admin.patient-records.create') }}">
                    <i class="fas fa-user-plus me-1"></i> {{ trans('global.add') }}
                    {{ trans('cruds.patientRecord.title_singular') }}
                </a>
                <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                    <i class="fas fa-file-csv me-1"></i> {{ trans('global.app_csvImport') }}
                </button>
                @include('csvImport.modal', ['model' => 'PatientRecord', 'route' => 'admin.patient-records.parseCsvImport'])
            </div>
        </div>
    @endcan

    <div class="card">
        <div class="card-header">
            {{ trans('cruds.patientRecord.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
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
                        @foreach($patientRecords as $patientRecord)
                            <tr data-entry-id="{{ $patientRecord->id }}">
                                <td></td>
                                <td>{{ \Carbon\Carbon::parse($patientRecord->date_processed)->format('F j, Y g:i A') }}</td>
                                <td>{{ $patientRecord->case_type ?? '' }}</td>
                                <td>{{ $patientRecord->control_number ?? '' }}</td>
                                <td>{{ $patientRecord->claimant_name ?? '' }}</td>
                                <td>{{ App\Models\PatientRecord::CASE_CATEGORY_SELECT[$patientRecord->case_category] ?? '' }}
                                </td>
                                <td>{{ $patientRecord->patient_name ?? '' }}</td>
                                <td class="text-truncate" style="max-width: 200px;">{{ $patientRecord->diagnosis ?? '' }}</td>
                                <td>{{ $patientRecord->age ?? '' }}</td>
                                <td>{{ $patientRecord->address ?? '' }}</td>
                                <td>{{ $patientRecord->contact_number ?? '' }}</td>
                                <td>{{ $patientRecord->case_worker ?? '' }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @can('patient_record_show')
                                            <a href="{{ route('admin.patient-records.show', $patientRecord->id) }}" class="mr-3"><i
                                                    class="fas fa-eye"></i></a>
                                        @endcan
                                        @can('patient_record_edit')
                                            <a href="{{ route('admin.patient-records.edit', $patientRecord->id) }}" class="mr-3"><i
                                                    class="fas fa-edit"></i></a>
                                        @endcan
                                        @can('patient_record_delete')
                                            <form action="{{ route('admin.patient-records.destroy', $patientRecord->id) }}"
                                                method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                class="d-inline">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn p-0 border-0 bg-transparent mr-3"><i
                                                        class="fas fa-trash-alt text-danger"></i></button>
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

    <!-- Mass Submit Modal -->
    <div class="modal fade" id="massSubmitModal" tabindex="-1" role="dialog" aria-labelledby="massSubmitModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="massSubmitForm">
                @csrf
                <input type="hidden" name="ids" id="massSubmitIds">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Submit Selected Patients</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Remarks (optional):</p>
                        <textarea class="form-control" name="remarks" id="massSubmitRemarks" rows="3"
                            placeholder="Enter remarks..."></textarea>
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
    <script>
        $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

            @can('patient_record_delete')
                dtButtons.push({
                    text: '{{ trans('global.datatables.delete') }}',
                    url: "{{ route('admin.patient-records.massDestroy') }}",
                    className: 'btn-danger',
                    action: function (e, dt, node, config) {
                        var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                            return $(entry).data('entry-id')
                        });

                        if (ids.length === 0) {
                            alert('{{ trans('global.datatables.zero_selected') }}')
                            return
                        }

                        if (confirm('{{ trans('global.areYouSure') }}')) {
                            $.ajax({
                                headers: { 'x-csrf-token': _token },
                                method: 'POST',
                                url: config.url,
                                data: { ids: ids, _method: 'DELETE' }
                            }).done(function () { location.reload() })
                        }
                    }
                })
            @endcan

                @can('submit_patient_application')
                    let selectedIds = [];

                    dtButtons.push({
                        text: 'Submit Selected',
                        className: 'btn-primary',
                        action: function (e, dt, node, config) {
                            selectedIds = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                                return $(entry).data('entry-id')
                            });

                            if (selectedIds.length === 0) {
                                alert('No records selected');
                                return;
                            }

                            $('#massSubmitRemarks').val('');
                            $('#massSubmitModal').modal('show');
                        }
                    });

                    $('#massSubmitForm').on('submit', function (e) {
                        e.preventDefault();

                        $.ajax({
                            headers: { 'x-csrf-token': _token },
                            method: 'POST',
                            url: "{{ route('admin.patient-records.massSubmit') }}",
                            data: {
                                ids: selectedIds,
                                remarks: $('#massSubmitRemarks').val(),
                                _method: 'POST'
                            },
                            success: function (response) {
                                $('#massSubmitModal').modal('hide');
                                if (response.toast) {
                                    showToast(response.toast);
                                }
                            },

                            error: function () {
                                alert('An error occurred while submitting the records.');
                            }
                        });
                    });
                @endcan

            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [[1, 'desc']],
                pageLength: 100,
            });

            let table = $('.datatable-PatientRecord:not(.ajaxTable)').DataTable({ buttons: dtButtons });

            $('a[data-toggle="tab"]').on('shown.bs.tab click', function () {
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
            var toastEl = document.getElementById('liveToast');
            var timerEl = document.getElementById('toast-timer');

            if (toastEl) {
                var toast = new bootstrap.Toast(toastEl, {
                    autohide: true,
                    delay: 5000
                });
                toast.show();

                // Countdown timer for display
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
    </script>
@endsection