<script setup lang="jsx">
import { ref } from 'vue';
import { defineProps } from 'vue';
import '../../scss/IncrementCounter.scss';

// Define and accept the prop 'content' which is an optional array with a default value of an empty array
const props = defineProps({
  content: {
    type: [Array, Object],
    default: () => []
  }
});

// Create a reactive reference 'counter' initialized to 0
const counter = ref(0);

// Function to increment the counter value
const incrementCounterAdd = () => {
  counter.value++;
};

// Render function using JSX to create the component's template
const render = () => {
  // Check if props.content is an array and map over it
  const contentArray = Array.isArray(props.content) ? props.content : Object.entries(props.content);
  
  return (
    <div>
      {/* Button to increment the counter value */}
      <button
        type="button"
        onClick={incrementCounterAdd}
        className="increment-counter"
      >
        Counter is: {counter.value} {/* Display the current counter value */}
      </button>
      <ul>
        {/* Iterate over the 'content' array prop and create a list item for each entry */}
        {contentArray.map(([key, value]) => (
          <li key={key}>{key}: {value}</li>
        ))}
      </ul>
    </div>
  );
};

</script>

<template>
  <!-- Use the render function as the template for the component -->
  <component :is="render" />
</template>
