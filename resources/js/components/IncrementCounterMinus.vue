<script setup lang="jsx">
import { ref } from 'vue'
import '../../scss/IncrementCounterMinus.scss'

const counter = ref(0)
const incrementCounterMinus = () => {
  counter.value--
}

const render = () => (
  <button
    type="button"
    onClick={incrementCounterMinus}
    class="increment-counter-minus"
  >
    Counter is: {counter.value}
  </button>
)

</script>

<template>
  <render />
</template>
