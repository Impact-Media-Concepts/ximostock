@props(['categories', 'checkedCategories' => null])

<div class="flex h-[28.37rem] w-[14.1rem]">
    <div class="w-[14.06rem] h-[5.18rem]">
        <div class="text-[16px] font-[600] relative right-[0.12rem] bottom-[0.18rem]">
            Categorieën
        </div>

        <div class="relative right-[0.08rem] underline w-[14rem] h-[0.15rem] bg-[#f8f8f8] mb-2 mt-[0.17rem]">
        </div>

        <div id="categoriesContainer">
            <div class="ml-[-0.2rem] mt-[0.85rem]">
                <input class="sticky rounded-md pl-[1.085rem] pt-[0.05rem] pr-[1rem] text-[#717171] category-search" style="font-size: 14px; border:1px solid #D3D3D3; width:14.06rem; height:2.5rem" type="text" id="searchInput" placeholder="Zoeken">
            </div>

            <ul style="font-family: 'Inter', sans-serif;" id="categoriesList"></ul>
        </div>
    </div>
</div>

<script>
    // // Find parent function
    let categoriesData = [<x-product.categories.product-categories-data :categories="$categories" :checkedCategories="$checkedCategories"/>];
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
        if (ul) {
            ul.classList.add('hidden');
            category.subcategories.forEach(subcategory => {
                subcategory.checked = false; // Uncheck the subcategory itself
                removeRotateArrowClass(category);
                const input = document.getElementById(`checkbox_category_${subcategory.id}`);
                input.checked = false;
                if (subcategory.subcategories.length > 0) {
                    uncheckSubcategories(subcategory); // Recursively uncheck subcategories
                }
            });
        }
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

    // Function to handle checkbox click
    function handleCheckboxClick(category) {

        category.checked = !category.checked;
        if (category.checked) {
            updateParents(category);
        } else {
            uncheckSubcategories(category);
            console.log("unchecked boiii");
        }
        //open or close the subcategories of this category
        if (category.checked) {
            
            showSubcategories(category);
        } else {
            hideSubcategories(category);
        }
        const searchInputValue = document.getElementById('searchInput').value.trim();
        if (searchInputValue !== '') {
            updateCategories();
        }

    }

    function removeRotateArrowClass(category) {
        const checkboxes = document.querySelectorAll(`input[type='checkbox'][value='${category.id}']`);
        checkboxes.forEach(checkbox => {
            const li = checkbox.closest('li');
            const arrowDown = li.querySelector('img');
            if (arrowDown) {
                arrowDown.classList.remove('rotate-arrow');
                
            }
        });
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
        ul.classList.add('mt-[0.8rem]');
        ul.style.maxHeight = '330px';
        ul.style.overflowY = 'scroll';

        categoriesData.forEach(category => {
            const li = document.createElement('li');

            li.id = `category_${category.id}`;
            li.classList.add('ml-[1.1rem]', 'font-[600]', 'text-[14px]', 'py-[0.35rem]');

            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.value = category.id;
            checkbox.checked = category.checked;
            checkbox.id = `checkbox_category_${category.id}`;
            checkbox.name = 'categories[]';
            checkbox.classList.add('cursor-pointer');
            checkbox.addEventListener('click', () => {
                arrowDown.classList.toggle('rotate-arrow');
                handleCheckboxClick(category);
            });

            const categoryNameSpan = document.createElement('span');
            categoryNameSpan.textContent = category.name;
            categoryNameSpan.classList.add('ml-[0.59rem]', 'relative', 'bottom-[0.125rem]');
            categoryNameSpan.style.display = 'inline-flex';
            categoryNameSpan.style.width = '85%';
            categoryNameSpan.style.zIndex = 99;
            categoryNameSpan.classList.add('no-select');

            categoryNameSpan.addEventListener('click', () => {

                arrowDown.classList.toggle('rotate-arrow');
                checkbox.checked = !checkbox.checked;
                handleCheckboxClick(category);
            });

            const arrowDownDiv = document.createElement('span');
            const arrowDown = document.createElement('img');

            arrowDownDiv.style.width = '85%';
            arrowDownDiv.classList.add('flex', 'items-center', 'justify-end');

            arrowDownDiv.appendChild(arrowDown);
            arrowDown.src = '../../images/arrow-down-icon.png';
            arrowDown.alt = 'Arrow Down';
            arrowDown.classList.add('w-[0.8rem]', 'h-[0.5rem]', 'flex', 'mt-[0.18rem]', 'mr-[0.25rem]', 'cursor-pointer');

            categoryNameSpan.appendChild(arrowDownDiv);
            li.appendChild(checkbox);
            li.appendChild(categoryNameSpan);

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

        subcategories.forEach((subcategory, index) => {
            const li = document.createElement('li');
            li.id = `category_${subcategory.id}`;
            li.classList.add('ml-5', 'font-[400]', 'text-[14px]', 'relative', 'py-[0.362rem]', 'li-class');

            if (index === 0) {
                li.classList.add('mt-2');
            }

            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.value = subcategory.id;
            checkbox.checked = subcategory.checked;
            checkbox.id = `checkbox_category_${subcategory.id}`;
            checkbox.name = 'categories[]';
            checkbox.classList.add('cursor-pointer');

            checkbox.addEventListener('click', () => { 
                console.log("sub Yooo?");
                const arrowDown = li.querySelector('img');
                if (arrowDown) {
                    console.log("sub if Yooo?");
                    arrowDown.classList.toggle('rotate-arrow');
                }
                handleCheckboxClick(subcategory);
            });

            const categoryNameSpan = document.createElement('span');
            categoryNameSpan.textContent = subcategory.name;
            categoryNameSpan.classList.add('ml-2', 'relative', 'bottom-[0.15rem]', 'inline-flex');
            categoryNameSpan.style.display = 'inline-flex';
            categoryNameSpan.style.width = '75%';
            categoryNameSpan.classList.add('no-select');

            categoryNameSpan.addEventListener('click', () => { 
                const arrowDown = li.querySelector('img');
                if (arrowDown) {
                    arrowDown.classList.toggle('rotate-arrow');
                }
                checkbox.checked = !checkbox.checked;
                handleCheckboxClick(subcategory);
            });

            li.appendChild(checkbox);
            li.appendChild(categoryNameSpan);
            ul.appendChild(li);

            if (subcategory.subcategories.length > 0) {
                categoryNameSpan.classList.add('font-[600]');
                const arrowDownDiv = document.createElement('span');
                const arrowDown = document.createElement('img');

                arrowDownDiv.style.width = '85%';
                arrowDownDiv.classList.add('flex', 'items-center', 'justify-end');

                arrowDownDiv.appendChild(arrowDown);
                arrowDown.src = '../../images/arrow-down-icon.png';
                arrowDown.alt = 'Arrow Down';
                arrowDown.classList.add('w-[0.8rem]', 'h-[0.5rem]', 'flex', 'mt-[0.18rem]', 'mr-[0.25rem]', 'cursor-pointer');

                categoryNameSpan.appendChild(arrowDownDiv);
                renderSubcategories(subcategory.subcategories, li, subcategory.checked);
            } else {
                li.classList.add('font-[400]');
            }
        });
        parentElement.appendChild(ul);
    }

    // Initialize rendering
    renderCategories();

    function debounce(func, delay) {
        let timeoutId;
        return function() {
            const context = this;
            const args = arguments;
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                func.apply(context, args);
            }, delay);
        };
    }

    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', debounce(() => {
        const searchText = searchInput.value.trim();

        if (!searchText) {
            renderCategories();
        } else {
            searchCategories(searchText);
        }
    }, 300));
</script>