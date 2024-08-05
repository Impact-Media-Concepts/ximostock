<template>
    <div class="category-item">
        <input type="text" v-model="categoryCopy.name" @input="updateCategory">
        <div v-if="categoryCopy.children && categoryCopy.children.length">
            <category-item v-for="child in categoryCopy.children" :key="child.id" :category="child" @update:category="updateChild($event, child.id)"></category-item>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CategoryItem',
    props: {
        category: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            categoryCopy: { ...this.category }
        }
    },
    methods: {
        updateCategory() {
            this.$emit('update:category', this.categoryCopy);
        },
        updateChild(updatedChild, childId) {
            const index = this.categoryCopy.children.findIndex(child => child.id === childId);
            this.$set(this.categoryCopy.children, index, updatedChild);
            this.updateCategory();
        }
    }
};
</script>

<style scoped>
.category-item {
    margin-left: 20px;
}
</style>
