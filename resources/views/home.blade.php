@extends('app')

@section('title'){{ 'Početna' }}@stop

@section('content')
<div class='barbell'></div>
<div class='container'>
    <h1>Royal Gym</h1>
    <p><a href='{{ route("members.index") }}'>Članovi</a></p>
</div>
@stop
