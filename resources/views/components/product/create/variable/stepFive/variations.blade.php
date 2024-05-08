@props(['properties'])

<?php
    $app_url = env('VITE_APP_URL');
?>

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

                            <!-- <li id="new_properties_li_1" class="pt-[0.35rem] pb-[0.35rem]">
                                <div class="flex items-center h-[4.75rem] basic:w-[62rem] hd:w-[89rem] uhd:w-[130rem] bg-[#F8F8F8] rounded-md hover:cursor-pointer border-t-lg" id="newPropertyItemContainer_1" style="border: 1px solid rgb(211, 211, 211);">
                                    <span class="ml-[2.5rem] font-bold relative bottom-[0.125rem] select-none text-[18px] whitespace-nowrap">asd (number)</span>
                                    <div class="pr-[1rem] w-full flex justify-end items-center">
                                        <button type="button" class="delete-props-btn w-[11.18rem] h-[2.5rem] rounded-md hover:bg-gray-100 flex items-center justify-center" style="border: 1px solid rgb(113, 113, 114);">
                                            <img src="http://127.0.0.1:8000/images/archive-icon.png" alt="delete icon" class="mr-[0.5rem] hover:cursor-pointer select-none">
                                            <p>Verwijderen</p>
                                        </button>
                                    </div>
                                
                                    <span class="flex items-center justify-end select-none mr-[1.5rem]">
                                    <img src="http://127.0.0.1:8000/images/big-arrow-down-icon.png" alt="Arrow Down" class="w-[1.2rem] flex mt-[0.18rem] rotate-arrow"></span>
                                    <input id="new_properties[1]" type="hidden" value="" name="newProperties[1][value]">
                                    <input name="newProperties[1][name]" type="hidden" value="asd">
                                    <input name="newProperties[1][type]" type="hidden" value="number">
                                </div>
                                <div id="new_prop_div_1" class="hidden grid h-[6.58rem] hd:w-[89rem] uhd:w-[130rem] bg-[#F8F8F8] rounded-b-lg" style="border: 1px solid rgb(211, 211, 211); height: 4.58rem;">
                                    <div class="flex items-center ml-[2rem]">
                                        <div class="flex rounded-md basic:w-[31rem] hd:w-[41rem] uhd:w-[50.6rem] h-[2.12rem]" style="border: 1px solid rgb(211, 211, 211);">
                                            <div class="w-[2.12rem] h-[2.12rem] flex items-center justify-center hover:cursor-pointer active:bg-gray-100 rounded-md">
                                                <img class="select-none" src="http://127.0.0.1:8000/images/minus-icon.png">
                                            </div>
                                            <input type="number" class="numberInput text-center basic:w-[31rem] hd:w-[41rem] uhd:w-[50.6rem] h-[2.12rem] flex">
                                            <div class="w-[2.12rem] h-[2.12rem] flex items-center justify-center hover:cursor-pointer active:bg-gray-100 rounded-md" style="border: 1px solid rgb(211, 211, 211);">
                                                <img class="select-none" src="http://127.0.0.1:8000/images/plus-icon.png">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li> -->

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // const container = document.getElementById('createProdPropertyList2');
    // const hoi = document.getElementById('newPropertyItemContainer');
    // const addnewTestBtn = document.getElementById('addnewTest');
    // const li = document.createElement('li');

    function renderCreatedVariation(variationProperty) {
        
        console.log("RECEIVED: ", variationProperty);
        const li = document.createElement('li');
        const container = document.getElementById('createProdPropertyList2');
        container.classList.add('basic:max-h-[16rem]', 'hd:max-h-[22.5rem]', 'uhd:max-h-[27rem]');
        li.id = `properties_li_${variationProperty.id}`;
        li.classList.add('pt-[0.35rem]', 'pb-[0.35rem]');
        
        // Build components for the clicked property
        const trueInput = document.createElement('input');
        trueInput.id = `properties[${variationProperty.id}]`;
        trueInput.type = 'hidden';
        trueInput.value = null;

        const propertyNameSpan = document.createElement('span');
        propertyNameSpan.textContent = variationProperty.name;
        propertyNameSpan.classList.add('relative', 'bottom-[0.125rem]');
        propertyNameSpan.style.display = 'inline-flex';
        propertyNameSpan.style.width = '85%';
        propertyNameSpan.style.zIndex = 99;
        propertyNameSpan.classList.add('no-select');

        const propertyValueSpan = document.createElement('span');
        propertyValueSpan.textContent = variationProperty.name;
        propertyValueSpan.classList.add('relative', 'bottom-[0.125rem]');
        propertyValueSpan.style.display = 'inline-flex';
        propertyValueSpan.style.width = '85%';
        propertyValueSpan.style.zIndex = 99;
        propertyValueSpan.classList.add('no-select');
        
        const arrowDownDiv = document.createElement('span');
        const arrowDown = document.createElement('img');
        arrowDownDiv.classList.add('flex', 'items-center', 'justify-end', 'select-none', 'mr-[1.5rem]');
        arrowDownDiv.appendChild(arrowDown);
        arrowDown.src = '{{$app_url}}/images/big-arrow-down-icon.png';
        arrowDown.alt = 'Arrow Down';
        arrowDown.classList.add('w-[1.2rem]', 'flex', 'mt-[0.18rem]');
        

        const propertyTextContainer = document.createElement('div');
        propertyTextContainer.classList.add('flex', 'items-center', 'h-[4.06rem]', 'p-[0.7rem]');
        propertyTextContainer.style.border = " 1px solid #F0F0F0";

        const nameTextSpan = document.createElement('span');
        const nameText = document.createTextNode(variationProperty.name);
        nameTextSpan.classList.add('px-[0.5rem]', 'items-center', 'justify-center', 'flex', 'rounded-md', 'bg-[#3DABD5]','h-[2.5rem]', 'relative', 'bottom-[0.125rem]', 'select-none', 'text-[14px]', 'whitespace-nowrap', 'text-[#fff]');
        nameTextSpan.appendChild(nameText);

        const valueTextSpan = document.createElement('span');
        const valueText = document.createTextNode(variationProperty.value);
        valueTextSpan.classList.add('px-[0.5rem]', 'items-center', 'justify-center', 'flex', 'bg-[#F8F8F8]', 'rounded-md','ml-[0.5rem]', 'h-[2.5rem]', 'relative', 'bottom-[0.125rem]', 'select-none', 'text-[14px]', 'whitespace-nowrap', 'text-[#717171]');
        valueTextSpan.style.border = '1px solid #717172';
        valueTextSpan.appendChild(valueText);
        
        const propertyTitleContainer = document.createElement('div');
        propertyTitleContainer.classList.add('flex', 'items-center', 'h-[4.75rem]', 'basic:w-[62rem]', 'hd:w-[89rem]', 'uhd:w-[130rem]', 'bg-[#F8F8F8]', 'rounded-md', 'hover:cursor-pointer', 'border-t-lg');
        propertyTitleContainer.style.border = '1px solid #D3D3D3';
        
        propertyTitleContainer.addEventListener('click', () => {
            // propertyHandleCheckboxClick(variationProperty, trueInput);
            arrowDown.classList.toggle('rotate-arrow');
        });

        const delImgContainer = document.createElement('div');
        delImgContainer.classList.add('pr-[1rem]','flex', 'justify-end', 'items-center');

        const delPropBtn = document.createElement('button');
        delPropBtn.type = 'button';
        delPropBtn.style.border = '1px solid #717172';
        delPropBtn.classList.add('delete-props-btn', 'w-[11.18rem]', 'h-[2.5rem]', 'rounded-md', 'hover:bg-gray-100', 'flex', 'items-center', 'justify-center');

        delPropBtn.addEventListener('click', (event) => {
            event.stopPropagation();
            li.remove();
        });

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
        


        const changePropContainer = document.createElement('div');
        changePropContainer.classList.add('pr-[1rem]', 'w-full', 'flex', 'justify-end', 'items-center');

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
        
        changePropBtnText.appendChild(changeTextNode);
        changePropBtn.appendChild(changeImg);
        changePropBtn.appendChild(changePropBtnText);
        changePropContainer.appendChild(changePropBtn);

        propertyTextContainer.appendChild(nameTextSpan);
        propertyTextContainer.appendChild(valueTextSpan);

        propertyTitleContainer.appendChild(propertyTextContainer);
        propertyTitleContainer.appendChild(changePropContainer);
        propertyTitleContainer.appendChild(delImgContainer);
        propertyTitleContainer.appendChild(arrowDownDiv);
        propertyTitleContainer.appendChild(trueInput);
        li.appendChild(propertyTitleContainer);
        container.appendChild(li);
    }
</script>
