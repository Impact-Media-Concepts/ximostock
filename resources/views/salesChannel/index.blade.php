<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <h1>sales Channels</h1>
    <form action="saleschannels/bulkdelete" method="POST">
        @csrf
        <ul>
            @foreach ($salesChannels as $salesChannel)
                <li>
                    <p>
                        <input name="saleschannels[]" type="checkbox" value="{{$salesChannel->id}}">
                        {{ $salesChannel->id }}
                        {{ $salesChannel->name }}
                        {{ $salesChannel->channel_type }}
                    </p>
                </li>
            @endforeach
            <li>
                <input type="submit" value="bulk delete">
            </li>
        </ul>
    </form>
</body>

</html>
