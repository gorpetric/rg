<!doctype html>
<html lang='{{ app()->getLocale() }}'>
<head>
    @include('partials._headrepeat')
    <meta name='description' content='Royal Gym Brezje'>
    <title>Royal Gym - Početna</title>
</head>
<body>
    <div id='app'>
        <div id='welcome'>
            <img class='logo' src='{{ asset("img/logo.png") }}' alt='Royal Gym logo'>
            <h1>Royal Gym</h1>
            <h2>Brezje</h2>
            <a class='btn' href='/login/facebook'>Prijava putem Facebook-a</a>
        </div>
    </div>
    <script src='{{ asset("js/app.js") }}'></script>
</body>
</html>
