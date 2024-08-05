<template>
    <div class="category-overview">
        <div class="content">
            <section>
                <span>informatie</span>
                <div class="form-group">
                    Hello world
                </div>
                <div v-if="categories.length">
                    <category-item v-for="category in categories" :key="category.id" :category.sync="category"></category-item>
                </div>
                <p v-else>No categories found</p>
            </section>
        </div>
        <general-notification v-if="errors" :errors="errors"></general-notification>
    </div>
</template>

<script>
import { defineComponent, inject } from 'vue';
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
        };
    },
    methods: {
        // Example method
        //   duplicate() {
        //     axios.post(this.route('products.duplicate', this.productData.id))
        //       .then(response => {
        //         window.location.href = this.route('products.index');
        //       });
        //   },
    },
    setup() {
        const route = inject('route'); // Injecting route helper
        return {
            route,
        };
    },
    mounted() {
        console.log('Component mounted.');
        console.log(this.categories);
    },
});
</script>
