
const clickedProperties = [];
const seachPropertyTitles = document.getElementById('seachPropertyTitles');

function searchPropertyTitles(propertySearchText) {
    propertyTitleData.forEach((property) => {
        const li = document.getElementById(`add_property_li_${property.id}`);
        if (
            !propertySearchText || property.name.toLowerCase().includes(propertySearchText.toLowerCase()) || property.type.toLowerCase().includes(propertySearchText.toLowerCase())
        ) {
            li.classList.remove('hidden');
        } else {
            li.classList.add('hidden');
        }
    });
}

if (seachPropertyTitles) {
    seachPropertyTitles.addEventListener('input', () => {
        const propertySearchTitles = seachPropertyTitles.value.trim();
        
        // Render propertys if search input is empty
        if (!propertySearchTitles) {
            renderPropertyTitles(); //vervang met show all
        } else {
            searchPropertyTitles(propertySearchTitles);
        }
    });
}

function renderCreatedProperties(property, inputName, inputType, inputOptions) {
    const createProdPropertyList = document.getElementById('createProdPropertyList');
    // createProdPropertyList.innerHTML = '';
    createProdPropertyList.classList.add('basic:max-h-[16rem]', 'hd:max-h-[22.5rem]', 'uhd:max-h-[27rem]');
    const li = document.createElement('li');
    li.id = `new_properties_li_${property.id}`;
    li.classList.add('pt-[0.35rem]', 'pb-[0.35rem]');
    
    // Build components for the clicked property
    const trueInput = document.createElement('input');
    trueInput.id = `new_properties[${property.id}]`;
    trueInput.type = 'hidden';
    trueInput.value = null;
    
    const propertyNameSpan = document.createElement('span');
    propertyNameSpan.textContent = property.name;
    propertyNameSpan.classList.add('relative', 'bottom-[0.125rem]');
    propertyNameSpan.style.display = 'inline-flex';
    propertyNameSpan.style.width = '85%';
    propertyNameSpan.style.zIndex = 99;
    propertyNameSpan.classList.add('no-select');
    
    const arrowDownDiv = document.createElement('span');
    const arrowDown = document.createElement('img');
    arrowDownDiv.classList.add('flex', 'items-center', 'justify-end', 'select-none', 'mr-[1.5rem]');
    arrowDownDiv.appendChild(arrowDown);
    arrowDown.src = `${create_property_app_url}/images/big-arrow-down-icon.png`;
    arrowDown.alt = 'Arrow Down';
    arrowDown.classList.add('w-[1.2rem]', 'flex', 'mt-[0.18rem]');
    
    const textSpan = document.createElement('span');
    const text = document.createTextNode(property.name + ` (${property.type})`);
    textSpan.classList.add('ml-[2.5rem]', 'font-bold', 'relative', 'bottom-[0.125rem]', 'select-none', 'text-[18px]', 'whitespace-nowrap');
    textSpan.appendChild(text);
    
    const propertyTitleContainer = document.createElement('div');
    propertyTitleContainer.classList.add('flex', 'items-center', 'h-[4.75rem]', 'basic:w-[62rem]', 'hd:w-[89rem]', 'uhd:w-[130rem]', 'bg-[#F8F8F8]', 'rounded-md', 'hover:cursor-pointer', 'border-t-lg');
    propertyTitleContainer.style.border = '1px solid #D3D3D3';
    propertyTitleContainer.id = `newPropertyItemContainer_${property.id}`;
    
    propertyTitleContainer.addEventListener('click', () => {
        propertyHandleCreateCheckboxClick(property, trueInput, propertyTitleContainer);
        arrowDown.classList.toggle('rotate-arrow');
    });
    
    const delImgContainer = document.createElement('div');
    delImgContainer.classList.add('pr-[1rem]', 'w-full', 'flex', 'justify-end', 'items-center');
    
    const delPropBtn = document.createElement('button');
    delPropBtn.type = 'button';
    delPropBtn.style.border = '1px solid #717172';
    delPropBtn.classList.add('delete-props-btn', 'w-[11.18rem]', 'h-[2.5rem]', 'rounded-md', 'hover:bg-gray-100', 'flex', 'items-center', 'justify-center');
    
    delPropBtn.addEventListener('click', (event) => {
        event.stopPropagation();
        li.remove();
    });
    
    const delImg = document.createElement('img');
    delImg.src = `${create_property_app_url}/images/archive-icon.png`;
    delImg.alt = 'delete icon';
    delImg.classList.add('mr-[0.5rem]', 'hover:cursor-pointer', 'select-none');
    
    const delPropBtnText = document.createElement('p');
    const textNode = document.createTextNode('Verwijderen');
    
    delPropBtnText.appendChild(textNode);
    delPropBtn.appendChild(delImg);
    delPropBtn.appendChild(delPropBtnText);
    delImgContainer.appendChild(delPropBtn);
    
    propertyTitleContainer.appendChild(textSpan);
    propertyTitleContainer.appendChild(delImgContainer);
    propertyTitleContainer.appendChild(arrowDownDiv);
    propertyTitleContainer.appendChild(trueInput);
    
    const nameInput = document.createElement('input');
    nameInput.name = inputName.name;
    nameInput.type = 'hidden';
    nameInput.value = inputName.value;
    
    const typeInput = document.createElement('input');
    typeInput.name = inputType.name;
    typeInput.type = 'hidden';
    typeInput.value = inputType.value;
    
    inputOptions.forEach(inputOption => {
        const optionsInput = document.createElement('input');
        optionsInput.name = inputOption.name;
        optionsInput.type = 'hidden';
        optionsInput.value = inputOption.value;
        propertyTitleContainer.appendChild(optionsInput);
    });
    
    propertyTitleContainer.appendChild(nameInput);
    propertyTitleContainer.appendChild(typeInput);
    
    li.appendChild(propertyTitleContainer);
    renderCreatedProperty(property, li, trueInput);
    
    // Add the list item to the list of properties
    createProdPropertyList.appendChild(li);
    
    // Open property if it is already selected
    if (property.selected) {
        propertyHandleCreateCheckboxClick(property, trueInput);
        arrowDown.classList.toggle('rotate-arrow');
    }
}

