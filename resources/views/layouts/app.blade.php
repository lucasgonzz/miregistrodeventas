<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user" content="{{ Auth()->user() }}">
    <meta name="percentage-card" content="{{ Auth()->user()->percentage_card }}">
    <meta name="user-can-see-costs" 
            content="{{ Auth()->user()->hasPermissionTo('article.index.cost') }}">
    <meta name="asset" content="{{ asset('') }}">
    <title>Mi Registro de Ventas</title>

    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Icons -->
    <link href="{{ asset('styles.css') }}" rel="stylesheet">
</head>
<body>
    <!-- <div id="app"> -->
        @include('layouts.navbar')

        <main class="py-4">
            <div id="app">
                @yield('content')
            </div>
        </main>
    <!-- </div> -->
<script src="{{ asset('js/app.js') }}"></script>
@yield('scripts')
</body>
</html>
