@extends('app')

@section('title'){{ 'Novi član' }}@stop
@section('description'){{ 'Royal Gym Brezje - Unos novog člana' }}@stop

@section('content')
<div class='container'>
    <p>
        <a href='{{ route("members.index") }}'>Članovi</a>
    </p>
    <h1>Novi član</h1>
    <form action='{{ route("members.new") }}' method='POST' autocomplete='off'>
        <div class='form-group'>
            <input type='text' name='name' id='name' value='{{ Request::old("name") ?: '' }}'>
            <label for='name'>* Ime</label>
        </div>
        <div class='form-group'>
            <input type='text' name='address' id='address' value='{{ Request::old("address") ?: '' }}'>
            <label for='address'>Adresa</label>
        </div>
        <div class='form-group'>
            <input type='text' name='phone' id='phone' value='{{ Request::old("phone") ?: '' }}'>
            <label for='phone'>Kontakt broj</label>
        </div>
        <div class='form-group2'>
            <span>Spol</span>&nbsp;
            <input type='radio' name='sex' id='sex-m' value='M' checked='checked'><label for='sex-m'>Muško</label>&nbsp;
            <input type='radio' name='sex' id='sex-f' value='F'><label for='sex-f'>Žensko</label>
        </div>
        <div class='form-group2'>
            <label for='joined_at'>* Pridružio se</label>
            <input type='date' name='joined_at' id='joined_at'>
        </div>
        <div class='form-group2'>
            <label for='active'>Član je aktivan</label>&nbsp;
            <input type='checkbox' name='active' id='active' checked='checked'>
        </div>
        {{ csrf_field() }}
        <input class='form-btn' type='submit' value='Kreiraj'>
    </form>
    @include('partials._errors')
</div>
@stop
