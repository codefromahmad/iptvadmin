@extends('layouts.admin')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card" >
                    <div class="card-header bg-primary">{{ __('IPTV Register') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.iptvuser.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('MAC Adress') }}</label>

                                <div class="col-md-6">
                                    <input placeholder="00:aa:bb:cc:dd:11" id="name" type="text" class="form-control{{ $errors->has('mac_address') ? ' is-invalid' : '' }}" name="mac_address" value="{{ old('mac_address') }}" required autofocus>

                                    @if ($errors->has('mac_address'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('mac_address') }}</strong>
                                    </span>
                                    @endif

                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('M3U File') }}</label>

                                <div class="col-md-6">
                                    <span class="m3u-file">
                                        <input id="email" type="file" class="form-control{{ $errors->has('m3ufile') ? ' is-invalid' : '' }}" name="m3ufile" value="{{ old('m3ufile') }}">
                                        <span>Or Use <a role="button" class="link-btn badge badge-primary">Link</a></span>
                                    </span>


                                    @if ($errors->has('m3ufile'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('m3ufile') }}</strong>
                                    </span>
                                    @endif

                                    <span class="m3u-link">
                                        <input type="text" name="m3u_link" class="form-control" placeholder="Enter M3U link here">
                                        <span>Or Use <a role="button" class="file-btn badge badge-primary">File</a></span>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('EPG File') }}</label>

                                <div class="col-md-6">
                                    <span class="epg-file">
                                        <input id="password" type="file" class="form-control{{ $errors->has('epgfile') ? ' is-invalid' : '' }}" name="epgfile">
                                        <span>Or Use <a role="button" class="epg-link-btn badge badge-primary">Link</a></span>
                                    </span>

                                    @if ($errors->has('epgfile'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('epgfile') }}</strong>
                                    </span>
                                    @endif
                                    <span class="epg-link">
                                        <input type="text" class="form-control" name="epg_link" placeholder="Enter EPG link here">
                                        <span>Or Use <a role="button" class="epg-file-btn badge badge-primary">File</a></span>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelector('.link-btn').addEventListener('click', ()=>{
            document.querySelector('.m3u-file').style.display = 'none'
            document.querySelector('.m3u-link').style.display = 'block'
        })
        document.querySelector('.file-btn').addEventListener('click', ()=>{
            document.querySelector('.m3u-file').style.display = 'block'
            document.querySelector('.m3u-link').style.display = 'none'
        })

        document.querySelector('.epg-link-btn').addEventListener('click', ()=>{
            document.querySelector('.epg-file').style.display = 'none'
            document.querySelector('.epg-link').style.display = 'block'
        })
        document.querySelector('.epg-file-btn').addEventListener('click', ()=>{
            document.querySelector('.epg-file').style.display = 'block'
            document.querySelector('.epg-link').style.display = 'none'
        })
    </script>
@endsection
