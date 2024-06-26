@props(['categories', 'checkedCategories' => null])

<?php
$category_ids = $checkedCategories ? $checkedCategories : [];
?>

@foreach ($categories as $category)
    {
        id: {{ $category->id }},
        
        name: '{{ $category->name }}',
        
        checked: {{ in_array($category->id, $category_ids) ? 'true' : 'false' }},
        
        subcategories:[
        
        <x-product.categories.product-categories-data :categories="$category->child_categories_recursive" :checkedCategories="$checkedCategories" />
        
        ],
    },
@endforeach
