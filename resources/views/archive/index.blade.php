@extends('layouts.app')
@php
    $icons= [
            'trash' => file_get_contents('images/trash-icon.svg'),
            'save' => file_get_contents('images/save-icon.svg'),
            'close' => file_get_contents('images/close-icon.svg'),
            'chevron' => file_get_contents('images/chevron-down-dark.svg'),
            'warning' => file_get_contents('images/warning-icon.svg'),
        ];
    
@endphp
@section('content')

    <archive-overview
        :items='@json($items)'
        :icons='@json($icons)'
        :order='@json($order)'
        :orderby='@json($orderby)'
        :types='@json($types)'
        ></archive-overview>
@endsection
