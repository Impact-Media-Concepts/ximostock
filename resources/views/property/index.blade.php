<x-layout._layout :sidenavActive="$sidenavActive" :activeWorkspace="$activeWorkspace" :workspaces="$workspaces">
    <div class="pt-20">
        <form action="/properties/bulkdelete" method="POST">
            @csrf
            <ul>
                @foreach ($properties as $property)
                    <li>
                        <input type="checkbox" name="properties[]" value="{{$property->id}}"/>
                        {{ 'name: ' . $property->name . '  type: ' . $property->type }}
                        <a href="/properties/{{ $property->id }}">property</a>
                    </li>
                @endforeach
            </ul>
            @if ($errors->any())
                <div>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <input type="submit" value="bulk delete"/>
        </form>
    </div>
</x-layout._layout>
