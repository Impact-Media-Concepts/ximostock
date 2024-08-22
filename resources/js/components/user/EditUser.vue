<template>
    <div class="edit-user">
        <div class="form-container">
            <div class="form-group" v-if="this.admin">
                <label>Work space:</label>
                <input type="number" v-model="localUser.work_space_id">
            </div>
            <div class="form-group">
                <label>Name:</label>
                <input type="text" v-model="localUser.name">
            </div>
            <div class="form-group" v-if="this.admin">
                <label for="role">Role:</label>
                <select v-model="localUser.role">
                    <option v-for="role in roles" :value="role">{{ role }}</option>
                </select>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" v-model="localUser.email">
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" v-model="localUser.password">
            </div>
            <div class="form-group">
                <label>Confirm Password:</label>
                <input type="password" v-model="localUser.password_confirmation">
            </div>
            <div class="form-group">
                <button @click="save()" class="save">Opslaan</button>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent, inject } from 'vue';
import '../../../scss/user/EditUser.scss';
import axios from 'axios';

export default defineComponent({
    props: {
        user: {
            type: Object, // Ensure the prop type is Array
            default: () => [],
        },
        roles: {
            type: Object, // Ensure the prop type is Array
            default: () => [],
        },
        admin: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            localUser: { ...this.user, password: '', password_confirmation: '' },
        };
    },
    methods: {
        save() {
            axios.patch(route('users.update', this.localUser.id), this.localUser)
                .then((response) => {
                    console.log(response.data.user);
                    this.localUser = response.data.user;
                })
                .catch((error) => {
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
    mounted() {
        console.log(this.localUser);
    },
});
</script>