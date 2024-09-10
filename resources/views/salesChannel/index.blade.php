@extends('layouts.app')

@section('content')
@php
    $icons= [
            'trash' => file_get_contents('images/trash-icon.svg'),
            'save' => file_get_contents('images/save-icon.svg'),
            'close' => file_get_contents('images/close-icon.svg'),
            'chevron' => file_get_contents('images/chevron-down-dark.svg'),
            'warning' => file_get_contents('images/warning-icon.svg'),
        ];
@endphp
<div id="app">
    <saleschannel-overview
        :saleschannels='@json($saleschannels)'
        :icons='@json($icons)'
        :orderby='@json($orderby)'
        :order='@json($order)'
    ></saleschannel-overview>
</div>
@endsection
