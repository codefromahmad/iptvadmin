@extends('layouts.admin')
@section('content')
@can('news_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.news.create") }}">
                {{ trans('global.add') }} News
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        News {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            Title
                        </th>
                        <th>
                            Description
                        </th>
                        <th>
                            &nbsp; Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $key => $news)
                        <tr data-entry-id="{{ $news->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $news->title ?? '' }}
                            </td>
                            <td>
                                {{ $news->description ?? '' }}
                            </td>
                            <td>
                                @can('news_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.news.show', $news->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan
                                @can('news_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.news.edit', $news->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan
                                @can('news_delete')
                                    <form action="{{ route('admin.news.destroy', $news->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <form action="{{ route('admin.news.destroy') }}" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                            @csrf
                                            @method("delete")
                                            <input type="hidden" name="id" value="{{$news->id}}">
                                            <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                        </form>
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
{{--@can('iptvuser_delete')--}}
{{--  dtButtons.push(deleteButton)--}}
{{--@endcan--}}

  $('.datatable:not(.ajaxTable)').DataTable({ buttons: dtButtons })
})

</script>
@endsection
