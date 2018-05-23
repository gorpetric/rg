@extends('app')

@section('title'){{ 'Members vacuum' }}@stop
@section('description'){{ 'Royal Gym Brezje - Members vacuum' }}@stop

@section('content')
<vacuum-index :appointments="{{ $appointments }}" :members="{{ $members }}"></vacuum-index>
@stop
