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
		checkbox.addEventListener("click", () => {
			arrowDown.classList.toggle('rotate-arrow');
			propertyHandleCheckboxClick(property)
			}
		);

		const propertyNameSpan = document.createElement('span');
		propertyNameSpan.textContent = property.name;
		propertyNameSpan.classList.add('ml-[0.59rem]', 'relative', 'bottom-[0.125rem]');
		propertyNameSpan.style.display = 'inline-flex';
		propertyNameSpan.style.width = '85%';
		propertyNameSpan.style.zIndex = 99;
		propertyNameSpan.classList.add('no-select');

		const arrowDownDiv = document.createElement('span');
		const arrowDown = document.createElement('img');

		arrowDownDiv.style.width = '85%';
		arrowDownDiv.classList.add('flex', 'items-center', 'justify-end');

		arrowDownDiv.appendChild(arrowDown);
		arrowDown.src = '../../images/arrow-down-icon.png';
		arrowDown.alt = 'Arrow Down';
		arrowDown.classList.add('w-[0.8rem]', 'h-[0.5rem]', 'flex', 'mt-[0.18rem]', 'mr-[0.8rem]', 'cursor-pointer');

		const textSpan = document.createElement("span");
		const text = document.createTextNode(property.name);
		textSpan.classList.add("ml-[0.59rem]", "font-[600]", "relative", "bottom-[0.125rem]")
		//add components to list item
		textSpan.appendChild(text);

		const propertyTitleContainer = document.createElement("div");
		propertyTitleContainer.classList.add("flex", "items-center");

        propertyTitleContainer.addEventListener('click', () => {

			arrowDown.classList.toggle('rotate-arrow');
			checkbox.checked = !checkbox.checked;
			propertyHandleCheckboxClick(property);
		});


		propertyTitleContainer.appendChild(checkbox);
		propertyTitleContainer.appendChild(textSpan);
		propertyTitleContainer.appendChild(arrowDownDiv);
		li.appendChild(propertyTitleContainer);
		renderProperty(property, li);

		//add listitem to list of properties
		propertyList.appendChild(li);
	});
}

