@extends('app')

@section('title'){{ 'Promjena lozinke' }}@stop

@section('content')
<div class='section container'>
    <form action='{{ route("auth.changepassword") }}' method='POST' autocomplete='off'>
        <div class='field'>
            <label for='old' class='label'>Stara lozinka</label>
            <div class='control has-icons-left'>
                <input type='password' class='input' name='old' id='old' autofocus>
                <span class='icon is-small is-left'><i class='fas fa-key'></i></span>
            </div>
        </div>
        <div class='field'>
            <label for='new' class='label'>Nova lozinka</label>
            <div class='control has-icons-left'>
                <input type='password' class='input' name='new' id='new'>
                <span class='icon is-small is-left'><i class='fas fa-key'></i></span>
            </div>
        </div>
        <input type='submit' value='Promijeni lozinku' class='button'>
        {{ csrf_field() }}
    </form>
    <br>
    @include('partials._errors')
</div>
@stop
