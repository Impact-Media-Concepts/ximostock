

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
                    <button class="bulkaction-button">
                        Herstellen
                    </button>
                    <button class="bulkaction-button">
                        Verwijderen
                    </button>
                </div>
                <div class="table-content">
                    <div v-for="item in items['data']" :class="{'table-item': true, 'active': isActive(item.type + item.id)}">
                        <div class="table-info" >
                            <div class="select-name">
                                <input @click="toggleItemById($event.target.checked ,item)" :checked="itemIsChecked(item)" type="checkbox">
                                <span @click="toggleActive(item.type + item.id)">{{ item.name }}</span>
                            </div>
                            <div class="type"  @click="toggleActive(item.type + item.id)">
                                {{ item.type }}
                            </div>
                            <div class="date" @click="toggleActive(item.type + item.id)">
                                {{ formatDate(item.deleted_at) }}
                                <img  :class="{'chevron-down': true, 'active': isActive(item.type + item.id)}" src="/images/chevron-down-dark.svg" alt="chevron-down" >
                            </div>
                        </div>
                        <div class="action-buttons">
                            <button @click="restoreItem(item.id, item.type)">Herstellen</button>
                            <button @click="forceDeleteItem(item.id, item.type)">Verwijderen</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="archive-filter">
                filter
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
        }
    },
    data() {
        return {
            activeItemId: null,
            selectedItems:[],
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
            const data = this.selectedItems;
            
            console.log(data);
            axios.post(this.route('archive.forcedelete'), data)
            .then(response => {
                window.location.href = this.route('archive.index');
            });
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
