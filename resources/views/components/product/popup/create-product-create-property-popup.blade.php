@props(['properties'])

<?php
    $app_url = env('VITE_APP_URL');
?>

<div
    class="create-prop-pop-up w-full h-full fixed top-0 bg-black bg-opacity-75 hidden pt-32 select-none left-0" style="z-index: 999;"
>
    <div
        x-transition
        class="create-prop-pop-up-container relative w-[44rem] h-[30remrem] bg-white pb-0 rounded-md text-center p-8 mx-auto mt-20 transform -translate-y-40 transition-transform duration-300"
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
            <div class="w-[40.87rem] h-[25rem] mt-[2rem]" style="border: 1px solid #f0f0f0; border-radius: 10px;">
                <div class="w-[40.87rem] h-[2.5rem] bg-[#3DABD5] rounded-t-lg flex justify-start items-center pl-4 text-white">
                    <p>Eigenschappen toevoegen aan variatie</p>
                </div>
                <form id="propertyForm" action="/properties" method="POST">
                    @csrf

                    <label for="newPropertyName">Eigenschap naam:</label>
                    <input type="text" id="newPropertyName" name="name" autocomplete="off" required>
                    <label for="newPropertyType">Eigenschap type:</label>
                    <select id="newPropertyType" name="type" required>
                        <option value=""></option>
                        <option value="singleselect">Single Select</option>
                        <option value="multiselect">Multi Select</option>
                        <option value="number">number</option>
                        <option value="bool">bool</option>
                        <option value="text">text</option>
                    </select>
                    <ul id="optionsList" class="overflow-y-auto max-h-[14rem]"></ul>
                    <button type="button" id="addOptionButton" class="hidden">Voeg 1 eigenschapswaarde toe</button>
                </form>
                
                <div class="create-prop-buttons flex items-center gap-[0.7rem] absolute bottom-[1.1rem] right-[0.3rem]">
                    <button type="button" class="create-prop-close flex justify-center gap-2 items-center create-prop-cancel w-[7.87rem] h-[2.68rem] hover:bg-gray-100 rounded-md" style="border: 1px solid #717172;">
                        <img class="create-prop-close select-none w-[0.8rem] h-[0.8rem] flex" src="{{$app_url}}/images/x-icon.png" alt="x icon">
                        <p class="create-prop-close flex text-[#717171]">Annuleren</p>
                    </button>
                    <button id="createNewPropertyBtn" type="button" class="create-property-create-btn flex justify-center items-center w-[7.87rem] h-[2.68rem] bg-[#3DABD5] rounded-md hover:bg-[#3999BE] gap-[0.5rem]">
                        <img class="create-property-create-btn" src="{{$app_url}}/images/save-icon.png">
                        <p class="create-property-create-btn flex text-[#F8F8F8]">Save</p>
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

    const propertyNameInput = document.getElementById('newPropertyName');
    const propertyTypeSelect = document.getElementById('newPropertyType');
    const optionsList = document.getElementById('optionsList');
    const addOptionButton = document.getElementById('addOptionButton');
    const addPropertyButton = document.getElementById('createNewPropertyBtn');
    const showCreatePropPopup = document.querySelector('.create-prop-pop-up');
    const createPropContainer = document.getElementById('createProdPropertyList');

    // Function to create an option item
    function createOptionItem() {
        const li = document.createElement('li');

        li.style.paddingTop = "0.5rem";
        li.style.paddingBottom = "0.5rem";
        const optionInput = document.createElement('input');
        optionInput.classList.add('property-input');
        optionInput.attribute = "autocomplete='off'";
        optionInput.type = 'text';
        optionInput.name = 'options[]';
        optionInput.style.border = '2px solid blue';
        optionInput.required = true;
        
        const removeOptionButton = document.createElement('button');
        removeOptionButton.type = 'button';
        removeOptionButton.textContent = 'Remove';
        removeOptionButton.classList.add('removeOptionButton');
        li.appendChild(optionInput);
        li.appendChild(removeOptionButton);
        
        return li;
    }

    // Event listener for adding an option
    addOptionButton.addEventListener('click', function () {
        const optionItem = createOptionItem();
        optionsList.appendChild(optionItem);
    });

    

    // Event listener for removing an option
    optionsList.addEventListener('click', function (event) {
        if (event.target && event.target.classList.contains('removeOptionButton')) {
            const listItem = event.target.parentNode;
            optionsList.removeChild(listItem);
        }
    });
    let selectedType;

    propertyTypeSelect.addEventListener('change', function () {
        selectedType = propertyTypeSelect.value;
        console.log("selectedType 1: ", selectedType);

        // Remove all existing option items
        optionsList.innerHTML = '';

        if (selectedType === 'singleselect' || selectedType === 'multiselect') {
            // Add an initial option item
            const initialOptionItem = createOptionItem();
            optionsList.appendChild(initialOptionItem);

            // Show option items and add option button
            document.querySelectorAll('#optionsList li').forEach(item => {
                item.classList.remove('hidden');
            });
            addOptionButton.classList.remove('hidden');
        } else {
            // Hide option items and add option button
            document.querySelectorAll('#optionsList li').forEach(item => {
                item.classList.add('hidden');
            });
            addOptionButton.classList.add('hidden');
        }
    });

    

    document.querySelector('.create-property-create-btn').addEventListener('click', function (event) {
        console.log("selectedType 2: ", typeof(selectedType));
        console.log("propertyNameInput.value: ", propertyNameInput.value);
        const propertyInputs = document.querySelectorAll('.property-input');
        console.log("propertyInputs before:", propertyInputs);
        console.log("propertyTypeSelect.value: ", typeof(propertyTypeSelect.value));
        
        addPropertyData.forEach(newProperty => {
            console.log(newProperty.id);
        });

        if (selectedType !== undefined && selectedType !== '') {
            if (
                (Array.from(propertyInputs).some(input => input.value !== '') && propertyNameInput.value !== '') ||
                (propertyNameInput.value !== '' && Array.from(propertyInputs).some(input => input.value !== '')) ||
                (propertyNameInput.value !== '' && selectedType !== 'singleselect' && selectedType !== 'multiselect')
            ) {
                event.preventDefault();

                propertyInputs.forEach(function(propertyInput) {
                    // console.log("property Option: ", propertyInput.value);
                });

                showCreatePropPopup.classList.add('fade-out');
                showCreatePropPopup.addEventListener('animationend', function(event) {
                    if (event.animationName === 'fadeOut') {
                        showCreatePropPopup.classList.add('hidden');
                    }
                }, false);
                showCreatePropPopup.classList.remove('fade-in');

                const li = document.createElement('li');
                li.id = `new_properties_li_`;
                li.classList.add('pt-[0.35rem]', 'pb-[0.35rem]');
                
                // Build components for the clicked property
                const trueInput = document.createElement('input');
                trueInput.id = `new_properties[]`;
                trueInput.type = 'hidden';
                trueInput.value = null;

                const propertyNameSpan = document.createElement('span');
                propertyNameSpan.textContent = propertyNameInput.value;
                propertyNameSpan.classList.add('relative', 'bottom-[0.125rem]');
                propertyNameSpan.style.display = 'inline-flex';
                propertyNameSpan.style.width = '85%';
                propertyNameSpan.style.zIndex = 99;
                propertyNameSpan.classList.add('no-select');
                
                const arrowDownDiv = document.createElement('span');
                const arrowDown = document.createElement('img');
                arrowDownDiv.classList.add('flex', 'items-center', 'justify-end', 'select-none', 'mr-[1.5rem]');
                arrowDownDiv.appendChild(arrowDown);
                arrowDown.src = '{{$app_url}}/images/big-arrow-down-icon.png';
                arrowDown.alt = 'Arrow Down';
                arrowDown.classList.add('w-[1.2rem]', 'flex', 'mt-[0.18rem]');
                
                const textSpan = document.createElement('span');
                const text = document.createTextNode(propertyNameInput.value + ` (${selectedType})`);
                textSpan.classList.add('ml-[2.5rem]', 'font-bold', 'relative', 'bottom-[0.125rem]', 'select-none', 'text-[18px]', 'whitespace-nowrap');
                textSpan.appendChild(text);
                
                const propertyTitleContainer = document.createElement('div');
                propertyTitleContainer.classList.add('flex', 'items-center', 'h-[4.75rem]', 'basic:w-[62rem]', 'hd:w-[89rem]', 'uhd:w-[130rem]', 'bg-[#F8F8F8]', 'rounded-md', 'hover:cursor-pointer', 'border-t-lg');
                propertyTitleContainer.style.border = '1px solid #D3D3D3';
                
                propertyTitleContainer.addEventListener('click', () => {
                    propertyHandleCheckboxClick(propertyNameInput, trueInput);
                    arrowDown.classList.toggle('rotate-arrow');
                });

                const delImgContainer = document.createElement('div');
                delImgContainer.classList.add('pr-[1rem]', 'w-full', 'flex', 'justify-end', 'items-center');

                const delPropBtn = document.createElement('button');
                delPropBtn.type = 'button';
                delPropBtn.style.border = '1px solid #717172';
                delPropBtn.classList.add('delete-props-btn', 'w-[11.18rem]', 'h-[2.5rem]', 'rounded-md', 'hover:bg-gray-100', 'flex', 'items-center', 'justify-center');


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
                
                propertyTitleContainer.appendChild(textSpan);
                propertyTitleContainer.appendChild(delImgContainer);
                propertyTitleContainer.appendChild(arrowDownDiv);
                propertyTitleContainer.appendChild(trueInput);
                li.appendChild(propertyTitleContainer);
                renderProperty(propertyNameInput, li, trueInput);
                
                // Add the list item to the list of properties
                createPropContainer.appendChild(li);
                
                // Open property if it is already selected
                if (selectedType) {
                    propertyHandleCheckboxClick(propertyNameInput, trueInput);
                    arrowDown.classList.toggle('rotate-arrow');
                    trueInput.value = selectedType;
                }
                propertyTypeSelect.value = '';
                selectedType = '';
                propertyNameInput.value = '';
                propertyInputs.forEach(function(propertyInput) {
                    propertyInput.value = '';
                });
                optionsList.innerHTML = '';
                addOptionButton.classList.add('hidden');
            } else if (propertyNameInput.value === '' && Array.from(propertyInputs).some(input => input.value !== '') || propertyNameInput.value === '' && selectedType !== 'singleselect' && selectedType !== 'multiselect') {
                alert("Vul alstublieft de eigenschap naam in.");
            } else if (Array.from(propertyInputs).every(input => input.value === '') && propertyNameInput.value === '') {
                alert("Vul alstublieft de eigenschap naam en minstens één eigenschapswaarde in.");
            } else if (Array.from(propertyInputs).every(input => input.value === '') && propertyNameInput.value !== '') {
                alert("Vul alstublieft minstens één eigenschapswaarde in.");
            }
        } else if (selectedType === undefined) {
            alert("Kies alstublieft een eigenschapstype.");
        } else if (selectedType === '') {
        alert("Kies alstublieft een eigenschapstype.");
        }
    });
</script>
