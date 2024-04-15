<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <h1>sales Channel create</h1>
    <form action="/saleschannels" method="POST" >
        @csrf
        <ul>
            <li>
                <label for="name">name:</label>
                <input type="text" id="name" name="name" required>
            </li>
            <li>
                <select name="type" required>
                    <option></option>
                    <option value="WooCommerce">WooCommerce</option>
                </select>
            </li>
            <li>
                <label for="url">url:</label>
                <input type="text" id="url" name="url">
            </li>
            <li>
                <label for="flavicon_url">flavicon url:</label>
                <input type="text" id="flavicon_url" name="flavicon_url">
            </li>
            <li>
                <label for="api_key">api key:</label>
                <input type="text" name="api_key">
            </li>
            <li>
                <label for="secret">secret:</label>
                <input type="text" id="secret" name="secret">
            </li>
            <li>
                <input type="submit" id="save" value="save">
            </li>
        </ul>
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