@props(['categories'])

@foreach ($categories as $category)
    {
    id: {{ $category->id }},
    name: '{{ $category->name }}',
    checked: false,
    subcategories:[
        <x-product.show.category-data :categories="$category->child_categories"/>
    ]
    },
@endforeach
