@extends('app')

@section('title'){{ 'Uređivanje člana' }}@stop
@section('description'){{ 'Royal Gym Brezje - Uređivanje postojećeg člana - ' . $member->name }}@stop

@section('content')
<div class='container'>
    <p>
        <a href='{{ route("members.index") }}'>Članovi</a><br>
        <a href='{{ route("members.new") }}'>Novi član</a>
    </p>
    <h1><small>Uređivanje člana: </small>{{ $member->name }}</h1>
    <form action='{{ route("members.edit", $member) }}' method='POST' autocomplete='off'>
        <div class='form-group'>
            <input type='text' name='name' id='name' value='{{ Request::old("name") ?: $member->name }}'>
            <label for='name'>* Ime</label>
        </div>
        <div class='form-group'>
            <input type='text' name='address' id='address' value='{{ Request::old("address") ?: $member->address }}'>
            <label for='address'>Adresa</label>
        </div>
        <div class='form-group'>
            <input type='text' name='phone' id='phone' value='{{ Request::old("phone") ?: $member->phone }}'>
            <label for='phone'>Kontakt broj</label>
        </div>
        <div class='form-group2'>
            <span>Spol</span>&nbsp;
            <input type='radio' name='sex' id='sex-m' value='M' {{ $member->sex == 'M' ? "checked='checked'" : '' }}><label for='sex-m'>Muško</label>&nbsp;
            <input type='radio' name='sex' id='sex-f' value='F' {{ $member->sex == 'F' ? "checked='checked'" : '' }}><label for='sex-f'>Žensko</label>
        </div>
        <div class='form-group2'>
            <label for='active'>Član je aktivan</label>
            <input type='checkbox' name='active' id='active' {{ $member->active ? "checked='checked'" : '' }}>
        </div>
        {{ csrf_field() }}
        <input class='form-btn' type='submit' value='Uredi'>
    </form>
    @include('partials._errors')
</div>
@stop
