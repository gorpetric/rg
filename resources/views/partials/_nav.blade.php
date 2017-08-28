<nav class='navigation'>
    <div class='inner'>
        <a class='logo' href='{{ route("home") }}' title='Royal Gym'><img src='{{ asset("img/logo-dark.png") }}' alt='Royal Gym logo'></a>
        <span>Royal Gym</span>
        <div class='right'>
            <a onclick='event.preventDefault();' class='nav-link' href='#' title='{{ Auth::user()->name }}'><img src='{{ Auth::user()->avatar }}' alt='{{ Auth::user()->name }} avatar' class='avatar'></a>
            <a class='nav-link' href='{{ route("logout") }}' title='Odjava'><i class='fa fa-sign-out'></i></a>
        </div>
    </div>
</nav>
