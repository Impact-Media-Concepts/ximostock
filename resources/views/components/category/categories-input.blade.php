@props(['categories'])

<ul>
    @foreach ($categories as $category)
        <li>
            <input type="checkbox" id="category_{{ $category->id }}" name="categories[]" value="{{ $category->id }}">
            <label for="category_{{ $category->id }}">{{ $category->name }}</label>
            <x-category.categories-input :categories="$category->child_categories_recursive" />
        </li>
    @endforeach
</ul>
