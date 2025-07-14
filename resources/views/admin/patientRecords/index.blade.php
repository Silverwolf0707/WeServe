@extends('layouts.admin')
@section('content')
@can('patient_record_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success me-2" href="{{ route('admin.patient-records.create') }}">
                <i class="fas fa-user-plus me-1"></i> {{ trans('global.add') }} {{ trans('cruds.patientRecord.title_singular') }}
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
                    @foreach($patientRecords as $key => $patientRecord)
                        <tr data-entry-id="{{ $patientRecord->id }}">
                            <td></td>
                            <td>{{ \Carbon\Carbon::parse($patientRecord->date_processed)->format('F j, Y g:i A') }}</td>
                            <td>{{ $patientRecord->case_type ?? '' }}</td>
                            <td>{{ $patientRecord->control_number ?? '' }}</td>
                            <td>{{ $patientRecord->claimant_name ?? '' }}</td>
                            <td>{{ App\Models\PatientRecord::CASE_CATEGORY_SELECT[$patientRecord->case_category] ?? '' }}</td>
                            <td>{{ $patientRecord->patient_name ?? '' }}</td>
                            <td>{{ $patientRecord->diagnosis ?? '' }}</td>
                            <td>{{ $patientRecord->age ?? '' }}</td>
                            <td>{{ $patientRecord->address ?? '' }}</td>
                            <td>{{ $patientRecord->contact_number ?? '' }}</td>
                            <td>{{ $patientRecord->case_worker ?? '' }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @can('patient_record_show')
                                        <a href="{{ route('admin.patient-records.show', $patientRecord->id) }}" class="mr-3" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('patient_record_edit')
                                        <a href="{{ route('admin.patient-records.edit', $patientRecord->id) }}" class="mr-3" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('patient_record_delete')
                                        <form action="{{ route('admin.patient-records.destroy', $patientRecord->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                              class="m-0 p-0"
                                              style="display: inline;">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn p-0 border-0 bg-transparent mr-3" title="Delete">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </button>
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
@endsection

@section('scripts')
@parent
<script>
    $(function () {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

        @can('patient_record_delete')
        let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
        let deleteButton = {
            text: deleteButtonTrans,
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
                        headers: {'x-csrf-token': _token},
                        method: 'POST',
                        url: config.url,
                        data: { ids: ids, _method: 'DELETE' }
                    }).done(function () { location.reload() })
                }
            }
        }
        dtButtons.push(deleteButton)
        @endcan

        $.extend(true, $.fn.dataTable.defaults, {
            orderCellsTop: true,
            order: [[1, 'desc']],
            pageLength: 100,
        });

        let table = $('.datatable-PatientRecord:not(.ajaxTable)').DataTable({ buttons: dtButtons });

        $('a[data-toggle="tab"]').on('shown.bs.tab click', function (e) {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    });
</script>
@endsection
