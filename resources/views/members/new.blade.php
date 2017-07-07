@extends('app')

@section('title'){{ 'Novi član' }}@stop
@section('description'){{ 'Royal Gym Brezje - Unos novog člana' }}@stop

@section('content')
<div class='container'>
    <h1>Novi član</h1>
    <form action='{{ route("members.new") }}' method='POST' autocomplete='off'>
        <label for='name'>Ime</label>
        <input type='text' name='name' id='name'><br>
        <label for='address'>Adresa</label>
        <input type='text' name='address' id='address'><br>
        <label for='phone'>Kontakt broj</label>
        <input type='text' name='phone' id='phone'><br>
        <label for='joined_at'>Pridružio se</label>
        <input type='date' name='joined_at' id='joined_at'><br>
        <label for='active'>Član je aktivan</label>
        <input type='checkbox' name='active' id='active' checked='checked'><br>
        {{ csrf_field() }}
        <input type='submit' value='Kreiraj'>
    </form>
    @include('partials._errors')
</div>
@stop
