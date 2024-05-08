@props(['properties'])

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

    const propertyNameInput = document.getElementById('newPropertyName');
    
    const propertyTypeSelect = document.getElementById('newPropertyType');
    propertyTypeSelect.classList.add("px-[0.3rem]", "h-[2.5rem]", "w-[11rem]", "rounded-md");
    propertyTypeSelect.style.border = "1px solid #D3D3D3";
    
    const optionsList = document.getElementById('optionsList');
    const addOptionButton = document.getElementById('addOptionButton');
    const addPropertyButton = document.getElementById('createNewPropertyBtn');
    const showCreatePropPopup = document.querySelector('.create-prop-pop-up');
    const createPropContainer = document.getElementById('createProdPropertyList');

    // Function to create an option item
    function createOptionItem() {
        const li = document.createElement('li');
        li.setAttribute('class', 'flex justify-end');
        li.style.backgroundColor = 'blueviolet';

        li.style.paddingTop = "0.5rem";
        li.style.paddingBottom = "0.5rem";
        const optionInput = document.createElement('input');
        optionInput.classList.add('property-input', 'h-[2.5rem]', 'rounded-md', 'px-[0.8rem]');
        optionInput.style.border = "1px solid #D3D3D3";
        optionInput.attribute = "autocomplete='off'";
        optionInput.type = 'text';
        optionInput.name = 'options[]';
        optionInput.required = true;
        optionInput.value = '';

        const removeOptionButton = document.createElement('img');
        removeOptionButton.src = '{{$app_url}}/images/delete-icon.png';
        removeOptionButton.alt = 'Delete Icon';
        removeOptionButton.classList.add('removeOptionButton', 'w-[2rem]', 'pt-[0.3rem]', 'hover:cursor-pointer');
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

    //creates newPropObject
    let newPropData = {};

    //creates id
    let newPropId = 0;

    document.querySelector('.create-property-create-btn').addEventListener('click', function (event) {
        const propertyInputs = document.querySelectorAll('.property-input');

        //front-end validations, if inputs are empty or not
        if (!selectedType) {
            alert("Kies alstublieft een eigenschapstype.");
        } else if (!Array.from(propertyInputs).every(input => input.value.trim())) {
            alert("Eigenschapswaarde mag niet leeg zijn, vul alstublieft de eigenschap naam in");
        } else if (!Array.from(propertyInputs).some(input => input.value.trim())) {
            alert("Vul alstublieft minstens één eigenschapswaarde in.");
        } else if (!propertyNameInput.value.trim()) {
            alert("Vul alstublieft de eigenschap naam in.");
        } else {
            event.preventDefault();
            newPropId++;

            // code for popup close and open animation
            showCreatePropPopup.classList.add('fade-out');
            showCreatePropPopup.addEventListener('animationend', function(event) {
                if (event.animationName === 'fadeOut') {
                    showCreatePropPopup.classList.add('hidden');
                }
            }, false);
            showCreatePropPopup.classList.remove('fade-in');
            
            //set properties for newPropData object
            newPropData.id = newPropId;
            newPropData.name = propertyNameInput.value;
            newPropData.type = selectedType;
            newPropData.options = [];

            propertyInputs.forEach(function(propertyInput) {
                propertyInput.name = `newProperties[${newPropData.id}][options][]`;
                newPropData.options.push(propertyInput.value);
            });

            //sets names & values of input fields
            const propertyNameName = document.getElementById('propertyNameName');
            propertyNameName.name = `newProperties[${newPropData.id}][name]`;
            propertyNameName.value = propertyNameInput.value;

            const propertyTypeType = document.getElementById('valueInput');
            propertyTypeType.name = `newProperties[${newPropData.id}][type]`;
            propertyTypeType.value = selectedType;
            
            //calls function to create the new property, and passes the inputs
            renderCreatedProperties(newPropData, propertyNameName, propertyTypeType, propertyInputs);

            //resets all values after
            propertyTypeSelect.value = '';
            selectedType = '';
            propertyNameInput.value = '';
            propertyInputs.forEach(function(propertyInput) {
                propertyInput.value = '';
            });

            optionsList.innerHTML = '';
            addOptionButton.classList.add('hidden');

            propertyNameInput.name = '';
            propertyNameName.name = '';
            valueInput.name = '';
            optionsInputField.name = '';
        }
    });

    document.querySelector('.create-prop-pop-up').addEventListener('click', function (event) {
        // if cancel or close button is clicked
        if (
            event.target.matches('.create-prop-close') ||
            event.target.matches('.create-prop-cancel')
        ) {
            //also reset all values
            const propertyInputs = document.querySelectorAll('.property-input');
            newPropId++;
            
            newPropData.id = newPropId;
            newPropData.name = propertyNameInput.value;
            newPropData.type = selectedType;
            newPropData.options = [];

            propertyInputs.forEach(function(propertyInput) {
                propertyInput.name = `newProperties[${newPropData.id}][options][]`;

                newPropData.options.push(propertyInput.value);
            });

            const propertyNameName = document.getElementById('propertyNameName');
            const propertyTypeType = document.getElementById('valueInput');

            propertyNameName.name = `newProperties[${newPropData.id}][name]`;
            propertyTypeType.name = `newProperties[${newPropData.id}][type]`;

            propertyNameName.value = propertyNameInput.value;

            propertyTypeType.value = selectedType;

            propertyTypeSelect.value = '';
            selectedType = '';
            propertyNameInput.value = '';
            propertyInputs.forEach(function(propertyInput) {
                propertyInput.value = '';
            });

            optionsList.innerHTML = '';
            addOptionButton.classList.add('hidden');

            propertyNameInput.name = '';
            propertyNameName.name = '';
            valueInput.name = '';
            optionsInputField.name = '';
        }
    })
</script>
