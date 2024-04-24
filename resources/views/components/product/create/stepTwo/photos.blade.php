{{-- Photos --}}

<style>
    .primary-size {
        width: 20rem !important;
        height: 20rem !important;
    }

    .primary-foto-size {
        width: unset !important;
    }

    .primary-span-size {
        width: 350px !important;
        height: 350px !important;
        display: flex !important;
        justify-content: center !important;
        overflow: hidden !important;
    }

    /* .splide__arrow {
        background: #3DABD5;
        width: 2em !important;
        height: 2em !important;
    }
    .splide__arrow--prev {
        left: 0.7em !important;
    }

    .splide__arrow--prev svg {
        width: 1.2em !important;
        height: 1.2em !important;
    } */
</style>

<div class="bg-white rounded-t-lg basic:h-[38rem] hd:h-[50rem] uhd:h-[57rem] create-container-border">
    <div class="h-[4.56rem] flex flex-col gap-[0.5rem] rounded-t-lg" style="border: 1px solid #F0F0F0;">
        <div class="w-full ml-[1.56rem] mt-[0.6rem]">
            <p class="font-bold text-[18px] text-[#717171]">Lorem, ipsum dolor.</p>
            <p class="text-[14px]">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ea autem corrupti officia provident, maxime distinctio!</p>
        </div>
    </div>

    <div class="flex justify-center items-center w-full mt-[1.5rem]">
        <div class="flex flex-col justify-between items-center basic:w-[67rem] basic:h-[32rem] hd:h-[44rem] hd:w-[94rem] uhd:h-[50.5rem] uhd:w-[134rem] rounded-md" style="border: 1px solid #F0F0F0;">
            <div class="basic:w-[67rem] hd:w-[94rem] uhd:w-[134rem] rounded-md">
                <div class="bg-[#3DABD5] flex items-center justify-start rounded-t-lg h-[2.5rem]">
                    <p class="ml-[1.37rem] text-[14px] text-[#fff]">Foto's instellen</p>
                </div>

                <section class="splide hidden rounded-md flex hd:justify-start uhd:justify-center" id="splideSection" aria-label="Splide Basic HTML Example">
                    <div class="splide__track hd:ml-[3rem] hd:w-[89rem] uhd:w-[124rem]">
                        <ul class="splide__list uhd:!w-[119rem] uhd:!ml-[2rem]" id="splideList">
                            
                        </ul>
                    </div>
                </section>
            </div>

            <div class="flex w-full justify-start items-end gap-[0.5rem] mb-[1.5rem] ml-[2rem]">
                <button
                    class="hover:bg-[#3999BE] js-add-button hover:cursor-pointer text-left text-[14px] text-white duration-100 flex items-center justify-center z-20 w-[14.06rem] h-[2.78rem] text-sm font-light text-gray-700 bg-[#3dabd5] bottom-[0.05rem] border-1 border-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#3DABD5] focus:ring-offset-2 focus:ring-offset-gray-100 relative left-6 top-[0.02rem]"
                    style="border: 1px solid white"
                    type="button"
                    id="get_file"
                    accept=".jpg, .jpeg, .png">
                    Voeg foto toe
                    <div class="flex items-center justify-center">
                        <!-- todo add img file types -->
                        <input class="hidden" type="file" accept=".jpg" id="uploadFoto" class="hidden" multiple/>
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
</div>
