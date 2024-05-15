<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>{{$user->name}}</h1>
    <ul>
        <li>
            role: {{$user->role}}
        </li>
        <li>
            workspace: {{isset($user->workspace->name) ? $user->workspace->name : 'none'}}
        </li>
        <li>
            e-mail: {{$user->email}}
        </li>
        <form action="/users/{{$user->id}}" method="POST">
            @csrf
            @method('DELETE')
            <input type="submit" value="delete">
        </form>
    </ul>
</body>
</html>