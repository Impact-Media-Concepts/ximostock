<template>
  <div id="navbar" class="container">
    <div class="wrapper">
      <div class="logo-container" v-if="items.logo">
        <a :href="items.logo.href" class="logo-link">
          <span class="logo" v-html="items.logo.src"></span>
        </a>
        <div class="streep"></div>
      </div>

      <div class="options">
        <div class="add" v-if="items.add">
          <div @click="toggleDropdown('.add.select-options')" class="select-trigger button">
            <span class="icon" v-html="items.add.image"></span>
            {{ items.add.text }}
          </div>
        </div>

        <div class="workspaces" v-if="items.workspace">
          <a :href="items.workspace.href" class="button">
            <span class="icon" v-html="items.workspace.image"></span>
            {{ items.workspace.text }}
          </a>
        </div>

        <div class="user-options">
          <div class="icon-container" v-if="items.select">
            <span class="profile-picture" v-html="items.select.image"></span>
          </div>
          <div class="custom-selectbox">
            <div @click="toggleDropdown('.user.select-options')" id="select-trigger">
              <span id="username">{{ user.name }}</span>
              <span class="arrow" v-html="items.select.arrow" v-if="items.select"></span>
            </div>
            <div class="user select-options">
              <a v-for="(option, index) in items.select.options" :key="index" :href="option.href" :class="`option${index + 1}`">
                <span class="icon" v-html="option.icon"></span>
                <span>{{ option.title }}</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, onMounted, onUnmounted } from 'vue';
import '../../scss/Navbar.scss';


export default defineComponent({
  props: {
    items: {
      type: Object,
      default: () => ({})
    },
    user: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      activeDropdown: null,
    };
  },
  methods: {
    toggleDropdown(classname) {      
      const selectOptions = document.querySelector(classname);
      selectOptions.classList.toggle('active');
      if (selectOptions.classList.contains('active')) {
        this.activeDropdown = classname;
      } else {
        this.activeDropdown = null;
      }
    },
  },
});
</script>
