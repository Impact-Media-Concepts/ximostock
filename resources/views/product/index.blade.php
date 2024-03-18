{{-- Dependencies for the page --}}
<x-layout._header-dependencies />

<body class="bg-[#F3F4F8] text-[#717171] text-[14px]" style="font-family: 'Inter', sans-serif;">

    
    <x-header.header/>
    <div class="flex gap-8 relative top-20">
        <div><x-sidenav.sidenav /></div>
        <div class="flex justify-center items-center" style="width: 1257px; background: white;"> <x-product.product-container :perPage="$perPage" :products="$products" /></div>
        <div class="w-[16.56rem] h-[57rem] bg-white rounded-md flex my-[7rem] flex-col" style="padding: 20px 20px; gap: 20px">
            {{-- categories component --}}
            <x-product.categories.product-categories :categories="$categories" />
            {{-- eigenschappen/properties component --}}
            <!-- <x-product.product-properties :categories="$categories"/> -->
        </div>
     
    </div>

</body>

<x-layout._footer-dependencies /> 
<script type="text/javascript" src="{{ asset('./assets/js/product/header-button-data.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/navbar.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/bulk-actions.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/container-bulk-actions.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/single-product-bulk-action.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/discount-values.js') }}"></script>

</html>
