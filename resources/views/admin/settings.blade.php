@extends('app')

@section('title'){{ 'Settings' }}@stop
@section('description'){{ 'Royal Gym Brezje - Admin area - settings' }}@stop

@section('content')
<admin-settings :settingsprop="{{ $settings }}"></admin-settings>
@stop
