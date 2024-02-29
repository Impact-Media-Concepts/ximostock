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
                    <a href="/products/{{ $product->id }}">
                        {{ $product->title }}
                        <img src="{{ $product->primaryPhoto->url }}" width="200" height="200" />
                        {{ $product->sku . ' voorraad:' . $product->stock . '  Verkocht:' . $product->sales . '   laatst aangepast:' . $product->updated_at->diffForHumans() }}
                        @if ($product->discount != null)
                            <del>
                                {{ ' €' . $product->price }}
                            </del>
                            {{ '€' . $product->discount }}
                        @else{
                            {{ ' €' . $product->price }}
                            }
                        @endif
                        @if ($product->sales_channels_exists)
                            <strong>online</strong>
                        @else
                            <strong>offline</strong>
                        @endif
                        @if ($product->concept)
                            <strong>Concept</strong>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
        <button type="submit">Delete Selected Products</button>
    </form>

    {{-- Test Bulk discount --}}
    <h2>discount test</h2>
    <form action="{{route('products.bulkDiscount')}}" method="POST">
        @csrf
        <ul>
            @foreach ($products as $product)
                <li>
                    <input type="checkbox" name="product_ids[{{ $product->id }}][discount]" value='17.00'>
                    {{$product->title}}
                </li>
            @endforeach
        </ul>
        <input type="submit" value="discount selected products">
        @if ($errors->any())
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </form>
    <h2>test bulk link verkoop kanaal</h2>
    <form action="{{route('products.bulkLinkSalesChannel')}}" method="POST">
        @csrf
        <h3>products</h3>
        <ul>
            @foreach ($products as $product)
                <li>
                    <input type="checkbox" name="product_ids[]" value='{{ $product->id }}'>
                    {{$product->title}}
                </li>
            @endforeach
        </ul>
        <h3>sales channels</h3>
        <ul>
            @foreach ($sales_channels as $sales_channel)
                <li>
                    <input type="checkbox" name="sales_channel_ids[]" value='{{ $sales_channel->id }}'>
                    {{$sales_channel->name}}
                </li>
            @endforeach
        </ul>
        <input type="submit" value="bulk link saleschannels">
    </form>
    
    {{-- test --}}
    <h2>test bulk unlink verkoop kanaal</h2>
    <form action="{{route('products.bulkUnlinkSalesChannel')}}" method="POST">
        @csrf
        <h3>products</h3>
        <ul>
            @foreach ($products as $product)
                <li>
                    <input type="checkbox" name="product_ids[]" value='{{ $product->id }}'>
                    {{$product->title}}
                </li>
            @endforeach
        </ul>
        <h3>sales channels</h3>
        <ul>
            @foreach ($sales_channels as $sales_channel)
                <li>
                    <input type="checkbox" name="sales_channel_ids[]" value='{{ $sales_channel->id }}'>
                    {{$sales_channel->name}}
                </li>
            @endforeach
        </ul>
        <input type="submit" value="bulk unlink saleschannels">
    </form>

    {{-- categories --}}
    <x-categories :categories="$categories" />

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
