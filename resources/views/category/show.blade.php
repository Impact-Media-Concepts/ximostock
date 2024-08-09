@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $category->name }}</h1>
        <p>{{ $category->description }}</p>
        <h2>Products</h2>
        <ul>
            @foreach($category->products as $product)
                <li>{{ $product->name }}</li>
            @endforeach
        </ul>
    </div>
@endsection