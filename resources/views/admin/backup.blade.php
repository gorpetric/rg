@extends('app')

@section('title'){{ 'Admin - Backup' }}@stop
@section('description'){{ 'Royal Gym Brezje - Admin - Backup area' }}@stop

@section('content')
<div class='section container content'>
    <h1 class='title is-3'>Backup</h1>
    <p><a href='{{ route("admin.backup.create") }}'>Create DB backup</a></p>
    @if(!count($backups))
        <p>No backups!</p>
    @else
        <div class='table-container'>
            <table class='table is-bordered is-striped is-hoverable is-fullwidth'>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Size</th>
                        <th>Download</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($backups as $backup)
                        <tr>
                            <td>{{ $backup['name'] }}</td>
                            <td>{{ $backup['size'] }}</td>
                            <td><a href='{{ route("admin.backup.download", $backup["name"]) }}'>Download</a></td>
                            <td><a href='{{ route("admin.backup.delete", $backup["name"]) }}'>Delete</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@stop
