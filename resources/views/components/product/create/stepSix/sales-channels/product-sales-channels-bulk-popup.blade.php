@props(['salesChannels'])

<div
    class="sales-popup w-full h-full fixed top-0 bg-black bg-opacity-75 hidden pt-32 select-none left-0" style="z-index: 999;"
>
    <div
        x-transition
        class="sales-popup-container relative w-[70rem] h-[43rem] bg-white pb-0 rounded-md text-center p-8 mx-auto mt-20 transform -translate-y-40 transition-transform duration-300"
    >
        <div
            class="w-[2rem] sales-popup-close flex items-center relative bottom-4 left-[64rem] hover:cursor-pointer z-[1000]"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="2"
                stroke="gray"
                class="sales-popup-close select-none flex items-center justify-center w-8 h-8"
            >
                <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M6 18 18 6M6 6l12 12"
                class="sales-popup-close"
                />
            </svg>
        </div>
       
        <div class="relative bottom-8">
            
        </div>
    </div>
</div>

<x-product.popup.sales-channels.sales-channels-data :salesChannels="$salesChannels" />
