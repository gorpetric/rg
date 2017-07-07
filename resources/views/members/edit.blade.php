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
        <label for='name'>* Ime</label>
        <input type='text' name='name' id='name' value='{{ Request::old("name") ?: $member->name }}'><br>
        <label for='address'>Adresa</label>
        <input type='text' name='address' id='address' value='{{ Request::old("address") ?: $member->address }}'><br>
        <label for='phone'>Kontakt broj</label>
        <input type='text' name='phone' id='phone' value='{{ Request::old("phone") ?: $member->phone }}'><br>
        <label for='active'>Član je aktivan</label>
        <input type='checkbox' name='active' id='active' {{ $member->active ? "checked='checked'" : '' }}><br>
        {{ csrf_field() }}
        <input type='submit' value='Uredi'>
    </form>
    @include('partials._errors')
</div>
@stop
