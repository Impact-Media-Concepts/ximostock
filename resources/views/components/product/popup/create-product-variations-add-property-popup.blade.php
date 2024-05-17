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
    
    const mainPropsBtnsContainer = document.getElementById('variationAddPropBtnsContainer');
    const addNewDropdownButton = document.getElementById('variationAddPropBtn');
    const addVariationBtn = document.getElementById('addVariationBtn');
    const hideCreateVariationPopup = document.querySelector('.variations-add-prop-pop-up');
    const variationAddPropBtnsContainer = document.getElementById('variationAddPropBtnsContainer');
    const cancelBtn = document.querySelector('.variations-add-prop-close');
    
    let currentDropdownContainer = null; // Define currentDropdownContainer outside the event listener scope
    let selectedPropertyTextContent = null; // Track the initial text content of selectedPropertySpan
    let variationPropertyId = 0;
    
    let selectedPropertyName;
    let selectedOptionName;
    let variationPropertiesDatass = [];
    
    let variationPropertyData = {
        values: []
    };
    
    addNewDropdownButton.addEventListener('click', (event) => {
        variationPropertyId++;
        
        const newDropdownContainer = document.createElement('div');
        newDropdownContainer.id = `option_container_${variationPropertyId}`
        newDropdownContainer.classList.add("variationsPropDropdownContainer", "flex", "gap-[1rem]");
        newDropdownContainer.innerHTML = `
            <div x-data="{ open: false, selectedProperty: '' }" class="variationsPropDropdown fooo2  relative flex items-center justify-start text-left right-6">
                <input type="hidden" name="selected_property_id" x-bind:value="selectedProperty.id">
                <button @click="open = !open;" class="flex items-center z-20 w-[9rem] px-[1.08rem] h-[2.68rem] text-sm font-light bottom-[0.05rem] border-1 border-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#717171] focus:ring-offset-2 focus:ring-offset-gray-100 relative left-6 top-[0.02rem] variation-prop-btn-width" style="border: 1px solid #717171" type="button" @click.away="open = false">
                    <span id="selected_variation_property_name_${variationPropertyId}" class="text-[14px] text-gray-700 line-clamp-1 relative right-2 w-full flex justify-start ml-[0.3rem] overflow-visible selectedPropertySpan" x-text="selectedProperty.name"></span>
                    <div class="w-full flex justify-end">
                        <img class="select-none w-[0.8rem] h-[0.5rem] flex mt-[0.30rem]" src="{{$app_url}}/images/arrow-down-icon.png" alt="Arrow down">
                    </div>
                </button>
                <div x-cloak x-show="open" class="absolute flex justify-center items-center overflow-y-auto propBtnHeight basic:h-[8rem] variation-prop-btn-width bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-30 top-[3.2rem]" style="border: 1px solid #F0F0F0; left: 1.5rem;">
                    <ul class="mt-0 propBtnHeight" id="propertyListContainer"></ul>
                </div>
            </div>
            
            <div>
                <img class="w-[2rem] pt-[0.7rem]" src="{{$app_url}}/images/long-arrow-right-icon.png" alt="long arrow right icon">
            </div>
        `;
        
        variationAddPropsData.forEach(property => {
            const propertyListItem = document.createElement('li');
            propertyListItem.innerHTML = `
                <button type="button" class="variation-prop-btn-width hover:bg-[#3999BE] duration-100 block w-[10.43rem] px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none flex justify-start ml-[1rem]">
                    <span id="selected_variation_property_value_${variationPropertyId}" class="flex items-center justify-center pr-3">${property.name}</span>
                </button>
            `;
            newDropdownContainer.querySelector("#propertyListContainer").appendChild(propertyListItem);
            
            propertyListItem.querySelector('button').addEventListener('click', (event) => {
                const clickedButton = event.target;
                
                const selectedPropertySpan = clickedButton.closest('.variationsPropDropdown').querySelector('.selectedPropertySpan');
                const propertyNameSpan = clickedButton.querySelector('span');
                
                selectedPropertyName = propertyNameSpan.innerText;
                
                if (!selectedPropertyTextContent) {
                    selectedPropertyTextContent = selectedPropertySpan.textContent.trim();
                }
                
                selectedPropertySpan.textContent = selectedPropertyName;
                selectedPropertySpan.title = selectedPropertyName;
                
                let optionsContainer = newDropdownContainer.querySelector('.propertyOptionsContainer'); // Reference the specific options container within this container
                if (!optionsContainer) {
                    optionsContainer = document.createElement('div');
                    optionsContainer.classList.add('propertyOptionsContainer', 'flex', "gap-[1rem]");
                    newDropdownContainer.appendChild(optionsContainer);
                } else {
                    optionsContainer.innerHTML = '';
                }
                
                variationAddPropsData.forEach(property => {
                    if (selectedPropertyName === property.name) {
                        optionsContainer.innerHTML = `
                            <div x-data="{ open: false, selectedOption: '' }" class="variationsPropOptionsDropdown relative flex items-center justify-start text-left right-6">
                                <input type="hidden" name="selected_option_id" x-bind:value="selectedOption.id">
                                <button @click="open = !open;" class="variation-prop-btn-width flex items-center z-20 w-[9rem] px-[1.08rem] h-[2.68rem] text-sm font-light bottom-[0.05rem] border-1 border-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#717171] focus:ring-offset-2 focus:ring-offset-gray-100 relative left-6 top-[0.02rem]" style="border: 1px solid #717171" type="button" @click.away="open = false">
                                    <span id="selected_option_name_${variationPropertyId}" class="text-[14px] text-gray-700 line-clamp-1 relative right-2 w-full flex justify-start ml-[0.5rem] overflow-visible selectedOptionSpan" x-text="selectedOption.name"></span>
                                    <div class="w-full flex justify-end">
                                        <img class="select-none w-[0.8rem] h-[0.5rem] flex mt-[0.30rem]" src="{{$app_url}}/images/arrow-down-icon.png" alt="Arrow down">
                                    </div>
                                </button>
                                <div x-cloak x-show="open" class="left-[1.7rem] variation-prop-btn-width absolute flex justify-center items-center overflow-y-auto propBtnHeight basic:h-[8rem] w-[10.43rem] bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-30 top-[3.2rem]" style="border: 1px solid #F0F0F0;">
                                    <ul class="mt-0 propBtnHeight" id="optionsListContainer"></ul>
                                </div>
                            </div>
                            
                            <div class="removeVariationsDropdown" id="variationRemovePropBtn" style="background: gray">
                                <img class="w-[2rem] pt-[0.3rem] hover:cursor-pointer" src="{{$app_url}}/images/delete-icon.png" alt="long arrow right icon">
                            </div>
                        </ul>
                        `;
                        
                        property.options.forEach(option => {
                            const optionListItem = document.createElement('li');
                            optionListItem.innerHTML = `
                                <button type="button" class="variation-prop-btn-width hover:bg-[#3999BE] duration-100 block w-[10.43rem] px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none flex justify-start ml-[1rem]">
                                    <span id="options_item_${variationPropertyId}" class="flex items-center justify-center pr-3"> ${option} </span>
                                </button>
                            `;
                            optionListItem.querySelector('button').addEventListener('click', (event) => {
                                const clickedOptionButton = event.currentTarget;
                                
                                const selectedOptionSpan = clickedOptionButton.closest('.variationsPropOptionsDropdown').querySelector('.selectedOptionSpan');
                                const optionSpan = clickedOptionButton.querySelector('span');
                                selectedOptionName = optionSpan.innerText;
                                
                                selectedOptionSpan.textContent = selectedOptionName;
                                selectedOptionSpan.title = selectedOptionName;
                                variationPropertyData.values.push({
                                    name: selectedPropertyName,
                                    value: selectedOptionName,
                                    id: property.id
                                });
                            });
                            optionsContainer.querySelector("#optionsListContainer").appendChild(optionListItem);
                        });
                        
                        const removeButtons = document.querySelectorAll(".removeVariationsDropdown");
                        removeButtons.forEach((removeButton, index) => {
                            removeButton.addEventListener('click', (event) => {
                                const itemToRemove = event.target.closest('.variationsPropDropdownContainer');
                                //TODO: remove object from variationPropertyData array
                                itemToRemove.parentNode.removeChild(itemToRemove);
                                removeObjectById(property.id);
                                variationPropertiesDatass.splice(index, 1);
                            });
                        });
                    }
                })
            });
        });
        mainPropsBtnsContainer.appendChild(newDropdownContainer);
    });
    
    let variationPropObjectId = 0;
    
    addVariationBtn.addEventListener('click', () => {
        variationPropertiesDatass.push(variationPropertyData);
        
        variationPropObjectId++
        if (variationPropertiesDatass.length !== 0 && variationPropertyData.values.length !== 0) {
            // Checks for duplicate names
            const nameOccurrences = {};
            let hasDuplicates = false;
            variationPropertyData.values.forEach(item => {
                if (nameOccurrences[item.name]) {
                    hasDuplicates = true;
                } else {
                    nameOccurrences[item.name] = true;
                }
            });
            
            if (hasDuplicates) {
                alert("Kan niet meerder eigenschapsnamen hebben die gelijk zijn.");
            } else {
                // code for popup close and open animation
                hideCreateVariationPopup.classList.add('fade-out');
                hideCreateVariationPopup.addEventListener('animationend', function(event) {
                    if (event.animationName === 'fadeOut') {
                        hideCreateVariationPopup.classList.add('hidden');
                    }
                }, false);
                hideCreateVariationPopup.classList.remove('fade-in');
                
                variationPropertiesDatass.id = variationPropObjectId;
                
                // send data to function
                renderCreatedVariation(variationPropertyData);
                
                //clear fields
                variationPropertiesDatass = [];
                variationPropertyData = {
                    values: []
                };
                variationAddPropBtnsContainer.innerHTML = '';
            }
        } else if (variationPropertiesDatass.length === 0) {
            alert("Kies alstublieft een eigenschapsnaam of waarde.");
        } else if (variationPropertyData.values.length === 0) {
            variationPropertiesDatass = [];
            alert("Kies alstublieft een eigenschapsnaam of waarde.");
        }
    })
    
    cancelBtn.addEventListener('click', () => {
        //clear fields
        variationPropertiesDatass = [];
        variationPropertyData = {
            values: []
        };
        variationAddPropBtnsContainer.innerHTML = '';
    });
    
    function removeObjectById(id) {
        variationPropertyData.values = variationPropertyData.values.filter(obj => obj.id !== id);
    }
</script>
