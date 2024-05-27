<x-layout._layout :sidenavActive="$sidenavActive" :activeWorkspace="$activeWorkspace" :workspaces="$workspaces">
    <form action="/properties/forcedelete" method="POST">
        @csrf
        
        <ul>
            @foreach ($properties as $property)
            <li>
                <input name="properties[]" id="property_checkbox_{{$property->id}}" type="checkbox" value="{{$property->id}}">
                <label for="property_checkbox_{{$property->id}}">{{$property->name}}</label>
            </li>
            @endforeach
            <input type="submit" value="restore">
        </ul>
    </form>
</x-layout._layout>
