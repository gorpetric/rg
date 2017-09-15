@extends('app')

@section('title'){{ 'Admin - Backup' }}@stop
@section('description'){{ 'Royal Gym Brezje - Admin - Backup area' }}@stop

@section('content')
<div class='container'>
    <h1>Backup</h1>
    <p><a href='{{ route("admin.backup.create") }}'>Create DB backup</a></p>
    @if(!count($backups))
        <p>No backups!</p>
    @else
        @foreach($backups as $backup)
            <p>{{ $backup['name'] }} - {{ $backup['size'] }} - <a href='{{ route("admin.backup.download", $backup["name"]) }}'>Download</a> - <a href='{{ route("admin.backup.delete", $backup["name"]) }}'>Delete</a></p>
        @endforeach
    @endif
</div>
@stop
