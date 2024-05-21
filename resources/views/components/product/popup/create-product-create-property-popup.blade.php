@props(['properties'])

<!-- sets url path for img -->
<?php
    $app_url = env('VITE_APP_URL');
?>

<div
    class="create-prop-pop-up w-full h-full fixed top-0 bg-black bg-opacity-75 hidden pt-32 select-none left-0" style="z-index: 999;"
>
    <div
        x-transition
        class="create-prop-pop-up-container relative w-[44rem] h-[38rem] bg-white pb-0 rounded-md text-center p-8 mx-auto mt-20 transform -translate-y-40 transition-transform duration-300"
    >
        <div
            class="w-[2rem] create-prop-close flex items-center relative bottom-4 left-[39rem] hover:cursor-pointer z-[1000]"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="2"
                stroke="gray"
                class="create-prop-close select-none flex items-center justify-center w-8 h-8"
            >
                <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M6 18 18 6M6 6l12 12"
                class="create-prop-close"
                />
            </svg>
        </div>
        
        <div class="relative bottom-8">
            <div class="w-[40.87rem] h-[33rem] mt-[2rem]" style="border: 1px solid #f0f0f0; border-radius: 10px;">
                <div class="w-[40.87rem] h-[2.5rem] bg-[#3DABD5] rounded-t-lg flex justify-start items-center pl-4 text-white">
                    <p>Eigenschappen toevoegen aan variatie</p>
                </div>
                <form id="propertyForm" action="/properties" method="POST">
                    @csrf
                    
                    <div class="flex px-[2rem] mt-[1rem]">
                        <div class="w-full flex flex-col items-start justify-center gap-[0.3rem]">
                            <label for="newPropertyName">Eigenschap naam:</label>
                            <input class="w-[13rem] h-[2.5rem] rounded-md px-[0.8rem]" style="border: 1px solid #D3D3D3;" type="text" name='' id="newPropertyName" autocomplete="off" required>
                        </div>
                        
                        <div class="w-full flex flex-col items-end justify-center gap-[0.3rem]">
                            <label for="newPropertyType">Eigenschap type:</label>
                            <select id="newPropertyType" name="type" required>
                                <option value=""></option>
                                <option value="singleselect">Single Select</option>
                                <option value="multiselect">Multi Select</option>
                                <option value="number">number</option>
                                <option value="bool">bool</option>
                                <option value="text">text</option>
                            </select>
                        </div>
                    </div>
                    
                    <input type="hidden" name='' id="propertyNameName" required>
                    <input type="hidden" name='' id="valueInput" required>
                    <input type="hidden" name='' id="optionsInputField" required>
                    <ul id="optionsList" class="overflow-y-auto max-h-[16rem] flex flex-col w-full justify-flex-start items-flex-start">
                    
                    </ul>
                    <button id="addOptionButton" type="button" class="hidden flex justify-center items-center w-[16rem] absolute top-[25rem] left-[23.3rem] h-[2.5rem] bg-[#3DABD5] rounded-md hover:bg-[#3999BE] gap-[0.5rem]">
                        <img class="" src="{{$app_url}}/images/save-icon.png">
                        <p class=" flex text-[#F8F8F8]">Voeg 1 eigenschapswaarde toe</p>
                    </button>
                </form>
                
                <div class="create-prop-buttons flex items-center gap-[0.7rem] absolute bottom-[1.1rem] right-[0.3rem]">
                    <button type="button" class="create-prop-cancel flex justify-center gap-2 items-center create-prop-cancel w-[7.87rem] h-[2.68rem] hover:bg-gray-100 rounded-md" style="border: 1px solid #717172;">
                        <img class="create-prop-cancel select-none w-[0.8rem] h-[0.8rem] flex" src="{{$app_url}}/images/x-icon.png" alt="x icon">
                        <p class="create-prop-cancel flex text-[#717171]">Annuleren</p>
                    </button>
                    <button id="createNewPropertyBtn" type="button" class="create-property-create-btn flex justify-center items-center w-[7.87rem] h-[2.68rem] bg-[#3DABD5] rounded-md hover:bg-[#3999BE] gap-[0.5rem]">
                        <img class="create-property-create-btn" src="{{$app_url}}/images/save-icon.png">
                        <p class="create-property-create-btn flex text-[#F8F8F8]">Toevoegen</p>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let addPropertyData = [
        @foreach ( $properties as $property)
            {
                id:{{$property->id}},
                name: '{{ $property->name }}',
                type: '{{ $property->type }}',
                options:[
                    @foreach ($property->options as $option)
                        "{{$option}}",
                    @endforeach
                ]
            },
        @endforeach
    ];
    const create_prod_create_prop_popup_app_url = {!! json_encode($app_url) !!};
</script>
<script type="text/javascript" src="{{ asset('./assets/js/product/create-product-create-property-popup.js') }}"></script>
