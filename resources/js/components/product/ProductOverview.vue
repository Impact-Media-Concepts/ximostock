<template>
    <div class="product-overview">
        <ProductList 
            :filteredProducts="filteredProducts.data" 
            :saleschannels="initialSaleschannels"
            :pagination="filteredProducts" 
            @update:selectedProducts="updateSelectedProducts"
            @update:pagination="changePagination" />
        <CategoryFilters 
            :categories="categories" 
            :selectedCategories="selectedCategories"
            @update:selectedCategories="updateSelectedCategories" />
    </div>
</template>

<script>
import { defineComponent, inject } from 'vue';
import CategoryFilters from './templates/CategoryFilters.vue';
import ProductList from './templates/ProductList.vue';
import axios from 'axios';
import '../../../scss/product/ProductOverview.scss';

export default defineComponent({
    components: {
        CategoryFilters,
        ProductList,
    },
    props: {
        initialProducts: {
            type: [Array, Object], // Now it's an object because it contains pagination info
            required: true,
        },
        initialCategories: {
            type: [Array, Object],
            required: true,
        },
        initialSaleschannels: {
            type: [Array, Object],
            required: true,
        },
    },
    data() {
        return {
            products: this.initialProducts.data, // Extracting product list from paginated data
            categories: this.initialCategories,
            selectedCategories: [],
            selectedProducts: [],
            filteredProducts: this.initialProducts, // Full paginated data object
        };
    },
    methods: {
        updateSelectedCategories(newSelectedCategories) {
            this.selectedCategories = newSelectedCategories;
            this.filterProducts();
        },
        updateSelectedProducts(newSelectedProducts) {
            this.selectedProducts = [...newSelectedProducts];
        },
        changePagination(link) {
            // Check if the link is not active (to prevent reloading the same page)
            if (!link.active && link.url) {
                axios.get(link.url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => {
                    this.filteredProducts = response.data.products;
                })
                .catch(error => {
                    console.error('Error loading more products:', error);
                });
            }
        },
        filterProducts() {
            if (this.selectedCategories.length === 0) {
                this.filteredProducts.data = this.products;
            } else {
                this.filteredProducts.data = this.products.filter(product =>
                    product.categories.some(category =>
                        this.selectedCategories.includes(category.id)
                    )
                );
            }
        },
    },
    mounted() {
        this.filteredProducts.links = this.filteredProducts.links.slice(1, -1);
    },
    watch: {
        filteredProducts: {
            handler(newFilteredProducts) {
                newFilteredProducts.links = newFilteredProducts.links.slice(1, -1);
            },
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
