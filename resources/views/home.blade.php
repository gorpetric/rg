@extends('app')

@section('title'){{ 'Početna' }}@stop

@section('content')
<h1>Royal Gym</h1>
@if(Auth::check())
    <a href='{{ route("logout") }}'>Logout</a>
@else
    <a href='/login/facebook'>Login with Facebook</a>
@endif
@stop
