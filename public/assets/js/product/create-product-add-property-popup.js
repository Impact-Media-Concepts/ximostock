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
    
	//render alpine js dropdown button via js
    const newDropdownContainer = document.createElement('div');
    newDropdownContainer.id = `option_container_${variationPropertyId}`
    newDropdownContainer.classList.add("variationsPropDropdownContainer", "flex", "gap-[1rem]");
    newDropdownContainer.innerHTML = `
        <div x-data="{ open: false, selectedProperty: '' }" class="variationsPropDropdown fooo2  relative flex items-center justify-start text-left right-6">
            <input type="hidden" name="selected_property_id" x-bind:value="selectedProperty.id">
            <button @click="open = !open;" class="flex items-center z-20 w-[9rem] px-[1.08rem] h-[2.68rem] text-sm font-light bottom-[0.05rem] border-1 border-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#717171] focus:ring-offset-2 focus:ring-offset-gray-100 relative left-6 top-[0.02rem] variation-prop-btn-width" style="border: 1px solid #717171" type="button" @click.away="open = false">
                <span id="selected_variation_property_name_${variationPropertyId}" class="text-[14px] text-gray-700 line-clamp-1 relative right-2 w-full flex justify-start ml-[0.3rem] overflow-visible selectedPropertySpan" x-text="selectedProperty.name"></span>
                <div class="w-full flex justify-end">
                    <img class="select-none w-[0.8rem] h-[0.5rem] flex mt-[0.30rem]" src="${create_prod_add_prop_popup_app_url}/images/arrow-down-icon.png" alt="Arrow down">
                </div>
            </button>
            <div x-cloak x-show="open" class="absolute flex justify-center items-center overflow-y-auto propBtnHeight basic:h-[8rem] variation-prop-btn-width bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-30 top-[3.2rem]" style="border: 1px solid #F0F0F0; left: 1.5rem;">
                <ul class="mt-0 propBtnHeight" id="propertyListContainer"></ul>
            </div>
        </div>
        
        <div>
            <img class="w-[2rem] pt-[0.7rem]" src="${create_prod_add_prop_popup_app_url}/images/long-arrow-right-icon.png" alt="long arrow right icon">
        </div>
    `;
    
	//render options for dropdown button via js
	// variationAddPropsData is defined in component file
    variationAddPropsData.forEach(property => {
        const propertyListItem = document.createElement('li');
        propertyListItem.innerHTML = `
            <button type="button" class="variation-prop-btn-width hover:bg-[#3999BE] duration-100 block w-[10.43rem] px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none flex justify-start ml-[1rem]">
                <span id="selected_variation_property_value_${variationPropertyId}" class="flex items-center justify-center pr-3">${property.name}</span>
            </button>
        `;
        newDropdownContainer.querySelector("#propertyListContainer").appendChild(propertyListItem);
        
		//when a option in the list is clicked
        propertyListItem.querySelector('button').addEventListener('click', (event) => {
            const clickedButton = event.target;
            
            const selectedPropertySpan = clickedButton.closest('.variationsPropDropdown').querySelector('.selectedPropertySpan');
            const propertyNameSpan = clickedButton.querySelector('span');
            
            selectedPropertyName = propertyNameSpan.innerText;
            
            if (!selectedPropertyTextContent) {
                selectedPropertyTextContent = selectedPropertySpan.textContent.trim();
            }
            
			//set clicked option textcontent to button span text
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
            
			//render according options for properties
            variationAddPropsData.forEach(property => {
                if (selectedPropertyName === property.name) {
                    optionsContainer.innerHTML = `
                        <div x-data="{ open: false, selectedOption: '' }" class="variationsPropOptionsDropdown relative flex items-center justify-start text-left right-6">
                            <input type="hidden" name="selected_option_id" x-bind:value="selectedOption.id">
                            <button @click="open = !open;" class="variation-prop-btn-width flex items-center z-20 w-[9rem] px-[1.08rem] h-[2.68rem] text-sm font-light bottom-[0.05rem] border-1 border-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#717171] focus:ring-offset-2 focus:ring-offset-gray-100 relative left-6 top-[0.02rem]" style="border: 1px solid #717171" type="button" @click.away="open = false">
                                <span id="selected_option_name_${variationPropertyId}" class="text-[14px] text-gray-700 line-clamp-1 relative right-2 w-full flex justify-start ml-[0.5rem] overflow-visible selectedOptionSpan" x-text="selectedOption.name"></span>
                                <div class="w-full flex justify-end">
                                    <img class="select-none w-[0.8rem] h-[0.5rem] flex mt-[0.30rem]" src="${create_prod_add_prop_popup_app_url}/images/arrow-down-icon.png" alt="Arrow down">
                                </div>
                            </button>
                            <div x-cloak x-show="open" class="left-[1.7rem] variation-prop-btn-width absolute flex justify-center items-center overflow-y-auto propBtnHeight basic:h-[8rem] w-[10.43rem] bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-30 top-[3.2rem]" style="border: 1px solid #F0F0F0;">
                                <ul class="mt-0 propBtnHeight" id="optionsListContainer"></ul>
                            </div>
                        </div>
                        
                        <div class="removeVariationsDropdown" id="variationRemovePropBtn" style="background: gray">
                            <img class="w-[2rem] pt-[0.3rem] hover:cursor-pointer" src="${create_prod_add_prop_popup_app_url}/images/delete-icon.png" alt="long arrow right icon">
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
							
							//sets the objects with data
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

//function for removing values from objects
function removeObjectById(id) {
    variationPropertyData.values = variationPropertyData.values.filter(obj => obj.id !== id);
}