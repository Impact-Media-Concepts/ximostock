@extends('layouts.app')

@php
    $icons = [
        'trash' => file_get_contents('images/trash-icon.svg'),
        'save' => file_get_contents('images/save-icon.svg'),
        'close' => file_get_contents('images/close-icon.svg'),
        'chevron' => file_get_contents('images/chevron-down-dark.svg'),
        'supplier' => file_get_contents('images/supplier-icon.svg'),
        'warning' => file_get_contents('images/warning-icon.svg'),
    ];
@endphp

@section('content')

    <workspace-overview :user='@json($currentUser)' :environments='@json($workspaces)' :icons='@json($icons)'></workspace-overview>
    
@endsection