function renderProperties(property) {
    // Check if property is already clicked
    const isClicked = clickedProperties.some(prop => prop.id === property.id);
    // If not already clicked, add it to clickedProperties
    if (!isClicked) {
        clickedProperties.push(property);
    }
    
    const createProdPropertyList = document.getElementById('createProdPropertyList');
    // createProdPropertyList.innerHTML = '';
    createProdPropertyList.classList.add('basic:max-h-[16rem]', 'hd:max-h-[22.5rem]', 'uhd:max-h-[27rem]');
    
    // Render all clicked properties
    clickedProperties.forEach(prop => {
        const beter = document.getElementById(`properties_li_${prop.id}`);
        if (beter === null) {
            const li = document.createElement('li');
            li.id = `properties_li_${prop.id}`;
            li.classList.add('pt-[0.35rem]', 'pb-[0.35rem]');
            
            // Build components for the clicked property
            const trueInput = document.createElement('input');
            trueInput.id = `properties[${prop.id}]`;
            trueInput.type = 'hidden';
            trueInput.value = null;
            
            const propertyNameSpan = document.createElement('span');
            propertyNameSpan.textContent = prop.name;
            propertyNameSpan.classList.add('relative', 'bottom-[0.125rem]');
            propertyNameSpan.style.display = 'inline-flex';
            propertyNameSpan.style.width = '85%';
            propertyNameSpan.style.zIndex = 99;
            propertyNameSpan.classList.add('no-select');
            
            const arrowDownDiv = document.createElement('span');
            const arrowDown = document.createElement('img');
            arrowDownDiv.classList.add('flex', 'items-center', 'justify-end', 'select-none', 'mr-[1.5rem]');
            arrowDownDiv.appendChild(arrowDown);
            arrowDown.src = `${create_property_app_url}/images/big-arrow-down-icon.png`;
            arrowDown.alt = 'Arrow Down';
            arrowDown.classList.add('w-[1.2rem]', 'flex', 'mt-[0.18rem]');
            
            const textSpan = document.createElement('span');
            const text = document.createTextNode(prop.name + ` (${prop.type})`);
            textSpan.classList.add('ml-[2.5rem]', 'font-bold', 'relative', 'bottom-[0.125rem]', 'select-none', 'text-[18px]', 'whitespace-nowrap');
            textSpan.appendChild(text);
            
            const propertyTitleContainer = document.createElement('div');
            propertyTitleContainer.classList.add('flex', 'items-center', 'h-[4.75rem]', 'basic:w-[62rem]', 'hd:w-[89rem]', 'uhd:w-[130rem]', 'bg-[#F8F8F8]', 'rounded-md', 'hover:cursor-pointer', 'border-t-lg');
            propertyTitleContainer.style.border = '1px solid #D3D3D3';
            
            propertyTitleContainer.addEventListener('click', () => {
                propertyHandleCheckboxClick(prop, trueInput);
                arrowDown.classList.toggle('rotate-arrow');
            });
            
            const delImgContainer = document.createElement('div');
            delImgContainer.classList.add('pr-[1rem]', 'w-full', 'flex', 'justify-end', 'items-center');
            
            const delPropBtn = document.createElement('button');
            delPropBtn.type = 'button';
            delPropBtn.style.border = '1px solid #717172';
            delPropBtn.classList.add('delete-props-btn', 'w-[11.18rem]', 'h-[2.5rem]', 'rounded-md', 'hover:bg-gray-100', 'flex', 'items-center', 'justify-center');
            
            delPropBtn.addEventListener('click', (event) => {
                event.stopPropagation();
                clickedProperties.splice(prop, 1);
                // add removed prop back to propertyTitles
                propertyTitleData.push(prop);
                li.remove();
                // Re-render the property list
                renderPropertyTitles();
            });
            
            const delImg = document.createElement('img');
            delImg.src = `${create_property_app_url}/images/archive-icon.png`;
            delImg.alt = 'delete icon';
            delImg.classList.add('mr-[0.5rem]', 'hover:cursor-pointer', 'select-none');
            
            const delPropBtnText = document.createElement('p');
            const textNode = document.createTextNode('Verwijderen');
            
            delPropBtnText.appendChild(textNode);
            delPropBtn.appendChild(delImg);
            delPropBtn.appendChild(delPropBtnText);
            delImgContainer.appendChild(delPropBtn);
            
            propertyTitleContainer.appendChild(textSpan);
            propertyTitleContainer.appendChild(delImgContainer);
            propertyTitleContainer.appendChild(arrowDownDiv);
            propertyTitleContainer.appendChild(trueInput);
            li.appendChild(propertyTitleContainer);
            renderProperty(prop, li, trueInput);
            
            // Add the list item to the list of properties
            createProdPropertyList.appendChild(li);
            
            // Open property if it is already selected
            if (prop.selected) {
                propertyHandleCheckboxClick(prop, trueInput);
                arrowDown.classList.toggle('rotate-arrow');
                trueInput.value = prop.selectedOption;
            }
        }
        
    });
}

