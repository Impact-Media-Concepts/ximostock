{{-- Dependencies for the page --}}
<x-layout._header-dependencies :sidenavActive="$sidenavActive" />

<body class="bg-[#F3F4F8] text-[#717171] text-[14px]" style="font-family: 'Inter', sans-serif;">
    <x-header.header :search="$search" />
    <div class="flex h-full pt-20 w-full gap-[1.9rem]">
        <div class="h-full">
            <x-sidenav.sidenav :sidenavActive="$sidenavActive" />
        </div>

        <div class="flex w-full h-full pr-10 py-[1.95rem] gap-8">
            <div class="flex justify-start items-start w-full h-full"> <x-product.product-container :orderBy="$orderBy" :perPage="$perPage" :products="$products" /></div>
            <div class="flex justify-center items-start">
                <div class="w-[16.56rem] h-full bg-white rounded-md" style="padding: 20px 20px; gap: 20px">
                    <form id="searchForm" method="GET" action="/products">
                        {{-- categories component --}}
                        <x-product.categories.product-categories :categories="$categories" :checkedCategories="$selectedCategories" />
                        {{-- eigenschappen/properties component --}}
                        <!-- <x-product.product-properties :categories="$categories"/> -->
                        <input type="hidden" name="search" id="productSearchInput" value="{{$search}}">
                        <input type="hidden" name="orderByInput" id="orderByInput" value="{{$orderBy}}">
                        <input type="submit" value="search test">
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

<x-layout._footer-dependencies />
<script type="text/javascript" src="{{ asset('./assets/js/product/header-button-data.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/navbar.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/show-pop-ups.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/container-bulk-actions.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/single-product-bulk-action.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/discount-values.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/collect-filters.js') }}"></script>
</html>
