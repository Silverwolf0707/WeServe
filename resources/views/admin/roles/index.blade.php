@extends('layouts.admin')
@section('content')
@can('role_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success mr-2" href="{{ route('admin.roles.create') }}">
                <i class="fas fa-plus mr-1"></i> {{ trans('global.add') }} {{ trans('cruds.role.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.role.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-Role">
                <thead class="thead-dark">
                    <tr>
                        <th width="10"></th>
                        <th>{{ trans('cruds.role.fields.id') }}</th>
                        <th>{{ trans('cruds.role.fields.title') }}</th>
                        <th>{{ trans('cruds.role.fields.permissions') }}</th>
                        <th class="text-center">{{ trans('global.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $key => $role)
                        <tr data-entry-id="{{ $role->id }}">
                            <td></td>
                            <td>{{ $role->id }}</td>
                            <td>{{ $role->title }}</td>
                            <td>
                                @foreach($role->permissions as $item)
                                    <span class="badge badge-info">{{ $item->title }}</span>
                                @endforeach
                            </td>
                            <td>
                                <div class="d-flex align-items-center justify-content-center">
                                    @can('role_show')
                                        <a href="{{ route('admin.roles.show', $role->id) }}" class="mr-2" title="View">
                                            <i class="fas fa-eye text-primary"></i>
                                        </a>
                                    @endcan
                                    @can('role_edit')
                                        <a href="{{ route('admin.roles.edit', $role->id) }}" class="mr-2" title="Edit">
                                            <i class="fas fa-edit text-info"></i>
                                        </a>
                                    @endcan
                                    @can('role_delete')
                                        <form action="{{ route('admin.roles.destroy', $role->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                              class="m-0 p-0"
                                              style="display: inline;">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn p-0 border-0 bg-transparent mr-2" title="Delete">
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

        @can('role_delete')
        let deleteButton = {
            text: '{{ trans('global.datatables.delete') }}',
            url: "{{ route('admin.roles.massDestroy') }}",
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
                        headers: {'x-csrf-token': _token},
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
            dom: 'lBfrtip', // entries + buttons + filtering + pagination
        });

        let table = $('.datatable-Role:not(.ajaxTable)').DataTable({ buttons: dtButtons });

        $('a[data-toggle="tab"]').on('shown.bs.tab click', function () {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    });
</script>
@endsection
