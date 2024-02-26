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
        {{$product->title}}
    </h1>
    <h4>
        {{$product->ean . '  ' . $product->sku}}
    </h4>
    <p>
        {{$product->short_description}}
    </p>
    <h2>primary category {{$product->primaryCategory->name}}</h2>
    @foreach ($product->photos as $photo)
        <img src="{{$photo->url}}" width="200" height="200"/>
    @endforeach
    <ul>
        @foreach ($product->properties as $property)
        <li>
            {{$property->name . ': ' . $property->pivot->property_value->value }}
        </li>
        @endforeach
    </ul>
    <ul>
        @foreach ($product->childProducts as $child)
            <li>
                <strong>
                    {{$child->title.'  '.$child->sku.'  '.$child->ean}}
                </strong>
                <ul>
                    @foreach ($child->decodedProps as $prop)
                        <li>
                            {{$prop['name'].'   '. $prop['value']}}
                        </li>
                    @endforeach
                </ul>
            </li>

        @endforeach
    </ul>
</body>
</html>