@extends('app')

@section('title'){{ 'Admin' }}@stop
@section('description'){{ 'Royal Gym Brezje - Admin area' }}@stop

@section('content')
<div class='container'>
    <h1>Admin</h1>
    <p><a href='{{ route("admin.backup.index") }}'>Backup</a></p>
    @foreach($users as $user)
        <div>
            <h4>{{ $user->name }}</h4>
            <form action='{{ route("admin.users.syncRoles", $user) }}' method='POST' autocomplete='off'>
                <label for='role_admin'>admin</label>
                <input type='checkbox' name='role_admin' id='role_admin' {{ $user->hasRole('admin') ? "checked" : '' }}>&nbsp;
                <label for='role_boss'>boss</label>
                <input type='checkbox' name='role_boss' id='role_boss' {{ $user->hasRole('boss') ? "checked='checked'" : '' }}>&nbsp;
                {{ csrf_field() }}
                <input type='submit' value='Update' class='form-btn'>
            </form>
            <hr>
        </div>
    @endforeach
</div>
@stop
