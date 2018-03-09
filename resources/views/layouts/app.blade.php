<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script charset="utf-8">
        window.App = {!! json_encode([
            'signedIn' => Auth::check(),
            'user' => auth()->user()
        ]) !!};
    </script>

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<style>
    .level {
        display: flex;
        align-items: center;
    }

    .flex {
        flex: 1;
    }

    .mr-1 {
        margin-right: 1rem;
    }

    [v-cloak] {
        display: none;
    }
</style>

<body>
<div id="app">
    @include('layouts.nav')

    @yield('content')

    <flash data="{{ session('flash') }}"></flash>
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
