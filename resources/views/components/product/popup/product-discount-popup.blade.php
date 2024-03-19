<div
    class="discount-popup w-full h-full fixed top-0 bg-black bg-opacity-75 hidden pt-32 select-none left-0" style="z-index: 999;"
>
    <div
        x-data="{
        message: 'Weet u zeker dat u dit product wilt archiveren?',
        explanation_part1: 'Als u op \'ja\' klikt, zal het product naar het archief worden verplaatst. Op de',
        explanation_archive: 'archief',
        explanation_part2: 'pagina kunt u dit product terugvinden en naar keuze terugzetten.',
        yes: 'Ja ik weet het zeker!',
        no: 'Nee toch maar niet'
        }"
        x-transition
        class="discount-popup-container relative w-[44rem] h-[24.81rem] bg-white pb-0 rounded-md text-center p-8 mx-auto mt-20 transform -translate-y-40 transition-transform duration-300"
    >
        <div
            class="discount-popup-close flex justify-end items-center relative bottom-4 left-2"
        >
            <a
                href="#0"
                class="discount-popup-close w-10 h-10 flex items-center justify-center cursor-pointer"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="2"
                    stroke="gray"
                    class="discount-popup-close flex items-center justify-center w-8 h-8 cursor-pointer"
                >
                    <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M6 18 18 6M6 6l12 12"
                    />
                </svg>
            </a>
        </div>
        <div class="relative bottom-8">
            <div class="w-[40.87rem] h-[19.31rem] mt-4">
                <div class="w-[40.87rem] h-[2.5rem] bg-[#3DABD5] rounded-t-lg flex justify-start items-center pl-4 text-white"> 
                    <p>
                        Korting
                    </p>
                </div>
                <div class="h-[16.81rem] w-[40.87rem] rounded-b-lg" style="border: 1px solid #F0F0F0"> 
                    <div class="w-[40.87rem] h-[10.31rem]">
                        <div class="flex items-center gap-3.5">
                            <div x-data="{ open: false, selectedProperty: '' }" class="relative flex items-center justify-start text-left bottom-[2.5rem] right-6">
                                <input type="hidden" name="selected_property_id" x-bind:value="selectedProperty.id">
                                <button type="button" @click="open = !open;"
                                    class="flex items-center z-20 w-[38.06rem] px-[1.08rem] h-[2.68rem] text-sm font-light bottom-[0.05rem] border-1 border-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#717171] focus:ring-offset-2 focus:ring-offset-gray-100 relative left-12 top-[4rem]"
                                    style="border: 1px solid #717171"  @click.away="open = false">
                                    <!-- Display selected property name -->
                                    <div class="flex mt-[0.08rem] relative right-[0.2rem]">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="white" class="w-3 h-3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                    
                                    </div>
                                    <span class="w-52 text-left text-[14px] text-gray-700 relative right-2" x-text="selectedProperty ? selectedProperty.name : 'Korting percentage'">
                                    </span>
                                </button>
                        
                                <div x-cloak x-show="open"
                                    class="overflow-y-auto overflow-x-hidden absolute flex justify-center items-center h-[7.5rem] w-[38.06rem] bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-30 left-[3rem] top-[7.5rem]" style="border: 1px solid #F0F0F0;">
                                    <ul >
                                        <template  x-for="property in discount" :key="property.data_pages">
                                            <li >
                                                <!-- Store property ID when clicked and log it -->
                                                <button type="button" @click="selectedProperty = property; open = false;"
                                                    class="hover:bg-[#3999BE] duration-100 block w-[38.06rem] px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none flex justify-start">
                                                    <span  class="flex items-center justify-start pr-3 pl-4 " x-text="property.name"></span>
                                                </button>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="discount-buttons flex items-center gap-[0.7rem] absolute bottom-5 right-5">
                        <button type="button" class="flex justify-center gap-2 items-center discountCancel w-[7.87rem] h-[2.68rem] hover:bg-gray-100 rounded-md" style="border: 1px solid #717172;">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="gray"
                                class="discount-popup-close flex items-center justify-center w-8 h-8 cursor-pointer"
                            >
                                <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6 18 18 6M6 6l12 12"
                                />
                            </svg>
                            <p class="flex text-[#717171]">
                                Annuleren
                            </p>
                        </button>
                        <button type="button" class="flex justify-center items-center w-[7.87rem] h-[2.68rem] bg-[#3DABD5] rounded-md hover:bg-[#3999BE]">
                            <img src="../images/save-icon.png">
                            <p class="flex text-[#F8F8F8]">
                                Save
                            </p>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
