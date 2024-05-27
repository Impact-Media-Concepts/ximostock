<x-layout._layout :sidenavActive="$sidenavActive" :activeWorkspace="$activeWorkspace" :workspaces="$workspaces">
    <form action="/categories/{{ $category->id }}" method="POST">
        @csrf
        @method('PATCH')
        <input type="text" name="name" value="{{ $category->name }}">
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


        <h3>
            parent category: {{ $category->parent_category->name ?? ''}}
            
            <input type="number" name="parent_category_id" value="{{ $category->parent_category->id ?? '' }}">

        </h3>
        <ul>
            @foreach ($category->child_categories as $child)
                <li>
                    {{ $child->name }}
                </li>
            @endforeach
        </ul>
    </form>
    <form method="POST" action="/categories/{{$category->id}}">
        @csrf
        @method('DELETE')
        <input type="submit" value="delete">
    </form>
</x-layout._layout>
