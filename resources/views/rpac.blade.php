@auth
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-token" content="{{ optional(auth()->user())->api_token ?? '' }}">
    <meta name="user" content='{"id":"{{ optional(auth()->user())->id ?? "" }}", "email":"{{ optional(auth()->user())->email ?? "" }}", "name":"{{ optional(auth()->user())->name ?? "" }}"}'>
    <meta name="app-url" content='{{ Route::has("home") ? route("home") : url("/") }}'>
    <!-- <meta name="app-url" content='{{ config("rpac.app_url") ?? url("/") }}'> -->

    <title>{{ config('app.name', 'RPAC') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('vendor/rpac/rpac.js') }}" defer></script>

    <!-- Styles -->
    <style type="text/css">
        body {
            font-family: Arial;
            margin: 0;
        }
        *::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        *::-webkit-scrollbar-track {
            background-color: rgba(200, 200, 200, 0.3);
        }
        *::-webkit-scrollbar-thumb {
            background-color: rgba(100, 100, 100, 0.5);
        }
        *::-webkit-scrollbar-thumb:hover {
            background-color: rgba(100, 100, 100, 0.7);
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
</head>
<body>
<div id="rpac"></div>
</body>
</html>
@endauth
