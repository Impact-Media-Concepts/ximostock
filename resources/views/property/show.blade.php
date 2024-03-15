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
    <form action="/properties/{{$property->id}}" method="POST">
        @csrf
        @method('PATCH')
        <h4>
            <input type="text" name="name"  value="{{$property->name}}"/>
        </h4>

        @if ($property->type == 'multiselect' || $property->type == 'singleselect')
            <h2>options</h2>
            <ul>
                @foreach ($property->options as $option)
                    <li>
                        <input type="text" name="options[]" value="{{ $option }}">
                    </li>
                @endforeach
            </ul>
        @endif
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
</body>

</html>
