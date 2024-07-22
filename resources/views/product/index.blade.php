@extends('layouts.app')

@section('content')
<div id="app">
    <product-overview
        :initial-products="{{ json_encode($products) }}"
        :initial-categories="{{ json_encode($categories) }}">
    </product-overview>
</div>
@endsection
