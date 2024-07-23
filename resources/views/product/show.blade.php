@extends('layouts.app')

@section('content')
    <single-product :product='@json($product)'></single-product>
@endsection