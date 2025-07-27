@extends('layouts.admin')
@section('content')

<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">
                <i class="fas fa-lock me-2"></i> {{ trans('cruds.permission.title') }}
            </h5>
           
        </div>

        @can('permission_create')
           <a class="btn btn-success" href="{{ route('admin.permissions.create') }}">
                <i class="fas fa-plus me-1"></i> {{ trans('global.add') }} {{ trans('cruds.permission.title_singular') }}
            </a>
        @endcan
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-Permission">
                <thead class="thead-dark">
                    <tr>
                        <th width="10"></th>
                        <th>{{ trans('cruds.permission.fields.id') }}</th>
                        <th>{{ trans('cruds.permission.fields.title') }}</th>
                        <th class="text-center">{{ trans('global.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permissions as $permission)
                        <tr data-entry-id="{{ $permission->id }}">
                            <td></td>
                            <td>{{ $permission->id }}</td>
                            <td>{{ $permission->title }}</td>
                            <td>
                                <div class="d-flex align-items-center justify-content-center">
                                    @can('permission_show')
                                        <a href="{{ route('admin.permissions.show', $permission->id) }}" class="mr-3" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('permission_edit')
                                        <a href="{{ route('admin.permissions.edit', $permission->id) }}" class="mr-3" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('permission_delete')
                                        <form action="{{ route('admin.permissions.destroy', $permission->id) }}"
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
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);

        @can('permission_delete')
        let deleteButton = {
            text: '{{ trans('global.datatables.delete') }}',
            url: "{{ route('admin.permissions.massDestroy') }}",
            className: 'btn-danger',
            action: function (e, dt, node, config) {
                var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                    return $(entry).data('entry-id');
                });

                if (ids.length === 0) {
                    alert('{{ trans('global.datatables.zero_selected') }}');
                    return;
                }

                if (confirm('{{ trans('global.areYouSure') }}')) {
                    $.ajax({
                        headers: { 'x-csrf-token': _token },
                        method: 'POST',
                        url: config.url,
                        data: { ids: ids, _method: 'DELETE' }
                    }).done(function () { location.reload(); });
                }
            }
        }
        dtButtons.push(deleteButton);
        @endcan

        $.extend(true, $.fn.dataTable.defaults, {
            orderCellsTop: true,
            order: [[1, 'desc']],
            pageLength: 100,
            dom: 'lBfrtip',
        });

        let table = $('.datatable-Permission:not(.ajaxTable)').DataTable({ buttons: dtButtons });

        $('a[data-toggle="tab"]').on('shown.bs.tab click', function () {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    });
</script>
@endsection