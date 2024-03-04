<div class="bg-blue-200 h-[3.5rem] flex justify-center items-center">
    <input class="h-[1.06rem] mx-2" type="checkbox" name="product_ids[]" value="" />
    <div class="flex gap-10 pl-9">
        <div class="flex gap-[7rem] mr-[0.6rem]">
            <div class="flex justify-center items-center w=[2.75] h-[1.06] cursor-pointer"
                wire:click="setOrderBy('price')">
                <p class="text-[14px]">
                    SKU
                </p>

                <img class="ml-2 mt-1 w-[0.62rem] h-[0.37rem]" src="../images/arrow-down-icon.png">
            </div>
            <div class="flex justify-center items-center w=[2.75] h-[1.06] cursor-pointer"
                wire:click="setOrderBy('price')">
                <p class="text-[14px]">
                    Naam
                </p>

                <img class="ml-2 mt-1 w-[0.62rem] h-[0.37rem]" src="../images/arrow-down-icon.png">
            </div>
        </div>

        <div class="flex justify-center items-center w=[2.75] h-[1.06] pl-[15.5rem] pr-12 cursor-pointer"
            wire:click="setOrderBy('price')">
            <p class="text-[14px]">
                Prijs
            </p>

            <img class="ml-2 mt-1 w-[0.62rem] h-[0.37rem]" src="../images/arrow-down-icon.png">
        </div>

        <div class="flex gap-16 pr-4">
            <div class="flex justify-center items-center w=[2.75] h-[1.06] cursor-pointer"
                wire:click="setOrderBy('price')">
                <p class="text-[14px]">
                    Voorraad
                </p>

                <img class="ml-2 mt-1 w-[0.62rem] h-[0.37rem]" src="../images/arrow-down-icon.png">
            </div>
            <div class="flex justify-center items-center w=[2.75] h-[1.06] cursor-pointer"
                wire:click="setOrderBy('price')">
                <p class="text-[14px]">
                    Verkocht
                </p>

                <img class="ml-2 mt-1 w-[0.62rem] h-[0.37rem]" src="../images/arrow-down-icon.png">
            </div>
        </div>
        <div class="flex gap-16">
            <div class="flex justify-center items-center w=[2.75] h-[1.06] cursor-pointer"
                wire:click="setOrderBy('price')">
                <p class="text-[14px]">
                    Status
                </p>

                <img class="ml-2 mt-1 w-[0.62rem] h-[0.37rem]" src="../images/arrow-down-icon.png">
            </div>
            <div class="flex justify-center items-center w=[2.75] h-[1.06] cursor-pointer"
                wire:click="setOrderBy('price')">
                <p class="text-[14px]">
                    Gewijzigd
                </p>

                <img class="ml-2 mt-1 w-[0.62rem] h-[0.37rem]" src="../images/arrow-down-icon.png">
            </div>
        </div>

    </div>
</div>
