<div class="bg-white flex justify-center rounded-md">
    <div class="hd:w-[95rem] uhd:w-[134rem]">

        <div class="h-[15rem] mt-[2rem]">
            <div class="bg-[#3DABD5] flex items-center justify-start rounded-t-lg h-[2.5rem]">
                <p class="ml-[1.37rem] text-[14px] text-[#fff]">Informatie</p>
            </div>

            <div class="h-[12.5rem] rounded-b-lg" style="border: 1px solid #F0F0F0;">
                <div class="mt-[2rem] ml-[2rem]  flex flex-wrap columns-2" style="column-gap: 10rem; row-gap: 1rem">
                    <x-product.create.stepOne.title />
                    <x-product.create.stepOne.ean />
                    <x-product.create.stepOne.sku />
                    <x-product.create.stepOne.price />
                </div>
            </div>
           
        </div>
        
        <div class="h-[20.37rem] mt-[2rem]">
            <div class="bg-[#3DABD5] flex items-center justify-start rounded-t-lg h-[2.5rem]">
                <p class="ml-[1.37rem] text-[14px] text-[#fff]">Korte beschrijving</p>
            </div>
            <div class="h-[17.87rem] rounded-b-lg" style="border: 1px solid #F0F0F0;"> <x-product.create.stepOne.short-desc /> </div>
        </div>

        <div class="h-[23.06rem] rounded-md mt-[2rem]">
            <div class="bg-[#3DABD5] flex items-center justify-start rounded-t-lg h-[2.5rem]">
                <p class="ml-[1.37rem] text-[14px] text-[#fff]">Lange beschrijving</p>
            </div>
            <div class="h-[20.56rem] rounded-b-lg" style="border: 1px solid #F0F0F0;"> <x-product.create.stepOne.long-desc /> </div>
        </div>

        <div class="flex gap-[20rem]">
            <div class="h-[9.56rem] rounded-md mt-[2rem] hd:w-[40rem] uhd:w-[60rem]">
                <div class="bg-[#3DABD5] flex items-center justify-start rounded-t-lg h-[2.5rem]">
                    <p class="ml-[1.37rem] text-[14px] text-[#fff]">Backorders toestaan</p>
                </div>
                <div class="h-[7.06rem] rounded-b-lg" style="border: 1px solid #F0F0F0;"> <x-product.create.allow-backorder /> </div>
            </div>

            <div class="h-[9.56rem] rounded-md mt-[2rem]  hd:w-[40rem] uhd:w-[60rem]">
                <div class="bg-[#3DABD5] flex items-center justify-start rounded-t-lg h-[2.5rem]">
                    <p class="ml-[1.37rem] text-[14px] text-[#fff]">Voorraad communiceren naar verkoopkanaal</p>
                </div>
                <div class="h-[7.06rem] rounded-b-lg" style="border: 1px solid #F0F0F0;"> <x-product.create.communi-to-channel /> </div>
            </div>
        </div>
      
    </div>
 
</div>
