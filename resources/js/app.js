import { createApp } from 'vue';
import IncrementCounter from './components/IncrementCounter.vue';
import IncrementCounterMinus from './components/IncrementCounterMinus.vue';
import Navbar from './components/Navbar.vue';
import Sidebar from './components/Sidebar.vue';
 
createApp({})
  .component('IncrementCounter', IncrementCounter)
  .component('IncrementCounterMinus', IncrementCounterMinus)
  .component('Navbar', Navbar)
  .component('Sidebar', Sidebar)
  .mount('#app')