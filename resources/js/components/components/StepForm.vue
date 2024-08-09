<template>
    <div class="step-form">
        <div class="step-buttons">
            <span @click="previousStep" class="arrow previous">
                <img src="/images/chevron-left-dark.svg" alt="arrow-previous">
            </span>
            <span class="counter">Stap {{ this.currentStep + 1 }} van {{ components.length }}</span>
            <span @click="nextStep" class="arrow next">
                <img src="/images/chevron-right-dark.svg" alt="arrow-next">
            </span>
        </div>

        <div class="progress-bar">
            <div class="progress" :style="{ width: (currentStep + 1) / components.length * 100 + '%' }"></div>
        </div>

        <div class="steps">
            <component :is="currentComponent" :product="product" @updateProduct="updateProduct"></component>
        </div>
    </div>
</template>

<script>
// Import utilities
import { markRaw } from 'vue';

// Import components
import ProductInfo from './ProductInfo.vue';
import ProductCategories from './ProductCategories.vue';
import ProductImages from './ProductImages.vue';
import ProductSaleschannels from './ProductSaleschannels.vue';
import ProductStock from './ProductStock.vue';
import ProductProperties from './ProductProperties.vue';

// Import styles
import '../../../scss/components/StepForm.scss';

export default {
    components: {
        ProductInfo,
        ProductImages,
        ProductCategories,
        ProductSaleschannels,
        ProductStock,
        ProductProperties
    },
    data() {
        return {
            components: [
                markRaw(ProductInfo),
                markRaw(ProductImages),
                markRaw(ProductCategories),
                // markRaw(ProductSaleschannels),
                markRaw(ProductStock),
                // markRaw(ProductProperties)
            ],
            currentStep: 0,
            product: {
                parent_product_id: null,
                sku: '',
                ean: null,
                title: '',
                short_description: '',
                long_description: '',
                price: null,
                discount: null,
                backorders: false,
                stock_quantity: 0,
                status: false,
                archived: false,
                photos: [],
                categories: [],
                properties: [],
                salesChannels: [],
                childProducts: [],
            }
        }
    },
    computed: {
        currentComponent() {
            return this.components[this.currentStep];
        }
    },
    methods: {
        nextStep() {
            if (this.currentStep < this.components.length - 1) {
                this.currentStep++;
            }
        },
        previousStep() {
            if (this.currentStep > 0) {
                this.currentStep--;
            }
        },
        updateProduct(updatedData) {
            this.product = { ...this.product, ...updatedData };
            console.log(this.product);
            
        }
    },
    mounted() {
        this.product.backorders = this.product.backorders === 1 ? true : false;

    }
};
</script>