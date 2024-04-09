document.addEventListener("DOMContentLoaded", () => {

	document.getElementById("propertySearchInput").onkeypress = function (e) {
		let key = e.charCode || e.keyCode || 0;
		if (key == 13) {
			e.preventDefault();
		}
	}

	function renderProperties() {
		const propertyList = document.getElementById("propertyList");
		propertyList.innerHTML = ""; // Clear existing list
		propertyList.classList.add('hd:max-h-[22.5rem]','uhd:max-h-[30rem]');
		propertyList.style.overflowY = 'scroll';
		propertiesData.forEach((property) => {
			const li = document.createElement("li");
			li.id = `properties_li_${property.id}`;
			li.classList.add('pt-[0.35rem]', 'pb-[0.35rem]');
			//build components
			const checkbox = document.createElement("input");
			checkbox.type = "checkbox";
			checkbox.classList.add("hover:cursor-pointer");

			//add true input
			const trueInput = document.createElement('input');
			trueInput.id = `properties[${property.id}]`;
			trueInput.type = 'hidden';
			trueInput.value = null;

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
			arrowDownDiv.classList.add('flex', 'items-center', 'justify-end', "select-none");

			arrowDownDiv.appendChild(arrowDown);
			arrowDown.src = '../../images/arrow-down-icon.png';
			arrowDown.alt = 'Arrow Down';
			arrowDown.classList.add('w-[0.8rem]', 'h-[0.5rem]', 'flex', 'mt-[0.18rem]', 'mr-[0.8rem]', 'cursor-pointer');

			const textSpan = document.createElement("span");
			const text = document.createTextNode(property.name);
			textSpan.classList.add("ml-[0.59rem]", "font-[600]", "relative", "bottom-[0.125rem]", "select-none")
			//add components to list item
			textSpan.appendChild(text);

			const propertyTitleContainer = document.createElement("div");
			propertyTitleContainer.classList.add("flex", "items-center");

			checkbox.addEventListener("click", () => { checkbox.checked = !checkbox.checked; });

			propertyTitleContainer.addEventListener('click', () => {
				propertyHandleCheckboxClick(property, trueInput);
				arrowDown.classList.toggle('rotate-arrow');
				checkbox.checked = !checkbox.checked;
			}
			);

			propertyTitleContainer.appendChild(checkbox);
			propertyTitleContainer.appendChild(textSpan);
			propertyTitleContainer.appendChild(arrowDownDiv);
			propertyTitleContainer.appendChild(trueInput);
			li.appendChild(propertyTitleContainer);
			renderProperty(property, li, trueInput);


			//add listitem to list of properties
			propertyList.appendChild(li);

			//open property if it is already selected
			if (property.selected) {
				propertyHandleCheckboxClick(property, trueInput);
				arrowDown.classList.toggle('rotate-arrow');
				checkbox.checked = !checkbox.checked;
				trueInput.value = property.selectedOption;
			}
		});
	}

	function renderProperty(property, li, trueInput) {
		const div = document.createElement("div");
		div.id = `div_${property.id}`;
		div.classList.add("hidden", "grid", "mt-[0.5rem]");
		switch (property.type) {
			case "multiselect":
				rendermultiselect(property, div, trueInput);
				break;
			case "bool":
				renderBool(property, div, trueInput);
				break;
			case "singleselect":
				rendersingleselect(property, div, trueInput);
				break;
			case "text":
				renderText(div, trueInput, property);
				break;
			case "number":
				renderNumber(div, trueInput, property);
				break;
			default:
				break;
		}
		li.appendChild(div);
	}

	function renderNumber(div, trueInput, property) {
		const numberContainer = document.createElement("div");

		const numberselect = document.createElement("input");
		numberselect.addEventListener('input', function () {
			trueInput.value = this.value;
		});

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
		if(property.selectedOption){
			numberselect.value = property.selectedOption;
		}else{
			numberselect.value = 0;
		}
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
			trueInput.value = numberselect.value;

		});

		increment.addEventListener("click", () => {
			numberselect.value = parseInt(numberselect.value) + 1;

			trueInput.value = numberselect.value;
		});
	}

	function rendermultiselect(property, div, trueInput) {
		const input = document.createElement("input");

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
			searchProperty(input.value, property)
		);


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
		div.appendChild(input);

		const selectedOptionsContainer = document.createElement('div');	
			selectedOptionsContainer.style.display = "flex";
			selectedOptionsContainer.style.flexWrap = "wrap";
			selectedOptionsContainer.id = `selectedOptionsContainer${property.id}`;
			//input.parentNode.insertBefore(selectedOptionsContainer, input.nextSibling);
			div.insertBefore(selectedOptionsContainer, div.nextSibling);

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
				propertyMultiSelectControl(option, input, property.id, trueInput, selectedOptionsContainer)
			);
			options.appendChild(li);


		});
		
		div.appendChild(options);

		property.options.forEach((option) => {
			//add options already selected
			if (property.selected && property.selectedOption != null) {
				selectedOptions = property.selectedOption.split(',');
				if (selectedOptions.includes(option)) {
					propertyMultiSelectControl(option, input, property.id, trueInput, selectedOptionsContainer);
				}
			}
		});
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
		const input = document.createElement("input");

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
			searchProperty(input.value, property)
		);

		if(property.selectedOption == 'true'){
			input.value = 'ja';
		}else if(property.selectedOption == 'false'){
			input.value = 'nee';
		}

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
			trueInput.value = true;
		} else {
			input.value = "Nee";
			trueInput.value = false;
		}
	}

	//render the singel select property
	function rendersingleselect(property, div, trueInput) {
		const input = document.createElement("input");

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
		input.addEventListener("input", (event) => {
			searchProperty(input.value, property);
			trueInput.value = '';
		});
		input.value = property.selectedOption;

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
		div.appendChild(options);
	}

	function renderText(div, trueInput, property) {
		const textSpan = document.createElement("span");
		const text = document.createElement("input");

		text.type = "text";
		text.classList.add("w-[12.3rem]", "rounded-md", "h-[2.12rem]", "text-input");
		text.addEventListener('input', function () {
			trueInput.value = text.value;
		});
		text.value = property.selectedOption;

		text.style.border = "1px solid #D3D3D3";
		textSpan.appendChild(text);
		div.appendChild(textSpan);
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

	function propertyMultiSelectControl(option, input, id, trueInput, selectedOptionsContainer) {
		// Check if the option is already selected
		if (selectedOptionsContainer.querySelector(`div.selected-option[data-value="${option}"]`)) {
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
		removePropertyIcon.src = '../images/x-icon.png';

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
	function searchProperty(searchText, property) {
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
	}

	function propertyHandleCheckboxClick(property, trueInput) {
		property.checked = !property.checked;
		const div = document.getElementById(`div_${property.id}`);
		if (property.checked) {
			div.classList.remove("hidden");
		} else {
			div.classList.add("hidden");
		}

		if (property.checked) {
			trueInput.name = `properties[${property.id}]`;
		} else {
			trueInput.name = '';
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

			const li = document.getElementById(`properties_li_${property.id}`);
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
});
