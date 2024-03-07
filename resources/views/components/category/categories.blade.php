@props(['categories'])
<ul>
    @foreach ($categories as $category)
        <li>
            <strong>{{ $category->name . '  ' . Count($category->products) }}</strong>
            @if ($category->child_categories->isNotEmpty())
                <x-category.categories :categories="$category->child_categories" />
            @endif
        </li>
    @endforeach
</ul>
