import { createApp } from 'vue';
import IncrementCounter from './components/IncrementCounter.vue';
import IncrementCounterMinus from './components/IncrementCounterMinus.vue';
import Navbar from './components/Navbar.vue';
import Sidebar from './components/Sidebar.vue';
import MainProductInfo from './components/product/MainProductInfo.vue';
 
createApp({})
  .component('IncrementCounter', IncrementCounter)
  .component('IncrementCounterMinus', IncrementCounterMinus)
  .component('Navbar', Navbar)
  .component('Sidebar', Sidebar)
  .component('MainProductInfo', MainProductInfo)
  .mount('#app')