<x-layout._layout :sidenavActive="$sidenavActive" :activeWorkspace="$activeWorkspace" :workspaces="$workspaces">
    <h1>property</h1>
    <form action="/properties/{{$property->id}}" method="POST">
        @csrf
        @method('PATCH')
        <h4>
            <input type="text" name="name"  value="{{$property->name}}"/>
        </h4>
        
        @if ($property->type == 'multiselect' || $property->type == 'singleselect')
            <h2>options</h2>
            <ul>
                @foreach ($property->options as $option)
                    <li>
                        <input type="text" name="options[]" value="{{ $option }}">
                    </li>
                @endforeach
            </ul>
        @endif
        <input type="submit" value="update">
        @if ($errors->any())
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </form>
</x-layout._layout>
