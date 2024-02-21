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
        <input type="number" name="price" id="price" value="{{ old('price') }}">

        <div>
            <h3>Select Categories:</h3>
            <ul>
                @foreach ($categories as $category)
                    @if ($category->parent_category == null)
                        <li>
                            <input type="checkbox" id="category_{{ $category->id }}" name="categories[]"
                                value="{{ $category->id }}">
                            <label for="category_{{ $category->id }}">{{ $category->name }}</label>
                        </li>

                        <ul>
                            @foreach ($category->child_categories as $child)
                                <li>
                                    <input type="checkbox" id="category_{{ $child->id }}" name="categories[]"
                                        value="{{ $child->id }}">
                                    <label for="category_{{ $child->id }}">{{ $child->name }}</label>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @endforeach
            </ul>
            <label for='primaryCategory'>primaryCategory</label>
            <input type="number" id="primaryCategory" name="primaryCategory" />
        </div>

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
                        <input type="text" id="property_{{ $property->id }}"
                            name="properties[{{ $property->id }}]">
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

        <input type="submit" value="Submit"></input>
    </form>

</body>

</script>

</html>
