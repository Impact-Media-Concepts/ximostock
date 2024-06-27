@extends('layouts.app')

@section('content')
    <increment-counter :content="{{ json_encode($lorem) }}"></increment-counter>
@endsection