<x-layout._header-dependencies :sidenavActive="$sidenavActive" />

<body class="flex bg-[#F3F4F8] text-[#717171] text-[14px]" style="font-family: 'Inter', sans-serif; height:67.5rem">
    <x-header.header/>
    <div class="flex h-full pt-20 w-full gap-[1.9rem]">
        <div class="h-full">
            <x-sidenav.sidenav :sidenavActive="$sidenavActive"/>
        </div>
        <div class="pt-20">
            <x-category.categories :categories="$categories" />
        </div>
    </div>
</body>

<x-layout._footer-dependencies /> 
<script type="text/javascript" src="{{ asset('./assets/js/product/navbar.js') }}"></script>

</html>
