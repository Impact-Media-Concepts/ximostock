@props(['products'])

<div class="bg-white w-full h-[3.55rem] flex justify-start items-center normal:gap-[4.7rem]"
    style="border-bottom: 1px solid #0000001A; font-family: 'Inter', sans-serif;">
    <input id="bulkActionsCheckboxSubheader" class="h-[0.87rem] flex justify-start items-center w-[0.87rem] ml-[1.55rem] mt-1 cursor-pointer" type="checkbox" name="product_ids[]"
        value="" />
    <div class="flex items-center big:w-full">
        <div class="flex relative normal:mr-[7.75rem] normal:h-[0.6rem] normal:gap-[17.5rem] big:w-full big:gap-[25.5rem] big:mr-[13.55rem] enormous:gap-[60rem] enormous:mr-[20rem]">
            <x-product.product-sub-header-tab :product="$products" text="Naam" />
            <x-product.product-sub-header-tab :product="$products" text="SKU" />
        </div>

        <div class="normal:mr-[5.3rem] big:mr-[13.55rem] enormous:mr-[20rem] flex justify-center items-center w-[2.75] h-[1.06] cursor-pointer">
            <x-product.product-sub-header-tab :product="$products" class="mt-[0.12rem]" text="Prijs" />
        </div>

        <div class="normal:mr-[3.85rem] big:w-full flex big:gap-[9.75rem]">
            <x-product.product-sub-header-tab :product="$products" text="Voorraad" />
        </div>
        <div class="normal:mr-[3.35rem] big:w-full flex big:gap-[9.75rem]">
            <x-product.product-sub-header-tab :product="$products" text="Verkocht" />
        </div>

        <div class="normal:mr-[3.85rem] big:w-full flex big:gap-[10.8rem]">
            <x-product.product-sub-header-tab :product="$products" text="Status" />
        </div>

        <div class="big:w-full flex big:gap-[10.8rem]">
            <x-product.product-sub-header-tab :product="$products" text="Gewijzigd" />
        </div>
    </div>
</div>
