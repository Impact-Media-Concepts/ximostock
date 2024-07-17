@extends('layouts.app')

@section('content')
    <h1>Edit Workspace</h1>
    <form action="{{ route('workspaces.update', $workspace->id) }}" method="POST">
        @csrf
        @method('patch')
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="{{ $workspace->name }}" required>
        <button type="submit">Update</button>
    </form>
@endsection
