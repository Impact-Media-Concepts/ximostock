

<template>
    <div class="archive">
        <span class="title">Archief</span>
        <div class="archive-wrapper">
            <div class="archive-table">
                <div class="table-header">
                    <div class="select-name">
                        <input :checked="isAllChecked()" @click="toggleAllItems($event.target.checked)" class="select-all"  type="checkbox">
                        <span @click="orderByArchive('name')" class="orderby">Naam
                            <div :class="{'chevron':true, 'asc': order == 'asc', 'active': orderby == 'name'}">
                                <span v-html="icons['chevron']"></span>
                            </div>
                        </span>
                    </div>
                    <div class="type">
                        <span @click="orderByArchive('itemtype')" class="orderby">Type
                            <div :class="{'chevron':true, 'asc': order == 'asc', 'active': orderby == 'itemtype'}">
                                <span v-html="icons['chevron']"></span>
                            </div>
                        </span>
                    </div>
                    <div class="date">
                        <span @click="orderByArchive('deleted_at')" class="orderby">Datem
                            <div :class="{'chevron':true, 'asc': order == 'asc', 'active': orderby == 'deleted_at'}">
                                <span v-html="icons['chevron']"></span>
                            </div>
                        </span>
                    </div>
                </div>
                <div  :class="{'table-bulkAction-bar': true, 'open':this.selectedItems.length}">
                    <span class="bulkaction-text">{{this.selectedItems.length}} opties van de {{this.items['data'].length}} geselecteerd. <span @click="selectAllItems()" class="select-all-text">Selecteer alle opties</span></span>
                    <button @click="bulkRestore()" class="bulkaction-button">
                        Herstellen
                    </button>
                    <button @click="toggleBulkForceDeletePopup()" class="bulkaction-button">
                        Verwijderen
                    </button>
                </div>
                <div class="table-content">
                    <div class="contant-wrapper"></div>
                    <div v-for="item in items['data']" :class="{'table-item': true, 'active': isActive(item)}">
                        <div @click="toggleActive(item)" class="table-info" >
                            <div class="select-name">
                                <input @click.stop @click="toggleItemById($event.target.checked ,item)" :checked="itemIsChecked(item)" type="checkbox">
                                <span >{{ item.name }}</span>
                            </div>
                            <div class="type" >
                                {{ item.itemtype }}
                            </div>
                            <div class="date">
                                {{ formatDate(item.deleted_at) }}
                                <img  :class="{'chevron-down': true, 'active': isActive(item)}" src="/images/chevron-down-dark.svg" alt="chevron-down" >
                            </div>
                        </div>
                        <div class="action-buttons">
                            <button @click="restoreItem(item.id, item.itemtype)">Herstellen</button>
                            <button @click="toggleForceDeletePopup()">Verwijderen</button>
                        </div>
                    </div>
                    <div v-if="items['data'].length == 0">
                            er zijn geen verwijderde items in het archief.
                        </div>
                </div>
                <div class="table-footer">

                </div>
            </div>
            <div class="archive-filter">
                <span class="title">Soort</span>
                <hr>
                <div class="filter-list">
                    <div v-for="itemtype in types" class="filter-item">
                        <input @change="filterByType()" type="checkbox" :id="itemtype.name" v-model="itemtype.value"><label  :for="itemtype.name">{{itemtype.name}}</label>
                    </div>

                </div>
            </div>
        </div>
        <!-- popups -->
        <div :class="{'force-delete-popup':true, 'visible' : forceDeletePopup}">
            <div class="popup">
                <img @click="toggleForceDeletePopup()" class="popup-close" src="/images/close-icon.svg" alt="close-popup">
                <img src="/images/warning-icon.svg" alt="warning">
                <span class="title">verwijderen?</span>
                <p>Weet u zeker dat u dit item <strong>permanent</strong>wilt verwijderen?
                <br>
                Deze actie kan niet ongedaan gemaakt worden!</p>
                <div class="warning-buttons">
                    <button @click="toggleForceDeletePopup()" class="cancel-button">Annuleren</button>
                    <button @click="forceDeleteItem(this.activeItem.id, this.activeItem.itemtype)" class="confirm-button">Verwijderen</button>
                </div>
            </div>
        </div>
        <div :class="{'bulk-force-delete-popup':true, 'visible' : bulkForceDeletePopup}">
            <div class="popup">
                <img @click="toggleBulkForceDeletePopup()" class="popup-close" src="/images/close-icon.svg" alt="close-popup">
                <img src="/images/warning-icon.svg" alt="warning">
                <span class="title">verwijderen?</span>
                <p>Weet u zeker dat u deze items <strong>permanent</strong>
                    wilt verwijderen?
                <br>
                Deze actie kan niet ongedaan gemaakt worden!</p>
                <div class="warning-buttons">
                    <button @click="toggleBulkForceDeletePopup()" class="cancel-button">Annuleren</button>
                    <button @click="bulkForceDelete()" class="confirm-button">Verwijderen</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent, inject } from 'vue';
