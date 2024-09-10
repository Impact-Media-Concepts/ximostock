<script setup>
import { defineProps, computed, inject } from 'vue';
import '../../../scss/user/AddUser.scss';

const route = inject('route');

// Define and accept the prop 'users' which can be an array or an object
const props = defineProps({
  roles: {
    type: [Array, Object],
    default: () => []
  }
});

</script>

<template>
    <div>
        <label for="work_space_id">Workspace ID (Optional):</label>
        <input type="number" id="work_space_id" name="work_space_id">
    </div>

    <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
    </div>

    <div>
        <label for="role">Role:</label>
        <select id="role" name="role">
            <option v-for="role in roles" :value="role" :key="role">{{ role }}</option>
        </select>
    </div>

    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>

    <div>
        <label for="email_verified_at">Email Verified At:</label>
        <input type="datetime-local" id="email_verified_at" name="email_verified_at">
    </div>

    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>

    <div>
        <label for="password_confirmation">Confirm Password:</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required>
    </div>

    <div>
        <label for="remember_token">Remember Token:</label>
        <input type="text" id="remember_token" name="remember_token">
    </div>

    <div>
        <button type="submit">Submit</button>
    </div>
</template>