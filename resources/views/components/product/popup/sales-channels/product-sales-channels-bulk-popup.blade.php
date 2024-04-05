@props(['salesChannels'])

<?php 
echo $salesChannels;
?>

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
            <div class="mt-[2rem] mb-[0.5rem] h-[37rem]" style="border: 1px solid #f0f0f0; border-radius: 10px;">
                <div class="h-[2.5rem] bg-[#3DABD5] rounded-t-lg flex justify-start items-center pl-4 text-white">
                    <p>Verkoopkanalen</p>
                </div>
                <div>
                    <div class="flex items-center py-[1rem]" style="border: 1px solid #f0f0f0">
                        <div class="flex-col items-center justify-start">
                            <div class="w-full flex justify-start">
                                <p class="flex ml-[1.25rem]">Verkoopkanaal titel:</p>
                            </div>

                            <div class="flex mt-[0.08rem] items-center">
                                <button type="submit" class="relative flex items-center left-10">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                        stroke="#D3D3D3" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                    </svg>
                                </button>

                                <input id="salesChannelSearchInput" class="w-[33.43rem] h-[2.5rem] rounded-md pl-[3rem] pt-[0.1rem] pr-[1rem] text-[#717171] header-search"
                                    style="font-size: 16px; border:1px solid #D3D3D3;" name="search" type="text" placeholder="Zoeken..."
                                    autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center mb-[1rem] h-[3.56rem]"  style="border: 1px solid #f0f0f0">
                        <div class="flex justify-start ml-[1.5rem] gap-[2rem]">
                            <input id="selectAllSalesChannels" type="checkbox" class="slideon slideon-auto" name="sales_channel_id[]">
                            <p>
                                selecteer alle verkoopkanalen
                            </p>
                        </div>
                    </div>

                   <div class="h-[19rem] max-h-[19rem] overflow-y-auto" id="salesChannelList">
                       
                    </div>

                    <div class="sales-buttons flex items-center gap-[0.7rem] absolute right-[1rem] bottom-[1rem]">
                        <button type="button" class="sales-popup-close flex justify-center gap-2 items-center salesCancel w-[7.87rem] h-[2.68rem] hover:bg-gray-100 rounded-md" style="border: 1px solid #717172;">
                            <img class="sales-popup-close select-none w-[0.8rem] h-[0.8rem] flex" src="../images/x-icon.png" alt="x icon">
                            <p class="sales-popup-close flex text-[#717171]">Annuleren</p>
                        </button>

                         <button type="button" class="sales-popup-close flex justify-center gap-2 items-center salesCancel w-[7.87rem] h-[2.68rem] hover:bg-gray-100 rounded-md" style="border: 1px solid #717172;">
                            <p class="sales-popup-close flex text-[#717171]">Ontkoppel</p>
                        </button>

                        <button class="flex justify-center items-center w-[7.87rem] h-[2.68rem] bg-[#3DABD5] rounded-md hover:bg-[#3999BE] gap-[0.5rem]">
                            <p class="flex text-[#F8F8F8]">Koppel</p>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-product.popup.sales-channels.sales-channels-data :salesChannel="$salesChannels" />

<script>
    function renderSalesChannelss() {
        const salesChannelList = document.getElementById('salesChannelList');

        salesChannelList.innerHTML = '';

        salesChannelsData.forEach(salesChannel => {
            const li = document.createElement('li');
            li.classList.add('flex', 'items-center', 'justify-start');
            li.id = `li_${salesChannel.id}`;

            const a = document.createElement('a');
            a.href = `?salesChannel=${salesChannel.id}`;

            const button = document.createElement('button');
            button.classList.add('hover:bg-[#3999BE]', 'duration-100', 'block', 'w-[15.5rem]', 'h-[2.3rem]', 'px-4', 'py-2', 'text-sm', 'text-gray-700', 'hover:bg-gray-100', 'focus:outline-none', 'flex', 'justify-start', 'items-center');

            const span = document.createElement('span');
            span.classList.add('flex', 'items-center', 'justify-start', 'pr-3', 'text-[#717171]');
            span.textContent = salesChannel.name;

            button.appendChild(span);

            a.appendChild(button);
            li.appendChild(a);
            salesChannelList.appendChild(li);
        });
    }

    renderSalesChannelss();
    
    function searchSalesChannelss(salesChannelSearchText) {
        salesChannelsData.forEach((salesChannel) => {
        const li = document.getElementById(`li_${salesChannel.id}`);
        if (
            !salesChannelSearchText ||
            salesChannel.name.toLowerCase().includes(salesChannelSearchText.toLowerCase())
        ) {
            li.classList.remove("hidden");
        } else {
            li.classList.add("hidden");
        }
        });
    }

    const salesChannelSearchInput = document.getElementById("salesChannelSearchInput");

    if (salesChannelSearchInput) {
        salesChannelSearchInput.addEventListener("input", () => {
            const salesChannelSearchText = salesChannelSearchInput.value.trim();

            // Render SalesChannelss if search input is empty
            if (!salesChannelSearchText) {
                renderSalesChannelss(); //vervang met show all
            } else {
                searchSalesChannelss(salesChannelSearchText);
            }
        });
    }
</script>