import '../../../scss/archive/ArchiveOverview.scss';
import { format } from 'date-fns';
import axios from 'axios';

export default defineComponent({
    props: {
        items: {
            type: [Array, Object],
            required: true,
        },
        icons: {
            type: [Array, Object],
            required: true,
        },
        order: {
            type: String,
            required: false,
        },
        orderby: {
            type: String,
            required: false,
        },
        types: {
            type: [Array, Object],
            required: true,
        }
    },
    data() {
        return {
            activeItem: null,
            selectedItems:[],

            forceDeletePopup:false,
            bulkForceDeletePopup:false
        };
    },
    methods: {
        formatDate(date) {
            return format(new Date(date), 'yyyy-MM-dd HH:mm:ss');
        },
        toggleActive(item) {
            this.activeItem = JSON.stringify(this.activeItem) == JSON.stringify(item) ? null : item;
        },
        isActive(item) {
            return JSON.stringify(this.activeItem) == JSON.stringify(item);
        },
        restoreItem(itemId, itemtype) {
            const data = {
                id: itemId,
                type: itemtype
            }
            axios.post(this.route('archive.restore'), data)
            .then(response => {
                window.location.href = this.route('archive.index');
            });
        },
        forceDeleteItem(itemId, itemtype) {
            const data = {
                id: itemId,
                type: itemtype
            }
            console.log(data);
            axios.post(this.route('archive.forcedelete'), data)
            .then(response => {
                window.location.href = this.route('archive.index');
            });
        },
        itemIsChecked(value){
            const check = this.selectedItems.filter(item => JSON.stringify(item) == JSON.stringify(value));
            return check.length;
        },
        toggleAllItems(checked){
            checked ? this.selectedItems = this.items.data.map(item => item) : this.selectedItems = [];
        },
        selectAllItems(){
            this.selectedItems = this.items.data.map(item => item);
            console.log(this.selectedItems);
        },
        toggleItemById(checked, value){
            checked ? this.selectedItems.push(value) : this.selectedItems = this.selectedItems.filter(item => JSON.stringify(item) != JSON.stringify(value));
            console.log(this.selectedItems);
        },
        isAllChecked(){
            return this.items.data.every(item => this.selectedItems.includes(item));
        },
        bulkRestore(){
            const data =  {items: this.selectedItems };
            axios.post(this.route('archive.bulkrestore'), data)
            .then(response => {
                window.location.href = this.route('archive.index');
            });
        },
        bulkForceDelete(){
            const data =  {items: this.selectedItems };
            axios.post(this.route('archive.bulkforcedelete'), data)
            .then(response => {
                window.location.href = this.route('archive.index');
            })
            .catch(error => {
                console.log(error);
            });
        },
        toggleForceDeletePopup(){
            this.forceDeletePopup = !this.forceDeletePopup;
        },
        toggleBulkForceDeletePopup(){
            this.bulkForceDeletePopup = !this.bulkForceDeletePopup;
        },
        //order functionallity
        orderByArchive(column) {
            let order;
            if (this.orderby === column) {
                order = this.order === 'asc' ? 'desc' : 'asc';
            } else {
                order = 'asc';
            }

            // Get the currently selected types
            let typelist = Array.isArray(this.types) ? this.types : Object.values(this.types);
            let checked_types = typelist.filter(type => type.value).map(type => type.name);

            // Create the parameters object including both order and type filters
            const params = {
                orderby: column,
                order: order,
                checked_types: checked_types
            };

            // Navigate to the updated URL
            const url = this.route('archive.index', params);
            window.location.href = url;
        },

        //filte by type
        filterByType() {
            // Get the currently selected types
            let typelist = Array.isArray(this.types) ? this.types : Object.values(this.types);
            let checked_types = typelist.filter(type => type.value).map(type => type.name);

            // Create the parameters object including both type filters and the current order
            const params = {
                checked_types: checked_types,
                orderby: this.orderby,
                order: this.order
            };

            // Navigate to the updated URL
            const url = this.route('archive.index', params);
            window.location.href = url;
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
