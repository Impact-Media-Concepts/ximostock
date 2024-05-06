<x-layout._header-dependencies :sidenavActive="$sidenavActive" />

<body class="flex bg-[#F3F4F8] text-[#717171] text-[14px]" style="font-family: 'Inter', sans-serif;">
<x-header.header :activeWorkspace="$activeWorkspace" :workspaces="$workspaces"/>
    <div class="flex h-full pt-20 w-full gap-[1.9rem]">
        <div class="h-full">
            <x-sidenav.sidenav :activeWorkspace="$activeWorkspace"  :sidenavActive="$sidenavActive"/>
        </div>
        <form id="propertyForm" action="/properties" method="POST">
            @csrf

            <label for="propertyName">property Name:</label>
            <input type="text" id="propertyName" name="name" required>
            <label for="propertyType">property Type:</label>
            <select id="propertyType" name="type" required>
                <option value=""></option>
                <option value="singleselect">Single Select</option>
                <option value="multiselect">Multi Select</option>
                <option value="number">number</option>
                <option value="bool">bool</option>
                <option value="text">text</option>
            </select>
            <ul id="optionsList"></ul>
            <button type="button" id="addOptionButton" class="hidden">Add Option</button>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>

<x-layout._footer-dependencies />
<script type="text/javascript" src="{{ asset('./assets/js/product/navbar.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/header-button-data.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/show-pop-ups.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/container-bulk-actions.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/single-product-bulk-action.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/discount-values.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/sales-channels.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/collect-filters.js') }}"></script>
<script type="text/javascript" src="{{ asset('./assets/js/product/manage-bulk-action-form.js') }}"></script>

</html>
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