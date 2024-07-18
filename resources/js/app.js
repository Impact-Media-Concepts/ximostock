import { createApp } from 'vue';
import { route } from '../../vendor/tightenco/ziggy/dist/index'; // Import the route function from ziggy-js

import Navbar from './components/Navbar.vue';
import Sidebar from './components/Sidebar.vue';
import MainProductInfo from './components/product/MainProductInfo.vue';
import GeneralNotification from './components/GeneralNotification.vue';
import UserOverview from './components/user/UserOverview.vue';
import AddUser from './components/user/AddUser.vue';

// Create the Vue application
const app = createApp({});

// Register the components
app.component('Navbar', Navbar);
app.component('Sidebar', Sidebar);
app.component('MainProductInfo', MainProductInfo);
app.component('GeneralNotification', GeneralNotification);
app.component('UserOverview', UserOverview);
app.component('AddUser', AddUser);

// Provide the route function globally
app.provide('route', route);

// Mount the application
app.mount('#app');
