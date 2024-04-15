<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <h1>saleschannel show</h1>
    <form action="">
        <ul>
            <li>
                <label for="name">name:</label>
                <input  type="text" id="name" name="name" value="{{$salesChannel->name}}" required>
            </li>
            <li>
                <select name="type" required>
                    <option></option>
                    <option selected value="WooCommerce">WooCommerce</option>
                </select>
            </li>
            <li>
                <label for="url">url:</label>
                <input type="text" value="{{$salesChannel->url}}" id="url" name="url">
            </li>
            <li>
                <label for="flavicon_url">flavicon url:</label>
                <input type="text" id="flavicon_url" value="{{$salesChannel->flavicon_url}}" name="flavicon_url">
            </li>
            <li>
                <label for="api_key">api key:</label>
                <input type="text" value="{{$salesChannel->api_key}}" name="api_key">
            </li>
            <li>
                <label for="secret">secret:</label>
                <input type="text" id="secret" value="{{$salesChannel->secret}}" name="secret">
            </li>
            <li>
                <input type="submit" id="save" value="save">
            </li>
        </ul>
    </form>
    
    @if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</body>

</html>
