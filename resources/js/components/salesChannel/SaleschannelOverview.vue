<template>
    <div class="saleschannel-view">
        <span class="page-title">Verkoopkanalen</span>
        <div class="saleschannel-table">
            <div class="table-header">
                <div class="orderby select-name">
                    <input @click="toggleAllSaleschannels($event.target.checked)" :checked="isAllChecked()"
                        class="select-all" type="checkbox">
                    <span @click="orderBySaleschannels('name')" class="">Naam</span>
                    <div @click="orderBySaleschannels('name')" :class="{'chevron':true, 'asc': order == 'asc', 'active': orderby == 'name'}">
                        <span v-html="icons['chevron']"></span>
                    </div>
                </div>
                <div @click="orderBySaleschannels('channel_type')" class="orderby orderby-type">
                    <span class="orderby">Type </span>
                    <div  :class="{'chevron':true, 'asc': order == 'asc', 'active': orderby == 'channel_type'}">
                        <span  v-html="icons['chevron']"></span>
                    </div>
                </div>
                <div class="orderby orderby-date" @click="orderBySaleschannels('updated_at')">
                    <span class="orderby">Datum</span>
                    <div  :class="{'chevron':true, 'asc': order == 'asc', 'active': orderby == 'updated_at'}">
                        <span v-html="icons['chevron']"></span>
                    </div>
                </div>
                <div class="orderby create-button-wraper">
                    <button @click="toggleCreatePopup()" class="button-create">
                        <img class="saleschannel-icon" src="/images/saleschannel-icon-light.svg"
                            alt="saleschannel-icon-light"> Verkoopkanaal aanmaken
                    </button>
                </div>
            </div>
            <div :class="{ 'table-bulkAction-bar': true, 'open': this.selectedSaleschannels.length }">
                <span class="bulkaction-text"> {{ this.selectedSaleschannels.length }} verkoopkanalen van de
                    {{ this.usedSalesChannels.data.length }} geselecteerd. <span @click="selectAll()"
                        class="select-all-text">Selecteer alle verkoopkanalen</span></span>
                <button class="bulkaction-button" @click="toggleDeleteMultiPopup()">Archiveren</button>
                <!-- <button class="bulkaction-button">Verwijderen</button> -->
            </div>
            <div class="table-content">
                <div v-for="saleschannel in usedSalesChannels['data']" :key="saleschannel.id"
                    :class="{ 'table-item': true, 'active': isActive(saleschannel.id) }">
                    <div @click="toggleActive(saleschannel.id)" class="table-info">
                        <div class="select-name">
                            <input @click.stop @click="toggleSaleschannelById($event.target.checked, saleschannel.id)"
                                :checked="saleschannelIsChecked(saleschannel.id)" class="select"
                                :value="saleschannel.id" type="checkbox">
                            <span class="name">{{ saleschannel.name }}</span>
                        </div>
                        <div class="type">
                            <span>{{ saleschannel.channel_type }}</span>
                        </div>
                        <div class="date">
                            <span>{{ formatDate(saleschannel.created_at) }}</span>
                        </div>
                        <div class="delete-open">
                            <div class="dropdown-wrapper">
                                <img class="open" src="/images/chevron-down-dark.svg" alt="chevron-down">
                            </div>
                            <button @click.stop @click="openDeleteSinglePopup(saleschannel.id)"
                                class="delete-button">Verwijderen</button>
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
            <div class="table-footer">
            <span>
                {{ this.usedSalesChannels.current_page }} van {{ this.usedSalesChannels.last_page }} pagina's.
            </span>
            <div class="pagination">
                <span v-for="link in this.usedSalesChannels.links" :key="link.label"
                      @click="changePagination(link)"
                      :class="['link', { 'active-link': link.active }]">
                    {{ link.label }}
                </span>
            </div>
            </div>
        </div>

        <!-- popups -->
        <div :class="{ 'create-popup': true, 'visible': this.isOpenCreatePopup }">
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
                        <button @click="createNewSaleschannel()" class="save-button">
                            <img class="button-icon" src="/images/save-icon.svg" alt="cross">
                            save
                        </button>
                        <button @click="toggleCreatePopup()" class="cancel-button">
                            <img class="button-icon" src="/images/close-icon.svg" alt="cross">
                            Annuleren
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div :class="{ 'warning-delete-one-popup': true, 'visible': this.isOpenDeleteSinglePopup }">
            <div class="popup">
                <img @click="closeDeleteSinglePopup()" class="popup-close" src="/images/close-icon.svg"
                    alt="close-popup">
                <img src="/images/warning-icon.svg" alt="warning">

                <span class="title">verwijderen?</span>
                <p>Weet u zeker dat u dit Verkoopkanaal wilt verwijderen?</p>
                <div class="warning-buttons">
                    <button @click="closeDeleteSinglePopup()" class="cancel-button">Annuleren</button>
                    <button @click="deleteSaleschannel()" class="confirm-button">Verwijderen</button>
                </div>
            </div>
        </div>
        <div :class="{ 'warning-delete-one-popup': true, 'visible': this.isOpenDeleteMultiPopup }">
            <div class="popup">
                <img @click="toggleDeleteMultiPopup()" class="popup-close" src="/images/close-icon.svg"
                    alt="close-popup">
                <img src="/images/warning-icon.svg" alt="warning">

                <span class="title">verwijderen?</span>
                <p>Weet u zeker dat u de geselecteerde wilt verwijderen?</p>
                <div class="warning-buttons">
                    <button @click="toggleDeleteMultiPopup()" class="cancel-button">Annuleren</button>
                    <button @click="bulkdeleteSelectedSaleschannels()" class="confirm-button">Verwijderen</button>
                </div>
            </div>
        </div>

        <!-- succes and error messages -->
        <general-notification :messages="messages" :isError="messageIsError" v-if="messages" />

    </div>

