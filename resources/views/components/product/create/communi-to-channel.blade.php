<?php
    $app_url = env('VITE_APP_URL');
?>

<style>
    @media only screen and (min-width: 1280px) {
        .communi-to-chann-btn {
            width: 26rem;
        }
    }

    @media only screen and (min-width: 1920px) {
        .communi-to-chann-btn {
            width: 32rem;
        }
    }
    
    @media only screen and (min-width: 2560px) {
        .communi-to-chann-btn {
            width: 53rem;
        }
    }

    .back-order-btn-options {
        margin-top: 3.3rem;
    }
</style>

<ul>
    <div
        x-data="{ open: false, selectedcommuniToChannel: '' }"
        x-cloak
        class="relative inline-block text-left flex w-full justify-center"
    >
        <input type="hidden" name="selected_communiToChannel_id" x-bind:value="selectedcommuniToChannel.id">
        <button
            type="button"
            @click="open = !open;"
            class="communi-to-chann-btn flex items-center back-order-btn px-4 h-12 text-sm font-medium text-gray-700 bg-white border-2 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#717171] focus:ring-offset-2 focus:ring-offset-gray-100 relative"
            x-cloak
            @click.away="open = false"
            >
            <!-- Display selected communiToChannel name -->
            <span class="truncate w-52 text-left" x-text="selectedcommuniToChannel ? selectedcommuniToChannel.name : ''"></span>
            <div
                class="w-11 h-11 bg-gray-200 flex justify-center items-center absolute rounded-s-lg"
                style="left: calc(100% - 44px); border-left: 2px solid #d1d5db"
            >
                <img class="select-none w-[0.8rem] h-[0.5rem] flex mt-[0.30rem]"
                    src="{{$app_url}}/images/arrow-down-icon.png" alt="Arrow down">
            </div>
        </button>

        <div
        x-cloak
        x-show="open"
        class="communi-to-chann-btn absolute right-0 back-order-btn back-order-btn-options bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
        >
            <ul>
                <template x-for="communiToChannel in communicateToChannel" :key="communiToChannel.data">
                    <li>
                    <button type="button" @click="selectedcommuniToChannel = communiToChannel; open = false;$refs.communiToChannels.value = communiToChannel.data;" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none">
                        <span x-text="communiToChannel.name"></span>
                        <input type="hidden" id="communicate_stock" x-ref="communiToChannels" name="communicate_stock" value="1">
                    </button>
                    </li>
                </template>
            </ul>
        </div>
    </div>
</ul>
