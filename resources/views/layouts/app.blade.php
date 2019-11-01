<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Icons -->
    <link href="{{ asset('styles.css') }}" rel="stylesheet">
    <!-- sad -->
</head>
<body>
    <!-- <div id="app"> -->
        @include('layouts.navbar')

        <main class="py-4">
            <div class="container"> 
                <div id="app">
                    @yield('content')
                </div>
            </div>
        </main>
    <!-- </div> -->
<script src="{{ asset('js/app.js') }}"></script>
@yield('scripts')
</body>
</html>
