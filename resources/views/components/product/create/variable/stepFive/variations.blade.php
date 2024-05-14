@props(['properties', 'locations', 'locations'])

<?php
    $app_url = env('VITE_APP_URL');
?>

<style>
    .store-input {
        text-align: center;
    }

    .store-input::-webkit-outer-spin-button,
    .store-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .store-input[type=number] {
        -moz-appearance: textfield;
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

<div class='bg-white basic:h-[38rem] hd:h-[50rem] hd:w-[98rem] uhd:w-[138rem] uhd:h-[57rem] rounded-t-lg create-container-border'>
    <div class='h-[4.56rem] flex flex-col gap-[0.5rem] rounded-t-lg hd:relative hd:bottom[2.62rem]' style='border: 1px solid #F0F0F0;'>
        <div class='w-full ml-[1.56rem] mt-[0.6rem]'>
            <p class='font-bold text-[18px] text-[#717171]'>Lorem, ipsum dolor.</p>
            <p class='text-[14px]'>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ea autem corrupti officia provident, maxime distinctio!</p>
        </div>
    </div>

    <div class='w-full flex justify-center items-center mt-[1.5rem] '>
        <div class='basic:w-[67rem] hd:w-[94rem] uhd:w-[134rem]'>
            <div class='bg-[#3DABD5] rounded-t-lg h-[2.5rem] flex items-center justify-start'>
                <p class='pl-[1.56rem] text-[#fff]'>
                    Variaties aanmaken
                </p>
            </div>
            
            <div>
                <div class='h-[5.06rem] flex justify-start items-center gap-[2.3rem]' style='border: 1px solid #F0F0F0;'>
                    <button type='button' class='variations-add-prop-popup-trigger ml-[1.87rem] w-[10.68rem] h-[2.5rem] bg-[#3DABD5] hover:bg-[#3999BE] font-light rounded-md text-white'>
                        <p>
                            Nieuw toevoegen
                        </p>
                    </button>
                    <div x-data="{ open: false, selectedProperty: '' }" class='relative flex items-center justify-start text-left right-6'>
                        <button
                            type='button'
                            @click='open = !open;'
                            class='w-[12.18rem] h-[2.5rem] rounded-md hover:bg-gray-100 flex justify-center items-center text-[14px] text-gray-700'
                            style='border: 1px solid #717171' type='button' @click.away='open = false'>
                            <p class=''>
                                Bestaande toevoegen
                            </p>
                        </button>
                        <div class='flex justify-center'>
                            <div x-cloak x-show='open'
                                class='flex absolute mr-[7.9rem] flex-col justify-center items-center max-h-[36.16rem] w-[16.56rem] bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-30 top-[3rem]'
                                style='border: 1px solid #F0F0F0;'>
                                <div class='sticky flex justify-center top-0 w-full bg-white border-b border-gray-200'>
                                    <input @click.stop class='w-[14.06rem] h-[2.5rem] rounded-md mt-[1rem] mb-[1rem] pl-[1rem]' style='border: 1px solid #D3D3D3;' type='text' id='searchVariationPropertyTitles' placeholder='Zoeken' />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='flex flex-col items-center basic:h-[24.5rem] basic:max-h-[24.5rem] hd:h-[36.5rem] hd:max-h-[36.5rem] uhd:h-[43rem] uhd:max-h-[43rem] overflow-y-auto rounded-b-lg' style='border: 1px solid #F0F0F0;'>
                    <div class='general-prop-cont flex flex-col items-center gap-[0.8rem] mt-[0.8rem] pb-[0.8rem]'>
                        <ul class='mt-[0.85rem]' id='createProdPropertyList2'>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const locations = @json($locations);
    console.log(locations);
    let selectedLocationTextContent = null;
    let selectedLocationZoneName;
    let locationName;

    function renderCreatedVariation(variationObjects) {
        console.log("RECEIVED: ", variationObjects);
        // Build components for the clicked property
        const li = document.createElement('li');
        const container = document.getElementById('createProdPropertyList2');
        container.classList.add('basic:max-h-[16rem]', 'hd:max-h-[22.5rem]', 'uhd:max-h-[27rem]');
        li.id = `properties_li_${variationObjects.id}`;
        li.classList.add('py-[0.5rem]');
        
        const trueInput = document.createElement('input');
        trueInput.id = `properties[${variationObjects.id}]`;
        trueInput.type = 'hidden';
        trueInput.value = null;

        const propertyTitleContainer = document.createElement('div');
        propertyTitleContainer.classList.add('flex', 'items-center', 'h-[4.75rem]', 'basic:w-[62rem]', 'hd:w-[89rem]', 'uhd:w-[130rem]', 'bg-[#F8F8F8]', 'rounded-md', 'hover:cursor-pointer', 'border-t-lg', 'gap-[0.68rem]');
        propertyTitleContainer.style.border = '1px solid #D3D3D3';
        propertyTitleContainer.id = `variation_title_container_${variationObjects.id}`
        
        renderVariationOptions(variationObjects, propertyTitleContainer);

        const buttonsContainer = renderButtons(li, variationObjects, trueInput, propertyTitleContainer);

        propertyTitleContainer.appendChild(buttonsContainer);
        propertyTitleContainer.appendChild(trueInput);
        li.appendChild(propertyTitleContainer);

        renderVariationData(variationObjects, li, trueInput);
        container.appendChild(li);
    }

    function variationPropertyHandleCheckboxClick(property, trueInput, container) {
        const liIdNumber = extractIdNumber(trueInput.id); // Get numeric part of li id
        const divIdNumber = extractIdNumber(container.id);
        
        if (liIdNumber === divIdNumber) {
            trueInput.name = `newProperties[${liIdNumber}][value]`;
            property.checked = !property.checked;
            const div = document.getElementById(`variation_div_${liIdNumber}`);

            if (div.classList.contains('hidden')) {
                div.classList.remove('hidden');
            } else {
                div.classList.add('hidden');
            }
        }
    }

    function renderVariationData(property, li, trueInput) {
        const hiddenContainer = document.createElement('div');
        hiddenContainer.classList.add('w-full', 'flex', 'justify-center', 'items-center', 'select-none');
        const div = document.createElement('div');
        div.id = `variation_div_${property.id}`;
        div.classList.add('hidden', 'grid','hd:w-[89rem]', 'uhd:w-[130rem]', 'bg-[#F8F8F8]', 'rounded-b-lg', 'h-auto', 'justify-center', 'items-center');
        div.style.border = '1px solid #D3D3D3';

        const generalInfoContainer = renderGeneralInfo();
        const storeLocationContainer = renderStoreLocationInfo();
        
        div.appendChild(generalInfoContainer);
        div.appendChild(storeLocationContainer);
        hiddenContainer.appendChild(div);
        li.appendChild(div);
    }

    function renderGeneralInfo () {
        const generalInfoContainer = document.createElement('div');
        generalInfoContainer.classList.add('h-[8.93rem]', 'grid', 'grid-cols-2', 'gap-4', 'hd:w-[85rem]', 'uhd:w-[126rem]',);

        const eanContainer = document.createElement('div');
        eanContainer.classList.add('flex', 'flex-col');
        const eanTitle = document.createTextNode('EAN:')
        const eanInput = document.createElement('input');
        eanInput.classList.add('h-[2.5rem]');

        eanContainer.appendChild(eanTitle);
        eanContainer.appendChild(eanInput);

        const skuContainer = document.createElement('div');
        skuContainer.classList.add('flex', 'flex-col');
        const skuTitle = document.createTextNode('SKU:')
        const skuInput = document.createElement('input');
        skuInput.classList.add('h-[2.5rem]');

        skuContainer.appendChild(skuTitle);
        skuContainer.appendChild(skuInput);

        const priceContainer = document.createElement('div');
        priceContainer.classList.add('flex', 'flex-col');
        const priceTitle = document.createTextNode('Prijs:')
        const priceInput = document.createElement('input');
        priceInput.classList.add('h-[2.5rem]');

        priceContainer.appendChild(priceTitle);
        priceContainer.appendChild(priceInput);

        const discountContainer = document.createElement('div');
        discountContainer.classList.add('flex', 'flex-col');
        const discountTitle = document.createTextNode('Korting:')
        const discountInput = document.createElement('input');
        discountInput.classList.add('h-[2.5rem]');

        discountContainer.appendChild(discountTitle);
        discountContainer.appendChild(discountInput);

        generalInfoContainer.appendChild(eanContainer);
        generalInfoContainer.appendChild(skuContainer);
        generalInfoContainer.appendChild(priceContainer);
        generalInfoContainer.appendChild(discountContainer);

        return generalInfoContainer;
    }

    function renderStoreLocationInfo() {
        const storeLocationDataContainer = document.createElement('div');
        storeLocationDataContainer.className = 'w-full grid grid-cols-3 gap-[2rem] justify-center mb-[2rem]';

        const storeLocationContainer = document.createElement('div');
        storeLocationContainer.classList.add('flex', 'justify-center', 'items-center', 'flex-col', 'gap-[1rem]');
        storeLocationContainer.id = 'storeLocationContainer';
        const storeAmountContainer = document.createElement('div');
        storeAmountContainer.classList.add('flex', 'gap-[2rem]', 'w-full', 'mb-[1.5rem]');

        const decreaseStoreAmount = document.createElement('button');
        decreaseStoreAmount.textContent = 'DD';
        decreaseStoreAmount.classList.add('bg-[#3DABD5]', 'h-[3.75rem]', 'rounded-md', 'flex', 'justify-center', 'items-center', 'w-full');
        decreaseStoreAmount.type = "button";

        const storeAmount = document.createElement('input');
        storeAmount.classList.add('bg-[#F8F8F8]', 'h-[3.75rem]', 'rounded-md', 'font-bold', 'text-[17px]', 'flex', 'justify-center', 'items-center', 'store-input', 'px-[0.5rem]', 'w-full');
        storeAmount.style.border = '1px solid #717171';
        storeAmount.type = 'number';
        storeAmount.value = 0;
        storeAmount.id = 'store-amount';

        const increaseStoreAmount = document.createElement('button');
        increaseStoreAmount.textContent = 'II';
        increaseStoreAmount.classList.add('bg-[#3DABD5]', 'h-[3.75rem]', 'rounded-md', 'flex', 'justify-center', 'items-center', 'w-full');
        increaseStoreAmount.type = "button";

        const storeLocationDropdown = document.createElement('div');
        storeLocationDropdown.classList.add("variationsLocationDropdownContainer", "flex", "gap-[1rem]");

        storeLocationDropdown.innerHTML =  `
            <div x-data="{ open: false, selectedProperty: '' }" class="variationsLocationDropdown fooo2  relative flex items-center justify-start text-left right-6">
                <input type="hidden" name="selected_property_id" x-bind:value="selectedProperty.id">
                <button @click="open = !open;" class="flex items-center z-20 w-[9rem] px-[1.08rem] h-[2.68rem] text-sm font-light bottom-[0.05rem] border-1 border-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#717171] focus:ring-offset-2 focus:ring-offset-gray-100 relative left-6 top-[0.02rem] variation-prop-btn-width" style="border: 1px solid #717171" type="button" @click.away="open = false">
                    <span id="selected_variation_property_name_${id}" class="text-[14px] text-gray-700 line-clamp-1 relative right-2 w-full flex justify-start ml-[0.3rem] overflow-visible selectedLocationSpan" x-text="selectedProperty.name"></span>
                    <div class="w-full flex justify-end">
                        <img class="select-none w-[0.8rem] h-[0.5rem] flex mt-[0.30rem]" src="{{$app_url}}/images/arrow-down-icon.png" alt="Arrow down">
                    </div>
                </button>
                <div x-cloak x-show="open" class="absolute flex justify-center items-center overflow-y-auto propBtnHeight basic:h-[8rem] variation-prop-btn-width bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-30 top-[3.2rem]" style="border: 1px solid #F0F0F0; left: 1.5rem;">
                    <ul class="mt-0 propBtnHeight" id="locationListContainer"></ul>
                </div>
            </div>
        `;
        
        locations.forEach(location => {
            const locationListItem = document.createElement('li');

            const locationNameSpan = document.createElement('span');
            locationNameSpan.textContent = location.name;
            locationNameSpan.className = "variation-prop-btn-width duration-100 block w-[10.43rem] px-4 py-2 font-bold text-sm text-gray-700 flex justify-start ml-[1rem]";

            locationListItem.appendChild(locationNameSpan);

            location.location_zones.forEach(zone => {
                const zoneButton = document.createElement('button');
                zoneButton.type = "button";
                zoneButton.className = "variation-prop-btn-width hover:bg-[#3999BE] duration-100 block w-[10.43rem] font-normal px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none flex justify-start";

                zoneButton.addEventListener('click', () => {
                    const selectedLocationSpan = document.querySelector('.selectedLocationSpan');
                    
                    selectedLocationSpan.textContent = zone.name;
                    selectedLocationSpan.title = zone.name;
                    console.log("zone name: ", zone.name);
                    console.log("location Name 2: ", location.name);
                    selectedLocationZoneName = selectedLocationSpan.textContent;
                    locationName = location.name;
                    selectedLocationZoneName;
                    console.log("selectedLocationZoneName: ", selectedLocationZoneName);
                    console.log("locationName: ", locationName);
                });

                const zoneNameSpan = document.createElement('span');
                zoneNameSpan.className = "pl-[2rem]";
                zoneNameSpan.textContent = zone.name;
                zoneButton.appendChild(zoneNameSpan);
                locationListItem.appendChild(zoneButton);
            });

            storeLocationDropdown.querySelector("#locationListContainer").appendChild(locationListItem);

        });
       
        const storeLocationDropdownTitle = document.createTextNode('Doel / bestemming');




        const addStoreLocationBtn = document.createElement('button');
        addStoreLocationBtn.textContent = 'TOEVOEGEN';
        addStoreLocationBtn.classList.add('bg-[#3DABD5]', 'h-[3.75rem]', 'rounded-md', 'font-bold', 'text-[24px]', 'text-[#fff]', 'flex', 'justify-center', 'items-center', 'w-full');
        addStoreLocationBtn.type = "button";
        addStoreLocationBtn.id = 'add_store_location_btn'

        decreaseStoreAmount.addEventListener('click', function(event) {
            updateCounter(storeAmount, -1, addStoreLocationBtn, storeLocationContainer);
        });

        increaseStoreAmount.addEventListener('click', function(event) {
            updateCounter(storeAmount, 1, addStoreLocationBtn, storeLocationContainer);
        });

        addStoreLocationBtn.addEventListener('click', function() {
            if (storeAmount.value.trim() === '' || storeAmount.value.trim() === '0') {
                alert('Vul alstublieft een voorraad waarde in');
                return;
            }
            addLocation(storeAmount.value, storeLocationDataContainer, locationName, selectedLocationZoneName);
        });

        function updateCounter(amountValue, change) {
            let value = parseInt(amountValue.value);
            if (value + change < 0) {
                amountValue.value = 0;
            } else {
                value += change;
                amountValue.value = value;
            }
        }

        function addLocation(amountValue, container, location, locationZone) {
            console.log("received locationName: ", location);
            console.log("received locationName: ", locationZone);
            const locationDiv = document.createElement('div');
            locationDiv.classList.add('w-full', 'h-[7rem]', 'flex', 'flex-col', 'justify-center', 'items-center', 'rounded-md');

            const locationNameDiv = document.createElement('div');
            locationNameDiv.className = 'w-full flex justify-start items-center'
            const locationName = document.createElement('span');
            locationName.innerText = location;
            locationName.classList.add('h-[2.5rem]', 'flex', 'justify-center', 'items-center');

            const locationSubDiv = document.createElement('div');
            locationSubDiv.className = 'flex items-center justify-center w-full h-[7rem] bg-[#dcdcdc] px-[2rem] rounded-md';
            
            const locationZoneName = document.createElement('span');
            locationZoneName.innerText = locationZone;
            locationZoneName.classList.add('w-[11.56rem]', 'h-[2.5rem]', 'flex', 'justify-center', 'items-center');

            const amount = document.createElement('input');
            amount.classList.add('w-[11.56rem]', 'h-[2.5rem]', 'px-[0.5rem]', 'text-left', 'store-input', 'rounded-md');
            amount.value = amountValue;

            locationNameDiv.appendChild(locationName);
            locationSubDiv.appendChild(locationZoneName);
            locationSubDiv.appendChild(amount);

            locationDiv.appendChild(locationNameDiv);
            locationDiv.appendChild(locationSubDiv);

            container.appendChild(locationDiv);
        }

        storeAmountContainer.appendChild(decreaseStoreAmount);
        storeAmountContainer.appendChild(storeAmount);
        storeAmountContainer.appendChild(increaseStoreAmount);

        storeLocationDropdown.appendChild(storeLocationDropdownTitle);

        storeLocationContainer.appendChild(storeAmountContainer);
        storeLocationContainer.appendChild(storeLocationDropdown);
        storeLocationContainer.appendChild(addStoreLocationBtn);
        storeLocationContainer.appendChild(storeLocationDataContainer);
        

        return storeLocationContainer;
    }
    
    function renderButtons(li, variationObjects, trueInput, propertyTitleContainer) {
        const buttonsContainer = document.createElement('div');
        buttonsContainer.classList.add('w-full', 'flex', 'items-center', 'justify-end');

        const changePropContainer = document.createElement('div');
        changePropContainer.classList.add('pr-[1rem]', 'flex', 'justify-center', 'items-center');

        const changePropBtn = document.createElement('button');
        changePropBtn.type = 'button';
        changePropBtn.style.border = '1px solid #717172';
        changePropBtn.classList.add('w-[14.18rem]', 'h-[2.5rem]', 'rounded-md', 'hover:bg-gray-100', 'flex', 'items-center', 'justify-center');

        const changeImg = document.createElement('img');
        changeImg.src = '{{$app_url}}/images/edit-icon.png';
        changeImg.alt = 'edit icon';
        changeImg.classList.add('mr-[0.5rem]', 'hover:cursor-pointer', 'select-none');
        
        const changePropBtnText = document.createElement('p');
        const changeTextNode = document.createTextNode('Eigenschappen aanpassen');

        const delImgContainer = document.createElement('div');
        delImgContainer.classList.add('pr-[1rem]','flex', 'justify-center', 'items-center');

        const delPropBtn = document.createElement('button');
        delPropBtn.type = 'button';
        delPropBtn.style.border = '1px solid #717172';
        delPropBtn.classList.add('delete-props-btn', 'w-[11.18rem]', 'h-[2.5rem]', 'rounded-md', 'hover:bg-gray-100', 'flex', 'items-center', 'justify-center');

        deleteVariationPropertyItem(delPropBtn, li);

        const delImg = document.createElement('img');
        delImg.src = '{{$app_url}}/images/archive-icon.png';
        delImg.alt = 'delete icon';
        delImg.classList.add('mr-[0.5rem]', 'hover:cursor-pointer', 'select-none');
        
        const delPropBtnText = document.createElement('p');
        const textNode = document.createTextNode('Verwijderen');
        
        delPropBtnText.appendChild(textNode);
        delPropBtn.appendChild(delImg);
        delPropBtn.appendChild(delPropBtnText);
        delImgContainer.appendChild(delPropBtn);
        

        const arrowDownDiv = document.createElement('span');
        const arrowDown = document.createElement('img');
        arrowDownDiv.classList.add('flex', 'items-center', 'justify-end', 'select-none', 'mr-[1.5rem]');
        arrowDownDiv.appendChild(arrowDown);
        arrowDown.src = '{{$app_url}}/images/big-arrow-down-icon.png';
        arrowDown.alt = 'Arrow Down';
        arrowDown.classList.add('w-[1.2rem]', 'flex');

        propertyTitleContainer.addEventListener('click', () => {
            variationPropertyHandleCheckboxClick(variationObjects, trueInput, propertyTitleContainer);
            arrowDown.classList.toggle('rotate-arrow');
        });


        buttonsContainer.appendChild(changePropContainer);
        buttonsContainer.appendChild(delImgContainer);
        buttonsContainer.appendChild(arrowDownDiv);
        
        changePropBtnText.appendChild(changeTextNode);
        changePropBtn.appendChild(changeImg);
        changePropBtn.appendChild(changePropBtnText);
        changePropContainer.appendChild(changePropBtn);

        return buttonsContainer;
    }

    function renderVariationOptions(variationObjects, propertyTitleContainer) {
        variationObjects.forEach(variationProperty => {
            variationProperty.values.forEach(variationProp => {
                const propertyNameSpan = document.createElement('span');
                propertyNameSpan.textContent = variationProp.name;
                propertyNameSpan.classList.add('relative', 'bottom-[0.125rem]');
                propertyNameSpan.style.display = 'inline-flex';
                propertyNameSpan.style.width = '85%';
                propertyNameSpan.style.zIndex = 99;
                propertyNameSpan.classList.add('no-select');

                const propertyValueSpan = document.createElement('span');
                propertyValueSpan.textContent = variationProp.name;
                propertyValueSpan.classList.add('relative', 'bottom-[0.125rem]');
                propertyValueSpan.style.display = 'inline-flex';
                propertyValueSpan.style.width = '85%';
                propertyValueSpan.style.zIndex = 99;
                propertyValueSpan.classList.add('no-select');

                const propertyTextContainer = document.createElement('div');
                propertyTextContainer.classList.add('flex', 'items-center', 'h-[4.06rem]', 'p-[0.7rem]', 'gap-[0.5rem]', 'first:ml-[0.68rem]');
                propertyTextContainer.style.border = " 1px solid #F0F0F0";

                const nameTextSpan = document.createElement('span');
                const nameText = document.createTextNode(variationProp.name);
                nameTextSpan.classList.add('mt-[0.4rem]', 'px-[0.5rem]', 'items-center', 'justify-center', 'flex', 'rounded-md', 'bg-[#3DABD5]','h-[2.5rem]', 'relative', 'bottom-[0.125rem]', 'select-none', 'text-[14px]', 'whitespace-nowrap', 'text-[#fff]');
                nameTextSpan.appendChild(nameText);

                const valueTextSpan = document.createElement('span');
                const valueText = document.createTextNode(variationProp.value);
                valueTextSpan.classList.add('mt-[0.4rem]', 'px-[0.5rem]', 'items-center', 'justify-center', 'flex', 'bg-[#F8F8F8]', 'rounded-md', 'h-[2.5rem]', 'relative', 'bottom-[0.125rem]', 'select-none', 'text-[14px]', 'whitespace-nowrap', 'text-[#717171]');
                valueTextSpan.style.border = '1px solid #717172';
                valueTextSpan.appendChild(valueText);
                propertyTextContainer.appendChild(nameTextSpan);
                propertyTextContainer.appendChild(valueTextSpan);

                propertyTitleContainer.appendChild(propertyTextContainer);
            });
        });
    }

    function deleteVariationPropertyItem(deleteBtn, item) {
        deleteBtn.addEventListener('click', (event) => {
            event.stopPropagation();
            item.remove();
        });
    }

    
    
</script>
