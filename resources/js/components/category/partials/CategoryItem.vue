<template>
    <div class="category-item">
        <input type="checkbox" :value="categoryCopy.id" @change="select($event.target)">
        <img v-if="hasChildren" @click="expand()" src="/images/chevron-down-dark.svg" alt="" class="chevron">
        <input type="text" v-model="categoryCopy.name" @input="updateCategory" :class="{ parent: hasChildren }">
        <div v-if="hasChildren" class="category-item-container">
            <category-item 
                v-for="child in categoryCopy.children" 
                :key="child.id" 
                :category="child"
                @update:checked="selectChild($event)" 
                @update:category="updateChild($event, child.id)"
            ></category-item>
        </div>
    </div>
</template>

<script>
import { reactive, watch } from 'vue';

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
            hasChildren: false,
        };
    },
    methods: {
        expand() {
            const parentDiv = this.$el;
            const childDivs = parentDiv.querySelectorAll(':scope > .category-item-container');
            childDivs.forEach(div => {
                div.classList.toggle('expanded');
            });
        },
        select(checkboxClicked) {
            const parentDiv = this.$el;
            const childCheckboxes = parentDiv.querySelectorAll('.category-item-container input[type="checkbox"]');
            const allCheckboxes = [checkboxClicked, ...Array.from(childCheckboxes)];

            allCheckboxes.forEach(checkbox => {
                checkbox.checked = checkboxClicked.checked;
                this.$emit('update:checked', { id: checkbox.value, checked: checkbox.checked });
            });
        },
        selectChild(event) {
            this.$emit('update:checked', event);
        },
    },
    setup(props, { emit }) {
        const categoryCopy = reactive({ ...props.category });

        watch(
            () => props.category,
            (newCategory) => {
                Object.assign(categoryCopy, newCategory);
            },
            { immediate: true }
        );

        const updateCategory = () => {
            emit('update:category', categoryCopy);
        };

        const updateChild = (updatedChild, childId) => {
            const index = categoryCopy.children.findIndex(child => child.id === childId);
            if (index !== -1) {
                categoryCopy.children[index] = updatedChild;
                updateCategory();
            }
        };

        return {
            categoryCopy,
            updateCategory,
            updateChild,
        };
    },
    mounted() {
        this.hasChildren = this.category.children && this.category.children.length;
    },
};
</script>
