<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>

        <!-- Font Awesome -->
        <link href="vendor/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Lato|Open+Sans|Source+Sans+Pro" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="css/main.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            @yield('content')
        </div>
    </body>
</html>
