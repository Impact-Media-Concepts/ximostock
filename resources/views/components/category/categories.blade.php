@props(['categories'])
<ul>
    @foreach ($categories as $category)
        <li class="ml-12">
            <strong>{{ $category->name }}</strong>
            @if ($category->child_categories_recursive->isNotEmpty())
                <x-category.categories :categories="$category->child_categories_recursive" />
            @endif
        </li>
    @endforeach
</ul>
