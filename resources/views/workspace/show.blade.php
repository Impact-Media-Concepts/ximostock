@extends('layouts.app')

@section('content')
    <h1>{{ $workspace->name }}</h1>
    <p>Created at: {{ $workspace->created_at }}</p>
    <p>Updated at: {{ $workspace->updated_at }}</p>
    <a href="{{ route('workspaces.index') }}">Back to List</a>
@endsection