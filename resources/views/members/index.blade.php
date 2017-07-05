@extends('app')

@section('title'){{ 'Članovi' }}@stop

@section('content')
<div class='container'>
    <h1>Članovi</h1>
    <members :data='{{ $data }}'></members>
</div>
@stop
