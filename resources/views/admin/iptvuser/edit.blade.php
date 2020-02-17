@extends('layouts.admin')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card" >
                    <div class="card-header bg-primary">{{ __('IPTV Register') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.iptvuser.update') }}">
                            @csrf
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('MAC Adress') }}</label>

                                <div class="col-md-6">
                                    <input placeholder="00:aa:bb:cc:dd:11" id="name" type="text" class="form-control{{ $errors->has('mac_address') ? ' is-invalid' : '' }}" name="mac_address" value="{{$data['iptv']->mac_address}}" disabled>

                                    @if ($errors->has('mac_address'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('mac_address') }}</strong>
                                    </span>
                                    @endif

                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Period') }}</label>

                                <div class="col-md-6">
                                    <input id="id" type="hidden"  name="id" value="{{$data['iptv']->id}}">
                                    <input id="period" type="text" class="form-control{{ $errors->has('period') ? ' is-invalid' : '' }}" name="period" value="{{$data['iptv']->period}}">

                                    @if ($errors->has('period'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('period') }}</strong>
                                    </span>
                                    @endif

                                </div>
                            </div>

{{--                            <div class="form-group row">--}}
{{--                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('M3U File') }}</label>--}}

{{--                                <div class="col-md-6">--}}
{{--                                    <input id="email" type="file" class="form-control{{ $errors->has('m3ufile') ? ' is-invalid' : '' }}" name="m3ufile" value="{{ old('m3ufile') }}" disabled>--}}

{{--                                    @if ($errors->has('m3ufile'))--}}
{{--                                        <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $errors->first('m3ufile') }}</strong>--}}
{{--                                    </span>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="form-group row">--}}
{{--                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('EPG File') }}</label>--}}

{{--                                <div class="col-md-6">--}}
{{--                                    <input id="password" type="file" class="form-control{{ $errors->has('epgfile') ? ' is-invalid' : '' }}" name="epgfile">--}}

{{--                                    @if ($errors->has('epgfile'))--}}
{{--                                        <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $errors->first('epgfile') }}</strong>--}}
{{--                                    </span>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                            </div>--}}

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Update Membership') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
