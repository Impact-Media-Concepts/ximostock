@props(['categories'])
<ul>
    @foreach ($categories as $category)
        <li>
            <strong>{{ $category->name.'  '.$category->productTotal}}</strong>
            <x-categories :categories="$category->child_categories"/>
        </li>
    @endforeach
</ul>
