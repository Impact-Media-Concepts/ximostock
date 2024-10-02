<template>
    <div class="property-overview">
        <span class="page-title">Eigeschappen</span>

        <div class="property-table">
            <div class="table-header">
                <div class="orderby select-name">
                    <input type="checkbox">
                    <span> Naam </span>
                    <div class="chevron active">
                        <span v-html="icons['chevron']"></span>
                    </div>
                </div>
                <div class="orderby type" >
                    <span>Type</span>
                    <div class="chevron active">
                        <span v-html="icons['chevron']"></span>
                    </div>
                </div>
                <div class="orderby date-create">
                    <span class="date">Datem
                        <div class="chevron active">
                            <span v-html="icons['chevron']"></span>
                        </div>
                    </span>
                    <button class="create-button">Eigeschap aanmaken</button>
                </div>
            </div>
            <div class="table-bulkAction-bar">
            </div>
            <div class="table-content">
                <div v-for="property in properties['data']" :class="{'table-item': true, 'active': isActive(property.id) && hasOptions(property.type) }">
                    <div  @click="toggleActive(property.id, property.type)" class="table-info">
                        <div class="select-name">
                            <input type="checkbox">
                            <span class="name">{{ property.name }}</span>
                        </div>
                        <div class="type">
                            <span class="name">{{ property.type }}</span>
                        </div>
                        <div class="date">
                            <span class="name">{{ formatDate(property.created_at) }}</span>
                        </div>
                        <div class="delete-open">
                            <button @click.stop @click="openDeleteSinglePopup(saleschannel.id)"
                            class="delete-button">Verwijderen</button>
                            <div class="dropdown-wrapper">
                                <img v-if="hasOptions(property.type)" class="chevron open" src="/images/chevron-down-dark.svg" alt="chevron-down">
                            </div>
                        </div>
                    </div>
                    <div :key="property.id" class="item-content">
                        <div class="item-content">
                            <div class="property-options-grid">
                                <div   class="grid-item">
                                    <span  v-html="this.icons.close" class="close-icon"></span>
                                    <input type="text" placeholder="Optie">
                                </div>
                                <div class="add-option-button">
                                    Voeg optie toe
                                </div>
                            </div>
                            <button class="save-button">
                                <span class="save-icon" v-html="this.icons.save"></span>
                                Opslaan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-footer">
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent, inject } from 'vue';
import '../../../scss/property/PropertyOverview.scss';
import { format, formatDate, set } from 'date-fns';
import axios from 'axios';
import GeneralNotification from '../GeneralNotification.vue';
export default defineComponent({
    props: {
        icons: {
            type: [Array, Object],
            required: true,
        },
        order: {
            type: String,
            required: true,
        },
        orderby: {
            type: String,
            required: true,
        },
        properties: {
            type: [Array, Object],
            required: true,
            default: () => [],
        },
    },
    data(){
        return{
            activeItemId: null,
        }
    },
    methods:{
        formatDate(date) {
            return format(new Date(date), 'yyyy-MM-dd HH:mm:ss');
        },
        toggleActive(id,type) {
            if(this.hasOptions(type)){
                this.activeItemId = this.activeItemId === id ? null : id;
            }
        },
        isActive(id) {
            return this.activeItemId === id;
        },
        hasOptions(type){
            if(type === 'singleselect' || type === 'multiselect'){
                return true;
            }else{
                return false;
            }
        },
    }
});

</script>
