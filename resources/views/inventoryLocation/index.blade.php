<x-layout._layout :sidenavActive="$sidenavActive" :activeWorkspace="$activeWorkspace" :workspaces="$workspaces">
    <ul>
        <h1>locations</h1>
        @foreach ($locations as $location)
            <li>
                <strong>{{$location->name}}</strong> 
                <ul>
                    @foreach ($location->location_zones as $zone)
                        <li>
                            {{$zone->name}}
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
</x-layout._layout>
