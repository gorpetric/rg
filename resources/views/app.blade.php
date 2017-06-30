<!doctype html>
<html lang='{{ app()->getLocale() }}'>
<head>
    @include('partials._headrepeat')
    <meta name='description' content='@yield("description", "Royal Gym Brezje")'>
    <title>Royal Gym - @yield('title')</title>
</head>
<body>
    <div id='app'>
        @include('partials._nav')
        <main class='main'>@yield('content')</main>
    </div>
    <script src='{{ asset("js/app.js") }}'></script>
    @yield('js')
</body>
</html>
