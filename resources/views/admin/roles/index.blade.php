@extends('layouts.admin')
@section('content')
    
    <style>
        .btn-warning {
            color: black !important;
        }
    </style>

    <div class="card-header d-flex justify-content-between align-items-center"
        style="background-color: green; color: white;">
        <div>
            <h5 class="mb-0">
                <i class="fas fa-users-cog me-2"></i> {{ trans('cruds.role.title') }}
            </h5>
        </div>

        @can('role_create')
            <a class="btn btn-warning ms-auto" href="{{ route('admin.roles.create') }}">
                <i class="fas fa-plus me-1"></i> {{ trans('global.add') }} {{ trans('cruds.role.title_singular') }}
            </a>
        @endcan
    </div>



    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-Role">
                <thead class="thead-dark">
                    <tr>
                        <th width="10"></th>
                        <th>{{ trans('cruds.role.fields.id') }}</th>
                        <th>{{ trans('cruds.role.fields.title') }}</th>
                        <th class="text-center">{{ trans('global.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                        <tr data-entry-id="{{ $role->id }}">
                            <td></td>
                            <td>{{ $role->id }}</td>
                            <td>{{ $role->title }}</td>
                            <td>
                                <div class="d-flex align-items-center justify-content-center">
                                    @can('role_show')
                                        <a href="{{ route('admin.roles.show', $role->id) }}" class="mr-3" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('role_edit')
                                        <a href="{{ route('admin.roles.edit', $role->id) }}" class="mr-3" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('role_delete')
                                        <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST"
                                            onsubmit="return confirm('{{ trans('global.areYouSure') }}');" class="m-0 p-0"
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

            let table = $('.datatable-Role:not(.ajaxTable)').DataTable({ buttons: dtButtons });

            $('a[data-toggle="tab"]').on('shown.bs.tab click', function () {
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        });
    </script>
@endsection