@extends('layouts.app')

@section('content')
    <main-product-info :product='@json($product)'></main-product-info>
@endsection