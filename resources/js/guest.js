import { createApp } from 'vue';
import userLogin from './components/user/login.vue';

 
createApp({})
  .component('UserLogin', userLogin)
  .mount('#app')