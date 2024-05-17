<x-layout._header-dependencies :sidenavActive="$sidenavActive" />

<body class="flex bg-[#F3F4F8] text-[#717171] text-[14px]" style="font-family: 'Inter', sans-serif; height:67.5rem">
    <x-header.header :activeWorkspace="$activeWorkspace" :workspaces="$workspaces"/>
    <div class="flex h-full pt-20 w-full gap-[1.9rem]">
        <div class="h-full">
            <x-sidenav.sidenav :activeWorkspace="$activeWorkspace" :sidenavActive="$sidenavActive"/>
        </div>
        <div class="pt-20">
            <x-category.categories :categories="$categories" />
        </div>
    </div>
</body>

<script type="text/javascript" src="{{ asset('./assets/js/product/navbar.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/header-button-data.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/show-pop-ups.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/container-bulk-actions.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/single-product-bulk-action.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/discount-values.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/sales-channels.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/collect-filters.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/manage-bulk-action-form.js') }}"></script>

</html>
