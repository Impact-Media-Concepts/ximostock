@extends('layouts.app')

@php
    $icons= [
        'trash' => file_get_contents('images/trash-icon.svg'),
        'save' => file_get_contents('images/save-icon.svg'),
        'close' => file_get_contents('images/close-icon.svg'),
        'chevron' => file_get_contents('images/chevron-down-dark.svg'),
        'supplier' => file_get_contents('images/supplier-icon.svg'),
        'warning' => file_get_contents('images/warning-icon.svg'),
    ];
@endphp
@section('content')
<div id="app">
    <supplier-overview
        :suppliers='@json($suppliers)'
        :icons='@json($icons)'
        :order='@json($order)'
        :orderby='@json($orderby)'
    ></supplier-overview>
</div>
@endsection
