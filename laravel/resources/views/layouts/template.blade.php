<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>

        <!-- Font Awesome -->
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700|Open+Sans|Source+Sans+Pro" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="{{ asset('css/main.css') }}" type="text/css" rel="stylesheet">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    </head>
    <body>
        <div class="container">
            <nav>@yield('nav')</nav>
            @yield('content')
        </div>

        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        @yield('pageSpecificJs')
    </body>
</html>
