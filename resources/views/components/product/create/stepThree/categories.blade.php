
<div class="basic:h-[38rem] hd:w-[98rem] uhd:w-[138rem] bg-white hd:h-[50rem] uhd:h-[57rem] rounded-t-lg create-container-border">
    <div>
        <div class="h-[4.56rem] flex flex-col gap-[0.5rem] rounded-t-lg" style="border: 1px solid #F0F0F0;">
            <div class="w-full ml-[1.56rem] mt-[0.6rem]">
                <p class="font-bold text-[18px] text-[#717171]">Lorem, ipsum dolor.</p>
                <p class="text-[14px]">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ea autem corrupti officia provident, maxime distinctio!</p>
            </div>
        </div>
        <div class="flex justify-center mt-[1.5rem] basic:gap-[5rem] hd:gap-[2rem] uhd:gap-[6rem]">
            <div class="basic:h-[39rem] basic:w-[31rem] hd:h-[39rem] hd:w-[46rem] uhd:h-[39rem] uhd:w-[64rem]">
                <div class="bg-[#3DABD5] rounded-t-lg h-[2.5rem] flex items-center justify-start">
                    <p class="pl-[1.56rem] text-[#fff]">
                        Beschikbare categorieën
                    </p>
                </div>
                
                <div class="rounded-b-lg" style="border: 1px solid #F0F0F0;">
                    <x-product.create.categories.category-checkbox-list :categories="$categories"/>
                </div>
            </div>

            <div class="basic:h-[39rem] basic:w-[31rem] hd:h-[39rem] hd:w-[46rem] uhd:h-[39rem] uhd:w-[64rem]">
                <div class="bg-[#3DABD5] rounded-t-lg h-[2.5rem] flex items-center justify-start">
                    <p class="pl-[1.56rem] text-[#fff]">
                        Categorie pad
                    </p>
                </div>

                <div class="w-full flex flex-col items-center justify-start basic:max-h-[29.5rem] basic:h-[29.5rem] hd:max-h-[41.65rem] hd:h-[41.65rem] uhd:h-[48rem] uhd:max-h-[48rem] overflow-y-auto rounded-b-lg pb-[1.5rem]" style="border: 1px solid #F0F0F0">
                    <div id="categoryListContainer" class="flex flex-col items-center rounded-md"></div>
                </div>
            </div>
        </div>
    </div>
</div>
