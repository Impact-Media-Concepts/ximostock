<template>
    <div class="supplier-overview">
        <span class="page-title">Leveranciers</span>
        <div class="supplier-table">
            <div class="table-header">
                <div class="orderby select-name">
                    <input :checked="isCheckedAll()" @click="toggleCheckedAll($event.target.checked)" class="select-all" type="checkbox" >
                    <span>Naam <span class="chevron" v-html="icons['chevron']"></span></span>
                </div>
                <div class="orderby company-name">
                    <span>Bedrijfsnaam<span class="chevron" v-html="icons['chevron']"></span></span>
                </div>
                <div class="date-create orderby">
                    <span class="date">Datem<span class="chevron" v-html="icons['chevron']"></span></span>
                    <button @click="isOpenCreatePopup = true" class="create-button"><span class="supplier-icon" v-html="icons['supplier']"></span>Leverancier aanmaken</button>
                </div>
            </div>
            <div :class="{'table-bulkAction-bar': true, 'open': this.selectedSuppliers.length}">
                <div class="bulkaction-text">
                    <span>{{ this.selectedSuppliers.length }} leveranciers van de {{ this.suppliers['data'].length }} geselecteerd. <span @click="toggleCheckedAll(true)" class="select-all-text">Selecteer alle leveranciers </span></span>
                    <button @click="isOpenDeleteSelectedPopup = true" class="bulkaction-button">Bulk verwijderen</button>
                </div>
            </div>
            <div class="table-content">
                <div v-for="supplier in suppliers['data']" :class="{'table-item': true, 'active': isActive(supplier.id) }">
                    <div @click="toggleActive(supplier.id)" class="table-info">
                        <div class="select-name">
                            <input @click.stop @click="toggleChecked(supplier.id)" :checked="isChecked(supplier.id)" type="checkbox">
                            <span>{{supplier.name}}</span>
                        </div>
                        <div class="company-name">
                            {{supplier.company_name}}
                        </div>
                        <div class="end-info-wrapper">
                            <div class="date">{{ formatDate(supplier.updated_at) }}</div>
                            <button @click.stop @click="OpenDeletePopup(supplier.id)" class="delete-button"> <span class="trash-icon" v-html="icons['trash']"></span>Verwijderen</button>
                            <img :class="{'chevron-down': true, 'active' : isActive(supplier.id)}" src="/images/chevron-down-dark.svg" alt="chevron-down" @click.stop="toggleActive(location.id)">
                        </div>
                    </div>
                    <div class="item-content">
                        <div class="supplier-form">
                            <div class="form-input">
                                Naam:
                                <input type="text" v-model="supplier.name">
                            </div>
                            <div class="form-input">
                                Bedrijfsnaam:
                                <input type="text" v-model="supplier.company_name">
                            </div>
                            <div class="form-input">
                                Telefoonnummer:
                                <input type="text" v-model="supplier.phone_number">
                            </div>
                            <div class="form-input">
                                Website:
                                <input type="text" v-model="supplier.website">
                            </div>
                        </div>
                        <button @click="updateSupplier(supplier)" class="save-button">
                            <img src="/images/save-icon.svg" alt="save icon">
                            Save
                        </button>
                    </div>
                </div>

            </div>

            <div class="table-footer">

            </div>
        </div>
        <!-- popups -->
        <div :class="{'delete-popup' : true, 'visible' : isOpenDeleteWaringSupplier}">
            <div class="popup">
                <span v-html="icons['close']" class="popup-close" @click="isOpenDeleteWaringSupplier = false"></span>
                <span v-html="icons['warning']"></span>
                <span>Weet je zeker dat je deze leverancier wilt verwijderen?</span>
                <div class="warning-buttons">
                    <button @click="isOpenDeleteWaringSupplier = false" class="cancel-button">Annuleren</button>
                    <button @click="deleteActiveSupplier" class="confirm-button">Verwijderen</button>
                </div>
            </div>
        </div>
        <div :class="{'delete-selected-popup': true, 'visible' : isOpenDeleteSelectedPopup}" >
            <div class="popup">
                <span v-html="icons['close']" class="popup-close" @click="isOpenDeleteSelectedPopup = false"></span>
                <span v-html="icons['warning']"></span>
                <span>Weet je zeker dat je de geselecteerde leveranciers wilt verwijderen?</span>
                <div class="warning-buttons">
                    <button @click="isOpenDeleteSelectedPopup = false" class="cancel-button">Annuleren</button>
                    <button @click="deleteSelectedSuppliers()" class="confirm-button">Verwijderen</button>
                </div>
            </div>
        </div>
        <!-- create popup -->
        <div :class="{'create-popup': true, 'visible': isOpenCreatePopup}">
            <div class="popup">
                <span v-html="icons['close']" class="popup-close" @click="isOpenCreatePopup = false"></span>
                <div class="popup-content">
                    <div class="popup-header">
                        <span>Leverancier aanmaken</span>
                    </div>
                    <div class="supplier-create-form">
                        <div class="create-form-inputs">
                            <div class="create-input">
                                <span>Naam:</span>
                                <input type="text" v-model="newSupplier.name">
                            </div>
                            <div class="create-input">
                                <span>Bedrijfsnaam:</span>
                                <input type="text" v-model="newSupplier.company_name">
                            </div>
                            <div class="create-input">
                                <span>Telefoonnummer:</span>
                                <input type="text" v-model="newSupplier.phone_number">
                            </div>
                            <div class="create-input">
                                <span>Website:</span>
                                <input type="text" v-model="newSupplier.website">
                            </div>
                        </div>
                    </div>
                    <div class="action-buttons">
                        <button @click="createSupplier()" class="save-button"><span v-html="icons['save']"></span>Save</button>
                        <button @click="isOpenCreatePopup = false" class="cancel-button"><img class="button-icon" src="/images/close-icon.svg" alt="close"> Annuleren</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- succes and error messages -->
        <GeneralNotification :messages="messages" :isError="messageIsError" v-if="messages"/>
    </div>
