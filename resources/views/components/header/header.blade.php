<style>
    input::placeholder {
        color: #D3D3D3;
        opacity: 1;
        font-weight: 400;
    }

    input::-ms-input-placeholder {
        color: #D3D3D3;
        font-weight: 400;
    }
</style>

<div class="w-full h-[5.05rem] absolute shadow-[0_1px_14px_-5px_rgba(0,0,0,0.3)] bg-white flex items-center">
    <button class="px-11 pt-1 relative left-[0.08rem]">
        <a href="{{ url('/dashboard') }}"><img class="w-[10.75] h-[1.31rem] flex" src="../images/ximostock-logo.png" alt="ximostock logo"></a>
    </button>

    <div class="border-r-2 w-[0.08rem] h-[2.5rem] ml-[0.73rem] mt-1 bg-gray-200">
    </div>

    <div class="flex mt-[0.08rem] ml-[0.3rem] items-center">
        <form class="flex" method="GET" action="/products">
            <button type="submit" class="relative flex items-center left-10">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#D3D3D3"
                    class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </button>
            <input class="w-[30.5rem] h-[3.12rem] rounded-md pl-[3rem] pt-[0.1rem] pr-[1rem] text-[#717171] header-search"
            style="font-size: 16px; border:1px solid #D3D3D3;" name="search" type="text" placeholder="Search..." autocomplete="off">
            {{-- <input class="w-[30.5rem] h-[3.12rem] rounded-md pl-[3rem] pt-[0.1rem] pr-[1rem] text-[#717171] header-search"
            style="font-size: 16px; border:1px solid #D3D3D3;" name="search" type="text" placeholder="Search..." autocomplete="off" value="{{ $search }}"> --}}
        </form>
    </div>

    <div class="flex ml-auto mr-10">
        <div class="border-r-2 w-[0.08rem] h-[2.5rem] ml-[0.93rem] mr-[0.93rem] mt-2 bg-gray-200">
        </div>

        <div class="flex items-center">
            <div x-data="{ open: false, selectedProperty: '' }" x-cloak class="relative flex items-center justify-start text-left right-6">
                <input type="hidden" name="selected_property_id" x-bind:value="selectedProperty.id">
                <button @click="open = !open;"
                    class="flex items-center z-20 w-[10.53rem] px-[1.08rem] h-[2.78rem] text-sm font-light text-gray-700 bg-[#3dabd5] bottom-[0.05rem] border-1 border-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-100 relative left-6 top-[0.02rem]"
                    style="border: 1px solid white" x-cloak @click.away="open = false">
                    <!-- Display selected property name -->
                    <div class="flex mt-[0.08rem] relative right-[0.2rem]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="white" class="w-3 h-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
    
                    </div>
                    <span class="w-52 text-left text-[14px] text-white"
                        x-text="selectedProperty ? selectedProperty.name : 'Nieuw toevoegen'"></span>
                </button>
        
                <div x-cloak x-show="open"
                    class="absolute flex justify-center items-center w-[10.43rem] bg-[#3dabd5] divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-30 left-6 top-[3.2rem]">
                    <ul>
                        <template x-for="property in addButon" :key="property.data_pages">
                            <li>
                                <!-- Store property ID when clicked and log it -->
                                <button @click="selectedProperty = property; open = false;"
                                    class="flex items-center paginate-button block w-[10.43rem] px-4 py-2.5 text-sm text-white hover:bg-gray-100 focus:outline-none font-[300]"
                                    x-bind:data_pages="property.data_pages">
                                    <div class="flex  mt-[0.08rem] relative right-[0.2rem]">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="white" class="w-3 h-3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                    
                                    </div>
                                    <span class="flex items-center justify-center" x-text="property.name"></span>
                                </button>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>

        <div class="border-r-2 w-[0.08rem] h-[2.5rem] ml-[0.93rem] mr-[0.93rem] mt-2 bg-gray-200">
        </div>

        <div class="flex items-center gap-3.5">
            <a href="" class="w-[3.43rem] h-[3.43rem] bg-white rounded-full flex justify-center items-center"
                style="border: 1px solid #3dabd5;">
                <img class="w-[1.7rem] h-[2.1rem] flex mb-1" src="../images/user-icon.png" alt="user icon">
            </a>

            <div x-data="{ open: false, selectedProperty: '' }" x-cloak class="relative flex items-center justify-start text-left right-6">
                <input type="hidden" name="selected_property_id" x-bind:value="selectedProperty.id">
                <button @click="open = !open;"
                    class="flex items-center z-20 w-[9rem] px-[1.08rem] h-[2.68rem] text-sm font-light bottom-[0.05rem] border-1 border-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-100 relative left-6 top-[0.02rem]"
                    style="border: 1px solid #717171" x-cloak @click.away="open = false">
                    <!-- Display selected property name -->
                    <div class="flex mt-[0.08rem] relative right-[0.2rem]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="white" class="w-3 h-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
    
                    </div>
                    <span class="w-52 text-left text-[14px] text-gray-700 relative right-2"
                        x-text="selectedProperty ? selectedProperty.name : 'Jan janssen'">
                    </span>
                    <img class="w-[0.8rem] h-[0.5rem] flex mt-[0.30rem]" src="../images/arrow-down-icon.png"
                        alt="user icon">
                </button>
        
                <div x-cloak x-show="open"
                    class="absolute flex justify-center items-center h-[5.37rem] w-[10.43rem] bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-30 top-[3.2rem]" style="border: 1px solid #F0F0F0;">
                    <ul>
                        <template x-for="property in account" :key="property.data_pages">
                            <li>
                                <!-- Store property ID when clicked and log it -->
                                <button @click="selectedProperty = property; open = false;"
                                    class="paginate-button block w-[10.43rem] px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none"
                                    x-bind:data_pages="property.data_pages">
                                    <span class="flex items-center justify-center " x-text="property.name"></span>
                                </button>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.paginate-button');
        // TODO if pagesize > totalproducts, check styling of dropdown button, pagination links dissapear
        buttons.forEach(button => {
            button.addEventListener('click', function() {

            });
        });
    });

    // set pagination amount datas
    window.addButon = [{
            data_pages: 10,
            name: 'Product'
        },
        {
            data_pages: 20,
            name: 'Categorie'
        },
        {
            data_pages: 50,
            name: 'Eigenschap'
        },
    ];

    window.account = [{
            data_pages: 10,
            name: 'Instellingen'
        },
        {
            data_pages: 20,
            name: 'Uitloggen'
        },
    ];
    </script>