function renderPropertyTitles() {
    const propertyTitleList = document.getElementById('propertyLists');
    propertyTitleList.innerHTML = '';
    propertyTitleData.forEach(property => {
        const li = document.createElement('li');
        li.classList.add('flex', 'items-center', 'justify-start');
        li.id = `add_property_li_${property.id}`;
        const button = document.createElement('button');
        button.classList.add('hover:bg-[#3999BE]', 'duration-100', 'block', 'w-[15.5rem]', 'h-[2.3rem]', 'px-4', 'py-2', 'text-sm', 'text-gray-700', 'hover:bg-gray-100', 'focus:outline-none', 'flex', 'justify-start', 'items-center');
        button.type = 'button';
        const span = document.createElement('span');
        span.classList.add('flex', 'items-center', 'justify-start', 'pr-3', 'text-[#717171]');
        span.textContent = property.name + ` (${property.type})`;
        button.appendChild(span);
        li.addEventListener('click', () => {
            renderProperties(property);
            propertyTitleData.splice(property, 1);
            li.remove();
        });
        li.appendChild(button);
        propertyTitleList.appendChild(li);
    });
}

// Initial rendering
renderPropertyTitles();

function renderProperty(property, li, trueInput) {
    const hiddenContainer = document.createElement('div');
    hiddenContainer.classList.add('w-full', 'flex', 'justify-center', 'items-center', 'select-none');
    const div = document.createElement('div');
    div.id = `div_${property.id}`;
    div.classList.add('hidden', 'grid', 'h-[6.58rem]', 'hd:w-[89rem]', 'uhd:w-[130rem]', 'bg-[#F8F8F8]', 'rounded-b-lg');
    div.style.border = '1px solid #D3D3D3';
    hiddenContainer.appendChild(div);
    switch (property.type) {
        case 'multiselect':
            rendermultiselect(property, div, trueInput);
            break;
        case 'bool':
            renderBool(property, div, trueInput);
            break;
        case 'singleselect':
            rendersingleselect(property, div, trueInput);
            break;
        case 'text':
            renderText(div, trueInput, property);
            break;
        case 'number':
            renderNumber(div, trueInput, property);
            break;
        default:
            break;
    }
    li.appendChild(div);
}

