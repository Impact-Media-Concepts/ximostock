<ul>
    @foreach ($childCategories as $childCategory)
        <li>{{ $childCategory->name . '   ' . $childCategory->productTotal}}</li>
        @if ($childCategory->child_categories->count() > 0)
            @include('partials.child_categories', ['childCategories' => $childCategory->child_categories])
        @endif
    @endforeach
</ul>
