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
                <div class="form-group bulk">
                    <div class="input-container">
                        <input 
                            type="checkbox" 
                            @input="toggleSelection" 
                            name="check-all" 
                            v-model="selectAllChecked" 
                        />
                        <label for="check-all">Selecteer alles</label>
                    </div>
                    <div class="actions">
                        <span @click="collapseAll" class="button close-all">Alles dichtklappen</span>
                        <span @click="expandAll" class="button open-all">Alles openklappen</span>
                        <span @click="deleteSelected" class="button delete">Geselecteerde verwijderen</span>
                        <span @click="saveAll" class="button save">Alles opslaan</span>
                    </div>
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
import axios from 'axios';
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
            selectAllChecked: false,
        };
    },
    methods: {
        updateChecked(checkedCategory) {
            if (checkedCategory.checked) {
                if (!this.checkedCategories.includes(checkedCategory.id)) {
                    this.checkedCategories.push(checkedCategory.id);
                }
            } else {
                this.checkedCategories = this.checkedCategories.filter(id => id !== checkedCategory.id);
            }
        },
        filterCategories() {
            if (this.FilterInputText.trim() === '') {
                this.visibleCategories = JSON.parse(JSON.stringify(this.categories));
            } else {
                this.visibleCategories = JSON.parse(JSON.stringify(this.categories));
                this.visibleCategories = this.filterRecursiveArray(this.visibleCategories, this.FilterInputText.toLowerCase());
            }
            this.renderKey++;
        },
        filterRecursiveArray(arr, filterText) {
            return arr
                .map(category => {
                    if (category.children) {
                        category.children = this.filterRecursiveArray(category.children, filterText);
                    }
                    const matches = category.name.toLowerCase().includes(filterText);
                    return matches || (category.children && category.children.length) ? category : null;
                })
                .filter(category => category !== null);
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
        toggleSelection() {
            this.selectAllChecked = !this.selectAllChecked;
            this.checkedCategories = this.selectAllChecked ? this.categories.map(category => category.id) : [];

            var checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.selectAllChecked;
            });
        },
        collapseAll() {
            var categoryTab = document.querySelectorAll('.category-item-container');
            categoryTab.forEach(tab => {
                tab.classList.remove('expanded');
            });
        },
        expandAll() {
            var categoryTab = document.querySelectorAll('.category-item-container');
            categoryTab.forEach(tab => {
                tab.classList.add('expanded');
            });
        },
        deleteSelected() {

            axios.delete(route('categories.deleteCategories'), {
                data: { ids: this.checkedCategories }
            })
            .then((response) => {
                console.log(response);
            })
        },
        saveAll() {
            console.log(this.categories);
            axios.patch(route('categories.updateCategories'), {
                categories: this.categories
            })
            .then((response) => {
                console.log(response);
            })
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

        const route = inject('route');

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
