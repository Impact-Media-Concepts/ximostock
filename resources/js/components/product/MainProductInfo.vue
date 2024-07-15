<script setup lang="jsx">
import { defineProps } from 'vue';
import '../../../scss/product/MainProductInfo.scss';

// Define and accept the prop 'items' which can be an array or an object
const props = defineProps({
  product: {
    type: [Array, Object],
    default: () => []
  }
});

// Render function using JSX to create the component's template
const render = () => {

  const productArray = Array.isArray(props.product) ? props.product : Object.entries(props.product);

  console.log(productArray);
  
  return (
    <div class="main-product-info">
      <div className="thumbnail-container">
        <img src="" alt="" className="thumbnail" />
      </div>
      <div className="info-container">

      </div>
    </div>
  );
};
</script>

<template>
  <!-- Use the render function as the template for the component -->
  <component :is="render" />
</template>
