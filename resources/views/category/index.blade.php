<x-layout._header-dependencies />

<body class="flex bg-[#F3F4F8] text-[#717171] text-[14px]" style="font-family: 'Inter', sans-serif; height:67.5rem">
    <x-sidenav.sidenav :sidenavActive="$sidenavActive"/>
    <h1>All Categories</h1>
    <x-category.categories :categories="$categories" />

</body>

</html>
