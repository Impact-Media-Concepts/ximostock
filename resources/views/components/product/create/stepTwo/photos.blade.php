{{-- Photos --}}

<style>
    .primary-size {
        width: calc(22%) !important;
        height: unset !important;
    }

    .primary-foto-size {
        width: unset !important;
    }
</style>

<div class="bg-white rounded-md uhd:h-[42.56rem]" style="border: 1px solid #F0F0F0;">
    <div class="h-[4.56rem] hd:w-[95rem] uhd:w-[138rem] rounded-t-lg" style="border:  1px solid #F0F0F0;">
        <div class=" ml-[1.56rem] mt-[0.6rem]">
            <div>
                <p class="text-[18px] font-bold text-[#717171]">
                    Foto's
                </p>
            </div>
            <div>
                <p class="text-[14px] text-[#717171]">
                    Hier kan je foto's kiezen voor je product
                </p>
            </div>
        </div>
    </div>

    <div class="flex flex-col justify-between items-center h-[30.5rem] mt-[1.5rem]" style="border: 1px solid #F0F0F0;">
        <div class="hd:w-[95rem] uhd:w-[134rem] rounded-md">
            <div class="bg-[#3DABD5] flex items-center justify-start rounded-t-lg h-[2.5rem]">
                <p class="ml-[1.37rem] text-[14px] text-[#fff]">Foto's instellen</p>
            </div>

            <section class="splide hidden" id="splideSection" aria-label="Splide Basic HTML Example">
                <div class="splide__track">
                    <ul class="splide__list uhd:!w-[119rem] uhd:!ml-[2rem]"  id="splideList">
                        
                    </ul>
                </div>
            </section>
        </div>

        <div class="flex w-full justify-start items-end gap-[0.5rem] mb-[1rem]">
            <button
                class="hover:bg-[#3999BE] js-add-button hover:cursor-pointer text-left text-[14px] text-white duration-100 flex items-center justify-center z-20 w-[14.06rem] h-[2.78rem] text-sm font-light text-gray-700 bg-[#3dabd5] bottom-[0.05rem] border-1 border-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#3DABD5] focus:ring-offset-2 focus:ring-offset-gray-100 relative left-6 top-[0.02rem]"
                style="border: 1px solid white"
                type="button"
                id="get_file"
                accept=".jpg, .jpeg, .png">
                Voeg foto toe
                <div class="flex items-center justify-center">
                    <!-- todo add img file types -->
                    <input class="hidden" type="file" accept=".jpg, .jpeg, .png, avif, svg, webp" id="uploadFoto" class="hidden"/>
                </div>
            </button>

            <button
                class="hover:bg-[#3999BE] duration-100 flex items-center justify-center z-20 w-[14.06rem] h-[2.78rem] text-sm font-light text-gray-700 bg-[#3dabd5] bottom-[0.05rem] border-1 border-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#3DABD5] focus:ring-offset-2 focus:ring-offset-gray-100 relative left-6 top-[0.02rem]"
                style="border: 1px solid white"
                type="button"
                id="setPrimaryPhoto">
                <div class="flex items-center justify-center">
                    <span class="text-center text-[14px] text-white">Zet als primair</span>
                </div>
            </button>
        </div>
    </div>
</div>
