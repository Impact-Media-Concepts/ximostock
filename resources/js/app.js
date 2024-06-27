import { createApp } from 'vue';
import IncrementCounter from './components/IncrementCounter.vue';
import IncrementCounterMinus from './components/IncrementCounterMinus.vue';
 
createApp({})
  .component('IncrementCounter', IncrementCounter)
  .component('IncrementCounterMinus', IncrementCounterMinus)
  .mount('#app')