<template>
    <div class="category-item">
        <input type="checkbox" :value="categoryCopy.id" @change="select($event.target)">
        <img v-if="hasChildren" @click="expand()" src="/images/chevron-down-dark.svg" alt="" class="chevron">
        <input type="text" v-model="categoryCopy.name" @input="updateCategory" :class="{ parent: hasChildren }">

        <div v-if="hasChildren" class="category-item-container">
            <category-item 
                v-for="child in categoryCopy.child_categories_recursive" 
                :key="child.key" 
                :ref="child.id" 
                :category="child"
                @update:checked="selectChild($event)" 
                @update:category="updateChild($event, child.id)">
            </category-item>
        </div>
        <div class="new-category">
            <input type="text" placeholder="Voeg hier nieuwe categorie toe" v-model="newCategory"
                v-on:keyup="addNewCategory">
        </div>
    </div>
</template>


<script>
import axios from 'axios';
import { reactive, watch, inject } from 'vue';

export default {
    name: 'CategoryItem',
    props: {
        category: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            hasChildren: this.category.child_categories_recursive && this.category.child_categories_recursive.length > 0,
            newCategory: "",
        };
    },
    methods: {
        addNewCategory(e) {
            if (e.keyCode === 13) {
                const categoryData = {
                    category: this.category,
                    addCategory: this.newCategory
                };

                axios.post(this.route('categories.create'), categoryData)
                    .then(response => {
                        console.log(response.data.category);
                        console.log(this.categoryCopy);
                        this.categoryCopy.child_categories_recursive = response.data.category.child_categories_recursive;
                        this.newCategory = "";
                        this.hasChildren = this.categoryCopy.child_categories_recursive.length > 0;
                        this.$forceUpdate();
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        },
        expand() {
            // Toggle the expanded class on the container of children
            const parentDiv = this.$el;
            const childDivs = parentDiv.querySelector('.category-item-container');
            if (childDivs) {
                childDivs.classList.toggle('expanded');
            }
        },
        select(checkboxClicked) {
            const parentDiv = this.$el;
            const childCheckboxes = parentDiv.querySelectorAll('.category-item-container input[type="checkbox"]');
            const allCheckboxes = [checkboxClicked, ...Array.from(childCheckboxes)];

            allCheckboxes.forEach(checkbox => {
                checkbox.checked = checkboxClicked.checked;
                this.$emit('update:checked', { id: checkbox.value, checked: checkbox.checked });
            });
        },
        selectChild(event) {
            this.$emit('update:checked', event);
        }
    },
    setup(props, { emit }) {
        const categoryCopy = reactive({ ...props.category });

        const route = inject('route'); // Injecting route helper

        watch(
            () => props.category,
            (newCategory) => {
                Object.assign(categoryCopy, newCategory);
            },
            { immediate: true }
        );

        const updateCategory = () => {
            emit('update:category', categoryCopy);
        };

        const updateChild = (updatedChild, childId) => {
            const index = categoryCopy.children.findIndex(child => child.id === childId);
            if (index !== -1) {
                categoryCopy.children[index] = updatedChild;
                updateCategory();
            }
        };

        return {
            categoryCopy,
            updateCategory,
            updateChild,
            route,
        };
    },
    mounted() {
        this.hasChildren = this.category.child_categories_recursive && this.category.child_categories_recursive.length > 0;
    },
};
</script>
