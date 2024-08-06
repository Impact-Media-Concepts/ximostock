<script setup lang="jsx">
import { defineProps } from 'vue';
import '../../scss/Navbar.scss';

// Define and accept the prop 'items' which can be an array or an object
const props = defineProps({
  items: {
    type: [Array, Object],
    default: () => ({})
  },
  user: {
    type: Object,
    default: () => ({})
  }
});

const handleClick = (classname) => (event) => {
  event.stopPropagation();
  const selectOptions = document.querySelector(classname);
  selectOptions.classList.toggle('active');
  
  if (selectOptions.classList.contains('active')) {
    window.addEventListener('click', () => {
      selectOptions.classList.remove('active');
    });
  }
};

// Render function using JSX to create the component's template
const render = () => {
  const NavbarContent = Array.isArray(props.items) ? props.items : Object.entries(props.items);

  return (
    <div id="navbar" class="container">
      <div class="wrapper">

        <div class="logo-container">
          {props.items.logo && (
            <a href={props.items.logo.href} class="logo-link">
              <img src={props.items.logo.src} alt={props.items.logo.alt} class="logo"/>
            </a>
          )}
          <div class="streep"></div>
        </div>

        <div class="options">

          {props.items.add && (
            <div class="add">
              <div onClick={handleClick('.add.select-options')} class="select-trigger button">
                <span className="icon" v-html={props.items.add.image}></span>
                {props.items.add.text}
              </div>
            </div>
          )}

          {props.items.workspace && props.items.workspace && (
            <div class="workspaces">
              <a href={props.items.workspace.href} class="button">
                <span className="icon" v-html={props.items.workspace.image}></span>
                {props.items.workspace.text}
              </a>
            </div>
          )}

          <div class="user-options">
            <div class="icon-container">
              {props.items.select && (
                <span className="profile-picture" v-html={props.items.select.image}></span>
              )}
            </div>
            <div class="custom-selectbox">
              <div onClick={handleClick('.user.select-options')} id="select-trigger">
                <span id="username">{props.user.name}</span>
                {props.items.select && (
                  <span className="arrow" v-html={props.items.select.arrow}></span>
                )}
              </div>
              <div class="user select-options">
                {props.items.select && props.items.select.options && Object.values(props.items.select.options).map((option, index) => (
                  <a href={option.href} key={index} class={`option${index + 1}`} >
                    <span className="icon" v-html={option.icon}></span>
                    <span>{option.title}</span>
                  </a>
                ))}
              </div>
            </div>
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
