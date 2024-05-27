
<x-layout._layout :sidenavActive="$sidenavActive" :activeWorkspace="$activeWorkspace" :workspaces="$workspaces">
    <h1>users test</h1>
    <ul>
        @foreach ($users as $user)
        <li>
            <a href="/users/{{$user->id}}?workspace={{$activeWorkspace}}">
                <strong>{{$user->name}}</strong> {{$user->email .'  '}} {{isset($user->workspace->name) ? $user->workspace->name : ''}}
            </a>
        </li>
        @endforeach
    </ul>
</x-layout._layout>
