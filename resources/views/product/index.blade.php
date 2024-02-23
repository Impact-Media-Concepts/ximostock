<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>products</title>
</head>

<body>
    <h1>product page</h1>
    <form action="{{ route('products.bulkDelete') }}" method="POST">
        @csrf
        <ul>
            @foreach ($products as $product)
                <li>
                    <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" />
                    {{ $product->title }}
                    <img src="{{ $product->primaryPhoto->url }}" width="200" height="200" />
                    {{ $product->sku . ' €' . $product->price . ' voorraad:' . $product->stock . '  Verkocht:' . $product->sales . '   laatst aangepast:' . $product->updated_at->diffForHumans() }}
                    @if ($product->online)
                        <strong>online</strong>
                    @else
                        <strong>offline</strong>
                    @endif
                    @if ($product->concept)
                        <strong>Concept</strong>
                    @endif
                </li>
            @endforeach
        </ul>
        <button type="submit">Delete Selected Products</button>
    </form>
    {{-- categories --}}

    @foreach ($categories as $category)
        @if ($category->parent_category == null)
            <h1>
                {{ $category->name }}
            </h1>
            <ul>
                @foreach ($category->child_categories as $child)
                    <li>
                        {{ $child->name }}
                    </li>
                @endforeach
            </ul>
        @endif
    @endforeach

    <h1>eigenschappen/filters</h1>
    <ul>
        @foreach ($properties as $property)
            <li>
                {{ $property->name . '  ' . $property->values->type }}
                @if ($property->values->type == 'multiselect' || $property->values->type == 'singelselect')
                    <ol>
                        @foreach ($property->values->options as $option)
                            <li>
                                {{ $option }}
                            </li>
                        @endforeach
                    </ol>
                @endif
            </li>
        @endforeach
    </ul>
</body>

</html>
