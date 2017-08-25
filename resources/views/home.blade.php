@extends('app')

@section('title'){{ 'Početna' }}@stop

@section('content')
<div class='barbell'></div>
<div class='container'>
    <h1>Royal Gym</h1>
    <p>Web u izradi</p>
    @hasanyrole('admin|boss')
        <p><a href='{{ route("members.index") }}'>Članovi</a></p>
    @endhasanyrole
    @role('admin')
        <p><a href='{{ route("admin.index") }}'>Admin</a></p>
    @endrole
</div>
@stop
