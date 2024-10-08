@extends('layouts.app')

@php
    $icons= [
        'trash' => file_get_contents('images/trash-icon.svg'),
        'save' => file_get_contents('images/save-icon.svg'),
        'close' => file_get_contents('images/close-icon.svg'),
        'chevron' => file_get_contents('images/chevron-down-dark.svg'),
        'warning' => file_get_contents('images/warning-icon.svg'),
        'property' => file_get_contents('images/property-icon.svg'),
    ];
@endphp
@section('content')
<div id="app">
    
</div>
@endsection

