<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="/locations" method="POST">
        @csrf
        <h1>create location zones</h1>
        <ul>
            <li>
                name:<input type="text" name="name">
            </li>
            <li>
                zone: <input type="text" name="zones[]">
            </li>
            <li>
                zone: <input type="text" name="zones[]">
            </li>
            <li>
                <input type="submit" value="enter">
            </li>
        </ul>
    </form>
</body>
</html>