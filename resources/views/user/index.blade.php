<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ximostock</title>
</head>
<body>
    <h1>users test</h1>
    <ul>
        @foreach ($users as $user)
        <li>
            <a href="/users/{{$user->id}}">
                <strong>{{$user->name}}</strong> {{$user->email .'  '}} {{isset($user->workspace->name) ? $user->workspace->name : ''}}
            </a>
        </li>
        @endforeach
    </ul>
</body>
</html>