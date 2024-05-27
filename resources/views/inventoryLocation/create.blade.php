<x-layout._layout :sidenavActive="$sidenavActive" :activeWorkspace="$activeWorkspace" :workspaces="$workspaces">
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
</x-layout._layout>
