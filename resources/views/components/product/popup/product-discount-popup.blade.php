@props(['discountError'])

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
        class="discount-popup-container relative w-[44rem] h-[21.25rem] bg-white pb-0 rounded-md text-center p-8 mx-auto mt-20 transform -translate-y-40 transition-transform duration-300"
    >
        <div
            class="w-[2rem] discount-popup-close flex items-center relative bottom-4 left-[39rem] hover:cursor-pointer z-[1000]"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="2"
                stroke="gray"
                class="discount-popup-close select-none flex items-center justify-center w-8 h-8"
            >
                <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M6 18 18 6M6 6l12 12"
                class="discount-popup-close"
                />
            </svg>
        </div>
       
        <div class="relative bottom-8">
            <div class="w-[40.87rem] h-[15.81rem] mt-[2rem]" style="border: 1px solid #f0f0f0; border-radius: 10px;">
                <div class="w-[40.87rem] h-[2.5rem] bg-[#3DABD5] rounded-t-lg flex justify-start items-center pl-4 text-white">
                    <p>Korting</p>
                </div>
                <div x-data="{ showDecimals: false }" class="pt-[1.5rem] pl-[1.5rem]">
                    <div class="flex-col justify-center items-center">
                        <div class="flex items-center">
                            <div class="mr-[1rem]">
                                <input name="discount" class="text-center discount-input w-[18.68rem] h-[2.5rem] font-[16px] rounded-md" style="border: 1px solid #d3d3d3;" type="number" for="discountPercentage" placeholder="Kortingspercentage">
                            </div>
                            <div class="flex gap-[0.5rem]">
                                <input type="checkbox" id="roundDiscount" x-on:click="showDecimals = !showDecimals">
                                <input type="hidden" name="round" value="0" id="trueRoundDiscount">
                                <label class="text-[14px] font-bold" for="discountPercentage">Afronden op decimalen?</label>
                            </div>
                        </div>
                    </div>
                    <div x-show="showDecimals" class="flex justify-start items-center pt-[0.5rem]" style="">
                        <input name="cents" class="discount-input text-center w-[18.68rem] h-[2.5rem] font-[16px]rounded-md" style="border: 1px solid #d3d3d3;" type="number" for="discountDecimals" placeholder="Decimalen">
                        <label for="discountDecimals"></label>
                    </div>
                </div>
                
                <div class="discount-buttons flex items-center gap-[0.7rem] absolute bottom-[1.1rem] right-[0.3rem]">
                    <button type="button" class="discount-popup-close flex justify-center gap-2 items-center discountCancel w-[7.87rem] h-[2.68rem] hover:bg-gray-100 rounded-md" style="border: 1px solid #717172;">
                        <img class="discount-popup-close select-none w-[0.8rem] h-[0.8rem] flex" src="../images/x-icon.png" alt="x icon">
                        <p class="discount-popup-close flex text-[#717171]">Annuleren</p>
                    </button>
                    <button class="flex justify-center items-center w-[7.87rem] h-[2.68rem] bg-[#3DABD5] rounded-md hover:bg-[#3999BE] gap-[0.5rem]">
                        <img src="../images/save-icon.png">
                        <p class="flex text-[#F8F8F8]">Save</p>
                    </button>
                </div>
            </div>
        </div>

        @if ($discountError)
            <div class="flex justify-end">
                <div class="w-[32.18rem] h-[36.56rem] bg-white flex grid justify-center rounded-md">
                    <div class="flex justify-end relative top-[1rem]">
                        <img class="select-none w-[1.1rem] h-[1.1rem] flex" src="../images/x-icon.png" alt="x icon">
                    </div>
                    <div class="flex justify-center">
                        <img class="select-none w-[8.12rem] h-[7rem]" src="../images/archive-warning-icon.png"> 
                    </div>
                    <div class="w-[28rem] h-[5.93rem] flex">
                        <p class="text-[16px] text-[#717171]">
                            Waarschuwing, er zijn (xxx)producten waarbij het afronden bij de decimalen ervoor zorgt dat het product duurder word. Wilt u door gaan met deze actie of producten toch overslaan voor deze actie?
                        </p>
                    </div>
                    <div class="w-[29.12rem] h-[14.75rem] flex-col rounded-md overflow-y-auto overflow-x-hidden max-h-[14.75rem]" style="border: 1px solid #F0F0F0;">
                        <?php if ($discountError !== null): ?>
                            <?php foreach ($discountError as $index => $error): ?>
                                <div class="discount-error-item w-[27.75rem] h-[3.68rem] flex justify-between items-center border border-gray-200">
                                    <p class="flex-grow flex justify-start pl-[1.2rem] truncate max-w-[19rem]">
                                        {{ $error->title }}
                                        <input type="hidden" name="products_ids[{{ $error->id }}]" value="{{ $error->discount }}">
                                    </p>
                                    <div class="pr-[1rem]">
                                        <button type="button" class="skip-discount-error-item w-[6.06rem] h-[2.12rem] flex justify-center items-center rounded-md hover:bg-gray-100" style="border: 1px solid #717172;">
                                            <p class="text-[14px] text-[#717171]">
                                                Overslaan
                                            </p>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", (event) => {
        document.querySelectorAll('.skip-discount-error-item').forEach(function(button) {
        button.addEventListener('click', function() {
            // Find the parent container of the clicked button
            var discountErrorItem = button.closest('.discount-error-item');
            // Remove the corresponding item from the UI
            discountErrorItem.parentNode.removeChild(discountErrorItem);
            // Optional: Send an AJAX request to update the server-side data
        });
    });
    });
</script>