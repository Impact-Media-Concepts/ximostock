import { createApp } from 'vue';
import userLogin from './components/user/Login.vue';
import resetPassword from './components/user/ResetPassword.vue';

 
createApp({})
  .component('UserLogin', userLogin)
  .component('ResetPassword', resetPassword)
  .mount('#app')