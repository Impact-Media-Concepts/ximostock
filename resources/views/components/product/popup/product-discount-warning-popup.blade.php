@props(['discountError'])

@if ($discountError)
    <form action="/products/bulkdiscountforce" method="POST">
        @csrf
        <div
            class="discount-warning-popup w-full h-full fixed top-0 bg-black bg-opacity-75 hidden pt-32 select-none left-0" style="z-index: 999;"
        >
            <div class="flex items-start justify-center">
                <div class="w-[32.18rem] h-[36.56rem] flex grid justify-center rounded-md bg-white">
                    <div
                        class="w-[2rem] discount-popup-close flex items-center relative left-[28rem] top-[1rem] hover:cursor-pointer z-[1000]"
                    >
                        <img class="select-none w-[1.1rem] h-[1.1rem] flex discount-warning-popup-close" src="../images/x-icon.png" alt="x icon">
                    </div>
                    <div class="flex justify-center">
                        <img class="select-none w-[8.12rem] h-[7rem]" src="../images/archive-warning-icon.png"> 
                    </div>
                    <div class="w-[28rem] h-[5.93rem] flex">
                        <p class="text-[16px] text-[#717171] text-center">
                            Waarschuwing, er zijn (xxx)producten waarbij het afronden bij de decimalen ervoor zorgt dat het product duurder word. Wilt u door gaan met deze actie of producten toch overslaan voor deze actie?
                        </p>
                    </div>
                    <div class="w-[29.12rem] h-[14.75rem] flex-col rounded-md overflow-y-auto overflow-x-hidden max-h-[14.75rem]" style="border: 1px solid #F0F0F0;">
                        <?php if ($discountError !== null): ?>
                            <?php foreach ($discountError as $index => $error): ?>
                                <div class="discountWarningError-item w-[27.75rem] h-[3.68rem] flex justify-between items-center border border-gray-200">
                                    <p class="flex-grow flex justify-start pl-[1.2rem] truncate max-w-[19rem]">
                                        {{ $error->title }}
                                        <input type="hidden" name="product_ids[{{ $error->id }}]" value="{{ $error->discount }}">
                                    </p>
                                    <div class="pr-[1rem]">
                                        <button type="button" class="skip-discountWarningError-item w-[6.06rem] h-[2.12rem] flex justify-center items-center rounded-md hover:bg-gray-100" style="border: 1px solid #717172;">
                                            <p class="text-[14px] text-[#717171]">
                                                Overslaan
                                            </p>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="discount-buttons flex items-center gap-[13.2rem] mb-[0.5rem]">

                        <div class="flex items-center justify-start">
                            <button type="button" class="discount-warning-popup-close flex justify-center gap-2 items-center discountCancel w-[7.87rem] h-[2.68rem] hover:bg-gray-100 rounded-md" style="border: 1px solid #717172;">
                                <img class="discount-warning-popup-close select-none w-[0.8rem] h-[0.8rem] flex" src="../images/x-icon.png" alt="x icon">
                                <p class="discount-warning-popup-close flex text-[#717171]">Annuleren</p>
                            </button>
                        </div>
                    
                        <div class="flex items-center justify-end">
                            <button class="flex justify-center items-center w-[7.87rem] h-[2.68rem] bg-[#3DABD5] rounded-md hover:bg-[#3999BE] gap-[0.5rem]">
                                <img src="../images/save-icon.png">
                                <p class="flex text-[#F8F8F8]">Save</p>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endif

<script>
    document.addEventListener("DOMContentLoaded", (event) => {
        const discountWarningPopup = document.querySelector(".discount-warning-popup");
        document.querySelectorAll('.skip-discountWarningError-item').forEach(function(button) {
            button.addEventListener('click', function() {
                let discountWarningError = button.closest('.discountWarningError-item');
            
                discountWarningError.parentNode.removeChild(discountWarningError);
            });
        });

        const error = @json($discountError ?? null);

        if (error) {
            document.querySelector(".discount-warning-popup").classList.remove("hidden");
        }

        if (discountWarningPopup) {
            document.querySelector(".discount-warning-popup").addEventListener("click", function (event) {
                if (
                    event.target.matches(".discount-warning-popup-close") ||
                    event.target.matches(".discount-warningCancel")
                ) {
                    event.preventDefault();
                    discountWarningPopup.style.animation = "fadeOut 0.3s forwards"; 

                    discountWarningPopup.addEventListener("animationend", function() {
                        discountWarningPopup.style.display = "none";
                    });
                }
            });   
        }
    });   
</script>
