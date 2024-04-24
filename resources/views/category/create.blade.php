<x-layout._header-dependencies :sidenavActive="$sidenavActive" />

<body class="flex bg-[#F3F4F8] text-[#717171] text-[14px]" style="font-family: 'Inter', sans-serif;">
<x-header.header :activeWorkspace="$activeWorkspace" :workspaces="$workspaces"/>
    <div class="flex h-full pt-20 w-full gap-[1.9rem]">
        <div class="h-full">
            <x-sidenav.sidenav :activeWorkspace="$activeWorkspace" :sidenavActive="$sidenavActive"/>
        </div>
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
    </div>
</body>

<x-layout._footer-dependencies />
<script type="text/javascript" src="{{ asset('./assets/js/product/navbar.js') }}"></script>

</html>
