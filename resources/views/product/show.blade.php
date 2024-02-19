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
    <p>
        {{$product->short_description}}
    </p>
    <h2>primary category {{$product->primaryCategory->name}}</h2>
    <img src="{{$product->primaryPhoto->url}}" width="200" height="200"/>
    
</body>
</html>