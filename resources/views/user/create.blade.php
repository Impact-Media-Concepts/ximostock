<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <form action="/users" method="POST">
        @csrf
        <ul>
            <li>
                name:<input type="text" name='name'>
            </li>
            <li>
                <select name="role" id="">
                    <option value=""></option>
                    <option value="manager">manager</option>
                    <option value="supplier">supplier</option>
                    <option value="admin">admin</option>
                </select>
            </li>
            <li>
                email:<input type="email" name="email">
            </li>
            <li>
                <select required name="workspace" id="">
                    <option value=""></option>
                    @foreach ($workspaces as $workspace)
                        <option value="{{$workspace->id}}">{{$workspace->name}}</option>
                    @endforeach
                </select>
            </li>
            <li>
                <input type="submit" value="create">
            </li>
        </ul>
    </form>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

</body>
</html>