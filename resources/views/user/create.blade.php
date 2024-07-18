@extends('layouts.app')

@section('content')

    <h1>Create User</h1>
    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <add-user :roles='@json($roles)' ></add-user>

    </form>

@endsection