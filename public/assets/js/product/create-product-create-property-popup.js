const propertyNameInput = document.getElementById('newPropertyName');

const propertyTypeSelect = document.getElementById('newPropertyType');
propertyTypeSelect.classList.add("px-[0.3rem]", "h-[2.5rem]", "w-[11rem]", "rounded-md");
propertyTypeSelect.style.border = "1px solid #D3D3D3";

const optionsList = document.getElementById('optionsList');
const addOptionButton = document.getElementById('addOptionButton');
const addPropertyButton = document.getElementById('createNewPropertyBtn');
const hideCreatePropPopup = document.querySelector('.create-prop-pop-up');
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
    removeOptionButton.src = `${create_prod_create_prop_popup_app_url}/images/delete-icon.png`;
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
    
    //check if selected options if not undefined or empty
    if (selectedType !== undefined && selectedType !== '') {
        //checks every option from mutli and single is filled in
        if ((Array.from(propertyInputs).every(input => input.value.trim() !== ''))) {
            //checks if at least 1 is filled in and propertyname is filled in
            // checks if propertyname is filled in & checks if at least 1 is filled
            // checks if propertyname is filled in and is something else then singleselect and multiselect
            if (
                (Array.from(propertyInputs).some(input => input.value !== '') && propertyNameInput.value !== '') ||
                (propertyNameInput.value !== '' && Array.from(propertyInputs).some(input => input.value !== '')) ||
                (propertyNameInput.value !== '' && selectedType !== 'singleselect' && selectedType !== 'multiselect')
            ) {
                event.preventDefault();
                newPropId++;
            
                //code to hide pop up, animation
                hideCreatePropPopup.classList.add('fade-out');
                hideCreatePropPopup.addEventListener('animationend', function(event) {
                    if (event.animationName === 'fadeOut') {
                        hideCreatePropPopup.classList.add('hidden');
                    }
                }, false);
                hideCreatePropPopup.classList.remove('fade-in');
                
                //sets properties for the newPropdata object
                newPropData.id = newPropId;
                newPropData.name = propertyNameInput.value;
                newPropData.type = selectedType;
                newPropData.options = [];
                
                propertyInputs.forEach(function(propertyInput) {
                    propertyInput.name = `newProperties[${newPropData.id}][options][]`;
                    newPropData.options.push(propertyInput.value);
                });
                
                //sets name & value for inputs
                const propertyNameName = document.getElementById('propertyNameName');
                propertyNameName.name = `newProperties[${newPropData.id}][name]`;
                propertyNameName.value = propertyNameInput.value;
                
                const propertyTypeType = document.getElementById('valueInput');
                propertyTypeType.name = `newProperties[${newPropData.id}][type]`;
                propertyTypeType.value = selectedType;
                
                //call the rendercreatedProperties and passes object with data & inputs
                renderCreatedProperties(newPropData, propertyNameName, propertyTypeType, propertyInputs);
                
                //resets all values after created
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
            } else if (propertyNameInput.value === '' && Array.from(propertyInputs).some(input => input.value !== '') || propertyNameInput.value === '' && selectedType !== 'singleselect' && selectedType !== 'multiselect') {
                //checks if propertyName is left empty and propertyOptions are left empty
                //or if propertyName is left empty and type is something else then single or multi
                alert("Vul alstublieft de eigenschap naam in.");
            } else if (Array.from(propertyInputs).every(input => input.value === '') && propertyNameInput.value === '') {
                //checks if all propertyOptions are left empty and propertyName is left empty
                alert("Vul alstublieft de eigenschap naam en minstens één eigenschapswaarde in.");
            } else if (Array.from(propertyInputs).every(input => input.value === '') && propertyNameInput.value !== '') {
                //checks if all propertyOptions are left empty and propertyName was not empty
                alert("Vul alstublieft minstens één eigenschapswaarde in.");
            }
        } else if (Array.from(propertyInputs).some(input => input.value.trim() === '')) {
            // if a propertyOption is left empty
            alert("Eigenschapswaarde mag niet leeg zijn, vul alstublieft de eigenschap naam in");
        }
    } else if (selectedType === undefined || selectedType === '') {
        // if selectedType is undefined or was empty, so no type selected
        alert("Kies alstublieft een eigenschapstype.");
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