

<template>
    <div class="archive">
        <span class="title">Archief</span>
        <div class="archive-wrapper">
            <div class="archive-table">
                <div class="table-header">
                    <div class="select-name">
                        <input :checked="isAllChecked()" @click="toggleAllItems($event.target.checked)" class="select-all"  type="checkbox">
                        <span class="orderby">Naam <img class="chevron" src="/images/chevron-down-light.svg" alt="chevron-down"></span>
                    </div>
                    <div class="type">
                        <span class="orderby">Type <img class="chevron" src="/images/chevron-down-light.svg" alt="chevron-down"></span>
                    </div>
                    <div class="date">
                        <span class="orderby">Datem <img class="chevron" src="/images/chevron-down-light.svg" alt="chevron-down"></span>
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
                    <div v-for="item in items['data']" :class="{'table-item': true, 'active': isActive(item)}">
                        <div class="table-info" >
                            <div class="select-name">
                                <input @click="toggleItemById($event.target.checked ,item)" :checked="itemIsChecked(item)" type="checkbox">
                                <span @click="toggleActive(item)">{{ item.name }}</span>
                            </div>
                            <div class="type"  @click="toggleActive(item)">
                                {{ item.type }}
                            </div>
                            <div class="date" @click="toggleActive(item)">
                                {{ formatDate(item.deleted_at) }}
                                <img  :class="{'chevron-down': true, 'active': isActive(item)}" src="/images/chevron-down-dark.svg" alt="chevron-down" >
                            </div>
                        </div>
                        <div class="action-buttons">
                            <button @click="restoreItem(item.id, item.type)">Herstellen</button>
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
                    <div class="filter-item">
                        <input type="checkbox" id="product"> <label  for="product">Product</label>
                    </div>
                    <div class="filter-item">
                        <input type="checkbox" id="Category"> <label for="Category">Categorie</label>
                    </div>
                    <div class="filter-item">
                        <input type="checkbox" id="saleschannel"> <label for="saleschannel">Verkoopkanaal</label>
                    </div>
                    <div class="filter-item">
                        <input type="checkbox" id="property"> <label for="property">Eigenschap</label>
                    </div>
                </div>
            </div>
        </div>
        <!-- popups -->
        <div :class="{'force-delete-popup':true, 'visable' : forceDeletePopup}">
            <div class="popup">
                <img @click="toggleForceDeletePopup()" class="popup-close" src="/images/close-icon.svg" alt="close-popup">
                <img src="/images/warning-icon.svg" alt="warning">
                <span class="title">verwijderen?</span>
                <p>Weet u zeker dat u dit item <strong>permanent</strong>wilt verwijderen?
                <br>
                Deze actie kan niet ongedaan gemaakt worden!</p>
                <div class="warning-buttons">
                    <button @click="toggleForceDeletePopup()" class="cancel-button">Annuleren</button>
                    <button @click="forceDeleteItem(this.activeItem.id, this.activeItem.type)" class="confirm-button">Verwijderen</button>
                </div>
            </div>
        </div>
        <div :class="{'bulk-force-delete-popup':true, 'visable' : bulkForceDeletePopup}">
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
        restoreItem(itemId, itemType) {
            const data = {
                id: itemId,
                type: itemType
            }
            axios.post(this.route('archive.restore'), data)
            .then(response => {
                window.location.href = this.route('archive.index');
            });
        },
        forceDeleteItem(itemId, itemType) {
            const data = {
                id: itemId,
                type: itemType
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
            });
        },
        toggleForceDeletePopup(){
            this.forceDeletePopup = !this.forceDeletePopup;
        },
        toggleBulkForceDeletePopup(){
            this.bulkForceDeletePopup = !this.bulkForceDeletePopup;
        }
    },
    setup() {
        const route = inject('route'); // Injecting route helper
        return {
            route,
        };
    },
});
</script>
