<script setup>
import { ref } from 'vue'
import { defineProps } from 'vue'
import '../../scss/IncrementCounter.scss'

// Accept the prop
const props = defineProps({
  content: {
    type: Array,
    required: true
  }
})

const counter = ref(0)
const incrementCounterAdd = () => {
  counter.value++
}
</script>

<template>
  <div>
    <button
      type="button"
      @click="incrementCounterAdd"
      class="increment-counter"
    >
      Counter is: {{ counter }}
    </button>
    <ul>
      <li v-for="(value, key) in props.content" :key="key">{{ key }}: {{ value }}</li>
    </ul>
  </div>
</template>

<style scoped>
.increment-counter {
  /* Your styles here */
}
</style>