function renderCreatedProperty(property, li, trueInput) {
    const hiddenContainer = document.createElement('div');
    hiddenContainer.classList.add('w-full', 'flex', 'justify-center', 'items-center', 'select-none');
    const div = document.createElement('div');
    div.id = `new_prop_div_${property.id}`;
    div.classList.add('hidden', 'grid', 'h-[6.58rem]', 'hd:w-[89rem]', 'uhd:w-[130rem]', 'bg-[#F8F8F8]', 'rounded-b-lg');
    div.style.border = '1px solid #D3D3D3';
    hiddenContainer.appendChild(div);
    switch (property.type) {
        case 'multiselect':
            rendermultiselect(property, div, trueInput);
            break;
        case 'bool':
            renderBool(property, div, trueInput);
            break;
        case 'singleselect':
            rendersingleselect(property, div, trueInput);
            break;
        case 'text':
            renderText(div, trueInput, property);
            break;
        case 'number':
            renderNumber(div, trueInput, property);
            break;
        default:
            break;
    }
    li.appendChild(div);
}

function renderNumber(div, trueInput, property) {
    div.style.height = '4.58rem';
    const numberMainContainer = document.createElement('div');
    numberMainContainer.classList.add('flex', 'items-center', 'ml-[2rem]')
    
    const numberContainer = document.createElement('div');
    
    const numberselect = document.createElement('input');
        numberselect.addEventListener('input', function () {
        trueInput.value = this.value;
    });
    
    const decrement = document.createElement('div');
    const increment = document.createElement('div');
    
    const decrementIcon = document.createElement('img');
    decrementIcon.classList.add('select-none');
    decrementIcon.src = `${create_property_app_url}/images/minus-icon.png`;
    decrement.appendChild(decrementIcon);
    
    const incrementIcon = document.createElement('img');
    incrementIcon.classList.add('select-none');
    incrementIcon.src = `${create_property_app_url}/images/plus-icon.png`;
    increment.appendChild(incrementIcon);
    
    decrement.classList.add('w-[2.12rem]', 'h-[2.12rem]', 'flex', 'items-center', 'justify-center', 'hover:cursor-pointer', 'active:bg-gray-100', 'rounded-md');
    decrement.style.border = '';
    
    increment.classList.add('w-[2.12rem]', 'h-[2.12rem]', 'flex', 'items-center', 'justify-center', 'hover:cursor-pointer', 'active:bg-gray-100', 'rounded-md');
    increment.style.border = '1px solid #D3D3D3';
    
    numberselect.type = 'number';
    if (property.selectedOption) {
        numberselect.value = property.selectedOption;
    } else{
        numberselect.value = 0;
    }
    numberselect.classList.add('numberInput', 'text-center', 'basic:w-[31rem]', 'hd:w-[41rem]', 'uhd:w-[50.6rem]', 'h-[2.12rem]', 'flex');
    
    numberContainer.classList.add('flex', 'rounded-md','basic:w-[31rem]', 'hd:w-[41rem]', 'uhd:w-[50.6rem]', 'h-[2.12rem]');
    numberContainer.style.border = '1px solid #D3D3D3';
    
    numberContainer.appendChild(decrement);
    numberContainer.appendChild(numberselect);
    numberContainer.appendChild(increment);
    
    numberMainContainer.appendChild(numberContainer)
    div.appendChild(numberMainContainer);
    
    decrement.addEventListener('click', () => {
        if (parseInt(numberselect.value) > 0) {
            numberselect.value = parseInt(numberselect.value) - 1;
        }
        trueInput.value = numberselect.value;
    });
    
    increment.addEventListener('click', () => {
        numberselect.value = parseInt(numberselect.value) + 1;
        trueInput.value = numberselect.value;
    });
}

