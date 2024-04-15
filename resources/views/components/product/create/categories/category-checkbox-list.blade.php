@props(['categories', 'checkedCategories' => null])

<div id="categoriesContainer">
    <div class="w-full flex justify-center items-center">
        <input id="createProductCategoriesSearch" class="sticky property-search-input w-[43.43rem] h-[2.5rem] rounded-md mt-[0.55rem]" style="border: 1px solid #D3D3D3;" type="text" placeholder="Zoeken" />
    </div>
    <ul id="categoriesList"></ul>
</div>
<script>
    let categoriesData = [
        <x-product.create.categories.category-data :categories="$categories" :checkedCategories="$checkedCategories"/>
    ];

    // Find parent function
    function findParentCategory(categoryId, categories = categoriesData, parent = null) {
        for (const category of categories) {
            if (category.id === categoryId) {
                return parent;
            } else if (category.subcategories && category.subcategories.length > 0) {
                const result = findParentCategory(categoryId, category.subcategories, category);
                if (result !== null) {
                    return result;
                }
            }
        }
        return null;
    }

    // Function to update parents based on child state
    function updateParents(category) {
        const parentCategory = findParentCategory(category.id);
        if (parentCategory) {
            parentCategory.checked = true;
            updateParents(parentCategory);
        }
    }

    // Function to update subcategories based on parent state
    function uncheckSubcategories(category) {
        const ul = document.getElementById(`category_${category.id}`).querySelector('ul');
        ul.classList.add('hidden');
        category.subcategories.forEach(subcategory => {
            subcategory.checked = false; // Uncheck the subcategory itself
            const input = document.getElementById(`checkbox_category_${subcategory.id}`);
            input.checked = false;
            if (subcategory.subcategories.length > 0) {
                uncheckSubcategories(subcategory); // Recursively uncheck subcategories
            }
        });
    }

    // Function to search categories and subcategories
    function searchCategories(searchText) {
        categoriesData.forEach(category => {
            const li = document.getElementById(`category_${category.id}`);
            if (!searchText || category.name.toLowerCase().includes(searchText.toLowerCase())) {
                li.classList.remove('hidden');
                // If found through search, show its parents
                showParents(li);
            } else {
                li.classList.add('hidden');
            }

            if (category.subcategories.length > 0) {
                searchSubcategories(category.subcategories, searchText);
            }
        });
    }

    // Function to search subcategories recursively
    function searchSubcategories(subcategories, searchText) {
        subcategories.forEach(subcategory => {
            const li = document.getElementById(`category_${subcategory.id}`);
            if (!searchText || subcategory.name.toLowerCase().includes(searchText.toLowerCase())) {
                li.classList.remove('hidden');
                // If found through search, show its parents
                showParents(li);
            } else {
                li.classList.add('hidden');
            }

            if (subcategory.subcategories.length > 0) {
                searchSubcategories(subcategory.subcategories, searchText);
            }
        });
    }

    // Function to show parents recursively
    function showParents(element) {
        if (element.parentNode.tagName === 'UL') {
            const parentElement = element.parentNode.parentNode;
            parentElement.classList.remove('hidden');
            element.parentNode.classList.remove('hidden');
            showParents(parentElement);
        }
    }



    function showSubcategories(category) {
        const li = document.getElementById(`category_${category.id}`);
        const ul = li.querySelector('ul');
        if (ul) {
            ul.classList.remove('hidden');
        }
        category.subcategories.forEach(subcategory => {
            const subcategoryElement = document.getElementById(`category_${subcategory.id}`);
            subcategoryElement.classList.remove('hidden');
        });
    }

    function hideSubcategories(category) {
        const li = document.getElementById(`category_${category.id}`);
        const ul = li.querySelector('ul');
        if (ul) {
            ul.classList.add('hidden');
        }
        category.subcategories.forEach(subcategory => {
            const subcategoryElement = document.getElementById(`category_${subcategory.id}`);
            subcategoryElement.classList.add('hidden');
        });
    }

    function updateCategories() {

        //searchCategories();
        const searchText = document.getElementById('createProductCategoriesSearch').value.trim();

        categoriesData.forEach(category => {
            const checkbox = document.getElementById(`checkbox_category_${category.id}`);
            checkbox.checked = category.checked;

            if (category.subcategories !== null) {
                if (category.subcategories.length > 0) {
                    updateSubcategories(category.subcategories);
                }
            }
        });
    }

    function updateSubcategories(subcategories) {
        const searchText = document.getElementById('createProductCategoriesSearch').value.trim();

        subcategories.forEach(subcategory => {
            const checkbox = document.getElementById(`checkbox_category_${subcategory.id}`);
            checkbox.checked = subcategory.checked;

            if (subcategory.subcategories !== null) {
                if (subcategory.subcategories.length > 0) {
                    updateSubcategories(subcategory.subcategories);
                }
            }
        });
    }

    // Function to render categories
    function renderCategories() {
        const categoriesList = document.getElementById('categoriesList');
        categoriesList.innerHTML = ''; // Clear existing list

        const ul = document.createElement('ul');
        categoriesData.forEach(category => {
            const li = document.createElement('li');
            li.id = `category_${category.id}`;
            li.classList.add('ml-5');
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.checked = category.checked;
            checkbox.id = `checkbox_category_${category.id}`;

            checkbox.addEventListener('click', () => handleCheckboxClick(category));
            li.appendChild(checkbox);
            li.appendChild(document.createTextNode(category.name));
            ul.appendChild(li);

            if (category.subcategories.length > 0) {
                renderSubcategories(category.subcategories, li, category.checked);
            }
        });
        categoriesList.appendChild(ul);
    }

    // Function to render subcategories
    function renderSubcategories(subcategories, parentElement, parentChecked) {
        const ul = document.createElement('ul');

        // Add class to hide subcategories if parent category is not checked
        if (!parentChecked) {
            ul.classList.add('hidden');
        }

        subcategories.forEach(subcategory => {
            const li = document.createElement('li');
            li.id = `category_${subcategory.id}`;
            li.classList.add('ml-5');
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.value = subcategory.id;
            checkbox.checked = subcategory.checked;
            checkbox.id = `checkbox_category_${subcategory.id}`;

            checkbox.addEventListener('click', () => handleCheckboxClick(subcategory));
            li.appendChild(checkbox);
            li.appendChild(document.createTextNode(subcategory.name));
            ul.appendChild(li);

            if (subcategory.subcategories.length > 0) {
                renderSubcategories(subcategory.subcategories, li, subcategory.checked);
            }
        });
        parentElement.appendChild(ul);
    }

    function renderSelectedCategories() {
        const selectedCategoryList = document.getElementById('categoryListContainer');
        if (selectedCategoryList) {
            selectedCategoryList.innerHTML = '';
        }

        categoriesData.forEach(category => {
            if (category.checked) {
                addSelectedCategory(category);
            }
            if (category.subcategories.length > 0) {
                renderSelectedSubcategories(category.subcategories);
            }
        });

    }

    function renderSelectedSubcategories(subcategories) {
        const selectedCategoryList = document.getElementById('categoryListContainer');
        subcategories.forEach(subcategory => {
            if (subcategory.checked) {
                addSelectedCategory(subcategory);
            }
            if (subcategory.subcategories.length > 0) {
                renderSelectedSubcategories(subcategory.subcategories);
            }
        });
    }

    //funciton to add a selected category.
    function addSelectedCategory(category) {
        const selectedCategoryList = document.getElementById('selectedCategoriesList');
        const categoryListContainer = document.getElementById('categoryListContainer');
        
        const li = document.createElement('li');
        li.id = `selectedCategory${category.id}`;
        li.classList.add("h-[5.87rem]", "w-[42.87rem]", "flex", "justify-center", "items-center", "rounded-md", "mt-[1rem]");
        li.style.border =  "1px solid #D3D3D3";


        const categoryContainer = document.createElement('div');
        categoryContainer.classList.add("flex", "gap-[0.5rem]")
        
        const titleContainer = document.createElement('div');
        titleContainer.classList.add("h-[3.87rem]");

        const title = document.createElement('p');
        title.classList.add("text-[#717171]", 'text-[14px]');
        title.textContent = "Categorie titel:";

        const titleSubContainer = document.createElement('p');
        titleSubContainer.classList.add("h-[2.5rem]", "w-[14.68rem]", "rounded-md", "flex", "justify-start", "items-center", "pl-[0.5rem]", "truncate");
        titleSubContainer.style.border = "1px solid #D3D3D3";
        titleSubContainer.textContent = `${category.name}`;

        titleContainer.appendChild(title);
        titleContainer.appendChild(titleSubContainer);

        const pathContainer = document.createElement('div');
        pathContainer.classList.add("h-[3.87rem]");

        const pathTitle = document.createElement('p');
        pathTitle.classList.add("text-[#717171]", 'text-[14px]');
        pathTitle.textContent = "Categorie path:";

        const pathSubContainer = document.createElement('p');
        pathSubContainer.classList.add("select-none", "h-[2.5rem]", "w-[10.81rem]", "rounded-md", "flex", "justify-start", "items-center", "pl-[0.5rem]", "truncate");
        pathSubContainer.style.border = "1px solid #D3D3D3";
        pathSubContainer.textContent = getCategoryPath(category);
        pathSubContainer.title = getCategoryPath(category);


        const imgContainer = document.createElement("div");
        imgContainer.classList.add("relative", "left-[0.5rem]", "bottom-[1.5rem]", "hover:cursor-pointer");
        imgContainer.addEventListener('click', () => handleCheckboxClick(category));

        const x_icon = document.createElement('img');
        x_icon.src = "../images/x-icon.png";
        x_icon.alt = "x icon";
        x_icon.classList.add("select-none", "size-[1rem]")

        imgContainer.appendChild(x_icon);

        pathContainer.appendChild(pathTitle);
        pathContainer.appendChild(pathSubContainer);
        pathContainer.appendChild(imgContainer);

       
        const setPrimaryButton = document.createElement('button');
        setPrimaryButton.textContent = 'Zet als primaire categorie';
        setPrimaryButton.addEventListener('click', () => {
            const clickedButton = event.target; // Get the clicked button
            console.log("primary btn before: ", clickedButton);
            setPrimaryCategory(category, clickedButton);
            console.log(category);          
        }); 
        setPrimaryButton.classList.add("h-[2.5rem]", "w-[12.62rem]", "mt-[1.2rem]", "hover:bg-gray-100", "rounded-md", "text-[14px]");
        setPrimaryButton.style.border = "1px solid #717172";
        setPrimaryButton.type = "button";
        setPrimaryButton.id = `primaryCategory${category.id}`;

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = `categories[${category.id}]`;
        input.value = category.primary ? 1 : 0;



        categoryContainer.appendChild(titleContainer);
        categoryContainer.appendChild(pathContainer);
        categoryContainer.appendChild(setPrimaryButton);
        li.appendChild(categoryContainer);
        li.appendChild(input);
        li.appendChild(imgContainer);
        categoryListContainer.appendChild(li);
    }

    function getCategoryPath(category) {
        let path = category.name;

        function traversePath(currentCategory) {
            parent = findParentCategory(currentCategory.id);
            if (parent) {

                path = `${parent.name}/${path}`;
                traversePath(parent);
            }
        }
        traversePath(category);

        return path;
    }

    function setPrimaryCategory(category, button){
        console.log(categoriesData);
        resetPrimaryForAllCategories(categoriesData);
        category.primary = true;
        renderSelectedCategories();
    }

    function resetPrimaryForAllCategories(categories) {
        categories.forEach(category => {
            category.primary = false;
            if (category.subcategories && category.subcategories.length > 0) {
                // Recursively call the function for subcategories
                resetPrimaryForAllCategories(category.subcategories);
            }
        });
    }

    // Function to handle checkbox click
    function handleCheckboxClick(category) {
        category.checked = !category.checked;
        if (category.checked) {
            updateParents(category);
        } else {
            if(category.subcategories.length > 0){
                uncheckSubcategories(category);
            }
        }

        //open or close the subcategories of this category
        if (category.checked) {
            showSubcategories(category);
        } else {
            hideSubcategories(category);
        }

        const createProductCategoriesSearchValue = document.getElementById('createProductCategoriesSearch').value.trim();
        if (createProductCategoriesSearchValue === '') {
            renderCategories();
        } else {
            updateCategories();
        }

        renderSelectedCategories();
    }

    // Initialize rendering
    renderCategories();
    renderSelectedCategories();

    // Add event listener for search input
    const createProductCategoriesSearch = document.getElementById('createProductCategoriesSearch');
    createProductCategoriesSearch.addEventListener('input', () => {
        const searchText = createProductCategoriesSearch.value.trim();
        searchCategories(searchText);

        // Render categories if search input is empty
        if (!searchText) {
            renderCategories();
        }
    });
</script>