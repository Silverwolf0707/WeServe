@extends('layouts.admin')
@section('content')
    <div class="card shadow-sm border-0">
        <!-- Modernized Header -->
        <div class="card-header custom-header d-flex align-items-center"
            style="background-color: green; color: white; min-height: 80px; padding: 1.5rem;">
            <h4 class="mb-0 fw-bold d-flex align-items-center">
                <i class="fas fa-users me-2"></i> {{ trans('cruds.user.title') }}
            </h4>

            <div class="header-actions d-flex align-items-center ms-auto">
                @can('user_create')
                    <a class="btn btn-add" href="{{ route('admin.users.create') }}">
                        <i class="fas fa-user-plus"></i> {{ trans('global.add') }} {{ trans('cruds.user.title_singular') }}
                    </a>
                @endcan
            </div>
        </div>
    </div>


    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-User">
                <thead class="thead-dark">
                    <tr>
                        <th width="10"></th>
                        <th>{{ trans('cruds.user.fields.id') }}</th>
                        <th>{{ trans('cruds.user.fields.name') }}</th>
                        <th>{{ trans('cruds.user.fields.email') }}</th>
                        <th>{{ trans('cruds.user.fields.email_verified_at') }}</th>
                        <th>{{ trans('cruds.user.fields.roles') }}</th>
                        <th class="text-center">{{ trans('global.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                        <tr data-entry-id="{{ $user->id }}">
                            <td></td>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->email_verified_at }}</td>
                            <td>
                                @foreach($user->roles as $item)
                                    <span class="badge badge-info">{{ $item->title }}</span>
                                @endforeach
                            </td>
                            <td>
                                <div class="d-flex align-items-center justify-content-center">
                                    @can('user_show')
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="mr-2" title="View">
                                            <i class="fas fa-eye text-primary"></i>
                                        </a>
                                    @endcan
                                    @can('user_edit')
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="mr-2" title="Edit">
                                            <i class="fas fa-edit text-info"></i>
                                        </a>
                                    @endcan
                                    @can('user_delete')
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                            onsubmit="return confirm('{{ trans('global.areYouSure') }}');" class="m-0 p-0"
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

            @can('user_delete')
                let deleteButton = {
                    text: '{{ trans('global.datatables.delete') }}',
                    url: "{{ route('admin.users.massDestroy') }}",
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
                pageLength: 100
                // dom removed so it inherits from admin layout
            });

            let table = $('.datatable-User:not(.ajaxTable)').DataTable({ buttons: dtButtons });

            $('a[data-toggle="tab"]').on('shown.bs.tab click', function () {
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        });
    </script>
@endsection