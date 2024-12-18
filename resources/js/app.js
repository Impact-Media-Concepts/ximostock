import { createApp } from 'vue';
import { route } from '../../vendor/tightenco/ziggy/dist/index'; // Import the route function from ziggy-js

import Navbar from './components/Navbar.vue';
import Sidebar from './components/Sidebar.vue';
import SingleProduct from './components/product/SingleProduct.vue';
import UserOverview from './components/user/UserOverview.vue';
import AddUser from './components/user/AddUser.vue';
import ProductOverview from './components/product/ProductOverview.vue';
import SaleschannelOverview from './components/salesChannel/SaleschannelOverview.vue';
import ArchiveOverview from './components/archive/ArchiveOverview.vue';

import CategoryOverview from './components/category/CategoryOverview.vue';
import ThemeConfigurator from './components/user/ThemeConfigurator.vue';
import EditUser from './components/user/EditUser.vue';
import LocationOverview from './components/location/LocationOverview.vue';
import SupplierOverview from './components/supplier/SupplierOverview.vue';
import WorkspaceOveriew from './components/workspace/WorkspaceOverview.vue';
import PropertyOverview from './components/property/PropertyOverview.vue';
import ActivityLog from './components/activity/ActivityLog.vue';

// Create the Vue application
const app = createApp({});

// Register the components
app.component('Navbar', Navbar);
app.component('Sidebar', Sidebar);
app.component('SingleProduct', SingleProduct);
app.component('UserOverview', UserOverview);
app.component('AddUser', AddUser);
app.component('ProductOverview', ProductOverview);
app.component('SaleschannelOverview', SaleschannelOverview);
app.component('ArchiveOverview', ArchiveOverview);
app.component('CategoryOverview', CategoryOverview);
app.component('ThemeConfigurator', ThemeConfigurator);
app.component('LocationOverview', LocationOverview);
app.component('SupplierOverview', SupplierOverview);
app.component('EditUser', EditUser);
app.component('WorkspaceOverview', WorkspaceOveriew);
app.component('PropertyOverview', PropertyOverview);
app.component('ActivityLog', ActivityLog);

// Provide the route function globally
app.provide('route', route);

// Mount the application
app.mount('#app');
