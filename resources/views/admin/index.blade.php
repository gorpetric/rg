@extends('app')

@section('title'){{ 'Admin' }}@stop
@section('description'){{ 'Royal Gym Brezje - Admin area' }}@stop

@section('content')
<div class='section container content'>
    <h1 class='title is-3'>Admin</h1>
    @foreach($users as $user)
        <div>
            <h4 class='title is-4'><small>({{ $user->id }}) </small>{{ $user->name }}</h4>
            <form action='{{ route("admin.users.syncRoles", $user) }}' method='POST' autocomplete='off'>
                <label for='role_admin'>admin</label>
                <input type='checkbox' name='role_admin' id='role_admin' {{ $user->hasRole('admin') ? "checked" : '' }}>&nbsp;
                <label for='role_boss'>boss</label>
                <input type='checkbox' name='role_boss' id='role_boss' {{ $user->hasRole('boss') ? "checked='checked'" : '' }}>&nbsp;
                {{ csrf_field() }}
                <input type='submit' value='Update' class='button'>
                <a href='{{ route("admin.users.impersonate", $user) }}'>Impersonate</a>
            </form>
            <hr>
        </div>
    @endforeach
</div>
@stop
