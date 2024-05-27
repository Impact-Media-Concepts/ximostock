
    

<x-layout._layout :sidenavActive="$sidenavActive" :activeWorkspace="$activeWorkspace" :workspaces="$workspaces">
    <strong>
        {{$location->name}}
    </strong>
    <ul>
        @foreach ($location->location_zones as $zone)
            <li>
                {{$zone->name}}
            </li>
        @endforeach
    </ul>
</x-layout._layout>
