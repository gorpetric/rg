<nav class='navbar is-black is-fixed-top'>
    <div class='container'>
        <div class='navbar-brand'>
            <a href='{{ route("home") }}' class='navbar-item' title='Royal Gym'><img src='{{ asset("img/logo.png") }}' alt='Royal Gym logo'></a>
            <span role='button' class='navbar-burger' aria-label='menu' aria-expanded='false' data-target='main-menu'>
                <span aria-hidden='true'></span>
                <span aria-hidden='true'></span>
                <span aria-hidden='true'></span>
            </span>
        </div>
        <div class='navbar-menu' id='main-menu'>
            <div class='navbar-end'>
                <a href='{{ route("home") }}' class='navbar-item' title='Royal Gym'><i class='fas fa-home'></i></a>
                @auth
                    <a href='#' class='navbar-item' onclick='event.preventDefault();'>Forum</a>
                    <div class='navbar-item has-dropdown is-hoverable'>
                        <a class='navbar-link'><i class='fas fa-user'></i></a>
                        <div class='navbar-dropdown is-right'>
                            @hasanyrole('admin|boss')
                                <a href='{{ route("members.index") }}' class='navbar-item'>Članovi</a>
                                <a href='{{ route("members.vacuum.index") }}' class='navbar-item'>Vacuum</a>
                                <hr class='navbar-divider'>
                            @endhasanyrole
                            @role('admin')
                                <a href='{{ route("admin.index") }}' class='navbar-item'>Admin</a>
                                <a href='{{ route("admin.backup.index") }}' class='navbar-item'>Backup</a>
                                <a href='{{ route("admin.logs") }}' class='navbar-item'>Logs</a>
                                <hr class='navbar-divider'>
                            @endrole
                            @impersonating
                                <a href='#' class='navbar-item' onclick='event.preventDefault(); document.getElementById("stopImpersonateForm").submit();'>Stop impersonating</a>
                                <form action='{{ route("admin.users.impersonate.stop") }}' method='POST' id='stopImpersonateForm'>
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                </form>
                                <hr class='navbar-divider'>
                            @endimpersonating
                            <a href='{{ route("logout") }}' class='navbar-item'><i class='fas fa-sign-out-alt'></i>&nbsp;Odjava</a>
                        </div>
                    </div>
                @endauth
                @guest
                    <a href='/login/facebook' class='navbar-item'>Prijava &nbsp;<i class='fab fa-facebook-square'></i></a>
                @endguest
            </div>
        </div>
    </div>
</nav>
