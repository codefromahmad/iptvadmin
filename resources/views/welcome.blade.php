<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    </head>
    <body>
            <div class="container" style="text-align: center;margin-top:25%">
                <a class="btn btn-lg btn-primary" href="{{ route('iptv-register') }}">IPTV Register</a>
                <a class="btn btn-lg btn-success" href="{{ route('login') }}">Admin Login</a>
            </div>
    </body>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

</html>
