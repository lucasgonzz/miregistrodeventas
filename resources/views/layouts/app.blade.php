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
    <div id="app">
        <nav-component 
            vender="{{ route('vender') }}" 
            nuevo="{{ route('nuevo') }}" 
            listado="{{ route('listado') }}">
        </nav-component>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
<script src="{{ asset('js/app.js') }}" defer></script>
@yield('scripts')
</body>
</html>
