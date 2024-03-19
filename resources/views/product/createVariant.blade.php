<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <form method="POST" action="/products/variant" enctype="multipart/form-data">
        @csrf
        <h3>hoofd product</h3>
        <ul>
            <li>
                <label for="title">titel:</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}">
            </li>
            <li>
                <label for="short_description"> korte beschrijving:</label>
                <input type="text" name="short_description" id="short_description"
                    value="{{ old('short_description') }}">
            </li>
            <li>
                <label for="long_description"> lange beschrijving:</label>
                <input type="text" name="long_description" id="long_description"
                    value="{{ old('long_description') }}">
            </li>
            <li>
                <label for="price">price: </label>
                <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}">
            </li>
        </ul>
        <h3>set hoodfproduct eigenschappen</h3>
        @foreach ($properties as $property)
            @if ($property->values->type == 'text')
                <div>
                    <label for="property_{{ $property->id }}">{{ $property->name }}</label>
                    <input type="text" id="property_{{ $property->id }}" name="properties[{{ $property->id }}]">
                </div>
            @endif
        @endforeach
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
            <input type="file" id="primaryPhoto" value="{{old('primaryPhoto')}}" name="primaryPhoto" />
        </div>

        <ul>
            <li><input type="file" id="photos1" value="{{old('photos[1]')}}" name="photos[]" /></li>
            <li><input type="file" id="photos2"value="{{old('photos[2]')}}" name="photos[]" /></li>
        </ul>
        <div>
            <h3>Select Categories:</h3>
            <ul>
                <x-category.categories-input :categories="$categories"/>
            </ul>
            <label for='primaryCategory'>primaryCategory</label>
            <input type="number" id="primaryCategory" name="primaryCategory" />
        </div>
        <div>
            <h3>sales channels</h3>
            <ul>
                @foreach ($salesChannels as $channel)
                    <li>
                        <label for="salesChannel[{{ $channel->id }}]">{{ $channel->name }}</label>
                        <input type="checkbox" name="salesChannels[]" value="{{ $channel->id }}" id="salesChannel[{{ $channel->id }}]">
                    </li>
                @endforeach
            </ul>
        </div>
        <h3>variant 1</h3>
        <label for="variants[1]property_id">property id</label>
        <input type="number" id="variants[1]property_id[1]" name="variants[1][property_id][1]">
        <label for="variants[1]property_value">property value</label>
        <input type="text" id="variants[1]property_value[1]" name="variants[1][property_value][1]">
        <label for="variants[1]sku">artikel nummer</label>
        <input type="text" id="variants[1]sku" name="variants[1][sku]">
        <label for="variants[1]ean">EAN:</label>
        <input type="text" id="variants[1]ean" name="variants[1][ean]">
        <label for="variants[1]price">price: </label>
        <input type="number" step="0.01" value="0.00" name="variants[1][price]" id="variants[1]price">
        <div>
            <h3>voorraden</h3>
            <label>
                {{ $locations[0]->location_zones[0]->name }}
            </label>
            <input type="number" name="variants[1][location_zones][{{ $locations[0]->location_zones[0]->id }}]">
            <label>
                {{ $locations[0]->location_zones[1]->name }}
            </label>
            <input type="number" name="variants[1][location_zones][{{ $locations[0]->location_zones[1]->id }}]">
        </div>

        <h3>variant 2</h3>
        <label for="variants[2]property_id">property id</label>
        <input type="number" id="variants[2]property_id[1]" name="variants[2][property_id][1]">
        <label for="variants[2]property_value">property value</label>
        <input type="text" id="variants[2]property_value[1]" name="variants[2][property_value][1]">
        <label for="variants[2]sku">artikel nummer</label>
        <input type="text" id="variants[2]sku" name="variants[2][sku]">
        <label for="variants[2]ean">EAN:</label>
        <input type="text" id="variants[2]ean" name="variants[2][ean]">
        <label for="variants[2]price">price: </label>
        <input type="number" step="0.01" value="0.00" name="variants[2][price]" id="variant[2]price">
        <div>
            <h3>voorraden</h3>
            <label for="{{ $locations[0]->location_zones[0]->id }}">
                {{ $locations[0]->location_zones[0]->name }}
            </label>
            <input type="number" name="variants[2][location_zones][{{ $locations[0]->location_zones[0]->id }}]">
            <label for="{{ $locations[0]->location_zones[1]->id }}">
                {{ $locations[0]->location_zones[1]->name }}
            </label>
            <input type="number" name="variants[2][location_zones][{{ $locations[1]->location_zones[1]->id }}]">
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
