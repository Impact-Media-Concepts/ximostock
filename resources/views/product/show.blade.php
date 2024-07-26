@extends('layouts.app')

@section('content')
    <single-product :categories='@json($unrelatedCategories)' :product='@json($product)'></single-product>
@endsection