<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="/products/restore" method="POST">
        @csrf

        <ul>
            @foreach ($products as $product)
            <li>
                <input name="products[]" id="product_checkbox_{{$product->id}}" type="checkbox" value="{{$product->id}}">
                <label for="product_checkbox_{{$product->id}}">{{$product->title}}</label>
            </li>
            @endforeach
            <input type="submit" value="delete">
        </ul>
    </form>
</body>
</html>