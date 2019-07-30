@extends('app')

@section('title'){{ 'Prijava' }}@stop

@section('content')
<div class='section container'>
    <form action='{{ route("login") }}' method='POST'>
        <div class='field'>
            <label for='email' class='label'>Email</label>
            <div class='control has-icons-left'>
                <input type='text' class='input' name='email' id='email' placeholder='you@somewhere.com' value='{{ old("email", "") }}' autofocus>
                <span class='icon is-small is-left'><i class='fas fa-user'></i></span>
            </div>
        </div>
        <div class='field'>
            <label for='password' class='label'>Lozinka</label>
            <div class='control has-icons-left'>
                <input type='password' class='input' name='password' id='password'>
                <span class='icon is-small is-left'><i class='fas fa-key'></i></span>
            </div>
        </div>
        <input type='submit' value='Prijavi se' class='button'>
        {{ csrf_field() }}
    </form>
    <br>
    @include('partials._errors')
</div>
@stop
