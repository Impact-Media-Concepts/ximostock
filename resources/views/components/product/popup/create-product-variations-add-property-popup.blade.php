@props(['properties'])

<?php
    $app_url = env('VITE_APP_URL');
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
        width: 17.75rem;
    }

    .variation-prop-btn-right {
        right: 13rem;
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
                        <p>Korting</p>
                    </div>

                    <div id="variationPropContainer" class="max-h-[27rem] h-[27rem] overflow-y-auto pt-[1rem] pb-[1rem]">
                        <ul class="flex items-center justify-center gap-[0.5rem]">
                            <div class="flex flex-col items-start gap-[0.2rem]">
                                <div>
                                    <p>Kies een eigenschap:</p>
                                </div>
                               
                                <select name="cars" id="cars">
                                    
                                </select>
                                    <input type="hidden" name="selected_backOrder_id" x-bind:value="selectedbackOrder.id">
                            </div>
                            
                            <div>
                                <img class="w-[2rem] pt-[1rem]" src="{{$app_url}}/images/long-arrow-right-icon.png" alt="long arrow right icon">
                            </div>

                            <div class="flex flex-col items-start">
                                <div>
                                    <p>Kies een eigenschap waarde:</p>
                                </div>
                                <select name="cars" id="cars2">
                                    
                                </select>
                                <input type="hidden" name="selected_backOrder_id" x-bind:value="selectedbackOrder.id">
                            </div>

                            <div id="variationRemovePropBtn" style="background: red">
                                <img class="w-[2rem] pt-[1rem] hover:cursor-pointer" src="{{$app_url}}/images/delete-icon.png" alt="long arrow right icon">
                            </div>
                        </ul>

                    </div>

                    

                   
                    <div class="create-prop-buttons flex items-center gap-[0.7rem] absolute bottom-0 left-[44.8rem]">
                        <button type="button" class="variations-add-prop-close flex justify-center gap-2 items-center create-propCancel w-[7.87rem] h-[2.68rem] hover:bg-gray-100 rounded-md" style="border: 1px solid #717172;">
                            <img class="variations-add-prop-close select-none w-[0.8rem] h-[0.8rem] flex" src="{{$app_url}}/images/x-icon.png" alt="x icon">
                            <p class="variations-add-prop-close flex text-[#717171]">Annuleren</p>
                        </button>
                        <button type="button" class="flex justify-center items-center w-[7.87rem] h-[2.68rem] bg-[#3DABD5] rounded-md hover:bg-[#3999BE] gap-[0.5rem]">
                            <img src="{{$app_url}}/images/save-icon.png">
                            <p class="flex text-[#F8F8F8]">Save</p>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    
    const properties = @json($properties);

    const selectElement2 = document.getElementById("cars2");
    selectElement2.setAttribute('class', 'flex variation-prop-btn-width items-center back-order-btn px-4 h-12 text-sm font-medium text-gray-700 bg-white border-2 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[#717171] focus:ring-offset-2 focus:ring-offset-gray-100 relative hover:cursor-pointer');
    
    properties.forEach(property => {
    
        const options = document.createElement("option");
        options.classList.add("!w-[17.75rem]", "block", "w-full", "text-left", "px-4",  "py-2" , "text-sm", "text-gray-700", "hover:bg-gray-100" , "focus:outline-none");
        options.style.border = '1px solid #D3D3D3';
        
        options.value = property.name;
        
        options.textContent = property.name;
        
        selectElement2.appendChild(options);
    });

    
    const selectElement = document.getElementById("cars");

    
    properties.forEach(property => {
    
    const optionw = document.createElement("option");
    optionw.classList.add('block', 'w-full', 'text-left', 'px-4',  'py-2' , 'text-sm', 'text-gray-700', 'hover:bg-gray-100' , 'focus:outline-none');
    
    optionw.value = property.name;
    
    
    optionw.textContent = property.name;
    
    
    selectElement.appendChild(optionw);
    });








    window.addPropsNames = properties.map(property => {
        return { id: property.id, name: property.name };
    });

    document.getElementById('variationRemovePropBtn').addEventListener('click', (event) => {
        // Find the closest ul element to the clicked remove button
        const closestUl = event.target.closest('ul');
        
        // Remove the closest ul element
        closestUl.parentNode.removeChild(closestUl);
    });

    document.addEventListener('DOMContentLoaded', function () {
            const propertyNameInput = document.getElementById('propertyName');
            const propertyTypeSelect = document.getElementById('propertyType');
            const optionsList = document.getElementById('optionsList');
            const addOptionButton = document.getElementById('addOptionButton');

            // Function to create an option item
            function createOptionItem() {
                const li = document.createElement('li');
                const optionInput = document.createElement('input');
                optionInput.type = 'text';
                optionInput.name = 'options[]';
                optionInput.required = true;
                const removeOptionButton = document.createElement('button');
                removeOptionButton.type = 'button';
                removeOptionButton.textContent = '-';
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

            // Event listener for property type change
            propertyTypeSelect.addEventListener('change', function () {
                const selectedType = propertyTypeSelect.value;
                const optionItems = document.querySelectorAll('#optionsList li');

                // Remove existing option items
                optionItems.forEach(item => {
                    item.classList.add('hidden');
                });

                if (selectedType === 'singleselect' || selectedType === 'multiselect') {
                    // Add an initial option item
                    const initialOptionItem = createOptionItem();
                    optionsList.appendChild(initialOptionItem);

                    // Show option items and add option button
                    optionItems.forEach(item => {
                        item.classList.remove('hidden');
                    });
                    addOptionButton.classList.remove('hidden');
                } else {
                    // Hide option items and add option button
                    optionItems.forEach(item => {
                        item.classList.add('hidden');
                    });
                    addOptionButton.classList.add('hidden');
                }
            });
        });
</script>