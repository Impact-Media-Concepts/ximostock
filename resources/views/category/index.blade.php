@extends('layouts.app')

@section('content')

@foreach ($categoriesTree as $category)
    <div>
        {{ $category->name }}
        @if (!empty($category->children))
            @include('category.partials.subcategories', ['subcategories' => $category->children])
        @endif
    </div>
@endforeach


@endsection