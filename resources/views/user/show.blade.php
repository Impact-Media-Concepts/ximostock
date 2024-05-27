<x-layout._layout :sidenavActive="$sidenavActive" :activeWorkspace="$activeWorkspace" :workspaces="$workspaces">
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
</x-layout._layout>
