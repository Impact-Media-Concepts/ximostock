<template>
    <div class="saleschannel-view">
        <span class="page-title">Verkoopkanalen</span>
        <div class="saleschannel-table">
            <div class="saleschannel-table-header">
                <div class="select-name">
                    <input @click="toggleAllSaleschannels($event.target.checked)" :checked="isAllChecked()" class="select-all" type="checkbox">
                    <span class="orderby orderby-name">Naam <img class="chevron" src="/images/chevron-down-light.svg" alt="chevron-down"></span>
                </div>
                <div class="orderby-type">
                    <span class="orderby">Type <img class="chevron" src="/images/chevron-down-light.svg" alt="chevron-down"></span>
                </div>
                <div class="orderby-date">
                    <span class="orderby">Datum <img class="chevron" src="/images/chevron-down-light.svg" alt="chevron-down"></span>
                </div>
                <div class="create-button-wraper">
                    <button @click="toggleCreatePopup()" class="button-create">
                        <img class="saleschannel-icon" src="/images/saleschannel-icon-light.svg" alt="saleschannel-icon-light"> Verkoopkanaal aanmaken
                    </button>
                </div>
            </div>
            <div :class="{'saleschannel-bulkAction-bar': true, 'open':this.selectedSaleschannels.length}">
                <span class="bulkaction-text"> {{this.selectedSaleschannels.length}} verkoopkanalen van de {{this.saleschannels.data.length}} geselecteerd. <span @click="selectAll()" class="select-all-text">Selecteer alle verkoopkanalen</span></span>
                <button class="bulkaction-button" @click="bulkdeleteSelectedSaleschannels()">Archiveren</button>
                <!-- <button class="bulkaction-button">Verwijderen</button> -->
            </div>
            <div class="saleschannels-content">
                <div v-for="saleschannel in saleschannels['data']" :key="saleschannel.id" :class="{'saleschannel-item': true, 'active': isActive(saleschannel.id)}">
                    <div class="saleschannel-info">
                        <div class="select-name">
                            <input @click="toggleSaleschannelById($event.target.checked,saleschannel.id)" :checked="saleschannelIsChecked(saleschannel.id)" class="select" :value="saleschannel.id" type="checkbox">
                            <span @click="toggleActive(saleschannel.id)" class="name">{{ saleschannel.name }}</span>
                        </div>
                        <div class="type">
                            <span>{{ saleschannel.channel_type }}</span>
                        </div>
                        <div class="date">
                            <span>{{ formatDate(saleschannel.created_at) }}</span>
                        </div>
                        <div class="delete-open">
                            <div @click="toggleActive(saleschannel.id)" class="dropdown-wrapper">
                                <img class="open" src="/images/chevron-down-dark.svg" alt="chevron-down">
                            </div>
                            <button @click="deleteSaleschannel(saleschannel.id)" class="delete-button">Verwijderen</button>
                        </div>
                    </div>
                    <div class="saleschannel-form">
                        <div class="form">
                            <div class="form-input">
                                <span class="test">API Key:</span>
                                <input v-model="saleschannel.api_key" type="text">
                            </div>
                            <div class="form-input">
                                <span class="test">URL:</span>
                                <input v-model="saleschannel.url" type="text">
                            </div>
                            <div class="form-input">
                                <span class="test">Secret:</span>
                                <input v-model="saleschannel.secret" type="text">
                            </div>
                            <div class="form-input">
                                <span class="test">Kanaalnaam:</span>
                                <input v-model="saleschannel.name" type="text">
                            </div>
                        </div>
                        <div class="save-button-wrapper">
                            <button @click="updateSaleschannel(saleschannel.id)" class="save">
                                <img src="/images/save-icon.svg" alt="save-icon"> Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="saleschannel-footer">

            </div>
        </div>

        <!-- popups -->
         <div :class="{'create-popup': true, 'visable': this.isOpenCreatePopup}">
            <div class="popup">
                <img @click="toggleCreatePopup()" class="popup-close" src="/images/close-icon.svg" alt="close-popup">
                <div class="popup-content">
                    <div class="popup-header">
                        Verkoopkanaal aanmaken
                    </div>
                    <div class="saleschannel-create-form">
                        <div class="create-form-inputs">
                            
                            <div class="create-input">
                                <span>Naam:</span>
                                <input v-model="this.createSaleschannel.name" type="text">
                            </div>
                            <div class="create-input">
                                <span>Type:</span>
                                <select v-model="this.createSaleschannel.type" id="">
                                    <option value=""></option>
                                    <option value="WooCommerce">WooCommerce</option>
                                </select>
                            </div>
                            <div class="create-input">
                                <span>URL:</span>
                                <input v-model="this.createSaleschannel.url" type="text">
                            </div>
                            <div class="create-input">
                                <span>API Key:</span>
                                <input v-model="this.createSaleschannel.api_key" type="text">
                            </div>
                            <div class="create-input">
                                <span>Secret:</span>
                                <input v-model="this.createSaleschannel.secret" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="action-buttons">
                        <button  @click="createNewSaleschannel()" class="save-button">
                            <img class="button-icon" src="/images/save-icon.svg" alt="cross">
                            save
                        </button>
                        <button @click="toggleCreatePopup()" class="cancel-button">
                            <img  class="button-icon" src="/images/close-icon.svg" alt="cross">
                            Annuleren
                        </button>
                    </div>
                </div>
            </div>
         </div>
    </div>
