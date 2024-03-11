<x-layout._header-dependencies />

<body class="flex bg-[#F3F4F8] text-[#717171] text-[14px]" style="font-family: 'Inter', sans-serif; height:60.5rem">
    <x-layout._sidenav-header />
    <x-product.product-container :perPage="$perPage" :products="$products" />

    <div class="w-[16.56rem] h-[57rem] bg-white rounded-md flex my-[7rem] flex-col" style="padding: 20px 20px; gap: 20px">
        <x-product.categories.product-categories :categories="$categories" />
        <x-product.product-properties />
    </div>
</body>

</html>
