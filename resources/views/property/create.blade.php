<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ximostock</title>
</head>
<body>
    <h1>TEST property create</h1>
    <form action="/properties" method="POST">
        @csrf
        <label for="propName">naam:</label>
        <input type="text" id="propName" name="name">
        <label for="propType">type:</label>
        <input type="text" id="propType" name="type">
        <ul id="propOptions">
            <li>
                <label for="option1">option</label>
                <input type="text" placeholder="option name..." name="options[]">
                <button>-</button>
            </li>
            <li>
                <label for="option1">option</label>
                <input type="text" placeholder="option name..." name="options[]">
                <button>-</button>
            </li>
            <li>
                <label for="option2">option</label>
                <input type="text" placeholder="option name..." name="options[]">
                <button>-</button>
                <button>+</button>
            </li>
        </ul>
    </form>
    <script>

    </script>
</body>
</html>