</template>


<script>
import { defineComponent, inject } from 'vue';
import '../../../scss/supplier/SupplierOverview.scss';
import { format, formatDate, set } from 'date-fns';
import axios from 'axios';
import { is } from 'date-fns/locale';
import GeneralNotification from '../GeneralNotification.vue';

export default defineComponent({

    props: {
        suppliers:{
            type: [Array, Object],
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
            selectedSuppliers: [],
            supplierToDelete: null,
            isOpenDeleteWaringSupplier: false,
            isOpenDeleteSelectedPopup: false,
            isOpenCreatePopup: false,
            messages: null,
            messageIsError: false,
            newSupplier: {
                name: '',
                company_name: '',
                phone_number: '',
                website: '',
            },
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
        isChecked(id) {
            return this.selectedSuppliers.includes(id);
        },
        toggleChecked(id) {
            if (this.selectedSuppliers.includes(id)) {
                this.selectedSuppliers = this.selectedSuppliers.filter((item) => item !== id);
            } else {
                this.selectedSuppliers.push(id);
            }
        },
        isCheckedAll() {
            return this.selectedSuppliers.length === this.suppliers['data'].length;
        },
        toggleCheckedAll(checked) {
            if (checked) {
                this.selectedSuppliers = this.suppliers['data'].map((item) => item.id);
            } else {
                this.selectedSuppliers = [];
            }
        },
        //deleteFucntionalitijd
        deleteActiveSupplier() {
            axios.delete(this.route('suppliers.deleteById', this.supplierToDelete))
            .then(response => {
                window.location.href = this.route('suppliers.index');
            });
        },
        OpenDeletePopup(id) {
            this.supplierToDelete = id;
            this.isOpenDeleteWaringSupplier = true;
        },
        //bulkdeleteFunctionality
        deleteSelectedSuppliers() {
            const data = {
                suppliers: this.selectedSuppliers,
            };
            console.log(data);
            axios.delete(this.route('suppliers.bulkDelete', data))
            .then(response => {
                window.location.href = this.route('suppliers.index');
            });
        },
        //update functionality
        updateSupplier(supplier) {
            console.log(supplier);
            const updatedSupplier = supplier;
            axios.put(this.route('suppliers.update'), updatedSupplier)
                .then(response => {
                    this.messageIsError = false;
                    this.messages = response.data.message;
                })
                .catch(error => {
                    this.messageIsError = true;
                    this.messages = error.response.data.errors;
                });
        },
        //create functionality
        createSupplier() {
            console.log(this.newSupplier);
            axios.post(this.route('suppliers.store'), this.newSupplier)
                .then(response => {
                    this.messageIsError = false;
                    this.messages = response.data.message;
                    window.location.reload();
                })
                .catch(error => {
                    this.messageIsError = true;
                    this.messages = error.response.data.errors;
                });
        },
    },
    setup() {
        const route = inject('route');
        return {
            route,
        };
    },
})
</script>
