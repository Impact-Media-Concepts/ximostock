<x-layout._layout :sidenavActive="$sidenavActive" :activeWorkspace="$activeWorkspace" :workspaces="$workspaces">
    <div class="flex w-full pr-10 pt-[1.95rem] gap-8">
        <div class="flex justify-start items-start w-full"> 
            <x-product.product-container :activeWorkspace="$activeWorkspace" :salesChannels="$salesChannels" :discountError="$discountErrors" :orderBy="$orderBy" :perPage="$perPage" :products="$products" />
        </div>
        
        <div class="flex justify-center items-start">
            <div class="w-[16.56rem] bg-white rounded-md basic:h-[49rem] hd:h-[61rem] uhd:h-[71.2rem]" style="padding: 20px 20px;">
                <form id="searchForm" method="GET" action="/products">
                    <div class="w-full flex justify-end items-center">
                        <button type="submit" @click="open = !open;"
                            class="hover:bg-[#3999BE] duration-100 flex items-center z-20 w-[8] px-[1.08rem] h-[2.3rem] text-sm font-light text-gray-700 bg-[#3dabd5] bottom-[0.05rem] border-1 border-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#3DABD5] focus:ring-offset-2 focus:ring-offset-gray-100"
                            style="border: 1px solid white" @click.away="open = false">
                            <span class="pl-[0.2rem] text-[14px] text-white">Zoeken</span>
                        </button>
                    </div>
                    
                    <div class="flex-col flex basic:gap-0 uhd:gap-[5rem]">
                        <!-- <input type="submit" value="search test"> -->
                        {{-- categories component --}}
                        <x-product.categories.product-categories :categories="$categories" :checkedCategories="$selectedCategories" />
                        {{-- eigenschappen/properties component --}}
                        <input type="hidden" name="search" id="productSearchInput" value="{{$search}}">
                        <input type="hidden" name="orderByInput" id="orderByInput" value="{{$orderBy}}">
                        
                        <x-product.properties.properties :properties="$properties" :selectedProperties="$selectedProperties"/>
                    <div>
                </form>
            </div>
        </div>
    </div>
</x-layout._layout>

<script type="text/javascript" src="{{ asset('./assets/js/product/header-button-data.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/side-nav.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/show-pop-ups.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/container-bulk-actions.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/single-product-bulk-action.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/discount-values.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/sales-channels.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/collect-filters.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/manage-bulk-action-form.js') }}"></script>
