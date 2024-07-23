
<script setup>
import { defineProps, computed, inject } from 'vue';
import '../../../scss/product/SingleProduct.scss';

const route = inject('route');

// Define and accept the prop 'users' which can be an array or an object
const props = defineProps({
  product: {
    type: [Array, Object],
    default: () => []
  }
});
</script>

<template>
  <div class="product-view">
    <h1>product</h1>
    
    <div class="main-product-info">

    </div>
    <div class="product-content">
      <div class="product-tabs"></div>
      <div class="product-actions"></div>
    </div>
    <pre>
      {{ product }}
    </pre>
  </div>
</template>