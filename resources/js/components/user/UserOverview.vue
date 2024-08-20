<template>
  <div class="user-overview">
    <span class="title">Gebruikers</span>
    <div class="user-wrapper">
      <div class="user-table">
        <div class="table-header">
          <div class="orderby name">
            Naam
          </div>
          <div class="orderby email">
            Email
          </div>
          <div class="orderby actions">
            Acties
          </div>
        </div>
        <div class="table-content">
          <div class="table-item" v-for="user in localUsers" :key="user.id">
            <span class="table-info">{{ user.name }}</span>
            <span class="table-info">{{ user.email }}</span>
            <span class="table-info">
              <a :href="route('users.edit', user.id)" class="edit">Edit</a>
              <button @click="deleteUser(user.id)" class="delete">Delete</button>
            </span>
          </div>          
        </div>
        <div class="table-footer">

        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, inject } from 'vue';
import '../../../scss/user/UserOverview.scss';
import axios from 'axios';

export default defineComponent({
  props: {
    users: {
      type: Array, // Ensure the prop type is Array
      default: () => [],
    },
  },
  data() {
    return {
      localUsers: this.users, // Store users in local data for reactivity
    };
  },
  methods: {
    deleteUser(id) {
      axios.delete(this.route('users.destroy', id))
        .then(response => {
          this.localUsers = this.localUsers.filter(user => user.id !== id); // Update the local array reactively
        })
        .catch(error => {
          console.log(error);
        });
    },
  },
  setup() {
    const route = inject('route'); // Injecting route helper
    return {
      route,
    };
  },
});
</script>
