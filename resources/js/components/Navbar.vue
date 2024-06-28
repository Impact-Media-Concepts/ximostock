<script setup lang="jsx">
import { defineProps } from 'vue';
import '../../scss/Navbar.scss';

// Define and accept the prop 'items' which can be an array or an object
const props = defineProps({
  items: {
    type: [Array, Object],
    default: () => []
  }
});

// Render function using JSX to create the component's template
const render = () => {
  const NavbarContent = Array.isArray(props.items) ? props.items : Object.entries(props.items);

  return (
    <div id="navbar" class="container">
      <div class="wrapper">
        <div class="logo-container">
          <a href={props.items.logo.href} class="logo-link">
            <img src={props.items.logo.src} alt={props.items.logo.alt} class="logo"/>
          </a>
        </div>
        <div class="options">
          <div class="add">
            <a href={props.items.buttons.add.href} class="button-primary">
              <img src={props.items.buttons.add.image} alt="grid-icon" class="icon"/>
                {props.items.buttons.add.text}
            </a>
          </div>
          <div class="workspaces">
            <a href={props.items.buttons.workspace.href} class="button">
              <img src={props.items.buttons.workspace.image} alt="grid-icon" class="icon"/>
                {props.items.buttons.workspace.text}
            </a>
          </div>
          <div class="user-options">
            <img src={props.items.select.image} alt="Profile Picture" class="profile-picture"/>
              <select name="user" id="user-select" style={{backgroundImage: `url(${props.items.select.image})`}}>
                {Object.values(props.items.select.options).map((option, index) => (
                  <option key={index} value={`user${index + 1}`}>{option}</option>
                ))}
              </select>
          </div>
        </div>
      </div>
    </div>
  );
};
</script>

<template>
  <!-- Use the render function as the template for the component -->
  <component :is="render" />
</template>
