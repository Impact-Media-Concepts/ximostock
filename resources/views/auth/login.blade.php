@extends('layouts.guest')

@php
    $content = [
        'title' => "Inloggen",
        'description' => "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et",
        'button' => "Inloggen",
        'logo' => asset('/images/ximostock-logo.png'),
    ];
@endphp

@section('content')

<div class="container" id="login-form">
    <div class="wrapper">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <user-login :content='@json($content)'></user-login>
        </form>
    </div>
</div>

@endsection
