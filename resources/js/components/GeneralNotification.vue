<script setup lang="jsx">
import { defineProps } from 'vue';
import '../../scss/GeneralNotification.scss';

console.log("Log");

// Define and accept the prop 'content' which is an optional array with a default value of an empty array
const props = defineProps({
  errors: {
    type: [Array, Object, String],
    default: () => []
  }
});

// Render function using JSX to create the component's template
const render = () => {
  let errorElements;

  if (typeof props.errors === 'string') {
    errorElements = <div>{props.errors}</div>;
  } else if (Array.isArray(props.errors)) {
    errorElements = props.errors.map((error, index) => (
      <div key={index}>{error}</div>
    ));
  }

  return (
    <div class="general-notification">
      {errorElements}
    </div>
  );
};
</script>

<template>
  <!-- Use the render function directly -->
  <component :is="render" />
</template>
