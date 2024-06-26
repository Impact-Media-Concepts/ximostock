<script setup lang="jsx">
import { ref } from 'vue'
import '../../scss/IncrementCounter.scss'

const counter = ref(0)
const incrementCounterAdd = () => {
  counter.value++
}

const render = () => (
  <button
    type="button"
    onClick={incrementCounterAdd}
    class="increment-counter"
  >
    Counter is: {counter.value}
  </button>
)

</script>

<template>
  <render />
</template>
