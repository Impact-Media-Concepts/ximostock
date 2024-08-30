@extends('layouts.app')

@section('content')
    <single-product
        :categories='@json($unrelatedCategories)' 
        :product='@json($product)'
        :eigenschappen='@json($propertyTypes)'>
    </single-product>
@endsection