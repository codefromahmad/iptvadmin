<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

{{--    <link rel="manifest" href="site.webmanifest">--}}
    <link rel="apple-touch-icon" href="icon.png">
    <!-- Place favicon.ico in the root directory -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <meta name="theme-color" content="#fafafa">
</head>

<body>
<!--[if IE]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
<![endif]-->


<div class="nav">
    <div class="logo">
        <img src="./icon.png" alt="IPTV">
    </div>
    <ul class="ml-auto">
        <li><a href="/">NEWS</a></li>
        <li><a href="/mylist">My List</a></li>
    </ul>

</div>

<main>
    <div class="container">
        <div class="card">
            <h2 class="card-title">Playlist upload to Smart IPTV</h2>
            <div class="card-body">
                <ul>
                    <li>Select proper EPG country to correctly match channel electronic programming language</li>
                    <li>Use Disable plist logos to disable playlist logos or Override app logos (tvg-logo) to only use playlist logos</li>
                    <li> Use Save online only if you have problems loading playlist on your TV due to low memory</li>
                    <li>Use Detect EPG to automatically detect EPG URL included in your playlist (tvg-url, url-tvg, x-tvg-url)</li>
                    <li>Use Disable Groups to disable playlist groups when uploading multiple playlists</li>
                </ul>
                <h2>Upload playlist Files or external playlist URLs with auto-update</h2>
                @if(session()->has('update-success'))
                    <div class="alert alert-success">
                        {{ session()->get('update-success') }}
                    </div>
                @endif
                @if(session()->has('insert-success'))
                    <div class="alert alert-success">
                        {{ session()->get('insert-success') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('iptv-store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6 mac-div">
                            <label for="mac">MAC</label>
                            <input placeholder="00:aa:bb:cc:dd:11" id="name" type="text" class="form-control{{ $errors->has('mac_address') ? ' is-invalid' : '' }}" name="mac_address" value="{{ old('mac_address') }}"  autofocus>
                            @if ($errors->has('mac_address'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('mac_address') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="email" class="col-md-12 col-form-label">{{ __('M3U File') }}</label>

                                <div class="col-md-12">
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
                                <label for="password" class="col-md-12 col-form-label">{{ __('EPG File') }}</label>

                                <div class="col-md-12">
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
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary form-control">Send</button>
                </form>
                <br>
                <h2>Delete playlist(s)</h2>
                @if(session()->has('delete-success'))
                    <div class="alert alert-success">
                        {{ session()->get('delete-success') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('iptv-delete') }}">
                    @csrf
                    <div class="form-group">
                        <label for="mac_address">MAC</label>
                        <input placeholder="00:aa:bb:cc:dd:11" id="mac_address" type="text" class="form-control{{ $errors->has('mac_address') ? ' is-invalid' : '' }}" name="mac_address" value="{{ old('mac_address') }}" autofocus>


                        @if ($errors->has('mac_address'))
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('mac_address') }}</strong>
                                </span>
                        @endif                    </div>
                    <button type="submit" class="btn btn-danger form-control">Delete</button>
                </form>
            </div>
        </div>
    </div>
</main>

<!-- Add your site or application content here -->
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
</body>

</html>
