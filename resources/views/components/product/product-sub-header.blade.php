@props(['products'])

<div class="bg-white w-full h-[3.55rem] flex justify-start items-center gap-[5.2rem]"
    style="border-bottom: 1px solid #0000001A; font-family: 'Inter', sans-serif;">
    <input id="bulkActionsCheckboxSubheader" class="h-[0.87rem] flex justify-start items-center w-[0.87rem] ml-[1.55rem] mt-1 cursor-pointer" type="checkbox" name="product_ids[]"
        value="" />
    <div class="flex width-full">
        <div class="width-full margin-right pos-right flex sub-header-gap relative right-[0.83rem]">
            <x-product.product-sub-header-tab :product="$products" orderBy="name" text="Naam" />
            <x-product.product-sub-header-tab :product="$products" orderBy="price" text="SKU" />
        </div>

        <div class="width-full flex justify-center items-center w-[2.75] h-[1.06] cursor-pointer"
            wire:click="setOrderBy('price')"
            style="margin-right: 5.25rem;">
            <x-product.product-sub-header-tab :product="$products" class="mt-[0.12rem]" orderBy="price" text="Prijs" />
        </div>

        <div class="width-full flex gap-[3.75rem] relative left-[0.05rem]" style="margin-right: 5rem;">
            <x-product.product-sub-header-tab :product="$products" orderBy="inventory" text="Voorraad" />
            <x-product.product-sub-header-tab :product="$products" orderBy="sold" text="Verkocht" />
        </div>

        <div class="width-full flex gap-[3.8rem] relative right-[1.6rem]">
            <x-product.product-sub-header-tab :product="$products" orderBy="status" text="Status" />
            <x-product.product-sub-header-tab :product="$products" orderBy="modified" text="Gewijzigd" />
        </div>
    </div>
</div>