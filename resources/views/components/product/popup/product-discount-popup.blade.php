@props(['discountError'])

<!-- TODO: show save notif component when discount succesfull -->

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
                            <div class="mr-[1rem] flex items-center">
                                <input id="discountRoundedInput" name="discount" class="text-center discount-input w-[18.68rem] h-[2.5rem] font-[16px] rounded-md" style="border: 1px solid #d3d3d3;" type="number" min="0" max="100" for="discountPercentage" placeholder="Kortingspercentage" step="0.01">
                                <span class="relative right-[1.5rem] font-[16px]">%</span>
                            </div>
                            <div class="flex gap-[0.5rem]">
                                <input class="hover:cursor-pointer" type="checkbox" id="roundDiscount" x-on:click="showDecimals = !showDecimals">
                                <input type="hidden" name="round" value="0" id="trueRoundDiscount">
                                <label class="text-[14px] font-bold" for="roundDiscount">Afronden op decimalen?</label>
                            </div>
                        </div>
                    </div>
                    <div x-show="showDecimals" class="flex justify-start items-center pt-[0.5rem]" style="">
                        <input id="discountDecimalsInput" name="cents" class="discount-input discount-decimals-input text-center w-[18.68rem] h-[2.5rem] font-[16px] rounded-md" style="border: 1px solid #d3d3d3;" type="number" for="discountDecimals" placeholder="Centen">
                        <label for="discountDecimalsInput"></label>
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
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", (event) => {
        document.querySelectorAll('.skip-discount-error-item').forEach(function(button) {
            button.addEventListener('click', function() {
                let discountErrorItem = button.closest('.discount-error-item');
            
                discountErrorItem.parentNode.removeChild(discountErrorItem);
            });
        });
    });
    
    function restrictNumberInput(event) {
        let input = event.target;
        let value = parseInt(input.value, 10);
        let min = 10;
        let max = 100;
        
        if (isNaN(value)) {
            input.value = "";
            event.preventDefault();
        }
        else if (value > max) {
            input.value = max;
            event.preventDefault();
        }
    }
    
    let input = document.getElementById("discountRoundedInput");
    input.addEventListener("input", restrictNumberInput);
    
    function decimalRestrictNumberInput(event) {
        let decimalInput = event.target;
        let decimalValue = parseInt(decimalInput.value, 10);
        let decimalMin = 10;
        let decimalMax = 99;
        
        if (isNaN(decimalValue)) {
            decimalInput.value = "";
            event.preventDefault();
        }
        else if (decimalValue > decimalMax) {
            decimalInput.value = decimalMax;
            event.preventDefault();
        }
    }
    
    let decimalInput = document.getElementById("discountDecimalsInput");
    decimalInput.addEventListener("input", decimalRestrictNumberInput);
</script>
