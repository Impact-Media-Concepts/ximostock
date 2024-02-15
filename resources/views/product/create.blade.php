<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>stufff</h1>
    <form method="POST" action="/products">
        @csrf

        <label for="title">titel:</label>
        <input type="text" name="title" id="title" value="{{old('title')}}">

        <label for="sku">Artikelnummer:</label>
        <input type="text" name="sku" id="sku" value="{{old('sku')}}">

        <label for="ean">EAN: </label>
        <input type="number" name="ean" id="ean" value="{{old('ean')}}">

        <label for="short_description"> korte beschrijving:</label>
        <input type="text" name="short_description" id="short_description" value="{{old('short_description')}}">

        <label for="long_description"> lange beschrijving:</label>
        <input type="text" name="long_description" id="long_description" value="{{old('long_description')}}">

        <label for="price">price: </label>
        <input type="number" name="price" id="price" value="{{old('price')}}">

        <input type="submit" value="Submit"></input>
    </form>
</body>
</html>