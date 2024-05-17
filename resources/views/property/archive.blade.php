<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="/properties/forcedelete" method="POST">
        @csrf
        
        <ul>
            @foreach ($properties as $property)
            <li>
                <input name="properties[]" id="property_checkbox_{{$property->id}}" type="checkbox" value="{{$property->id}}">
                <label for="property_checkbox_{{$property->id}}">{{$property->name}}</label>
            </li>
            @endforeach
            <input type="submit" value="restore">
        </ul>
    </form>
</body>
</html>