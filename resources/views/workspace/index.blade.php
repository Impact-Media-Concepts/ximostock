@extends('layouts.app')

@section('content')
    <h1>Workspaces</h1>
    <a href="{{ route('workspaces.create') }}">Create New Workspace</a>
    <ul>
        @foreach ($workspaces as $workspace)
            <li>{{ $workspace->name }}
                <a href="{{ route('workspaces.edit', $workspace->id) }}">Edit</a>
                <form action="{{ route('workspaces.destroy', $workspace->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection