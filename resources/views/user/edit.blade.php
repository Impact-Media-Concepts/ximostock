@extends('layouts.app')

@section('content')

    <edit-user :user='@json($user)'></edit-user>

@endsection
