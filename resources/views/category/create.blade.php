<x-layout._layout :sidenavActive="$sidenavActive" :activeWorkspace="$activeWorkspace" :workspaces="$workspaces">
    <div class="pt-20">
        <form action="/categories" method="POST">
            @csrf
            <label for="name" >name:</label>
            <input type="text" id="name" name="name"/>
            <label for="parent_category_id">parent category:</label>
            <input type="number" id="parent_category_id" name="parent_category_id">
            <input type="submit" value="submit">
        </form>
    </div>
</x-layout._layout>
