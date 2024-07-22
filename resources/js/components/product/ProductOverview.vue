<template>
    <div class="product-overview">
        <ProductList 
            :filteredProducts="filteredProducts"
            @update:selectedProducts="updateSelectedProducts"
        />
        <CategoryFilters 
            :categories="categories" 
            :selectedCategories="selectedCategories" 
            @update:selectedCategories="updateSelectedCategories"
        />
    </div>
</template>

<script>
import { defineComponent, inject } from 'vue';
import CategoryFilters from './templates/CategoryFilters.vue';
import ProductList from './templates/ProductList.vue';
import '../../../scss/product/ProductOverview.scss';

export default defineComponent({
    components: {
        CategoryFilters,
        ProductList,
    },
    props: {
        initialProducts: {
            type: Array,
            required: true,
        },
        initialCategories: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            products: this.initialProducts,
            categories: this.initialCategories,
            selectedCategories: [],
            selectedProducts: [],
            filteredProducts: this.initialProducts,
        };
    },
    methods: {
        updateSelectedCategories(newSelectedCategories) {
            this.selectedCategories = newSelectedCategories;
            this.filterProducts();
        },
        updateSelectedProducts(newSelectedProducts) {
            this.selectedProducts = [...newSelectedProducts];
            console.log(this.selectedProducts);
        },
        filterProducts() {
            if (this.selectedCategories.length === 0) {
                this.filteredProducts = this.products;
            } else {
                this.filteredProducts = this.products.filter(product => 
                    product.categories.some(category => 
                        this.selectedCategories.includes(category.id)
                    )
                );
            }
        },
    },
    setup() {
        const route = inject('route'); // Injecting route helper
        return {
            route,
        };
    },
});
</script>
