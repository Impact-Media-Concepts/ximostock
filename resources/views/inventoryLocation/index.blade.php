<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <ul>
        <h1>locations</h1>
        @foreach ($locations as $location)
            <li>
                <strong>{{$location->name}}</strong> 
                <ul>
                    @foreach ($location->location_zones as $zone)
                        <li>
                            {{$zone->name}}
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
</body>
</html>