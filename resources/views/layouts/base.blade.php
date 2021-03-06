<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width; initial-scale=1">

        <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>

        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/fontawesome.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/brands.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/regular.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/solid.min.css') }}" rel="stylesheet">
        <!-- Google Fonts -->
    </head>
    <body class="text-gray-800">
        @yield('body')
    </body>
</html>