function rendermultiselect(property, div, trueInput) {
    div.style.height = '16.58rem';
    const multiContainer = document.createElement('div');
    multiContainer.classList.add('flex', 'flex-col', 'max-h-[16.58rem]', 'ml-[2rem]', 'mt-[1rem]')
    const input = document.createElement('input');
    
    input.type = 'text';
    input.classList.add(
        'flex',
        'z-20',
        'basic:w-[31rem]',
        'hd:w-[41rem]',
        'uhd:w-[50.06rem]',
        'h-[2.12rem]',
        'items-center',
        'justify-start',
        'z-20',
        'text-sm',
        'font-light',
        'text-gray-700',
        'bottom-[0.05rem]',
        'rounded-md',
        'relative',
        'top-[0.02rem]',
        'multi-select-input',
        'mb-[0.25rem]'
    );
    
    input.id = `searchProp_${property.id}`;
    input.style.border = '1px solid #D3D3D3';
    input.placeholder = 'Zoeken';
    input.addEventListener('input', (event) =>
        searchProperty(input.value, property)
    );
    
    const options = document.createElement('ul');
    options.classList.add(
        'hidden',
        'overflow-y-auto',
        'overflow-x-hidden',
        'basic:w-[31rem]',
        'basic:max-h-[30.67rem]',
        'hd:w-[41rem]',
        'hd:max-h-[40.67rem]',
        'uhd:w-[50.6rem]',
        'uhd:max-h-[50.67rem]',
        'rounded-mb',
    );
    
    input.addEventListener('focus', (event) => focusmultiSelect(options));
    input.addEventListener('blur', (event) => blurmultiSelect(options));
    multiContainer.appendChild(input);
    div.appendChild(multiContainer);
    
    const selectedOptionsContainer = document.createElement('div');	
    selectedOptionsContainer.style.display = 'flex';
    selectedOptionsContainer.style.flexWrap = 'wrap';
    selectedOptionsContainer.id = `selectedOptionsContainer${property.id}`;
    
    multiContainer.insertBefore(selectedOptionsContainer, null);
    
    if (Array.isArray(property.options)) {
        // Iterate over options only if it's an array
        property.options.forEach((option, index) => {
            const li = document.createElement('li');
            
            if (index === 0) {
                li.classList.add('rounded-t-lg');
            }
            
            if (index === property.options.length - 1) {
                li.classList.add('rounded-b-lg');
            }
            
            li.classList.add(
                'flex',
                'items-center',
                'block',
                'uhd:w-[50.06rem]',
                'h-[2.12rem]',
                'px-4',
                'py-2.5',
                'text-sm',
                'hover:bg-gray-100',
                'focus:outline-none',
                'font-[300]',
                'hover:cursor-pointer',
                'line-clamp-2',
                'bg-white'
            );
            
            li.style.border = '1px solid #D3D3D3';
            
            const span = document.createElement('span');
            span.classList.add(
                'flex',
                'items-center',
                'justify-center',
                'text-[#717171]',
                'hover:cursor-pointer',
                'text-[14px]'
            );
            
            span.textContent = option;
            li.id = `property_${property.id}_${option}`;
            li.appendChild(span);
            li.addEventListener('click', (event) =>
                propertyMultiSelectControl(option, input, property.id, trueInput, selectedOptionsContainer)
            );
            options.appendChild(li);
            
            if (property.selected && property.selectedOption != null) {
                selectedOptions = property.selectedOption.split(',');
                if (selectedOptions.includes(option)) {
                    propertyMultiSelectControl(option, input, property.id, trueInput, selectedOptionsContainer);
                }
            }
        })
    }
    //create options     
    multiContainer.appendChild(options);
}

function setTrueInputValueMulti(trueInput, optionsDiv) {
    const options = optionsDiv.querySelectorAll('.selected-option');
    let value = [];
    options.forEach(option => {
        value.push(option.getAttribute('data-value'));
    });
    trueInput.value = value;
}

