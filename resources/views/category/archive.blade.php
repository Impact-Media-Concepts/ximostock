<x-layout._layout :sidenavActive="$sidenavActive" :activeWorkspace="$activeWorkspace" :workspaces="$workspaces">
    <form action="/categories/restore" method="POST">
        @csrf
        <ul>
            @foreach ($categories as $category)
            <li>
                <input name="categories[]" id="category_checkbox_{{$category->id}}" type="checkbox" value="{{$category->id}}">
                <label for="category_checkbox_{{$category->id}}">{{$category->name}}</label>
            </li>
            @endforeach
            <input type="submit" value="restore">
        </ul>
    </form>
</x-layout._layout>
