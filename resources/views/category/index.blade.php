@extends('layouts.app')

@section('content')

    {{-- @if (!empty($categoriesTree))
        @foreach ($categoriesTree as $category)
            <div>
                {{ $category->name }}
                @if (!empty($category->children))
                    @include('category.partials.subcategories', ['subcategories' => $category->children])
                @endif
            </div>
        @endforeach
    @else
        <p>No categories found</p>
    @endif --}}
    <category-overview :categories='@json($categoriesTree)'></category-overview>

@endsection
