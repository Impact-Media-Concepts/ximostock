<div class="w-[78.85rem] h-[3.72rem] flex items-center bg-[#3DABD5] rounded-b-lg pt-1">
    <div x-data="{ open: false, selectedProperty: '' }" x-cloak class=" text-left flex justify-start items-center relative">
        <input type="hidden" name="selected_property_id" x-bind:value="selectedProperty.id">
        <button @click="open = !open;"
            class="hover:bg-[#3999BE] duration-100 flex items-center z-20 w-[5.3rem] px-[1.08rem] h-10 text-sm font-light text-gray-700 bg-[#3dabd5] bottom-[0.05rem] border-1 border-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#3DABD5] focus:ring-offset-2 focus:ring-offset-gray-100 relative left-6"
            style="border: 1px solid white" x-cloak @click.away="open = false">
            <!-- Display selected property name -->
            <span class="truncate w-52 text-left text-[16px] text-white"
                x-text="selectedProperty ? selectedProperty.name : '{{ $perPage }}'"></span>
            <div class="w-11 h-11 flex justify-center items-center relative" style="left: calc(100% - 44px);">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                    stroke="currentColor" class="w-[1.05rem] h-[1.05rem]">
                    <path style="color: white" stroke-linecap="round" stroke-linejoin="round"
                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>

            </div>
        </button>
        
        <div x-cloak x-show="open"
            class="absolute flex justify-center items-center w-[5.3rem] bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-30 left-6 bottom-12">
            <ul>
                <template x-for="property in pagination" :key="property.data_pages">
                    <li>
                        <!-- Store property ID when clicked and log it -->
                        <button @click="selectedProperty = property; open = false;"
                            class="paginate-button block w-[5.3rem] px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none"
                            x-bind:data_pages="property.data_pages">
                            <span class="flex justify-center items-center" x-text="property.name"></span>
                        </button>
                    </li>
                </template>
            </ul>
        </div>
    </div>
    {{ $products->onEachSide(0)->links() }}
</div>

<script>
    // set pagination amount
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.paginate-button');
        // TODO if pagesize > totalproducts, check styling of dropdown button, pagination links dissapear
        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const pageSize = parseInt(this.getAttribute('data_pages'));
                const totalProducts = {{ $products->total() }};

                if (pageSize < totalProducts) {
                    const url = new URL(window.location.href);
                    url.searchParams.set('perPage', pageSize);
                    window.location.href = url.toString();
                    return;
                } else {
                    const url = new URL(window.location.href);
                    url.searchParams.set('perPage', totalProducts);
                    window.location.href = url.toString();
                    return;
                }
            });
        });
    });

    // set pagination amount datas
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
