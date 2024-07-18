<script setup>
import { defineProps, computed, inject } from 'vue';
import axios from 'axios';
import '../../../scss/user/UserOverview.scss';

const route = inject('route');

// Define and accept the prop 'users' which can be an array or an object
const props = defineProps({
  users: {
    type: [Array, Object],
    default: () => []
  }
});

// Ensure users is always treated as an array
const users = computed(() => Array.isArray(props.users) ? props.users : [props.users]);

// Method to handle user deletion
const deleteUser = async (userId) => {
  try {
    axios.delete(route('users.destroy', userId));
    users.value = users.value.filter(user => user.id !== userId);
  } catch {

  }
};
</script>

<template>
  <div class="user-overview">
    <h1>Users</h1>
    <a :href="route('users.create')" class="button">Add user</a>
    <ul v-if="users.length > 0">
      <li v-for="user in users" :key="user.id">
        <span>{{ user.name }}</span>
        <span>{{ user.email }}</span>
        <a :href="route('users.edit', user.id)" class="edit">Edit</a>
        <button @click="deleteUser(user.id)" class="delete">Delete</button>
      </li>
    </ul>
    <p v-else>Geen gebruikers gevonden</p>
  </div>
</template>