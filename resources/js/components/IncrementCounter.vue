<script setup lang="jsx">
import { ref } from 'vue'
import { defineProps } from 'vue'
import '../../scss/IncrementCounter.scss'

// Accept the prop
const props = defineProps({
  content: Array
})
console.log("lorem");
console.log(props);

const counter = ref(0)
const incrementCounterAdd = () => {
  counter.value++
}

const render = () => (
  <div>
    <button
      type="button"
      onClick={incrementCounterAdd}
      class="increment-counter"
    >
      Counter is: {counter.value}
    </button>
    <ul>
      {props.content.map((item, index) => (
        <li key={index}>{item}</li>
      ))}
    </ul>
  </div>
)
</script>

<template>
  <render />
</template>
