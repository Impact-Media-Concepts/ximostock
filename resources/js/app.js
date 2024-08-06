import { createApp } from 'vue';
import { route } from '../../vendor/tightenco/ziggy/dist/index'; // Import the route function from ziggy-js

import Navbar from './components/Navbar.vue';
import Sidebar from './components/Sidebar.vue';
import SingleProduct from './components/product/SingleProduct.vue';
import GeneralNotification from './components/GeneralNotification.vue';
import UserOverview from './components/user/UserOverview.vue';
import AddUser from './components/user/AddUser.vue';
import ProductOverview from './components/product/ProductOverview.vue';
import CategoryOverview from './components/category/CategoryOverview.vue';
import ThemeConfigurator from './components/user/ThemeConfigurator.vue';


// Create the Vue application
const app = createApp({});

// Register the components
app.component('Navbar', Navbar);
app.component('Sidebar', Sidebar);
app.component('SingleProduct', SingleProduct);
app.component('GeneralNotification', GeneralNotification);
app.component('UserOverview', UserOverview);
app.component('AddUser', AddUser);
app.component('ProductOverview', ProductOverview);
app.component('CategoryOverview', CategoryOverview);
app.component('ThemeConfigurator', ThemeConfigurator);

// Provide the route function globally
app.provide('route', route);

// Mount the application
app.mount('#app');
