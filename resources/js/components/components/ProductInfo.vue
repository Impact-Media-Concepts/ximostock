<template>
    <div class="product-info">
        <div class="content informatie">
            <section>
                <span>informatie</span>
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" v-model="localProduct.title" id="title">
                    <label for="price">Price:</label>
                    <input type="number" v-model="localProduct.price" id="price" step="0.01">
                    <label for="discount">Discount:</label>
                    <input type="number" v-model="localProduct.discount" id="discount" step="0.01">
                </div>
            </section>
            <section>
                <span>Lange beschrijving</span>
                <div class="form-group">
                    <textarea v-model="localProduct.long_description" id="title"></textarea>
                </div>
            </section>
            <section>
                <span>Korte beschrijving</span>
                <div class="form-group">
                    <textarea v-model="localProduct.short_description" id="title"></textarea>
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
                localProduct: {...this.product}
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