</template>

<script>
import { defineComponent, inject } from 'vue';
import '../../../scss/saleschannel/SaleschannelOverview.scss';
import { format } from 'date-fns';
import axios from 'axios';

export default defineComponent({
    props: {
        saleschannels: {
            type: [Array, Object],
            required: true,
        }
    },
    data() {
        return {
            activeItemId: null,
            selectedSaleschannels : [],
            isOpenCreatePopup: false,
            createSaleschannel:{
                name:'',
                type: '',
                url: '',
                api_key: '',
                secret: ''
            }
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
        deleteSaleschannel(saleschannelId) {
            axios.delete(this.route('saleschannels.deleteBuId', saleschannelId))
            .then(response => {
                window.location.href = this.route('saleschannels.index');
            });
        },
        updateSaleschannel(saleschannelId) {
            const saleschannel = this.saleschannels.data.find(sc => sc.id === saleschannelId);
            const data = {
                name: saleschannel.name,
                url: saleschannel.url,
                api_key: saleschannel.api_key,
                secret: saleschannel.secret,
            };
            axios.put(this.route('saleschannels.updateById', saleschannelId), data)
            .then(response => {
                window.location.href = this.route('saleschannels.index');
            });
        },
        saleschannelIsChecked(id){
            const check = this.selectedSaleschannels.includes(id)

            return check;
        },
        toggleAllSaleschannels(checked){
            checked ? this.selectedSaleschannels = this.saleschannels.data.map(sc => sc.id) : this.selectedSaleschannels = [];
        },
        toggleSaleschannelById(checked, value){
            checked ? this.selectedSaleschannels.push(value) : this.selectedSaleschannels = this.selectedSaleschannels.filter(id => id !== value);

        },
        isAllChecked(){
            return this.saleschannels.data.every(sc => this.selectedSaleschannels.includes(sc.id));
        },
        selectAll(){
            this.selectedSaleschannels = this.saleschannels.data.map(sc => sc.id);
        },
        numberOfSelectedSaleschannels(){
            return this.selectedSaleschannels.count();
        },
        bulkdeleteSelectedSaleschannels(){
            const data = {saleschannels: this.selectedSaleschannels};
            console.log(data);
            axios.post(this.route('saleschannels.bulkDelete'), data)
            .then(response => {
                window.location.href = this.route('saleschannels.index');
            });
        },
        toggleCreatePopup(){
            this.isOpenCreatePopup = !this.isOpenCreatePopup;
        },
        createNewSaleschannel(){
            const saleschannel = this.createSaleschannel;
            const data = {
                name: saleschannel.name,
                type: saleschannel.type,
                url: saleschannel.url,
                api_key: saleschannel.api_key,
                secret: saleschannel.secret,
            };
            console.log(data);
            axios.post(this.route('saleschannels.store'), data)
            .then(response => {
                window.location.href = this.route('saleschannels.index');
            });
        }
    },
    setup() {
        const route = inject('route');
        return {
            route,
        };
    },
})
</script>