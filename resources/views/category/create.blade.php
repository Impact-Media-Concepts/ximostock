<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ximostock</title>
</head>
<body>
    <h2>new category</h2>
    <form action="/categories" method="POST">
        @csrf
        <label for="name" >name:</label>
        <input type="text" id="name" name="name"/>
        <label for="parent_category_id">parent category:</label>
        <input type="number" id="parent_category_id" name="parent_category_id">
        <input type="submit" value="submit">
    </form>
</body>
</html>