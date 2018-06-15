<!doctype html>
<html lang='{{ app()->getLocale() }}'>
<head>
    <meta charset='utf-8'>
    <meta name='theme-color' content='#333'>
    <meta name='author' content='Royal Gym'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='csrf-token' content='{{ csrf_token() }}'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=no'>
    <link rel='stylesheet' href='{{ asset("css/app.css") }}'>
    <link rel='shortcut icon' href='{{ asset("img/favicon.ico") }}'>
    <meta name='description' content='@yield("description", "Royal Gym Brezje")'>
    <title>Royal Gym - @yield('title')</title>
    <script>
        window.RG = {
            csrfToken: '{{ csrf_token() }}',
            user: {
                id: {{ Auth::check() ? Auth::user()->id : 'null' }},
                authenticated: {{ Auth::check() ? 'true' : 'false' }}
            }
        }
    </script>
</head>
<body class='has-navbar-fixed-top'>
    <div id='app'>
        @include('partials._nav')
        <main class='main'>@yield('content')</main>
        <div class='overlay'></div>
    </div>
    <script src='{{ asset("js/app.js") }}'></script>
    @yield('js')
</body>
</html>
