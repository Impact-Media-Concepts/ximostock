@extends('layouts.guest')

@php
    $content = [
        'title' => "Wachtwoord veranderen?",
        'description' => "Wachtwoord veranderen? Geen probleem, bij Ximostock zorgen we ervoor dat je snel en veilig een nieuw wachtwoord kunt instellen. Voer je e-mailadres, je huidige wachtwoord en het nieuwe wachtwoord in om door te gaan.",
        'button' => "Wachtwoord instellen",
        'logo' => asset('/images/ximostock-logo.png'),
        'logout' => route('logout'),
        'status' => session('status'),
    ];
@endphp

@section('content')

<div class="container" id="form">
    <div class="wrapper">
        <form method="POST" action="{{ route('update-password') }}">
            @csrf
            <reset-password :content='@json($content)'></reset-password>
        </form>
    </div>
</div>

@endsection
