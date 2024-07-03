import { createApp } from 'vue';
import IncrementCounter from './components/IncrementCounter.vue';
import IncrementCounterMinus from './components/IncrementCounterMinus.vue';
import Navbar from './components/Navbar.vue';
import Sidebar from './components/Sidebar.vue';
import Products from './components/Products.vue';

createApp({})
  .component('IncrementCounter', IncrementCounter)
  .component('IncrementCounterMinus', IncrementCounterMinus)
  .component('Navbar', Navbar)
  .component('Sidebar', Sidebar)
  .component('Products', Products)
  .mount('#app')