function renderBool(property, div, trueInput) {
    div.style.height = '8.58rem';
    const boolContainer = document.createElement('span');
    boolContainer.classList.add('ml-[2rem]', 'mt-[1rem]', 'rounded-b-lg');
    
    const input = document.createElement('input');
    
    input.type = 'text';
    input.classList.add(
        'z-20',
        'basic:w-[31rem]',
        'hd:w-[41rem]',
        'uhd:w-[50.06rem]',
        'h-[2.12rem]',
        'pl-3',
        'flex',
        'items-center',
        'justify-start',
        'text-sm',
        'font-light',
        'text-gray-700',
        'bottom-[0.05rem]',
        'rounded-md',
        'focus:ring-[#717171]',
        'top-[0.02rem]',
        'mb-1',
        'bool-input'
    );
    input.style.border = '1px solid #D3D3D3';
    input.id = `searchProp_${property.id}`;
    input.placeholder = 'Zoeken';
    input.addEventListener('input', (event) =>
        searchProperty(input.value, property)
    );
    
    if(property.selectedOption == 'true'){
        input.value = 'ja';
    }else if(property.selectedOption == 'false'){
        input.value = 'nee';
    }
    
    const options = document.createElement('ul');
    options.classList.add(
        'hidden',
        'w-[11rem]',
        'flex',
        'gap-1',
        'grid'
    );
    
    input.addEventListener('focus', (event) => focusSingleSelect(options));
    input.addEventListener('blur', (event) => blurSingleSelect(options));
    
    const optionTrue = document.createElement('li');
    const optionFalse = document.createElement('li');
    optionTrue.classList.add(
        'flex',
        'items-center',
        'block',
        'basic:w-[30.4rem]',
        'hd:w-[40.4rem]',
        'uhd:w-[50rem]',
        'h-[2.12rem]',
        'px-4',
        'py-2.5',
        'text-sm',
        'text-white',
        'hover:bg-gray-100',
        'focus:outline-none',
        'font-[300]',
        'rounded-md',
        'hover:cursor-pointer'
    );
    optionTrue.style.border = '1px solid #D3D3D3';
    optionFalse.classList.add(
        'flex',
        'items-center',
        'block',
        'basic:w-[30.4rem]',
        'hd:w-[40.4rem]',
        'uhd:w-[50rem]',
        'h-[2.12rem]',
        'px-4',
        'py-2.5',
        'text-sm',
        'text-white',
        'hover:bg-gray-100',
        'focus:outline-none',
        'font-[300]',
        'rounded-md',
        'hover:cursor-pointer'
    );
    optionFalse.style.border = '1px solid #D3D3D3';
    const spanTrue = document.createElement('span');
    const spanFalse = document.createElement('span');
    spanTrue.classList.add(
        'flex',
        'items-center',
        'justify-center',
        'text-black'
    );
    spanFalse.classList.add(
        'flex',
        'items-center',
        'justify-center',
        'text-black'
    );
    spanTrue.textContent = 'Ja';
    spanFalse.textContent = 'Nee';
    optionTrue.id = `property_${property.id}_true`;
    optionFalse.id = `property_${property.id}_false`;
    optionTrue.appendChild(spanTrue);
    optionFalse.appendChild(spanFalse);
    optionTrue.addEventListener('click', (event) =>
        BoolControl(1, input, trueInput)
    );
    optionFalse.addEventListener('click', (event) =>
        BoolControl(0, input, trueInput)
    );
    options.appendChild(optionTrue);
    options.appendChild(optionFalse);
    boolContainer.appendChild(input);
    boolContainer.appendChild(options);
    div.appendChild(boolContainer);
}

function focusmultiSelect(options) {
options.classList.remove('hidden');
options.querySelectorAll('li').forEach((option) => {
    option.classList.remove('hidden');
});
}

function BoolControl(option, input, trueInput) {
    if (option) {
        input.value = 'Ja';
        trueInput.value = true;
    } else {
        input.value = 'Nee';
        trueInput.value = false;
    }
}

