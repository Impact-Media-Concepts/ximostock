{{-- Dependencies for the page --}}
<x-layout._header-dependencies />

<body class="flex bg-[#F3F4F8] text-[#717171] text-[14px]" style="font-family: 'Inter', sans-serif;">
    {{-- univseral header --}}
    <x-layout._sidenav-header />

    {{-- Product container with smaller component, passes down perPage and products to component --}}
    <x-product.product-container :perPage="$perPage" :products="$products" />


    {{-- cate/eigenschappen container --}}
    <div class="w-[16.56rem] h-[57rem] bg-white rounded-md flex my-[7rem] flex-col" style="padding: 20px 20px; gap: 20px">
        {{-- categories component --}}
        <x-product.categories.product-categories :categories="$categories" />
        {{-- eigenschappen/properties component --}}
        <!-- <x-product.product-properties :categories="$categories"/> -->
    </div>
</body>

</html>
