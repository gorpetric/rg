@extends('app')

@section('title'){{ 'Logs' }}@stop
@section('description'){{ 'Royal Gym Brezje - Admin area - logs' }}@stop

@section('content')
<admin-logs :logs="{{ $logs }}"></admin-logs>
@stop