</template>

<script>
import { defineComponent, inject } from 'vue';
import '../../../scss/saleschannel/SaleschannelOverview.scss';
import { format } from 'date-fns';
import axios from 'axios';
import GeneralNotification from '../GeneralNotification.vue';


export default defineComponent({
    components: {
        GeneralNotification,
    },
    props: {
        saleschannels: {
            type: [Array, Object],
            required: true,
            default: () => [],
        },
        order: {
            type: String,
            required: true,
        },
        orderby: {
            type: String,
            required: true,
        },
        icons: {
            type: [Array, Object],
            required: true,
        },
    },
    data() {
        return {
            activeItemId: null,
            selectedSaleschannels: [],
            isOpenCreatePopup: false,
            isOpenDeleteSinglePopup: false,
            isOpenDeleteMultiPopup: false,
            createSaleschannel: {
                name: '',
                type: '',
                url: '',
                api_key: '',
                secret: ''
            },
            toDeleteId: null,
            messages: null,
            usedSalesChannels: this.saleschannels,
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
        deleteSaleschannel() {
            axios.delete(this.route('saleschannels.deleteBuId', this.toDeleteId))
                .then(response => {
                    window.location.href = this.route('saleschannels.index');
                })
                .catch(error => {
                    this.messageIsError = true;
                    this.messages = error.response.data.errors;
                });
        },
        updateSaleschannel(saleschannelId) {
            const saleschannel = this.usedSalesChannels.data.find(sc => sc.id === saleschannelId);
            const data = {
                name: saleschannel.name,
                url: saleschannel.url,
                api_key: saleschannel.api_key,
                secret: saleschannel.secret,
            };
            axios.put(this.route('saleschannels.updateById', saleschannelId), data)
                .then(response => {
                    window.location.href = this.route('saleschannels.index');
                })
                .catch(error => {
                    this.messageIsError = true;
                    this.messages = error.response.data.errors;
                });
        },
        saleschannelIsChecked(id) {
            const check = this.selectedSaleschannels.includes(id)

            return check;
        },
        toggleAllSaleschannels(checked) {
            checked ? this.selectedSaleschannels = this.usedSalesChannels.data.map(sc => sc.id) : this.selectedSaleschannels = [];
        },
        toggleSaleschannelById(checked, value) {
            checked ? this.selectedSaleschannels.push(value) : this.selectedSaleschannels = this.selectedSaleschannels.filter(id => id !== value);

        },
        isAllChecked() {
            return this.usedSalesChannels.data.every(sc => this.selectedSaleschannels.includes(sc.id));
        },
        selectAll() {
            this.selectedSaleschannels = this.usedSalesChannels.data.map(sc => sc.id);
        },
        numberOfSelectedSaleschannels() {
            return this.selectedSaleschannels.count();
        },
        bulkdeleteSelectedSaleschannels() {
            const data = { saleschannels: this.selectedSaleschannels };
            console.log(data);
            axios.post(this.route('saleschannels.bulkDelete'), data)
                .then(response => {
                    window.location.href = this.route('saleschannels.index');
                })
                .catch(error => {
                    this.messageIsError = true;
                    this.messages = error.response.data.errors;
                });
        },
        toggleCreatePopup() {
            this.isOpenCreatePopup = !this.isOpenCreatePopup;
        },
        createNewSaleschannel() {
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
                })
                .catch(error => {
                    this.messageIsError = true;
                    this.messages = error.response.data.errors;
                });
        },
        closeDeleteSinglePopup() {
            this.isOpenDeleteSinglePopup = false;
            this.toDeleteId = null;
        },
        openDeleteSinglePopup(id) {
            this.isOpenDeleteSinglePopup = true;
            this.toDeleteId = id;
        },
        toggleDeleteMultiPopup() {
            this.isOpenDeleteMultiPopup = !this.isOpenDeleteMultiPopup;
        },
        orderBySaleschannels(column) {
            let order;
            if(this.orderby === column) {
                order = this.order === 'asc' ? 'desc' : 'asc';
            } else {
                order = 'asc';
            }

            const params = {
                orderby: column,
                order: order
            };
            const url = this.route('saleschannels.index', params);
            window.location.href = url;
        },
        changePagination(link) {
            // Check if the link is not active (to prevent reloading the same page)
            if (!link.active && link.url) {
                axios.get(link.url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => {

                    this.usedSalesChannels = response.data.saleschannels;
                })
                .catch(error => {
                    console.error('Error loading more saleschannels:', error);
                });
            }
        },
    },
    watch: {
        usedSalesChannels: {
            handler(newSalesChannels) {
                newSalesChannels.links = newSalesChannels.links.slice(1, -1);
            },
        },
    },
    setup() {
        const route = inject('route');
        return {
            route,
        };
    },
    mounted() {
        this.usedSalesChannels.links = this.usedSalesChannels.links.slice(1, -1);
    },
})
</script>
