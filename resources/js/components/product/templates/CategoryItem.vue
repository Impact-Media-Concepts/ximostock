<template>
    <div class="category-item">
        <div class="input">
            <input type="checkbox" :checked=category.selected :value="category.id" @change="toggleCategory(category, $event)">
            {{ category.name }}
        </div>
        <category-item v-for="child in category.child_categories_recursive" @toggleCategory="toggleCategory" :category="child"></category-item>
    </div>
</template>

<script>
import { defineComponent, inject } from 'vue';
import '../../../../scss/product/templates/CategoryItem.scss';

export default defineComponent({
    props: {
        category: {
            type: Object,
            required: true,
        },
        to: {
            type: String,
            default: 'available',
        }
    },
    data() {
        return {
            example: [],
        };
    },
    watch: {
    },
    methods: {
        toggleCategory(category, event) {
            this.$emit('toggleCategory', category, event);
        },
    },
    mounted() {
        
    },
    setup() {
        const route = inject('route'); // Injecting route helper
        return {
            route,
        };
    }
});
</script>
