<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>product</title>
</head>

<body>
    <h1>
        {{ $product->title }}
    </h1>
    <h4>
        {{ $product->ean . '  ' . $product->sku }}

    </h4>
    <h4>
        Primary category: {{ $product->primaryCategory?->name ?? 'none' }}
    </h4>


    <p>
        {{ $product->short_description }}
    </p>
    {{-- <h2>primary category {{ $product->primaryCategory->name }}</h2> --}}
    @foreach ($product->photos as $photo)
        <img src="{{ $photo->url }}" width="200" height="200" />
    @endforeach
    <ul>
        @foreach ($product->properties as $property)
            <li>
                {{ $property->name . ': ' . $property->pivot->property_value->value }}
            </li>
        @endforeach
    </ul>
    <ul>
        @foreach ($product->childProducts as $child)
            <li>
                <strong>
                    {{ $child->title . '  ' . $child->sku . '  ' . $child->ean }}
                </strong>
                <ul>
                    @foreach ($child->decodedProps as $prop)
                        <li>
                            {{ $prop['name'] . '   ' . $prop['value'] }}
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
    <h1>edit product</h1>
    <form action="/products/{{ $product->id }}" method="POST">
        @csrf
        @method('PATCH')
        <ul>
            <li>
                <label for="title">title: </label>
                <input type="text" value="{{ $product->title }}" name="title">
            </li>
            <li>
                <label for="sku">articel nummer: </label>
                <input type="text" value="{{ $product->sku }}" name="sku">
            </li>
            <li>
                <label for="ean">EAN: </label>
                <input type="text" value="{{ $product->ean }}" name="ean">
            </li>
            <li>
                <label for="price">prijs: </label>
                <input type="number" step="0.01" value="{{ $product->price == null ? '0.00' : $product->price }}"
                    name="price" id="price">
            </li>
            <li>
                <label for="discount">actieprijs: </label>
                <input type="number" step="0.01" value="{{ $product->discount }}" name="discount" id="discount">
            </li>
            <li>
                <label for="short_description">Short Description:</label><br>
                <textarea id="short_description" name="short_description" rows="10" cols="80">{{ $product->short_description }}</textarea><br><br>
            </li>
            <li>
                <label for="long_description">Long Description:</label><br>
                <textarea id="long_description" name="long_description" rows="10" cols="80">{{ $product->long_description }}</textarea><br><br>
            </li>
            <li>
                <input type="checkbox" id="backorders" {{ $product->backorders ? 'checked' : '' }} name="backorders"
                    value="1">
                <label for="backorders">enable backorders</label>
            </li>
            <li>
                <input type="checkbox" id="communicate_stock" {{ $product->communicate_stock ? 'checked' : '' }}
                    name="communicate_stock" value="1">
                <label for="communicate_stock">communicate stock</label>
            </li>
            <li>
                <x-product.show.category-checkbox-list :categories="$categories" :checkedCategories="$product->categories" />
            </li>
            <li>
                SalesChannels:
                <ul>
                    @foreach ($salesChannels as $salesChannel)
                        <li>
                            <input type="checkbox" name="salesChannelIds[]" value="{{ $salesChannel->id }}"
                                id="salesChannel{{ $salesChannel->id }}"
                                {{ $selectedSalesChannels->contains('sales_channel_id', $salesChannel->id) ? 'checked' : '' }} />
                            <label for="salesChannel{{ $salesChannel->id }}">{{ $salesChannel->name }}</label>
                            @if ($selectedSalesChannels->contains('sales_channel_id', $salesChannel->id))
                                <ul>
                                    <li>
                                        <label for="salesChannels{{ $salesChannel->id }}title">title:</label>
                                        <input type="text"
                                            value="{{ $selectedSalesChannels->firstWhere('sales_channel_id', $salesChannel->id)->title }}"
                                            name="salesChannels[{{ $salesChannel->id }}][title]"
                                            id="salesChannels{{ $salesChannel->id }}title">
                                    </li>
                                    <li>
                                        <label for="salesChannels{{ $salesChannel->id }}price">price:</label>
                                        <input type="number" step="0.01"
                                            value="{{ $selectedSalesChannels->firstWhere('sales_channel_id', $salesChannel->id)->price }}"
                                            name="salesChannels[{{ $salesChannel->id }}][price]"
                                            id="salesChannels{{ $salesChannel->id }}price">
                                    </li>
                                    <li>
                                        <label for="salesChannel{{ $salesChannel->id }}short_description">Short
                                            Description:</label><br>
                                        <textarea name="salesChannels[{{ $salesChannel->id }}][short_description]"
                                            id="salesChannel{{ $salesChannel->id }}short_description" name="short_description"
                                             rows="4" cols="80">{{ $selectedSalesChannels->firstWhere('sales_channel_id', $salesChannel->id)->short_description }}</textarea><br><br>
                                    </li>
                                    <li>
                                        <label for="salesChannel{{ $salesChannel->id }}long_description">Long
                                            Description:</label><br>
                                        <textarea name="salesChannels[{{ $salesChannel->id }}][long_description]"
                                            id="salesChannel{{ $salesChannel->id }}long_description" 
                                            name="long_description" rows="4" cols="80">{{ $selectedSalesChannels->firstWhere('sales_channel_id', $salesChannel->id)->long_description }}</textarea><br><br>
                                    </li>
                                </ul>
                            @endif

                        </li>
                    @endforeach
                </ul>
            </li>
        </ul>

        @if ($errors->any())
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <input type="submit" value="enter" />
    </form>
    <a href="/products">terug</a>

</body>

</html>
