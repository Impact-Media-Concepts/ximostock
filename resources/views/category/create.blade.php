<x-layout._layout :sidenavActive="$sidenavActive" :activeWorkspace="$activeWorkspace" :workspaces="$workspaces">
    <div class="pt-20">
        <form action="/categories" method="POST">
            @csrf
            <label for="name">name:</label>
            <input type="text" id="name" name="name" />
            <label for="parent_category_id">parent category:</label>
            <input type="number" id="parent_category_id" name="parent_category_id">
            @if ($activeWorkspace)
                <input type="hidden" name="work_space_id" value="{{ $activeWorkspace }}">
            @endif
            <input type="submit" value="submit">
        </form>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</x-layout._layout>
