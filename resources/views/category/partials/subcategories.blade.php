@foreach ($subcategories as $subcategory)
    <div style="margin-left: 20px;">
        {{ $subcategory->name }}
        @if (!empty($subcategory->children))
            @include('category.partials.subcategories', ['subcategories' => $subcategory->children])
        @endif
    </div>
@endforeach