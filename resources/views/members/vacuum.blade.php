@extends('app')

@section('title'){{ $member->name . ' - Vacuum' }}@stop
@section('description'){{ 'Royal Gym Brezje - Vacuum - ' . $member->name }}@stop

@section('content')
<member-vacuum :memberprop="{{ $member }}" :parts="{{ $parts }}"></member-vacuum>
@stop
