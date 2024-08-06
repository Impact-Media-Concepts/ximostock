@extends('layouts.app')

@section('content')
    <theme-configurator :user='@json($user)'></theme-configurator>
@endsection