function renderProperty(property, li) {
	const div = document.createElement("div");
	div.id = `div_${property.id}`;
	div.classList.add("hidden", "grid", "mt-[0.5rem]");
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
	const numberContainer = document.createElement("div");

	const numberselect = document.createElement("input");

	const decrement = document.createElement("div");
	const increment = document.createElement("div");

	const decrementIcon = document.createElement("img");
	decrementIcon.classList.add("select-none");
	decrementIcon.src = "../images/minus-icon.png";
	decrement.appendChild(decrementIcon);

	const incrementIcon = document.createElement("img");
	incrementIcon.classList.add("select-none");
	incrementIcon.src = "../images/plus-icon.png";
	increment.appendChild(incrementIcon);

	decrement.classList.add("w-[2.12rem]", "h-[2.12rem]", "flex", "items-center", "justify-center", "hover:cursor-pointer", "active:bg-gray-100", "rounded-md");
	decrement.style.border = "1px solid #D3D3D3";

	increment.classList.add("w-[2.12rem]", "h-[2.12rem]", "flex", "items-center", "justify-center", "hover:cursor-pointer", "active:bg-gray-100", "rounded-md");
	increment.style.border = "1px solid #D3D3D3";

	numberselect.type = "number";
	numberselect.value = 0;
	numberselect.classList.add("numberInput", "text-center", "w-[8.12rem]", "h-[2.12rem]", "flex");

	numberContainer.classList.add("flex", "rounded-md", "w-[12.3rem]");
	numberContainer.style.border = "1px solid #D3D3D3";

	numberContainer.appendChild(decrement);
	numberContainer.appendChild(numberselect);
	numberContainer.appendChild(increment);

	div.appendChild(numberContainer);

	decrement.addEventListener("click", () => {
		if (parseInt(numberselect.value) > 0) {
			numberselect.value = parseInt(numberselect.value) - 1;
		}
	});

	increment.addEventListener("click", () => {
		numberselect.value = parseInt(numberselect.value) + 1;
	});
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
		"w-[12.3rem]",
		"max-h-[12.37rem]",
		"rounded-mb",
	);

	input.addEventListener("focus", (event) => focusmultiSelect(options));
	input.addEventListener("blur", (event) => blurmultiSelect(options));

	//create options
	property.options.forEach((option, index) => {
		const li = document.createElement("li");

		if (index === 0) {
			li.classList.add("rounded-t-lg");
		}

		if (index === property.options.length - 1) {
			li.classList.add("rounded-b-lg");
		}
		
		li.classList.add(
			"flex",
			"items-center",
			"block",
			"w-[11rem]",
			"h-[2.12rem]",
			"px-4",
			"py-2.5",
			"text-sm",
			"hover:bg-gray-100",
			"focus:outline-none",
			"font-[300]",
			"hover:cursor-pointer",
			"line-clamp-2",
			"bg-white"
		);

		li.style.border = "1px solid #D3D3D3";

		const span = document.createElement("span");
		span.classList.add(
			"flex",
			"items-center",
			"justify-center",
			"text-[#717171]",
			"hover:cursor-pointer",
			"text-[14px]"
		);

		span.textContent = option;
		li.id = `property_${property.id}_${option}`;
		li.appendChild(span);
		li.addEventListener("click", (event) =>
			propertyMultiSelectControl(option, input, trueInput)
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
	"hover:bg-gray-100",
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
	"hover:bg-gray-100",
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
		"w-[12.3rem]",
		"max-h-[12.37rem]",
		"rounded-mb",
	);

	input.addEventListener("focus", (event) => focusSingleSelect(options));
	input.addEventListener("blur", (event) => blurSingleSelect(options));

	//create options
	property.options.forEach((option, index) => {
		const li = document.createElement("li");

		if (index === 0) {
			li.classList.add("rounded-t-lg");
		}

		if (index === property.options.length - 1) {
			li.classList.add("rounded-b-lg");
		}
		
		li.classList.add(
			"flex",
			"items-center",
			"block",
			"w-[11rem]",
			"h-[2.12rem]",
			"px-4",
			"py-2.5",
			"text-sm",
			"text-white",
			"hover:bg-gray-100",
			"focus:outline-none",
			"font-[300]",
			"hover:cursor-pointer",
			"line-clamp-2",
			"bg-white"
		);

		const span = document.createElement("span");
		span.classList.add(
			"flex",
			"items-center",
			"justify-center",
			"text-[#717171]",
			"text-[14px]"
		);

		li.style.border = "1px solid #D3D3D3";

		span.textContent = option;
		li.id = `property_${property.id}_${option}`;
		li.appendChild(span);
		li.addEventListener("click", (event) =>
			propertySingleSelectControl(option, input, trueInput)
		);
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

let blurMultiDelayTimer;
function blurmultiSelect(options) {
	clearTimeout(blurMultiDelayTimer);
	blurMultiDelayTimer = setTimeout(() => {
	options.classList.add("hidden");
	}, 200);
}

let blurDelayTimer;
function blurSingleSelect(options) {
	clearTimeout(blurDelayTimer);
	blurDelayTimer = setTimeout(() => {
	options.classList.add("hidden");
	}, 200);
}

function propertyMultiSelectControl(option, input, trueInput) {
	// Check if the surrounding div exists, if not, create it
	let selectedOptionsDiv = document.getElementById("selectedOptionsDiv");

	if (!selectedOptionsDiv) {
		selectedOptionsDiv = document.createElement("div");
		selectedOptionsDiv.classList.add("flex", "flex-wrap");
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
	newDiv.classList.add("selected-option", "flex", "items-center", "bg-white", "w-fit", "p-[0.3rem]", "rounded-md", "m-[0.25rem]");
	newDiv.style.border = "1px solid #D3D3D3";
	newDiv.setAttribute("data-value", option);

	// Create a span element
	const span = document.createElement("span");
	span.classList.add("flex", "pt-[0.16rem]", "pl-[0.2rem]");
	const removePropertyIcon = document.createElement("img");
	removePropertyIcon.classList.add("select-none", "w-[0.75rem]", "h-[0.75rem]", "hover:cursor-pointer");
	removePropertyIcon.src = "../images/x-icon.png";

	removePropertyIcon.addEventListener("click", function () {
	newDiv.remove(); // Remove the specific selected-option div
	});

	span.appendChild(removePropertyIcon);

	const textNode = document.createTextNode(option);

	newDiv.appendChild(textNode);

	// Append the span to the new div
	newDiv.appendChild(span);

	// Append the text node to the new div

	// Append the new div to the surrounding div
	selectedOptionsDiv.appendChild(newDiv);

	input.value = "";

	// Optionally, you can update the hidden input value as well
	trueInput.value += (trueInput.value ? "," : "") + option;
}

function propertySingleSelectControl(option, input, trueInput) {
	input.value = option;
	trueInput.value = option;
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
