<template>
    <div class="product-container">
        <h1>Products</h1>
        <a :href="route('products.create')" class="button">Add product</a>

        <div v-if="filteredProducts.length > 0" class="product-table">
            <div class="product" v-for="product in filteredProducts" :key="product.id">
                <div class="checkbox">
                    <input 
                        type="checkbox"
                        :id="product.id"
                        :value="product.id"
                        :checked="isSelected(product.id)"
                        @change="toggleBulkSelection(product.id)"
                    >
                    {{ product.id }}
                </div>
                <div class="image">
                    <img v-for="photo in product.photos" :key="photo.id" :src="photo.url" alt="Product Photo" style="height: 40px;">
                </div>
                <div class="title">{{ product.title }}</div>
                <div class="price">
                    <span v-if="product.discount != null">
                        <span class="crossed">{{ product.price }}</span>
                        <strong class="price">{{ product.discount }}</strong>
                    </span>
                    <span v-else>    
                        <strong class="price">{{ product.price }}</strong>
                    </span>
                </div>
                <div class="stock">
                    <span v-if="product.communicate_stock == 1">Op voorraad</span>
                    <span v-else>Uitverkocht</span>
                </div>
                <div class="sold">
                    {{ product.orderByStock }}
                    <span v-if="product.orderByStock == 1">Verkocht</span>
                    <span v-else>Te koop</span>
                </div>
                <div class="status">
                    {{ product.orderByOnline }}
                    <span v-if="product.orderByOnline == 1">Online</span>
                    <span v-else>Offline</span>
                </div>
                <div class="last-updated">
                    {{ formatDate(product.updated_at) }}
                </div>

            </div>
        </div>
        <p v-else> Geen producten gevonden</p>
    </div>
</template>

<script>
import { defineComponent, inject } from 'vue';
import { format } from 'date-fns';
import '../../../../scss/product/templates/ProductList.scss';

export default defineComponent({
    props: {
        filteredProducts: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            selectedProducts: [],
        };
    },
    methods: {
        isSelected(productId) {
            return this.selectedProducts.includes(productId);
        },
        toggleBulkSelection(productId) {
            let updatedSelections = [...this.selectedProducts];
            if (updatedSelections.includes(productId)) {
                updatedSelections = updatedSelections.filter(id => id !== productId);
            } else {
                updatedSelections.push(productId);
            }
            this.selectedProducts = updatedSelections;
            // Emit the updated selections to parent if needed
            this.$emit('update:selectedProducts', updatedSelections);
        },
        formatDate(date) {
            return format(new Date(date), 'yyyy-MM-dd HH:mm:ss');
        }
    },
    setup() {
        const route = inject('route'); // Injecting route helper
        return {
            route,
        };
    },
});
</script>
