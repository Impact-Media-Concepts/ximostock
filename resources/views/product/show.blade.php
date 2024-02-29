<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>product</title>
</head>

<body>
    <h1>
        {{ $product->title }}
    </h1>
    <h4>
        {{ $product->ean . '  ' . $product->sku }}
    </h4>
    <p>
        {{ $product->short_description }}
    </p>
    <h2>primary category {{ $product->primaryCategory->name }}</h2>
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
                <label for="price">actieprijs: </label>
                <input type="number" step="0.01" value="{{ $product->price == null ? '0.00' : $product->price }}"
                    name="price" id="price">
            </li>
            <li>
                <label for="discount">actieprijs: </label>
                <input type="number" step="0.01"
                    value="{{ $product->discount == null ? '0.00' : $product->discount }}" name="discount"
                    id="discount">
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
                <input type="checkbox" id="enable_backorders" {{($product->backorders) ? 'checked' : ''}} name="enable_backorders" value="1">
                <label for="enable_backorders">enable backorders</label>
            </li>
            <li>
                <input type="checkbox" id="communicate_stock" {{($product->communicate_stock) ? 'checked' : ''}} name="communicate_stock" value="1">
                <label for="communicate_stock">communicate stock</label>
            </li>

        </ul>

        <input type="submit" value="enter" />
    </form>
</body>

</html>
