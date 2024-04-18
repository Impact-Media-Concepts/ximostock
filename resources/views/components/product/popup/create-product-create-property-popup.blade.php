
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
                    <p>Korting</p>
                </div>
                <form id="propertyForm" action="/properties" method="POST">
                    @csrf

                    <label for="propertyName">property Name:</label>
                    <input type="text" id="propertyName" name="name" autocomplete="off" required>
                    <label for="propertyType">property Type:</label>
                    <select id="propertyType" name="type" required>
                        <option value=""></option>
                        <option value="singleselect">Single Select</option>
                        <option value="multiselect">Multi Select</option>
                        <option value="number">number</option>
                        <option value="bool">bool</option>
                        <option value="text">text</option>
                    </select>
                    <ul id="optionsList" class="overflow-y-auto max-h-[14rem]"></ul>
                    <button type="button" id="addOptionButton" class="hidden">Add Option</button>
                </form>
                
                <div class="create-prop-buttons flex items-center gap-[0.7rem] absolute bottom-[1.1rem] right-[0.3rem]">
                    <button type="button" class="create-prop-close flex justify-center gap-2 items-center create-propCancel w-[7.87rem] h-[2.68rem] hover:bg-gray-100 rounded-md" style="border: 1px solid #717172;">
                        <img class="create-prop-close select-none w-[0.8rem] h-[0.8rem] flex" src="../images/x-icon.png" alt="x icon">
                        <p class="create-prop-close flex text-[#717171]">Annuleren</p>
                    </button>
                    <button type="button" class="flex justify-center items-center w-[7.87rem] h-[2.68rem] bg-[#3DABD5] rounded-md hover:bg-[#3999BE] gap-[0.5rem]">
                        <img src="../images/save-icon.png">
                        <p class="flex text-[#F8F8F8]">Save</p>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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