//render the singel select property
function rendersingleselect(property, div, trueInput) {
div.style.height = '13.58rem';
const input = document.createElement('input');
singleSelectSubContainer = document.createElement('div');
singleSelectSubContainer.classList.add('ml-[2rem]', 'mt-[1rem]');
input.type = 'text';
input.classList.add(
    'z-20',
    'basic:w-[31rem]',
    'hd:w-[41rem]',
    'uhd:w-[50.06rem]',
    'h-[2.12rem]',
    'pl-3',
    'flex',
    'items-center',
    'justify-start',
    'text-sm',
    'font-light',
    'text-gray-700',
    'bottom-[0.05rem]',
    'rounded-md',
    'focus:ring-[#717171]',
    'top-[0.02rem]',
    'mb-1',
    'single-input'
);
input.style.border = '1px solid #D3D3D3';
input.id = `searchProp_${property.id}`;
input.placeholder = 'Zoeken';
input.addEventListener('input', (event) => {
    searchProperty(input.value, property);
    trueInput.value = '';
});
if (!property.selectedOption) {
    input.value = 'Zoeken';
} else  {
    input.value = property.selectedOption;
}

const options = document.createElement('ul');
options.classList.add(
    'hidden',
    'overflow-y-auto',
    'overflow-x-hidden',
    'basic:w-[31rem]',
    'basic:max-h-[31.67rem]',
    'hd:w-[41rem]',
    'hd:max-h-[40.67rem]',
    'uhd:w-[50.6rem]',
    'uhd:max-h-[50.67rem]',
    'rounded-mb',
);

input.addEventListener('focus', (event) => focusSingleSelect(options));
input.addEventListener('blur', (event) => blurSingleSelect(options));

//create options
if (Array.isArray(property.options)) {
    property.options.forEach((option, index) => {
        const li = document.createElement('li');
        
        if (index === 0) {
            li.classList.add('rounded-t-lg');
        }
        
        if (index === property.options.length - 1) {
            li.classList.add('rounded-b-lg');
        }
        
        li.classList.add(
            'flex',
            'items-center',
            'block',
            'uhd:w-[50.06rem]',
            'h-[2.12rem]',
            'px-4',
            'py-2.5',
            'text-sm',
            'text-white',
            'hover:bg-gray-100',
            'focus:outline-none',
            'font-[300]',
            'hover:cursor-pointer',
            'line-clamp-2',
            'bg-white'
        );
        
        const span = document.createElement('span');
        
        span.classList.add(
            'flex',
            'items-center',
            'justify-center',
            'text-[#717171]',
            'text-[14px]'
        );
        
        li.style.border = '1px solid #D3D3D3';
        
        span.textContent = option;
        li.id = `property_${property.id}_${option}`;
        li.appendChild(span);
        li.addEventListener('click', (event) =>
            propertySingleSelectControl(option, input, trueInput)
        );
        options.appendChild(li);
    });
}
div.appendChild(input);
div.appendChild(options);

singleSelectSubContainer.appendChild(input);
singleSelectSubContainer.appendChild(options);
div.appendChild(singleSelectSubContainer);
}

function renderText(div, trueInput, property) {
const textSpan = document.createElement('span');
    textSpan.classList.add('flex', 'items-center', 'ml-[2rem]')
    
    const text = document.createElement('input');
    text.type = 'text';
    text.classList.add('basic:w-[31rem]', 'hd:w-[41rem]', 'uhd:w-[50.06rem]', 'rounded-md', 'h-[2.12rem]', 'text-input', 'uhd:w-[30rem]');
    text.addEventListener('input', function () {
        trueInput.value = text.value;
    });
    text.style.border = '1px solid #D3D3D3';
    textSpan.appendChild(text);
    div.appendChild(textSpan);
}

function focusSingleSelect(options) {
    options.classList.remove('hidden');
    options.querySelectorAll('li').forEach((option) => {
        option.classList.remove('hidden');
    });
}

let blurMultiDelayTimer;
function blurmultiSelect(options) {
    clearTimeout(blurMultiDelayTimer);
    blurMultiDelayTimer = setTimeout(() => {
        options.classList.add('hidden');
    }, 200);
}

let blurDelayTimer;
    function blurSingleSelect(options) {
    clearTimeout(blurDelayTimer);
    blurDelayTimer = setTimeout(() => {
        options.classList.add('hidden');
    }, 200);
}

