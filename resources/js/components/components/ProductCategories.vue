<template>
    <div class="product-categories">
        <div class="content categorieen">

            <section>
                <span>Beschikbare categorieën</span>
                <div class="form-group">
                    <input @input="FilterAvailableCategories()" v-model="filterInputText" type="text"
                        placeholder="Typ hier waarop u de lijst wil filteren">
                    <ul>
                        <li v-for="category in filteredCategories" :key="category.id">
                            <input type="checkbox" :value="category.id" @change="moveCategory(category, 'available')">
                            {{ category.name }}
                        </li>
                    </ul>
                </div>
            </section>

            <section>
                <span>Geselecteerde categorieën</span>
                <div class="form-group">
                    <ul>
                        <li v-for="category in localProduct.categories" :key="category.id">
                            <input type="checkbox" :value="category.id" @change="moveCategory(category, 'selected')"
                                checked>
                            {{ category.name }}
                        </li>
                    </ul>
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
            localProduct: { ...this.product },
            availableCategories: Object.values(this.product.categories),
            filteredCategories: Object.values(this.product.categories),
            filterInputText: ''
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
    methods: {
        FilterAvailableCategories() {
            console.log('Filtering categories', this.filterInputText);
            // Filter results into the new array
            this.filteredCategories = this.availableCategories.filter(category =>
                category.name.toLowerCase().includes(this.filterInputText.toLowerCase())
            );
        },
        moveCategory(category, from) {
            if (from === 'available') {
                // Remove category from available
                this.availableCategories = this.availableCategories.filter(c => c.id !== category.id);
                this.filteredCategories = this.filteredCategories.filter(c => c.id !== category.id);
                // Add category to selected
                this.productData.categories.push(category);
                console.log('Moving category to selected', category);
            } else {
                this.productData.categories = this.productData.categories.filter(c => c.id !== category.id);
                this.filteredCategories.push(category);
                this.availableCategories.push(category);
                console.log('Moving category to available', category);
            }
        },
    }
};
</script>
