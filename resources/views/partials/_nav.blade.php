<nav class='navigation'>
    <div class='inner'>
        <div class='brand'>
            <a href='{{ route("home") }}' title='Royal Gym'><img src='{{ asset("img/logo.png") }}' alt='Royal Gym logo'></a>
        </div>
        <div class='nav-toggle'><i class='fas fa-bars'></i></div>
        <div class='links'>
            <a href='{{ route("home") }}' class='nav-item {{ Request::is("/") ? "is-active" : "" }}' title='Početna'><i class='fas fa-home'></i></a>
            @auth
                <a href='#' class='nav-item {{ setActive("forum") }}'>Forum</a>
                @hasanyrole('admin|boss')
                    <a href='{{ route("members.index") }}' class='nav-item {{ setActive("members") }}'>Članovi</a>
                @endhasanyrole
                <div class='dropdown'>
                    <div class='dropdown-toggle'><i class='fas fa-user'></i>&nbsp;<i class='fas fa-caret-down'></i></div>
                    <div class='dropdown-inner'>
                        @role('admin')
                            <div class='group'>
                                <span>Admin</span>
                                <a href='{{ route("admin.index") }}' class='group-item'>Admin</a>
                                <a href='{{ route("admin.backup.index") }}' class='group-item'>Backup</a>
                                <a href='{{ route("admin.logs") }}' class='group-item'>Logs</a>
                            </div>
                        @endrole
                        @impersonating
                            <a href='#' class='dropdown-item' onclick='event.preventDefault(); document.getElementById("stopImpersonateForm").submit();'>Stop impersonating</a>
                            <form action='{{ route("admin.users.impersonate.stop") }}' method='POST' id='stopImpersonateForm'>
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                            </form>
                        @endimpersonating
                        <a href='{{ route("logout") }}' class='dropdown-item'><i class='fas fa-sign-out-alt'></i>&nbsp;Odjava</a>
                    </div>
                </div>
            @endauth
            @guest
                <a href='/login/facebook' class='nav-item'>Prijava &nbsp;<i class='fab fa-facebook-square'></i></a>
            @endguest
        </div>
    </div>
</nav>
