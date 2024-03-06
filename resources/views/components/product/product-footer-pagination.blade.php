<div class="w-[78.81rem] h-[4rem] flex justify-center items-center gap-[24.5rem] bg-[#3DABD5] rounded-b-lg pt-1">
    <h1 class="relative left-6 bottom-[0.2rem] text-white text-[18px]"
        style="font-family: 'Inter', sans-serif; font-weight:bold">

        {{ $products->links() }}

        <div x-data="{ open: false, selectedProperty: '' }" x-cloak class="inline-block text-left flex relative bottom-12">
            <input type="hidden" name="selected_property_id" x-bind:value="selectedProperty.id">
            <button @click="open = !open;"
                class="flex items-center w-24 px-4 h-12 text-sm font-medium text-gray-700 bg-white border-2 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-100 relative"
                x-cloak @click.away="open = false">
                <!-- Display selected property name -->
                <span class="truncate w-52 text-left" x-text="selectedProperty ? selectedProperty.name : '15'"></span>
                <div class="w-11 h-11 bg-gray-200 flex justify-center items-center absolute rounded-s-lg"
                    style="left: calc(100% - 44px); border-left: 2px solid #d1d5db">
                    <svg class="w-6 h-6 flex items-center justify-center" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M10.293 14.293a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 1.414-1.414L10 11.586l3.293-3.293a1 1 0 0 1 1.414 1.414l-4 4a1 1 0 0 1 0 1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
            </button>

            <div x-cloak x-show="open"
                class="absolute flex w-72 pb-44 bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                <ul>
                    <template x-for="property in pagination" :key="property.data_pages">
                        <li>
                            <!-- Store property ID when clicked and log it -->
                            <button @click="selectedProperty = property; open = false;"
                                class="paginate-button block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none"
                                x-bind:data_pages="property.data_pages">

                                <span x-text="property.name"></span>
                            </button>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </h1>
</div>

<script>
    // set pagination amount
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.paginate-button');

        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const pageSize = this.getAttribute('data_pages');

                console.log(pageSize);
                const url = new URL(window.location.href);
                url.searchParams.set('perPage', pageSize);
                window.location.href = url.toString();
            });
        });
    });

    // set pagination amount
    window.pagination = [{
            data_pages: 10,
            name: '10'
        },
        {
            data_pages: 20,
            name: '20'
        },
        {
            data_pages: 50,
            name: '50'
        },
        {
            data_pages: 100,
            name: '100'
        },
        {
            data_pages: 250,
            name: '250'
        }
    ];
</script>
