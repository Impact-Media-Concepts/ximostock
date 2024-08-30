<template>
    <div class="category-filter-container">
        <h2>Filter</h2>
        <ul>
            <li v-for="category in categories" :key="category.id">
                <input 
                    type="checkbox" 
                    :id="category.id" 
                    :value="category.id"
                    :checked="isSelected(category.id)"
                    @change="toggleCategory(category.id)"
                >
                <label :for="category.id">{{ category.name }}</label>
            </li>
        </ul>
    </div>
</template>

<script>
import { defineComponent } from 'vue';
import '../../../../scss/product/templates/CategoryFilters.scss';

export default defineComponent({
    props: {
        categories: {
            type: [Array, Object],
            required: true,
        },
        selectedCategories: {
            type: [Array, Object],
            required: true,
        },
    },
    methods: {
        isSelected(categoryId) {
            return this.selectedCategories.includes(categoryId);
        },
        toggleCategory(categoryId) {
            let updatedCategories = [...this.selectedCategories];
            if (updatedCategories.includes(categoryId)) {
                updatedCategories = updatedCategories.filter(id => id !== categoryId);
            } else {
                updatedCategories.push(categoryId);
            }
            this.$emit('update:selectedCategories', updatedCategories);
        },
    },
});
</script>
