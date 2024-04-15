<style>
    .back-order-btn {
        width: 53rem;
    }

    .back-order-btn-options {
        margin-right: 1.93rem;
        margin-top: 3.3rem;
    }
</style>

<ul>
    <div
        x-data="{ open: false, selectedbackOrder: '' }"
        x-cloak
        class="relative inline-block text-left flex w-full justify-center"
    >
        <input type="hidden" name="selected_backOrder_id" x-bind:value="selectedbackOrder.id">
        <button
            type="button"
            @click="open = !open;"
            class="flex items-center back-order-btn px-4 h-12 text-sm font-medium text-gray-700 bg-white border-2 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#717171] focus:ring-offset-2 focus:ring-offset-gray-100 relative"
            x-cloak
            @click.away="open = false"
            >
            <!-- Display selected backOrder name -->
            <span class="truncate w-52 text-left" x-text="selectedbackOrder ? selectedbackOrder.name : ''"></span>
            <div
                class="w-11 h-11 bg-gray-200 flex justify-center items-center absolute rounded-s-lg"
                style="left: calc(100% - 44px); border-left: 2px solid #d1d5db"
            >
                <img class="select-none w-[0.8rem] h-[0.5rem] flex mt-[0.30rem]"
                    src="../images/arrow-down-icon.png" alt="Arrow down">
            </div>
        </button>

        <div
        x-cloak
        x-show="open"
        class="absolute right-0 back-order-btn back-order-btn-options bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
        >
            <ul>
                <template x-for="backOrder in allowBackorders" :key="backOrder.data">
                    <li>
                    <button type="button" @click="selectedbackOrder = backOrder; open = false;$refs.backorders.value = backOrder.data; console.log('Updated backorders value:', $refs.backorders.value);" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none">
                        <span x-text="backOrder.name"></span>
                        <input type="hidden" id="backorders" x-ref="backorders" name="backorders" value="0">
                    </button>
                    </li>
                </template>
            </ul>
        </div>
    </div>
</ul>