function propertyMultiSelectControl(option, input, id, trueInput, selectedOptionsContainer) {
    // Check if the option is already selected
    if (selectedOptionsContainer.querySelector(`div.selected-option[data-value='${option}']`)) {
        // If the option is already selected, do nothing
        return;
    }
    
    // Create a new div for the selected option
    const newDiv = document.createElement('div');
    newDiv.classList.add('selected-option', 'flex', 'items-center', 'bg-white', 'w-fit', 'p-[0.3rem]', 'rounded-md', 'm-[0.25rem]');
    newDiv.style.border = '1px solid #D3D3D3';
    newDiv.setAttribute('data-value', option);
    
    // Create a span element
    const span = document.createElement('span');
    span.classList.add('flex', 'pt-[0.16rem]', 'pl-[0.2rem]');
    const removePropertyIcon = document.createElement('img');
    removePropertyIcon.classList.add('select-none', 'w-[0.75rem]', 'h-[0.75rem]', 'hover:cursor-pointer');
    removePropertyIcon.src = `${create_property_app_url}/images/x-icon.png`;
    
    removePropertyIcon.addEventListener('click', function () {
        newDiv.remove();
        setTrueInputValueMulti(trueInput, selectedOptionsContainer);
    });
    
    span.appendChild(removePropertyIcon);
    
    const textNode = document.createTextNode(option);
    
    newDiv.appendChild(textNode);
    newDiv.appendChild(span);
    
    // Append the new div to the surrounding container
    selectedOptionsContainer.appendChild(newDiv);
    
    input.value = '';
    
    setTrueInputValueMulti(trueInput, selectedOptionsContainer);
}

function propertySingleSelectControl(option, input, trueInput) {
    input.value = option;
    trueInput.value = option;
}

//search through properties options
function searchProperty(createProdPropertiesSearchText, property) {
    createProdPropertiesSearchText = createProdPropertiesSearchText.trim();
    property.options.forEach((option) => {
        const li = document.getElementById(
            `property_${property.id}_${option}`
        );
        
        if (
            !createProdPropertiesSearchText ||
            option.toLowerCase().includes(createProdPropertiesSearchText.toLowerCase())
        ) {
            li.classList.remove('hidden');
        } else {
            li.classList.add('hidden');
        }
    });
}

function propertyHandleCheckboxClick(property, trueInput) {
    trueInput.name = `properties[${property.id}]`;
    property.checked = !property.checked;
    const div = document.getElementById(`div_${property.id}`);
    if (property.checked) {
        div.classList.remove('hidden');
    } else {
        div.classList.add('hidden');
    }
}

function extractIdNumber(id) {
    // Extract numeric part from id using regex
    const matches = id.match(/\d+/);
    return matches ? parseInt(matches[0]) : null;
}

function propertyHandleCreateCheckboxClick(property, trueInput, container) {
    const liIdNumber = extractIdNumber(trueInput.id); // Get numeric part of li id
    const divIdNumber = extractIdNumber(container.id);
    
    if (liIdNumber === divIdNumber) {
        trueInput.name = `newProperties[${liIdNumber}][value]`;
        property.checked = !property.checked;
        const div = document.getElementById(`new_prop_div_${liIdNumber}`);
        
        if (div.classList.contains('hidden')) {
            div.classList.remove('hidden');
        } else {
            div.classList.add('hidden');
        }
    }
}

function removeRotateArrowClass(property) {
    const checkboxes = document.querySelectorAll(`input[type='checkbox'][value='${property.id}']`);
    checkboxes.forEach(checkbox => {
        const li = checkbox.closest('li');
        const arrowDown = li.querySelector('img');
        if (arrowDown) {
            arrowDown.classList.remove('rotate-arrow');
        }
    });
}

function searchProperties(createProdPropertiesSearchText) {
    propertiesData.forEach((property) => {
        const li = document.getElementById(`properties_li_${property.id}`);
        if (
            !createProdPropertiesSearchText ||
            property.name.toLowerCase().includes(createProdPropertiesSearchText.toLowerCase())
        ) {
            li.classList.remove('hidden');
        } else {
            li.classList.add('hidden');
        }
    });
}