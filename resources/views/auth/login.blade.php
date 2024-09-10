@extends('layouts.guest')

@php
    $content = [
        'title' => "Inloggen",
        'description' => "Welkom bij Ximo Stock – Beheer eenvoudig en efficiënt jouw voorraad met ons gebruiksvriendelijke systeem. Log in om je inventaris up-to-date te houden en altijd inzicht te hebben in je producten.",
        'button' => "Inloggen",
        'logo' => '/images/ximostock-logo.png',
        'resetPassword' => route('reset-password'),
        'status' => session('status'),
    ];
@endphp

@section('content')

<div class="container" id="form">
    <div class="wrapper">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <user-login :content='@json($content)'></user-login>
        </form>
    </div>
</div>

@endsection
