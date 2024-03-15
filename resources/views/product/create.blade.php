<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <h1>Create Simple Product</h1>
    <form method="POST" action="/products" enctype="multipart/form-data">
        @csrf

        <label for="title">titel:</label>
        <input type="text" name="title" id="title" value="{{ old('title') }}">

        <label for="sku">Artikelnummer:</label>
        <input type="text" name="sku" id="sku" value="{{ old('sku') }}">

        <label for="ean">EAN: </label>
        <input type="number" name="ean" id="ean" value="{{ old('ean') }}">

        <label for="short_description"> korte beschrijving:</label>
        <input type="text" name="short_description" id="short_description" value="{{ old('short_description') }}">

        <label for="long_description"> lange beschrijving:</label>
        <input type="text" name="long_description" id="long_description" value="{{ old('long_description') }}">

        <label for="price">price: </label>
        <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}">

        <div>
            <h3>Select Categories:</h3>
            <x-category.categories-input :categories="$categories" />

            <label for='primaryCategory'>primaryCategory</label>
            <input type="number" id="primaryCategory" value="{{ old('primaryCategory') }}" name="primaryCategory" />
        </div>

        <ul>
            <li>
                <label for="backorders">backorders</label>
                <input type="number" id="backorders" value="0" name="backorders">
            </li>
            <li>
                <label for="communicate_stock">communicate stock</label>
                <input type="number" checked id="communicate_stock" value="1" name="communicate_stock">
            </li>
        </ul>

        {{-- Photos --}}
        <div>
            <label for="primaryPhoto">primary Photo</label>
            <input type="file" id="primaryPhoto" name="primaryPhoto" />
        </div>

        <ul>
            <li><input type="file" id="photos1" name="photos[]" /></li>
            <li><input type="file" id="photos2" name="photos[]" /></li>
        </ul>

        <div>
            <h3>set eigenschappen</h3>
            @foreach ($properties as $property)
                @if ($property->values->type == 'text')
                    <div>
                        <label for="property_{{ $property->id }}">{{ $property->name }}</label>
                        <input type="text" id="property_{{ $property->id }}" name="properties[{{ $property->id }}]">
                    </div>
                @endif
            @endforeach
        </div>

        <div>
            <h3>voorraden</h3>
            <label for="{{ $locations[0]->location_zones[0]->id }}">
                {{ $locations[0]->location_zones[0]->name }}
            </label>
            <input type="number" name="location_zones[{{ $locations[0]->location_zones[0]->id }}]">
            <label for="{{ $locations[0]->location_zones[1]->id }}">
                {{ $locations[0]->location_zones[1]->name }}
            </label>
            <input type="number" name="location_zones[{{ $locations[0]->location_zones[1]->id }}]">
        </div>

        <div>
            <h3>sales channels</h3>
            <ul>
                @foreach ($salesChannels as $channel)
                    <li>
                        <label for="salesChannel[{{ $channel->id }}]">{{ $channel->name }}</label>
                        <input type="checkbox" name="salesChannels[]" value="{{ $channel->id }}"
                            id="salesChannel[{{ $channel->id }}]">
                    </li>
                @endforeach
            </ul>
        </div>

        @if ($errors->any())
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <input type="submit" value="Submit"></input>
    </form>

</body>

</html>
