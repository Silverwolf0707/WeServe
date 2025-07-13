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
                        <th>Patient</th>
                        <th>Document Type</th>
                        <th>File Name</th>
                        <th>Description</th>
                        <th>Date Uploaded</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents as $document)
                        <tr data-entry-id="{{ $document->id }}">
                            <td></td>
                            <td>{{ $document->patient->claimant_name ?? 'N/A' }}</td>
                            <td>{{ $document->document_type ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank">
                                    {{ $document->file_name }}
                                </a>
                            </td>
                            <td>{{ $document->description ?? '' }}</td>
                            <td>{{ $document->created_at->format('F j, Y g:i A') }}</td>
                            <td>
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.document-management.show', $document->id) }}">
                                    {{ trans('global.view') }}
                                </a>

                                @can('documents_management')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.document-management.edit', $document->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>

                                    <form action="{{ route('admin.document-management.destroy', $document->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display:inline-block;">
                                        @method('DELETE')
                                        @csrf
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan
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

        @can('documents_management')
        let deleteButton = {
            text: '{{ trans('global.datatables.delete') }}',
            url: "{{ route('admin.document-management.massDestroy') }}",
            className: 'btn-danger',
            action: function (e, dt, node, config) {
                let ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
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
            order: [[5, 'desc']],
            pageLength: 100,
        });

        $('.datatable-Document:not(.ajaxTable)').DataTable({ buttons: dtButtons });

        $('a[data-toggle="tab"]').on('shown.bs.tab click', function () {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    });
</script>
@endsection
