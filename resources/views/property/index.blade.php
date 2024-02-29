<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <h1>TEST properties</h1>
    <ul>
        @foreach ($properties as $property)
            <li>
                {{'name: '.$property->name.'  type: '. $property->type }}
                <a href="/properties/{{$property->id}}">property</a>
            </li>
        @endforeach
    </ul>

</body>

</html>
