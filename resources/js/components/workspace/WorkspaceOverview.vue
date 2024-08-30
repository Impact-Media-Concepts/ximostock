<template>
    <div class="workspace-overview">
        <div class="title-bar">
            <span class="title">Workspaces</span>
            <span @click="isOpenCreatePopup = true" class="button add">Nieuwe toevoegen</span>
        </div>
        <div class="workspace-wrapper">
            <div class="workspace-table">
                <div class="table-header">
                    <div class="orderby name">
                        Naam
                    </div>
                    <div class="orderby actions">
                        Acties
                    </div>
                </div>
                <div class="table-content">
                    <div class="table-item" v-for="workspace in this.workspaces" :key="user.id">
                        <span class="table-info">{{ workspace.name }}</span>
                        <span class="table-info"> 
                            <button @click="deleteWorkspace(workspace.id)" class="delete">Delete</button>
                        </span>
                    </div>
                </div>
                <div class="table-footer">

                </div>
            </div>
        </div>

        <!-- create popup -->
        <div :class="{'create-popup': true, 'visible': isOpenCreatePopup}">
            <div class="popup">
                <span v-html="icons['close']" class="popup-close" @click="isOpenCreatePopup = false"></span>
                <div class="popup-content">
                    <div class="popup-header">
                        <span>Workspace aanmaken</span>
                    </div>
                    <div class="supplier-create-form">
                        <div class="create-form-inputs">
                            <div class="create-input">
                                <span>Naam:</span>
                                <input type="text" v-model="newWorkspace.name">
                            </div>
                        </div>
                    </div>
                    <div class="action-buttons">
                        <button @click="createWorkspace()" class="save-button"><span v-html="icons['save']"></span>Save</button>
                        <button @click="isOpenCreatePopup = false" class="cancel-button"><img class="button-icon" src="/images/close-icon.svg" alt="close"> Annuleren</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- succes and error messages -->
        <general-notification :messages="messages" :isError="messageIsError" v-if="messages" />

    </div>
</template>

<script>
import { defineComponent, inject } from 'vue';
import '../../../scss/workspace/WorkspaceOverview.scss';
import axios from 'axios';
import GeneralNotification from '../GeneralNotification.vue';

export default defineComponent({
    components: {
        GeneralNotification,
    },
    props: {
        // Define the initialWorkspaces prop
        environments: {
            type: [Array, Object], // The prop is expected to be an array
            default: () => [], // Default to an empty array if not provided
        },
        user: {
            type: Object,
            default: () => ({}),
        },
        icons: {
            type: [Array, Object],
            required: true,
        },
    },
    data() {
        return {
            workspaces: this.environments,
            messages: null,
            messageIsError: false,
            isOpenCreatePopup: false,
            newWorkspace: {
                name: '',
            },
        };
    },

    methods: {
        deleteWorkspace(id) {
            axios.delete(this.route('workspaces.destroy', id))
                .then(response => {
                    this.messageIsError = false;
                    this.messages = response.data.message;
                    this.workspaces = this.workspaces.filter(workspace => workspace.id !== id);
                })
                .catch(error => {
                    console.log(error);
                    this.messageIsError = true;
                });
        },
        createWorkspace() {
            axios.post(this.route('workspaces.store'), this.newWorkspace)
                .then(response => {
                    this.messageIsError = false;
                    this.messages = response.data.message;
                    this.isOpenCreatePopup = false;
                    this.workspaces.push(response.data.workspace);
                    
                })
                .catch(error => {
                    console.log(error);
                    this.messageIsError = true;
                });
        },
    },
    mounted() {
    },
    setup() {
        const route = inject('route'); // Injecting route helper
        return {
            route,
        };
    },
});
</script>