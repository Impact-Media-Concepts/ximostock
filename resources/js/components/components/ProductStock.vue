<template>
    <div class="product-stock">
        <div class="content voorraad">
            <section>
                <span>Huidige status</span>
                <div class="form-group">
                    <div>
                        <label for="stock_quantity">Stock Quantity:</label>
                        <input type="number" v-model="localProduct.stock_quantity" id="stock_quantity">
                    </div>
                    <div>
                        <label for="backorders">Backorders:</label>
                        <input type="checkbox" v-model="localProduct.backorders" id="backorders"
                            :checked="localProduct.backorders">
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        product: {
            type: Object,
        }
    },
    data() {
        return {
            localProduct: { ...this.product }
        }
    },
    watch: {
        localProduct: {
            handler(newData) {
                this.$emit('updateProduct', newData);
            },
            deep: true
        }
    },
};
</script>