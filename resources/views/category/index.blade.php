<x-layout._header-dependencies />

<body class="flex bg-[#F3F4F8] text-[#717171] text-[14px]" style="font-family: 'Inter', sans-serif; height:67.5rem">
    <x-sidenav.sidenav :sidenavActive="$sidenavActive"/>
    <h1>All Categories</h1>
    <x-category.categories :categories="$categories" />

</body>

<x-layout._footer-dependencies /> 
<script type="text/javascript" src="{{ asset('./assets/js/product/navbar.js') }}"></script>

</html>
