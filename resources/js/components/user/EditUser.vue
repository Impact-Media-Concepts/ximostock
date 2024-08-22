<template>
    <div class="edit-user">
        <div class="form-container">
            <div class="form-group row">
                <label for="title">Name:</label>
                <input type="text" v-model="localUser.name" id="title">
            </div>
            <div class="form-group">
                <label for="mail">Email:</label>
                <input type="email" v-model="localUser.email" id="mail">
            </div>
            <div class="form-group">
                <label for="workspace">Work space:</label>
                <input type="number" v-model="localUser.work_space_id" id="workspace">
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <input type="text" v-model="localUser.role" id="role">
            </div>
            

        </div>
        <pre>
            {{ localUser }}
        </pre>
    </div>
</template>

<script>
import { defineComponent, inject } from 'vue';
import '../../../scss/user/EditUser.scss';

export default defineComponent({
    props: {
        user: {
            type: Array, // Ensure the prop type is Array
            default: () => [],
        },
    },
    data() {
        return {
            localUser: this.user, // Store users in local data for reactivity
        };
    },
    methods: {

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