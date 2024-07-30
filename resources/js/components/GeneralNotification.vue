<script setup lang="jsx">
import { defineProps } from 'vue';
import '../../scss/GeneralNotification.scss';

const props = defineProps({
  errors: {
    type: [Array, Object, String],
    default: () => []
  }
});

const renderErrorElements = (errors) => {
  if (typeof errors === 'string') {
    return <div>{errors}</div>;
  } else if (Array.isArray(errors)) {
    return errors.map((error, index) => (
      <div key={index}>{error}</div>
    ));
  } else if (typeof errors === 'object') {
    return Object.keys(errors).map((key) => (
      <div key={key}>
        <strong>{key}:</strong>
        {Array.isArray(errors[key]) 
          ? errors[key].map((msg, i) => <div key={i}>{msg}</div>)
          : <div>{errors[key]}</div>}
      </div>
    ));
  }
  return null;
};

const render = () => {
  return (
    <div class="general-notification">
      {renderErrorElements(props.errors)}
    </div>
  );
};
</script>

<template>
  <!-- Use the render function directly -->
  <component :is="render" />
</template>
