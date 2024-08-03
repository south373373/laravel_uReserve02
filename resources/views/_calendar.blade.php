<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 
        'resources/js/app.js', 
        'resources/js/flatpickr.js'])

    </head>
    <body class="font-sans antialiased">
        
        <!-- resources > calendar > indexの読み込み -->
        <!-- @include('calendar.index') -->
        @yield('content')
        <script src="{{ mix('js/flatpickr.js')}}"></script>
        <!-- @livewireScriptsは無しで設定 -->
    </body>
</html>