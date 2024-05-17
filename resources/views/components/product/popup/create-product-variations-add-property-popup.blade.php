@props(['properties','selectedProperties' => null])

<!-- TODO: variationPropBtn meteen laten zien inplaats van eerst een prop kiezen. -->
<!-- TODO fix: soms bij het verwijderen of selecteren van een eigenschapsnaam geeft ie een error: -->
<!-- Uncaught TypeError: Cannot read properties of null (reading 'innerText') - het selecteren van een eigenschapsnaam -->
<!-- Uncaught TypeError: Cannot read properties of null (reading 'removeChild') - het verwijderen van een item -->

<?php
    $app_url = env('VITE_APP_URL');
    $propertyIds = $selectedProperties ? array_keys($selectedProperties) : [];
?>

<style>
    @media only screen and (min-width: 1280px) {
        .back-order-btn {
            width: 26rem;
        }
    }
    
    @media only screen and (min-width: 1920px) {
        .back-order-btn {
            width: 32rem;
        }
    }
    
    @media only screen and (min-width: 2560px) {
        .back-order-btn {
            width: 53rem;
        }
    }
    
    .back-order-btn-options {
        margin-top: 3.3rem;
    }
    
    .variation-prop-btn-width {
        width: 16.75rem;
    }
    
    .variation-prop-btn-right {
        right: 13rem;
    }
    
    .propBtnHeight {
        height: 11.3rem;
        max-height: 11.3rem;
    }
</style>

<div
    class="variations-add-prop-pop-up w-full h-full fixed top-0 bg-black bg-opacity-75 hidden pt-32 select-none left-0" style="z-index: 999;"
>
    <div
        x-transition
        class="variations-add-prop-pop-up-container relative w-[65.06rem] h-[33.68rem] bg-white pb-0 rounded-md text-center p-8 mx-auto mt-20 transform -translate-y-40 transition-transform duration-300"
    >   
        <div class="w-full h-full flex flex-row-reverse">
            <div class="h-[2.68rem] flex items-center relative left-[1rem] hover:cursor-pointer z-[1000]">
                <button type="button" id="variationAddPropBtn" class="flex justify-center gap-2 items-center create-propCancel w-[16.18rem] h-[2.68rem] hover:bg-gray-100 rounded-md" style="border: 1px solid #717172;">
                    <p class="flex text-[#717171]">Eigenschap toevoegen</p>
                </button>
            </div>
            
            <div class="relative bottom-8">
                <div class="w-[43.93rem] h-[29.7rem] mt-[2rem]" style="border: 1px solid #f0f0f0; border-radius: 10px;">
                    <div class="w-[43.93rem] h-[2.5rem] bg-[#3DABD5] rounded-t-lg flex justify-start items-center pl-4 text-white">
                        <p>Eigenschappen</p>
                    </div>
                    
                    <div id="variationPropContainer" class="max-h-[27rem] h-[27rem] overflow-y-auto pt-[1rem] pb-[1rem]">
                        <ul id="ul_item_container" class="flex items-center justify-start gap-[0.5rem] ml-[1rem]">
                            <div class="flex gap-[13rem] flex-col" id="variationAddPropBtnsContainer"></div>
                        </ul>
                    </div>
                    
                    <div class="create-prop-buttons flex items-center gap-[0.7rem] absolute bottom-0 left-[44.8rem]">
                        <button type="button" class="variations-add-prop-close flex justify-center gap-2 items-center create-propCancel w-[7.87rem] h-[2.68rem] hover:bg-gray-100 rounded-md" style="border: 1px solid #717172;">
                            <img class="variations-add-prop-close select-none w-[0.8rem] h-[0.8rem] flex" src="{{$app_url}}/images/x-icon.png" alt="x icon">
                            <p class="variations-add-prop-close flex text-[#717171]">Annuleren</p>
                        </button>
                        <button id="addVariationBtn" type="button" class="flex justify-center items-center w-[7.87rem] h-[2.68rem] bg-[#3DABD5] rounded-md hover:bg-[#3999BE] gap-[0.5rem]">
                            <img src="{{$app_url}}/images/save-icon.png">
                            <p class="flex text-[#F8F8F8]">Voeg toe</p>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let variationAddPropsData = [
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
    const create_prod_add_prop_popup_app_url = {!! json_encode($app_url) !!};
</script>
<script type="text/javascript" src="{{ asset('./assets/js/product/create-product-add-property-popup.js') }}"></script>