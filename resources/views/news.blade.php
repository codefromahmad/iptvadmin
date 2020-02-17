<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

{{--    <link rel="manifest" href="site.webmanifest">--}}
    <link rel="apple-touch-icon" href="icon.png">
{{--    Css file--}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Place favicon.ico in the root directory -->
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
        <h1 class="news-heading">Smart IPTV News</h1>
        @foreach($data as $key => $news)
            <div class="card">
                <div class="card-body">
                    <p class="card-date">{{$news->created_at}}</p>
                    <h5 class="card-title">{{$news->title}}</h5>
                    <p class="card-text">{{$news->description}}</p>
                </div>
            </div>
        @endforeach
        <br>
        <div class="ml-auto mr-auto">
            {!! $data->links() !!}
        </div>
    </div>
</main>

@section('scripts')

@endsection
<!-- Add your site or application content here -->



</body>

</html>
