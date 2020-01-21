@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('global.iptvusers.title') }}
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th>
                        {{ trans('global.iptvusers.fields.id') }}
                    </th>
                    <td>
                        {{ $data['iptv']->id }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('global.iptvusers.fields.mac_address') }}
                    </th>
                    <td>
                        {{ $data['iptv']->mac_address }}
                    </td>
                </tr>
                <tr>


                    <th>
                        {{ trans('global.iptvusers.fields.epgfile') }}
                    </th>
                    <td>
                        {{ $data['epg']->efile }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('global.iptvusers.fields.m3ufile') }}
                    </th>
                    <td>
                        {{ $data['m3u']->mfile }}
                    </td>
                </tr>

                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
{{--                        @php--}}
{{--                            dd(redirect('/admin/iptvuser'));--}}
{{--                        @endphp--}}
                        <a class="btn btn-success" href="{{ redirect('/admin/iptvuser')->getTargetUrl() }}">
                            Go Back
                        </a>
                    </div>
                </div>
{{--                <tr>--}}
{{--                    <th>--}}
{{--                        Roles--}}
{{--                    </th>--}}
{{--                    <td>--}}
{{--                        @foreach($user->roles as $id => $roles)--}}
{{--                            <span class="label label-info label-many">{{ $roles->title }}</span>--}}
{{--                        @endforeach--}}
{{--                    </td>--}}
{{--                </tr>--}}
            </tbody>
        </table>
    </div>
</div>

@endsection
