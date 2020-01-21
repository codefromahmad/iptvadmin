@extends('layouts.admin')
@section('content')
@can('iptvuser_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.iptvuser.create") }}">
                {{ trans('global.add') }} {{ trans('global.iptvusers.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('global.user.title_singular') }} {{ trans('global.list') }}
    </div>

    @if(session()->has('delete-success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session()->get('delete-success') }}
        </div>
    @endif
    @if(session()->has('update-success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session()->get('update-success') }}
        </div>
    @endif
    @if(session()->has('insert-success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session()->get('insert-success') }}
        </div>
    @endif

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('global.iptvusers.fields.id') }}
                        </th>
                        <th>
                            {{ trans('global.iptvusers.fields.mac_address') }}
                        </th>
                        <th>
                            {{ trans('global.iptvusers.fields.period') }}
                        </th>
                        <th>
                            {{ trans('global.iptvusers.fields.m3ufile') }}
                        </th>
                        <th>
                            {{ trans('global.iptvusers.fields.epgfile') }}
                        </th>
                        <th>
                            {{ trans('global.iptvusers.fields.action') }}
                        </th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($data as $key => $d)
                        <tr data-entry-id="{{ $d->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $d->id ?? '' }}
                            </td>
                            <td>
                                {{ $d->mac_address ?? '' }}
                            </td>
                            <td>
                                {{ $d->period ?? '' }}
                            </td>
                            <td>
                                {{ $d->mfile ?? '' }}
                            </td>
                            <td>
                                {{ $d->efile ?? '' }}
                            </td>
{{--                            <td>--}}
{{--                                @foreach($d->roles as $key => $item)--}}
{{--                                    <span class="badge badge-info">{{ $item->title }}</span>--}}
{{--                                @endforeach--}}
{{--                            </td>--}}
                            <td>
                                @can('iptvuser_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.iptvuser.show', $d->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan
                                @can('iptvuser_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.iptvuser.edit', $d->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan
                                @can('iptvuser_delete')
                                    <form action="{{ route('admin.iptvuser.destroy') }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="id" value="{{$d->id}}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.iptvuser.massDestroy') }}",
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
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('iptvuser_delete')
  dtButtons.push(deleteButton)
@endcan

  $('.datatable:not(.ajaxTable)').DataTable({ buttons: dtButtons })
})

</script>
@endsection
