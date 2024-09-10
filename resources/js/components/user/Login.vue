<script setup lang="jsx">
import { defineProps } from 'vue';
import '../../../scss/user/Login.scss';

// Define and accept the prop 'content' which can be an array or an object
const props = defineProps({
  content: {
    type: [Array, Object],
    default: () => []
  }
});

// Render function using JSX to create the component's template
const render = () => {
  const content = props.content;

  return (
    <div class="login-form-content">

      <img src={content.logo} alt="ximostock-logo" className="logo" />

      <h2 className="title">{ content.title }</h2>
      <p className="description">{ content.description }</p>

      {content.status === 'validation-failed' && (
        <div className="error">
          <strong>Er is iets misgegaan. Probeer het opnieuw.</strong>
        </div>
      )}

      <div className="form-item email">
        <label htmlFor="email">Email:</label>
        <input type="email" name="email" placeholder="Vul je email hier in" />
      </div>
      
      <div className="form-item password">
        <label htmlFor="password">Wachtwoord:</label>
        <input type="password" name="password" placeholder="Vul je wachtwoord hier in" />
      </div>

      <button className="submit primary">{content.button}</button>

      <a href={ content.resetPassword } className="forgot-password">Wachtwoord vergeten</a>

    </div>
  );
};
</script>

<template>
  <!-- Use the render function as the template for the component -->
  <component :is="render" />
</template>

