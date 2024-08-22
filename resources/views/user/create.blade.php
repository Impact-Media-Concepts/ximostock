@extends('layouts.app')

@section('content')
    <h1>Create User</h1>
    <add-user :roles='@json($roles)' ></add-user>
@endsection