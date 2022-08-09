@extends('app')

@section('title'){{ 'Početna' }}@stop

@section('content')
<div id='home-about'>
    <div class='section container'>
        <h1 class='title is-1 is-size-2-mobile has-text-centered has-text-white'>ROYAL GYM</h1>
        <h2 class='title is-4 is-size-5-mobile has-text-centered has-text-white'>BREZJE</h2>
        <p class='has-text-centered has-text-white'>
            od ponedjeljka do petka&nbsp;<i class='fas fa-clock'></i>&nbsp;09 - 22<br>
            subotom&nbsp;<i class='fas fa-clock'></i>&nbsp;09 - 20<br>
            nedjeljom&nbsp;<i class='fas fa-clock'></i>&nbsp;10 - 18
        </p>
        <img class='home-logo' src='{{ asset("img/logo.png") }}' alt='Royal Gym logo'>
    </div>
</div>
@stop
