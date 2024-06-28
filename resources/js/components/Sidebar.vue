<script setup lang="jsx">
import { defineProps } from 'vue';
import '../../scss/Sidebar.scss';

const props = defineProps({
  items: {
    type: Array,
    required: true,
  },
});

const toggleCollapse = () => {
  const sidebar = document.getElementById('sidebar');
  const textElements = sidebar.querySelectorAll('.text');
  textElements.forEach((element) => {
    element.classList.toggle('collapsed');
  });

  const toggleButton = sidebar.querySelector('.close-button');
  toggleButton.classList.toggle('rotate');
};

const render = () => {
  return (
    <div id="sidebar">
      {props.items.map((link) => (
        <a href={link.url} class="sidebar-item" key={link.url}>
          <img src={link.image_url} alt="" class="icon" />
          <span class="text">{link.title}</span>
        </a>
      ))}
      <div class="close" onClick={toggleCollapse}>
        <img src="/images/sidebar/close-button.svg" alt="close-button" class="close-button" />
      </div>
    </div>
  );
};
</script>

<template>
  <component :is="render" />
</template>