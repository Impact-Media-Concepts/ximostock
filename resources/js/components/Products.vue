<script setup lang="jsx">
import { defineProps } from 'vue';
import '../../scss/Products.scss';

// Define and accept the prop 'items' which can be an array or an object
const props = defineProps({
  items: {
    type: [Array, Object],
    default: () => []
  }
});

// Render function using JSX to create the component's template
const render = () => {
  return (
    <div class="products">
        hello 
    </div>
    // <div class="filter">

    // </div>
  );
};
</script>

<template>
  <!-- Use the render function as the template for the component -->
  <component :is="render" />
</template>
