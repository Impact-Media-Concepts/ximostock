<script setup lang="jsx">
import { ref } from 'vue'
import { defineProps } from 'vue'
import '../../scss/IncrementCounterMinus.scss'

// Define and accept the prop 'content' which is an optional array with a default value of an empty array
const props = defineProps({
  content: {
    type: Array,
    default: () => []
  }
})

// Create a reactive reference 'counter' initialized to 0
const counter = ref(0)

// Function to decrement the counter value
const incrementCounterMinus = () => {
  counter.value--
}

// Render function using JSX to create the component's template
const render = () => {
  return (
    <div>
      {/* Button to decrement the counter value */}
      <button
        type="button"
        onClick={incrementCounterMinus}
        className="increment-counter-minus"
      >
        Counter is: {counter.value} {/* Display the current counter value */}
      </button>
      <ul>
        {/* Iterate over the 'content' array prop and create a list item for each entry */}
        {props.content.map((value, key) => ( 
          <li key={key}>{key}: {value}</li>
        ))}
      </ul>
    </div>
  );
};

</script>

<template>
  <render />
</template>