@props(['products'])

<div class="bg-white w-full h-[3.55rem] flex justify-start items-center laptop:gap-[4.6rem] normal:gap-[4.5rem] big:gap-[6rem] enormous:gap-[5.9rem]"
    style="border-bottom: 1px solid #0000001A; font-family: 'Inter', sans-serif;">
    <input id="bulkActionsCheckboxSubheader" class="h-[0.87rem] flex justify-start items-center w-[0.87rem] ml-[1.55rem] mt-1 cursor-pointer" type="checkbox" name="product_ids[]"
        value="" />
    <div class="flex items-center pt-[0.08rem]">
        <div class="flex relative laptop:gap-[7rem] laptop:mr-[2rem] normal:mr-[8rem] normal:h-[0.6rem] normal:gap-[17.5rem] big:w-full big:gap-[25.4rem] big:mr-[14.2rem] enormous:gap-[40rem] enormous:mr-[20rem]">
            <x-product.product-sub-header-tab :product="$products" text="Naam" />
            <x-product.product-sub-header-tab :product="$products" text="SKU" />
        </div>

        <div class="laptop:mr-[2rem] normal:mr-[5.3rem] big:mr-[13.7rem] enormous:mr-[20.2rem] flex justify-center items-center w-[2.75] cursor-pointer">
            <x-product.product-sub-header-tab :product="$products" class="mt-[0.12rem]" text="Prijs" />
        </div>

        <div class="laptop:mr-[2rem] normal:mr-[3.85rem] big:mr-[10.2rem] enormous:mr-[23rem]">
            <x-product.product-sub-header-tab :product="$products" text="Voorraad" />
        </div>
        <div class="laptop:mr-[2rem] normal:mr-[3.35rem] big:mr-[9.7rem] enormous:mr-[22.8rem]">
            <x-product.product-sub-header-tab :product="$products" text="Verkocht" />
        </div>

        <div class="laptop:mr-[2rem] normal:mr-[3.85rem] big:mr-[10.8rem] enormous:mr-[23.8rem]">
            <x-product.product-sub-header-tab :product="$products" text="Status" />
        </div>

        <div class="big:w-full flex big:gap-[10.8rem]">
            <x-product.product-sub-header-tab :product="$products" text="Gewijzigd" />
        </div>
    </div>
</div>
