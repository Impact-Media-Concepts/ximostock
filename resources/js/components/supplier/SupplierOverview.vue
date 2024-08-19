<template>
    <div class="supplier-overview">
        <span class="page-title">Leveranciers</span>
        <div class="supplier-table">
            <div class="table-header">
                <div class="orderby select-name">
                    <input class="select-all" type="checkbox" name="" id="">
                    <span>Naam <span class="chevron" v-html="icons['chevron']"></span></span>
                </div>
                <div class="orderby company-name">
                    <span>Bedrijfsnaam<span class="chevron" v-html="icons['chevron']"></span></span>
                </div>
                <div class="date-create orderby">
                    <span class="date">Datem<span class="chevron" v-html="icons['chevron']"></span></span>
                    <button class="create-button"><span class="supplier-icon" v-html="icons['supplier']"></span>Leverancier aanmaken</button>
                </div>
            </div>
            <div class="table-bulkAction-bar">

            </div>
            <div class="table-content">
                <div v-for="supplier in suppliers['data']" :class="{'table-item': true, 'active': isActive(supplier.id) }">
                    <div @click="toggleActive(supplier.id)" class="table-info">
                        <div class="select-name">
                            <input type="checkbox">
                            <span>{{supplier.name}}</span>
                        </div>
                        <div class="company-name">
                            {{supplier.company_name}}
                        </div>
                        <div class="end-info-wrapper">
                            <div class="date">{{ formatDate(supplier.updated_at) }}</div>
                            <button class="delete-button"> <span class="trash-icon" v-html="icons['trash']"></span>Verwijderen</button>
                            <img class="chevron-down" src="/images/chevron-down-dark.svg" alt="chevron-down" @click.stop="toggleActive(location.id)">
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
                        <button class="save-button">
                            <img src="/images/save-icon.svg" alt="save icon">
                            Save
                        </button>
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
import '../../../scss/supplier/SupplierOverview.scss';
import { format, formatDate } from 'date-fns';
import axios from 'axios';

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
    }
})
</script>
