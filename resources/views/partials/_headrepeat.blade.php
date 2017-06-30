<meta charset='utf-8'>
<meta name='theme-color' content='#25262b'>
<meta name='author' content='Royal Gym'>
<meta http-equiv='X-UA-Compatible' content='IE=edge'>
<meta name='csrf-token' content='{{ csrf_token() }}'>
<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=no'>
<link rel='stylesheet' href='{{ asset("css/app.css") }}'>
<link rel='shortcut icon' href='{{ asset("img/favicon.ico") }}'>
<script>
    window.RG = {
        csrfToken: '{{ csrf_token() }}',
        user: {
            id: {{ Auth::check() ? Auth::user()->id : 'null' }},
            authenticated: {{ Auth::check() ? 'true' : 'false' }}
        }
    }
</script>
