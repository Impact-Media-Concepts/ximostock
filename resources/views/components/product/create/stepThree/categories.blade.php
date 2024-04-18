
<div class="hd:w-[99rem] uhd:w-[138rem] bg-white h-[51.75rem] uhd:h-[59rem] rounded-t-lg">
    <div>
        <div class="h-[4.56rem] flex flex-col gap-[0.5rem] rounded-t-lg" style="border: 1px solid #F0F0F0;">
            <div class="w-full ml-[1rem] mt-[1rem]">
                <p class="font-bold text-[18px] text-[#717171]">Lorem, ipsum dolor.</p>
                <p class="text-[14px]">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ea autem corrupti officia provident, maxime distinctio!</p>
            </div>
        </div>
        <div class="flex justify-center mt-[1.5rem] uhd:gap-[40rem]">
            <div class="h-[39rem] w-[46rem]">
                <div class="bg-[#3DABD5] rounded-t-lg h-[2.5rem] flex items-center justify-start">
                    <p class="pl-[1.56rem] text-[#fff]">
                        Beschikbare categorieën
                    </p>
                </div>
                
                <div style="border: 1px solid #F0F0F0;">
                    <x-product.create.categories.category-checkbox-list :categories="$categories"/>
                </div>
            </div>

            <div class="h-[39rem] w-[46rem] ">
                <div class="bg-[#3DABD5] rounded-t-lg h-[2.5rem] flex items-center justify-start">
                    <p class="pl-[1.56rem] text-[#fff]">
                        Categorie pad
                    </p>
                </div>

                <div class="w-full flex flex-col items-center justify-start h-[42rem] max-h-[42rem] overflow-y-auto rounded-b-lg" style="border: 1px solid #F0F0F0">
                    <div id="categoryListContainer" class="flex flex-col items-center rounded-md"></div>
                </div>
            </div>
        </div>
    </div>
</div>
