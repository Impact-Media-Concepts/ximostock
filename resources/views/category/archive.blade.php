<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="/categories/restore" method="POST">
        @csrf

        <ul>
            @foreach ($categories as $category)
            <li>
                <input name="categories[]" id="category_checkbox_{{$category->id}}" type="checkbox" value="{{$category->id}}">
                <label for="category_checkbox_{{$category->id}}">{{$category->name}}</label>
            </li>
            @endforeach
            <input type="submit" value="restore">
        </ul>
    </form>
</body>
</html>