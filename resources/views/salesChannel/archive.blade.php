<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="/saleschannels/forcedelete" method="POST">
        @csrf

        <ul>
            @foreach ($salesChannels as $salesChannel)
            <li>
                <input name="saleschannels[]" id="salesChannel_checkbox_{{$salesChannel->id}}" type="checkbox" value="{{$salesChannel->id}}">
                <label for="salesChannel_checkbox_{{$salesChannel->id}}">{{$salesChannel->name}}</label>
            </li>
            @endforeach
            <input type="submit" value="restore">
        </ul>
    </form>
</body>
</html>