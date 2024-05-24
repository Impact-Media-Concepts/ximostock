@props(['products', 'orderBy'])
<!-- TODO:  Arrown down, items move position when arrow changes from subheader tab-->
<!-- TODO:  Items needs to stay in the same place-->

<div class="bg-white w-full h-[3.55rem] flex justify-start items-center basic:gap-[4.6rem] hd:gap-[4.5rem] uhd:gap-[6rem] shd:gap-[5.9rem]"
    style="border-bottom: 1px solid #0000001A; font-family: 'Inter', sans-serif;">
    <input id="bulkActionsCheckboxSubheader" class="h-[0.87rem] flex justify-start items-center w-[0.87rem] ml-[1.55rem] mt-1 cursor-pointer" type="checkbox" name="product_ids[]"
        value="" />
    <div class="flex items-center pt-[0.08rem]">
        <div class="flex relative basic:gap-[7rem] basic:mr-[2rem] hd:mr-[8.05rem] hd:h-[0.6rem] hd:gap-[18.5rem] uhd:w-full uhd:gap-[25.4rem] uhd:mr-[14.85rem] shd:gap-[40rem] shd:mr-[20.8rem]">
            <x-product.product-sub-header-tab :orderBy="$orderBy" orderId="Name" text="Naam" />
            <x-product.product-sub-header-tab :orderBy="$orderBy" orderId="SKU" text="SKU" />
        </div>
        
        <div class="basic:mr-[2rem] hd:mr-[7.4rem] uhd:mr-[14.85rem] shd:mr-[21.3rem] flex justify-center items-center w-[2.75] cursor-pointer">
            <x-product.product-sub-header-tab class="mt-[0.12rem]" :orderBy="$orderBy" orderId="Price" text="Prijs" />
        </div>
        
        <div class="basic:mr-[2rem] hd:mr-[5.9rem] uhd:mr-[11.4rem] shd:mr-[24.3rem]">
            <x-product.product-sub-header-tab :orderBy="$orderBy" orderId="Stock" class="w-6" text="Voorraad" />
        </div>
        
        <div class="basic:mr-[2rem] hd:mr-[4.4rem] uhd:mr-[10rem] shd:mr-[23.3rem]">
            <x-product.product-sub-header-tab :orderBy="$orderBy" orderId="Sold" text="Verkocht" />
        </div>
        
        <div class="basic:mr-[2rem] hd:mr-[4.8rem] uhd:mr-[11.5rem] shd:mr-[24.4rem]">
            <x-product.product-sub-header-tab :orderBy="$orderBy" orderId="Status" text="Status" />
        </div>
        
        <div class="uhd:w-full flex uhd:gap-[10.8rem]">
            <x-product.product-sub-header-tab :orderBy="$orderBy" orderId="UpdatedAt" text="Gewijzigd" />
        </div>
    </div>
</div>
