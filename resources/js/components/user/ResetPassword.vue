<script setup lang="jsx">
import { defineProps } from 'vue';
import '../../../scss/user/ResetPassword.scss';

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
    <div class="reset-password-form-content">

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

      <div className="form-item current-password">
        <label htmlFor="current_password">Huidige wachtwoord:</label>
        <input type="password" name="current_password" placeholder="Vul je huidige wachtwoord in" />
      </div>
      
      <div className="form-item password">
        <label htmlFor="password">Nieuwe wachtwoord:</label>
        <input type="password" name="password" placeholder="Vul je nieuwe wachtwoord in" />
      </div>

      <div className="form-item confirm-password">
        <label htmlFor="password_confirmation">Bevestig nieuwe wachtwoord:</label>
        <input type="password" name="password_confirmation" placeholder="Bevestig hier je nieuwe wachtwoord" />
      </div>

      <button className="submit primary">{content.button}</button>

      <a href={ content.logout } className="logout">Uitloggen</a>

    </div>
  );
};
</script>

<template>
  <!-- Use the render function as the template for the component -->
  <component :is="render" />
</template>

