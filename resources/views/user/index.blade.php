@extends('layouts.app')

@section('content')

    <user-overview :users='@json($users)'></user-overview>
    
@endsection