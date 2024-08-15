@extends('layouts.app')

@section('content')
@php
    $icons= [
        'trash' => file_get_contents('images/trash-icon.svg'),
        'save' => file_get_contents('images/save-icon.svg'),
        'close' => file_get_contents('images/close-icon.svg'),
    ];
@endphp
<div id="app">
    <location-overview
        :icons='@json($icons)'
        :locations='@json($locations)'
    ></location-overview>
</div>
@endsection
