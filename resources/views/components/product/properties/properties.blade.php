<style>
    .property-search-input {
        padding-left: 1.2rem;
        padding-right: 1rem;
    }

    .multi-select-input,
    .text-input {
        padding-left: 0.8rem;
    }

    .property-search-input::placeholder,
    .multi-select-input::placeholder,
    .bool-input::placeholder,
    .single-input::placeholder {
        color: #DBDBDB;
    }

    .numberInput::-webkit-outer-spin-button,
    .numberInput::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
    }

    /* Firefox */
    .numberInput[type=number] {
    -moz-appearance: textfield;
    }
</style>
<div class="flex grid justify-center h-[28.37rem]">
    <div>
        <input class="property-search-input w-[14.06rem] h-[2.5rem] rounded-md" style="border: 1px solid #D3D3D3;" type="text" id="propertySearchInput" placeholder="Zoeken" />
        <ul class="mt-[0.8rem]" id="propertyList"></ul>
    </div>
</div>

<script>
    let propertiesData = [
        {
        id: 1,
        name: "number",
        type: "number",
        options: [],
        },
        {
        id: 2,
        name: "bool",
        type: "bool",
        options: [],
        },
        {
        id: 3,
        name: "multiSelect",
        type: "multiselect",
        options: [
            "lange sjonge jonge",
            "kort",
            "JJ",
            "ECHTSUPER LANGE text van jip",
            "Jipppiee",
            "twee superlange wrdn",
        ],
        },
        {
        id: 4,
        name: "Single select",
        type: "singleselect",
        options: ["red", "blue", "green", "purple", "yellow", "orange"],
        },
        {
        id: 5,
        name: "Text",
        type: "text",
        options: [],
        }
    ];

    function renderProperties() {
        const propertyList = document.getElementById("propertyList");
        propertyList.innerHTML = ""; // Clear existing list
        propertyList.style.maxHeight = '330px';
        propertyList.style.overflowY = 'scroll';
        propertiesData.forEach((property) => {
        const li = document.createElement("li");
        li.id = `li_${property.id}`;
        li.classList.add('pt-[0.35rem]', 'pb-[0.35rem]');
        //build components
        const checkbox = document.createElement("input");
        checkbox.type = "checkbox";
        checkbox.addEventListener("click", () =>
            propertyHandleCheckboxClick(property)
        );

        const textSpan = document.createElement("span");
        const text = document.createTextNode(property.name);
        textSpan.classList.add("ml-[0.59rem]", "font-[600]", "relative", "bottom-[0.125rem]")
        //add components to list item
        textSpan.appendChild(text);
        li.appendChild(checkbox);
        li.appendChild(textSpan);
        renderProperty(property, li);

        //add listitem to list of properties
        propertyList.appendChild(li);
        });
    }

    function renderProperty(property, li) {
        const div = document.createElement("div");
        div.id = `div_${property.id}`;
        div.classList.add("hidden", "grid");
        switch (property.type) {
        case "multiselect":
            rendermultiselect(property, div);
            break;
        case "bool":
            renderBool(property, div);
            break;
        case "singleselect":
            rendersingleselect(property, div);
            break;
        case "text":
            renderText(div);
            break;
        case "number":
            renderNumber(div);
            break;
        default:
            break;
        }
        li.appendChild(div);
    }

    function renderNumber(div) {
            const numberselect = document.createElement("input");
            
            const decrement = document.createElement("div");
            const increment = document.createElement("div");

            decrement.classList.add("w-[2.12rem]", "h-[2.12rem]", "flex");
            decrement.style.border = "1px solid #D3D3D3";
        
            increment.classList.add("w-[2.12rem]", "h-[2.12rem]");
            increment.style.border = "1px solid #D3D3D3";
            numberselect.type = "number";
            numberselect.classList.add("numberInput", "text-center", "w-[8.12rem]", "h-[2.12rem]", "flex");
            numberselect.style.border = "1px solid #D3D3D3";

            div.appendChild(decrement);
            div.appendChild(numberselect);
            div.appendChild(increment);
    }

    function rendermultiselect(property, div) {
        const input = document.createElement("input");
        const trueInput = document.createElement("input");

        input.type = "text";
        input.classList.add(
        "flex",
        "z-20",
        "w-[12.3rem]",
        "h-[2.12rem]",
        "items-center",
        "justify-start",
        "z-20",
        "text-sm",
        "font-light",
        "text-gray-700",
        "bottom-[0.05rem]",
        "rounded-md",
        "relative",
        "top-[0.02rem]",
        "multi-select-input",
        "mb-[0.25rem]"
        );
        input.id = `searchProp_${property.id}`;
        input.style.border = "1px solid #D3D3D3";
        input.placeholder = "Zoeken";
        input.addEventListener("input", (event) =>
        searchProperty(input.value, property, trueInput)
        );

        //actual input send to backend
        trueInput.type = "hidden";
        trueInput.name = `prop[${property.id}]`;

        const options = document.createElement("ul");
        options.classList.add(
        "hidden",
        "overflow-y-auto",
        "overflow-x-hidden",
        "w-[11rem]",
        "max-h-[14rem]",
        "mt-[2rem]"
        );

        input.addEventListener("focus", (event) => focusmultiSelect(options));
        input.addEventListener("blur", (event) => blurmultiSelect(options));

        //create options
        property.options.forEach((option) => {
        const li = document.createElement("li");
        li.classList.add(
            "flex",
            "items-center",
            "block",
            "w-[11rem]",
            "px-4",
            "py-2.5",
            "text-sm",
            "text-white",
            "hover:bg-gray-200",
            "focus:outline-none",
            "font-[300]",
            "hover:cursor-pointer"
        );

        const span = document.createElement("span");
        span.classList.add(
            "flex",
            "items-center",
            "justify-center",
            "text-black",
            "hover:cursor-pointer"
        );

        span.textContent = option;
        li.id = `property_${property.id}_${option}`;
        li.appendChild(span);
        li.addEventListener("click", (event) =>
            propertySelectControl(option, input, trueInput)
        );
        options.appendChild(li);
        });
        div.appendChild(input);
        div.appendChild(trueInput);
        div.appendChild(options);
    }

    function renderBool(property, div) {
        const input = document.createElement("input");
        const trueInput = document.createElement("input");

        input.type = "text";
        input.classList.add(
        "z-20",
        "w-[12.3rem]",
        "h-[2.12rem]",
        "pl-3",
        "flex",
        "items-center",
        "justify-start",
        "text-sm",
        "font-light",
        "text-gray-700",
        "bottom-[0.05rem]",
        "rounded-md",
        "focus:ring-[#717171]",
        "top-[0.02rem]",
        "mb-1",
        "bool-input"
        );
        input.style.border = "1px solid #D3D3D3";
        input.id = `searchProp_${property.id}`;
        input.placeholder = "Zoeken";
        input.addEventListener("input", (event) =>
        searchProperty(input.value, property, trueInput)
        );

        //actual input send to backend
        trueInput.type = "hidden";
        trueInput.name = `prop[${property.id}]`;

        const options = document.createElement("ul");
        options.classList.add(
        "hidden",
        "w-[11rem]",
        "flex",
        "gap-1",
        "grid"
        );

        input.addEventListener("focus", (event) => focusSingleSelect(options));
        input.addEventListener("blur", (event) => blurSingleSelect(options));

        const optionTrue = document.createElement("li");
        const optionFalse = document.createElement("li");
        optionTrue.classList.add(
        "flex",
        "items-center",
        "block",
        "w-[12.3rem]",
        "h-[2.12rem]",
        "px-4",
        "py-2.5",
        "text-sm",
        "text-white",
        "hover:bg-gray-200",
        "focus:outline-none",
        "font-[300]",
        "rounded-md",
        "hover:cursor-pointer"
        );
        optionTrue.style.border = "1px solid #D3D3D3";
        optionFalse.classList.add(
        "flex",
        "items-center",
        "block",
        "w-[12.3rem]",
        "h-[2.12rem]",
        "px-4",
        "py-2.5",
        "text-sm",
        "text-white",
        "hover:bg-gray-200",
        "focus:outline-none",
        "font-[300]",
        "rounded-md",
        "hover:cursor-pointer"
        );
        optionFalse.style.border = "1px solid #D3D3D3";
        const spanTrue = document.createElement("span");
        const spanFalse = document.createElement("span");
        spanTrue.classList.add(
        "flex",
        "items-center",
        "justify-center",
        "text-black"
        );
        spanFalse.classList.add(
        "flex",
        "items-center",
        "justify-center",
        "text-black"
        );
        spanTrue.textContent = "Ja";
        spanFalse.textContent = "Nee";
        optionTrue.id = `property_${property.id}_true`;
        optionFalse.id = `property_${property.id}_false`;
        optionTrue.appendChild(spanTrue);
        optionFalse.appendChild(spanFalse);
        optionTrue.addEventListener("click", (event) =>
        BoolControl(1, input, trueInput)
        );
        optionFalse.addEventListener("click", (event) =>
        BoolControl(0, input, trueInput)
        );
        options.appendChild(optionTrue);
        options.appendChild(optionFalse);

        div.appendChild(input);
        div.appendChild(trueInput);
        div.appendChild(options);
    }

    function focusmultiSelect(options) {
        options.classList.remove("hidden");
        options.querySelectorAll("li").forEach((option) => {
        option.classList.remove("hidden");
        });
    }

    function BoolControl(option, input, trueInput) {
        if (option) {
        input.value = "Ja";
        } else {
        input.value = "Nee";
        }

        trueInput.value = option;
    }

    //render the singel select property
    function rendersingleselect(property, div) {
        const input = document.createElement("input");
        const trueInput = document.createElement("input");

        input.type = "text";
        input.classList.add(
            "z-20",
            "w-[12.3rem]",
            "h-[2.12rem]",
            "pl-3",
            "flex",
            "items-center",
            "justify-start",
            "text-sm",
            "font-light",
            "text-gray-700",
            "bottom-[0.05rem]",
            "rounded-md",
            "focus:ring-[#717171]",
            "top-[0.02rem]",
            "mb-1",
            "single-input"
        );
        input.style.border = "1px solid #D3D3D3";
        input.id = `searchProp_${property.id}`;
        input.placeholder = "Zoeken";
        input.addEventListener("input", (event) =>
        searchProperty(input.value, property, trueInput)
        );

        //actual input send to backend
        trueInput.type = "hidden";
        trueInput.name = `prop[${property.id}]`;

        const options = document.createElement("ul");
        options.classList.add(
        "hidden",
        "overflow-y-auto",
        "overflow-x-hidden",
        "max-h-[14rem]",
        "w-[12.3rem]"
        );

        input.addEventListener("focus", (event) => focusSingleSelect(options));
        input.addEventListener("blur", (event) => blurSingleSelect(options));

        //create options
        property.options.forEach((option) => {
        const li = document.createElement("li");
        li.classList.add(
            "flex",
            "items-center",
            "block",
            "w-[12.3rem]",
            "h-[2.12rem]",
            "px-4",
            "py-2.5",
            "text-sm",
            "text-white",
            "hover:bg-gray-200",
            "focus:outline-none",
            "font-[300]",
            "rounded-md",
            "hover:cursor-pointer"
        );
        const span = document.createElement("span");
        span.classList.add(
            "flex",
            "items-center",
            "justify-center",
            "text-black"
        );
        span.textContent = option;
        li.id = `property_${property.id}_${option}`;
        li.appendChild(span);
        //   li.addEventListener("click", (event) =>
        //     propertySelectControl(option, input, trueInput)
        //   );
        options.appendChild(li);
        });
        div.appendChild(input);
        div.appendChild(trueInput);
        div.appendChild(options);
    }

    function renderText(div) {
        const textSpan = document.createElement("span");
        const text = document.createElement("input");

        text.type = "text";
        text.classList.add("w-[12.3rem]", "rounded-md","h-[2.12rem]", "text-input");

        text.style.border = "1px solid #D3D3D3";
        textSpan.appendChild(text);
        div.appendChild(textSpan);
    }

    function renderTextBox() {
        // TODO
    }

    function focusSingleSelect(options) {
        options.classList.remove("hidden");
        options.querySelectorAll("li").forEach((option) => {
        option.classList.remove("hidden");
        });
    }

    let blurMultiDelayTimer; // Define a timer variable
    function blurmultiSelect(options) {
        clearTimeout(blurMultiDelayTimer);
        blurMultiDelayTimer = setTimeout(() => {
        options.classList.add("hidden");
        }, 200); // Adjust delay time as needed
    }

    let blurDelayTimer; // Define a timer variable
    function blurSingleSelect(options) {
        clearTimeout(blurDelayTimer);
        blurDelayTimer = setTimeout(() => {
        options.classList.add("hidden");
        }, 200); // Adjust delay time as needed
    }

    function propertySelectControl(option, input, trueInput) {
        // Check if the surrounding div exists, if not, create it
        let selectedOptionsDiv = document.getElementById("selectedOptionsDiv");
        if (!selectedOptionsDiv) {
        selectedOptionsDiv = document.createElement("div");
        selectedOptionsDiv.id = "selectedOptionsDiv";
        input.parentNode.insertBefore(selectedOptionsDiv, input.nextSibling);
        }

        // Check if the option is already selected
        if (
        selectedOptionsDiv.querySelector(
            `div.selected-option[data-value="${option}"]`
        )
        ) {
        // If the option is already selected, do nothing
        return;
        }

        // Create a new div for the selected option
        const newDiv = document.createElement("div");
        newDiv.classList.add("selected-option", "flex", "items-center"); // Add class to style the selected option
        newDiv.setAttribute("data-value", option); // Add data attribute to store the option value

        // Create a span element
        const span = document.createElement("span");

        // Create the SVG element
        const svg = document.createElementNS(
        "http://www.w3.org/2000/svg",
        "svg"
        );
        svg.setAttribute("xmlns", "http://www.w3.org/2000/svg");
        svg.setAttribute("fill", "none");
        svg.setAttribute("viewBox", "0 0 24 24");
        svg.setAttribute("stroke-width", "2");
        svg.setAttribute("stroke", "gray");
        svg.classList.add(
        "discount-popup-close",
        "flex",
        "items-center",
        "justify-center",
        "w-8",
        "h-8",
        "cursor-pointer"
        );

        // Create the path element within SVG
        const path = document.createElementNS(
        "http://www.w3.org/2000/svg",
        "path"
        );
        path.setAttribute("stroke-linecap", "round");
        path.setAttribute("stroke-linejoin", "round");
        path.setAttribute("d", "M6 18 18 6M6 6l12 12");

        // Add click event listener to the SVG to remove the specific selected-option div
        svg.addEventListener("click", function () {
        newDiv.remove(); // Remove the specific selected-option div
        });

        // Append the path to the SVG
        svg.appendChild(path);

        // Append the SVG to the span
        span.appendChild(svg);

        // Create a text node for the option
        const textNode = document.createTextNode(option);

        newDiv.appendChild(textNode);
        // Append the span to the new div
        newDiv.appendChild(span);

        // Append the text node to the new div

        // Append the new div to the surrounding div
        selectedOptionsDiv.appendChild(newDiv);

        // Clear the input value
        input.value = "";

        // Optionally, you can update the hidden input value as well
        trueInput.value += (trueInput.value ? "," : "") + option;
    }

    //search through properties options
    function searchProperty(searchText, property, trueInput) {
        searchText = searchText.trim();
        property.options.forEach((option) => {
        const li = document.getElementById(
            `property_${property.id}_${option}`
        );
        if (
            !searchText ||
            option.toLowerCase().includes(searchText.toLowerCase())
        ) {
            li.classList.remove("hidden");
        } else {
            li.classList.add("hidden");
        }
        });
        trueInput.value = null;
    }

    function propertyHandleCheckboxClick(property) {
        property.checked = !property.checked;
        const div = document.getElementById(`div_${property.id}`);
        if (property.checked) {
        div.classList.remove("hidden");
        } else {
        div.classList.add("hidden");
        }
    }

    function searchProperties(searchText) {
        propertiesData.forEach((property) => {
        const li = document.getElementById(`li_${property.id}`);
        if (
            !searchText ||
            property.name.toLowerCase().includes(searchText.toLowerCase())
        ) {
            li.classList.remove("hidden");
        } else {
            li.classList.add("hidden");
        }
        });
    }

    //initialise render
    renderProperties();

    // Add event listener for search input
    const propertySearchInput = document.getElementById("propertySearchInput");
    propertySearchInput.addEventListener("input", () => {
        const searchText = propertySearchInput.value.trim();

        // Render properties if search input is empty
        if (!searchText) {
        renderProperties(); //vervang met show all
        } else {
        searchProperties(searchText);
        }
    });
</script>
