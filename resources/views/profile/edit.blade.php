@extends('layouts.app')

@section('content')

    <edit-user :admin='@json($admin)' :roles='@json($roles)' :user='@json($currentUser)'></edit-user>

    
    <theme-configurator :user='@json($currentUser)'></theme-configurator>


@endsection