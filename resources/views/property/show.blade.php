<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <h1>property</h1>
    <h4>
        {{ $property->name . '  ' . $property->type }}
    </h4>

    @if ($property->type == 'multiselect' || $property->type == 'singleselect')
        <h2>options</h2>
        <ul>
            @foreach ($property->options as $option)
                <li>
                    {{ $option }}
                </li>
            @endforeach
        </ul>
    @endif
</body>

</html>
