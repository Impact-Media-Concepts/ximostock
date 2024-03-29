<style>
    .hidden {
        display: none;
    }
</style>

<div class="flex grid justify-center items-center w-56">
    <input type="text" id="propertySearchInput" placeholder="Search..." />
    <ul id="propertyList"></ul>
</div>

<script>
    let propertiesData = [
    {
        id: 2,
        name: "mutliselect",
        type: "mutliselect",
        options: [
        "lange sjonge jonge",
        "kort",
        "JJ",
        "ECHTSUPER LANGE text van jip",
        "Jipppiee",
        "twee superlange wrdn",
        ],
    },
    ];

    function renderProperties() {
    const propertyList = document.getElementById("propertyList");
    propertyList.innerHTML = ""; // Clear existing list

    propertiesData.forEach((property) => {
        const li = document.createElement("li");
        li.id = `li_${property.id}`;
        //build components
        const checkbox = document.createElement("input");
        checkbox.type = "checkbox";
        checkbox.addEventListener("click", () =>
        handleCheckboxClick(property)
        );
        const text = document.createTextNode(property.name);

        //add components to list item
        li.appendChild(checkbox);
        li.appendChild(text);
        renderProperty(property, li);

        //add listitem to list of properties
        propertyList.appendChild(li);
    });
    }

    function renderProperty(property, li) {
    const div = document.createElement("div");
    div.id = `div_${property.id}`;
    div.classList.add("hidden", "flex-col");
    switch (property.type) {
        case "mutliselect":
        rendermutliselect(property, div);
        break;
        default:
        break;
    }
    li.appendChild(div);
    }

    //render the singel select property
    function rendermutliselect(property, div) {
    const input = document.createElement("input");
    const trueInput = document.createElement("input");

    input.type = "text";
    input.classList.add(
        "flex",
        "z-20",
        "w-[14.25rem]",
        "h-[2.78rem]",
        "flex",
        "items-center",
        "justify-start",
        "z-20",
        "w-[14rem]",
        "h-[2.78rem]",
        "text-sm",
        "font-light",
        "text-gray-700",
        "bottom-[0.05rem]",
        "border-1",
        "border-white",
        "rounded-md",
        "shadow-sm",
        "focus:outline-none",
        "focus:ring-2",
        "focus:ring-indigo-500",
        "focus:ring-offset-2",
        "focus:ring-offset-gray-100",
        "relative",
        "top-[0.02rem]"
    );
    input.id = `searchProp_${property.id}`;
    input.placeholder = "zoek...";
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
        "max-h-[14rem]"
    );

    input.addEventListener("focus", (event) => focusmutliSelect(options));
    input.addEventListener("blur", (event) => blurmutliSelect(options));

    //create options
    property.options.forEach((option) => {
        const li = document.createElement("li");
        li.classList.add(
        "flex",
        "items-center",
        "block",
        "w-[14rem]",
        "px-4",
        "py-2.5",
        "text-sm",
        "text-white",
        "hover:bg-gray-200",
        "focus:outline-none",
        "font-[300]"
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
        li.addEventListener("click", (event) =>
        selectControl(option, input, trueInput)
        );
        options.appendChild(li);
    });
    div.appendChild(input);
    div.appendChild(trueInput);
    div.appendChild(options);
    }

    function focusmutliSelect(options) {
    options.classList.remove("hidden");
    options.querySelectorAll("li").forEach((option) => {
        option.classList.remove("hidden");
    });
    }

    let blurDelayTimer; // Define a timer variable
    function blurmutliSelect(options) {
    clearTimeout(blurDelayTimer);
    blurDelayTimer = setTimeout(() => {
        options.classList.add("hidden");
    }, 200); // Adjust delay time as needed
    }

    function selectControl(option, input, trueInput) {
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

    function handleCheckboxClick(property) {
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

    // Render categories if search input is empty
    if (!searchText) {
        renderProperties(); //vervang met show all
    } else {
        searchProperties(searchText);
    }
    });
</script>