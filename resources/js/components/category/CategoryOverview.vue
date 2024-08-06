<template>
    <div class="category-overview">
        <div class="content">
            <section>
                <span>Categorieën</span>
                <div class="form-group">
                    <input 
                        @input="filterCategories" 
                        v-model="FilterInputText" 
                        type="text" 
                        class="form-control" 
                        placeholder="Type hier om te zoeken naar categorie" 
                    />
                </div>
                <div class="form-group" :key="renderKey">
                    <category-item
                        v-for="category in visibleCategories"
                        :key="category.id"
                        :category="category"
                        @update:category="updateCategory"
                        @update:checked="updateChecked"
                    ></category-item>
                </div>
            </section>
        </div>
        <general-notification v-if="errors" :errors="errors"></general-notification>
    </div>
</template>

<script>
import { defineComponent, inject, reactive, watch } from 'vue';
import '../../../scss/category/CategoryOverview.scss';
import CategoryItem from './partials/CategoryItem.vue';

export default defineComponent({
    components: {
        CategoryItem,
    },
    props: {
        categories: {
            type: Array,
            default: () => [],
        },
    },
    data() {
        return {
            errors: 0,
            checkedCategories: [],
            FilterInputText: '',
            visibleCategories: [],
            renderKey: 0,
        };
    },
    methods: {
        updateChecked(checkedCategory) {
            if (checkedCategory.checked) {
                if (!this.checkedCategories.includes(checkedCategory.id)) {
                    this.checkedCategories.push(checkedCategory.id);
                }
            } else {
                if (this.checkedCategories.includes(checkedCategory.id)) {
                    this.checkedCategories = this.checkedCategories.filter(id => id !== checkedCategory.id);
                }
            }
            console.log(this.checkedCategories);
        },
        filterCategories() {
            if (this.FilterInputText.trim() === '') {
                // If the filter text is empty, restore the original categories
                this.visibleCategories = JSON.parse(JSON.stringify(this.categories));
            } else {
                // Otherwise, filter the categories based on the filter text
                this.visibleCategories = JSON.parse(JSON.stringify(this.categories));
                this.visibleCategories = JSON.parse(JSON.stringify(this.filterRecursiveArray(this.visibleCategories, this.FilterInputText.toLowerCase())));
            }
            
            this.renderKey++;
        },
        filterRecursiveArray(arr, filterText) {
            return arr
                .map(category => {
                    // If the category has children, recursively filter the children
                    if (category.children) {
                        category.children = this.filterRecursiveArray(category.children, filterText);
                    }
                    // Apply the filter condition to the current category
                    const matches = category.name.toLowerCase().includes(filterText);
                    // Return the category if it matches or if any of its children match
                    return matches || (category.children && category.children.length) ? category : null;
                })
                .filter(category => category !== null); // Remove null values
        },
        updateCategory(updatedCategory) {
            const updateRecursive = (categories, updatedCategory) => {
                for (let i = 0; i < categories.length; i++) {
                    if (categories[i].id === updatedCategory.id) {
                        categories[i] = updatedCategory;
                        return;
                    }
                    if (categories[i].children) {
                        updateRecursive(categories[i].children, updatedCategory);
                    }
                }
            };
            updateRecursive(this.categories, updatedCategory);
        },
    },
    setup(props) {
        const state = reactive({
            categories: props.categories,
            errors: 0,
        });

        watch(() => props.categories, (newCategories) => {
            state.categories = newCategories;
        });

        const route = inject('route'); // Injecting route helper

        return {
            ...state,
            route,
        };
    },
    mounted() {
        this.visibleCategories = JSON.parse(JSON.stringify(this.categories));
    },
});
</script>
