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
    <single-product
        :categories='@json($unrelatedCategories)'
        :product='@json($product)'
        :eigenschappen='@json($propertyTypes)'
        :properties='@json($properties)'
        :icons='@json($icons)'
    ></single-product>
@endsection
