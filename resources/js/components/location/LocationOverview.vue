<template>
    <div class="locations">
        <span class="title">Opslaglocaties</span>
        <div class="location-table">
            <div class="table-header">
                <div class="select-name">
                    <input class="select-all" type="checkbox">
                    <span class="orderby">Naam <img class="chevron" src="/images/chevron-down-light.svg" alt="chevron-down"></span>
                </div>
                <div class="date-create">
                    <span class="orderby date">Datem <img class="chevron" src="/images/chevron-down-light.svg" alt="chevron-down"></span>
                    <button class="create-button"><img src="/images/location-icon-light.svg" alt="inventory-icon">Opslaglocatie aanmaken</button>
                </div>
            </div>
            <div class="table-content">
                <div v-for="location in locations['data']" :class="{'table-item': true, 'active' : isActive(location.id)}">
                    <div class="table-info" @click="toggleActive(location.id)">
                        <div class="select-name">
                            <input class="select-all" type="checkbox" @click.stop>
                            <span class="orderby">{{ location.name }}</span>
                        </div>
                        <div class="end-info-wrapper">
                            <span class="date">30-10-24 09:00</span>
                            <button class="delete-button" @click.stop>
                                <span class="trash-icon" v-html="this.icons.trash"></span> Verwijderen
                            </button>
                            <img class="chevron-down" src="/images/chevron-down-dark.svg" alt="chevron-down" @click.stop="toggleActive(location.id)">
                        </div>
                    </div> 
                    <div class="item-content">
                        <div class="sublocation-grid">
                            <div class="grid-item">
                                <input type="text" value="henk">
                            </div>
                            <div class="grid-item">
                                <input type="text" value="henk">
                            </div>
                            <div class="grid-item">
                                <input type="text" value="henk">
                            </div>
                            <div class="grid-item">
                                <input type="text" value="henk">
                            </div>
                            <div class="grid-item">
                                <input type="text" value="henk">
                            </div>
                            <div class="grid-item">
                                <input type="text" value="henk">
                            </div>
                            <div class="grid-item">
                                <input type="text" value="henk">
                            </div>

                        </div>
                        <button class="save-button"><span class="save-icon" v-html="this.icons.save"></span>save</button>
                    </div> 
                </div>
            </div>
            <div class="table-footer">
            </div>
        </div>
        <!-- popups -->
         
    </div>
</template>

<script>
import { defineComponent, inject } from 'vue';
import '../../../scss/location/location.scss';
import { format } from 'date-fns';
import axios from 'axios';

export default defineComponent({
    props: {
        icons: {
            type: [Array, Object],
            required: true,
        },
        locations: {
            type: [Array, Object],
            required: true,
        }
    },
    data() {
        return {
            activeItemId: null,
        };
    },
    methods: {
        formatDate(date) {
            return format(new Date(date), 'yyyy-MM-dd HH:mm:ss');
        },
        toggleActive(id) {
            this.activeItemId = this.activeItemId === id ? null : id;
        },
        isActive(id) {
            return this.activeItemId === id;
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