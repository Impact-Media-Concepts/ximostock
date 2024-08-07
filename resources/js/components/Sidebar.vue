<script setup lang="ts">
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
  const textElements = sidebar?.querySelectorAll('.text');
  textElements?.forEach((element) => {
    element.classList.toggle('collapsed');
  });

  const toggleButton = sidebar?.querySelector('.close-button');
  toggleButton?.classList.toggle('rotate');
};
</script>

<template>
  <div id="sidebar">
    <a v-for="link in props.items" :href="link.url" class="sidebar-item" :key="link.url">
      <span class="icon" v-html="link.image_url"></span>
      <span class="text">{{ link.title }}</span>
    </a>
    <div class="close" @click="toggleCollapse">
      <img src="/images/sidebar/close-button.svg" alt="close-button" class="close-button" />
    </div>
  </div>
</template>
