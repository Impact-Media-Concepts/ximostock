<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @foreach ($categories as $category)
        @if ($category->parent_category == null)
            <h1>
                {{$category->name}}
            </h1>
            <ul>
                @foreach ($category->child_categories as $child)
                <li>
                    {{$child->name}}
                </li>
                @endforeach
            </ul>
            
        @endif
    @endforeach
</body>
</html>