<div class="bg-white h-[3.55rem] flex justify-center items-center"
    style="border-bottom: 1px solid #0000001A; font-family: 'Inter', sans-serif;">
    <input class="h-[0.87rem] w-[0.87rem] mx-2 mt-1 relative right-[2.08rem]" type="checkbox" name="product_ids[]"
        value="" />
    <div class="flex gap-[2.5rem] pl-[3.8rem]">
        <div class="flex gap-[18.35rem] mr-[0.2rem] relative right-[0.83rem]">
            <x-product.product-sub-header-tab orderBy="name" text="Naam" />
            <x-product.product-sub-header-tab orderBy="price" text="SKU" />
        </div>

        <div class="flex justify-center items-center w-[2.75] h-[1.06] pl-10 pr-11 cursor-pointer"
            wire:click="setOrderBy('price')">
            <p class="text-[14px] mt-[0.12rem]">
                Prijs
            </p>
            <img class="ml-[0.37rem] mt-[0.38rem] w-[0.62rem] h-[0.37rem]" src="../images/arrow-down-icon.png">
        </div>

        <div class="flex gap-[3.8rem] pr-[0.9rem] relative left-[0.05rem]">
            <x-product.product-sub-header-tab orderBy="inventory" text="Voorraad" />
            <x-product.product-sub-header-tab orderBy="sold" text="Verkocht" />
        </div>

        <div class="flex gap-[3.8rem] relative left-[0.05rem]">
            <x-product.product-sub-header-tab orderBy="status" text="Status" />
            <x-product.product-sub-header-tab orderBy="modified" text="Gewijzigd" />
        </div>

    </div>
</div>
