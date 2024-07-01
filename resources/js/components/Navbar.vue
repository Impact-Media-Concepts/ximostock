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

const handleClick = (classname) => (event) => {
  event.stopPropagation();
  const selectOptions = document.querySelector(classname);
  selectOptions.classList.toggle('active');
};

// Render function using JSX to create the component's template
const render = () => {
  // const NavbarContent = Array.isArray(props.items) ? props.items : Object.entries(props.items);

  return (
    <div id="navbar" class="container">
      <div class="wrapper">

        <div class="logo-container">
          <a href={props.items.logo.href} class="logo-link">
            <img src={props.items.logo.src} alt={props.items.logo.alt} class="logo"/>
          </a>
          <div class="streep"></div>
        </div>

        <div class="options">
          <div class="add">
            <div onClick={handleClick('.add.select-options')} class="select-trigger button">
              <img src={props.items.buttons.add.image} alt="Plus-icon" class="icon"/>
              {props.items.buttons.add.text}
            </div>
            <div class="add select-options">
              {Object.values(props.items.buttons.add.options).map((option, index) => (
                <a href={option.href} key={index} class={`option${index + 1}`} >
                  <img class="icon" src={props.items.buttons.add.image} alt="Plus icon" />
                  <span>{option.text}</span>
                </a>
              ))}
            </div>
          </div>
          <div class="workspaces">
            <a href={props.items.buttons.workspace.href} class="button">
              <img src={props.items.buttons.workspace.image} alt="grid-icon" class="icon"/>
                {props.items.buttons.workspace.text}
            </a>
          </div>

          <div class="user-options">
            <div class="icon-container">
                <img src={props.items.select.image} alt="Profile Picture" class="profile-picture"/>
            </div>
            <div class="custom-selectbox">
              <div onClick={handleClick('.user.select-options')} id="select-trigger">
                <span id="username">Susan</span>
                <img class="arrow" src={props.items.select.arrow} alt="arrow"/>
              </div>
              <div class="user select-options">
                {Object.values(props.items.select.options).map((option, index) => (
                  <a href={option.href} key={index} class={`option${index + 1}`} >
                    <img class="icon" src={option.icon} alt="icon" />
                    <span>{option.naam}</span>
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
