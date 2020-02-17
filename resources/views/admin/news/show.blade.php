@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('global.product.title') }}
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th>
                        Title
                    </th>
                    <td>
                        {{ $news->title }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Description
                    </th>
                    <td>
                        {!! $news->description !!}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection
