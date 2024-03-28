@props(['categories', 'checkedCategories' => null])

<div id="categoriesContainer">
    <h2>Categories</h2>
    <input type="text" id="searchInput" placeholder="Search...">
    <ul id="categoriesList"></ul>
</div>
<hr>
<div>
    <h2>selected categories</h2>
    <ul id="selectedCategoriesList">

    </ul>
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
        const searchText = document.getElementById('searchInput').value.trim();

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
        const searchText = document.getElementById('searchInput').value.trim();

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
        const selectedCategoryList = document.getElementById('selectedCategoriesList');
        selectedCategoryList.innerHTML = '';

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
        const selectedCategoryList = document.getElementById('selectedCategoriesList');
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
        const li = document.createElement('li');
        li.id = `selectedCategory${category.id}`;

        const title = document.createElement('h4');
        title.textContent = `category title: ${category.name}`;

        const path = document.createElement('p');
        path.textContent = getCategoryPath(category);

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = `categories[${category.id}]`
        input.value = category.primary ? 1 : 0;

        const setPrimaryButton = document.createElement('button');
        setPrimaryButton.textContent = 'Set as Primary';
        setPrimaryButton.addEventListener('click', () => setPrimaryCategory(category));
        setPrimaryButton.classList.add('g-transparent', 'hover:bg-blue-500', 'text-blue-700', 'font-semibold', 'hover:text-white', 'py-2', 'px-4', 'border', 'border-blue-500', 'hover:border-transparent', 'rounded');

        li.appendChild(title);
        li.appendChild(path);
        li.appendChild(input);
        li.appendChild(setPrimaryButton);
        selectedCategoryList.appendChild(li);
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

    function setPrimaryCategory(category){
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

        const searchInputValue = document.getElementById('searchInput').value.trim();
        if (searchInputValue === '') {
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
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', () => {
        const searchText = searchInput.value.trim();
        searchCategories(searchText);

        // Render categories if search input is empty
        if (!searchText) {
            renderCategories();
        }
    });
</script>