<nav class='navigation'>
    <div class='inner'>
        <div class='brand'>
            <a href='{{ route("home") }}' title='Royal Gym'><img src='{{ asset("img/logo.png") }}' alt='Royal Gym logo'></a>
        </div>
        <div class='nav-toggle'><i class='fa fa-bars'></i></div>
        <div class='links'>
            <a href='{{ route("home") }}' class='nav-item {{ Request::is("/") ? "is-active" : "" }}' title='Početna'><i class='fa fa-home'></i></a>
            @auth
                <a href='#' class='nav-item {{ setActive("forum") }}'>Forum</a>
                <div class='dropdown'>
                    <div class='dropdown-toggle'><i class='fa fa-user'></i>&nbsp;<i class='fa fa-caret-down'></i></div>
                    <div class='dropdown-inner'>
                        @hasanyrole('admin|boss')
                            <a href='{{ route("members.index") }}' class='dropdown-item'>Članovi</a>
                        @endhasanyrole
                        @role('admin')
                            <div class='group'>
                                <span>Admin</span>
                                <a href='{{ route("admin.index") }}' class='group-item'>Admin</a>
                                <a href='{{ route("admin.backup.index") }}' class='group-item'>Backup</a>
                            </div>
                        @endrole
                        <a href='{{ route("logout") }}' class='dropdown-item' title='Odjava'><i class='fa fa-sign-out'></i></a>
                    </div>
                </div>
            @endauth
            @guest
                <a href='/login/facebook' class='nav-item'>Prijava &nbsp;<i class='fa fa-facebook'></i></a>
            @endguest
        </div>
    </div>
</nav>
