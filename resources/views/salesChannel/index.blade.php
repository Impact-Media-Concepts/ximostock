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
    <ul>
        @foreach ($salesChannels as $salesChannel)
        <li>
            {{$salesChannel->name}}
        </li>
        @endforeach
    </ul>
   
</body>
</html>