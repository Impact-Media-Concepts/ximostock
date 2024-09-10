@extends('layouts.app')

@section('content')
    <user-overview :users='@json($users)' :roles='@json($roles)'></user-overview>
@endsection