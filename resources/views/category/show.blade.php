<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>

    <form action="/categories/{{$category->id}}" method="POST">
        @csrf
        @method("PATCH")
        <input type="text" name="name" value="{{ $category->name }}">
        <input type="submit" value="update">
        @if ($errors->any())
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </form>

    <h3>
        @if ($category->parent_category != null)
            parent category: {{ $category->parent_category->name }}
        @endif
    </h3>
    <ul>
        @foreach ($category->child_categories as $child)
            <li>
                {{ $child->name }}
            </li>
        @endforeach
    </ul>
    
</body>

</html>
