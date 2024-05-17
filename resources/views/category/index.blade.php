<x-layout._layout :sidenavActive="$sidenavActive" :activeWorkspace="$activeWorkspace" :workspaces="$workspaces">
    <div class="pt-20">
        <x-category.categories :categories="$categories" />
    </div>
</x-layout._layout>
