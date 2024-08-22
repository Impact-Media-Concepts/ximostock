<template>
  <div class="user-overview">
    <div class="title-bar">
      <span class="title">Gebruikers</span>
      <a :href="route('users.create')" class="button add">Nieuwe toevoegen</a>  
    </div>
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
            <span @click="ToggleUserInfo(user.id)" class="table-info">{{ user.name }}</span>
            <span @click="ToggleUserInfo(user.id)" class="table-info">{{ user.email }}</span>
            <span class="table-info">
              <button @click="deleteUser(user.id)" class="delete">Delete</button>
            </span>
            <edit-user :id="user.id" :user='user' :roles='roles'></edit-user>
          </div>          
        </div>
        <div class="table-footer">

        </div>
      </div>
    </div>
    
    <!-- succes and error messages -->
    <general-notification :messages="messages" :isError="messageIsError" v-if="messages"/>

  </div>
</template>

<script>
import { defineComponent, inject } from 'vue';
import '../../../scss/user/UserOverview.scss';
import axios from 'axios';
import EditUser from './EditUser.vue';
import GeneralNotification from '../GeneralNotification.vue';

export default defineComponent({
  components: {
    EditUser,
    GeneralNotification,
  },
  props: {
    users: {
      type: Array, // Ensure the prop type is Array
      default: () => [],
    },
    roles: {
      type: Array, // Ensure the prop type is Array
      default: () => [],
    },
  },
  data() {
    return {
      localUsers: this.users, // Store users in local data for reactivity
      messages: null,
      messageIsError: false,
    };
  },
  methods: {
    deleteUser(id) {
      axios.delete(this.route('users.destroy', id))
        .then(response => {
          this.localUsers = this.localUsers.filter(user => user.id !== id);
          this.messageIsError = false;
          this.messages = response.data.message;
        })
        .catch(error => {
          this.messageIsError = true;
          this.messages = error.response.data.message;
        });
    },
    ToggleUserInfo(id) {
      const htmlItem = document.getElementById(id);
      htmlItem.classList.toggle('active');
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
