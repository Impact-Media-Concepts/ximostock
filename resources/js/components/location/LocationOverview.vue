<template>
    <div class="locations">
        <span class="title">Opslaglocaties</span>
        <div class="location-table">
            <div class="table-header">
                <div class="select-name">
                    <input :checked="isAllChecked()" @click="toggleAllItems($event.target.checked)" class="select-all" type="checkbox">
                    <span class="orderby">Naam <img class="chevron" src="/images/chevron-down-light.svg" alt="chevron-down"></span>
                </div>
                <div class="date-create">
                    <span class="orderby date">Datem <img class="chevron" src="/images/chevron-down-light.svg" alt="chevron-down"></span>
                    <button @click="toggleCreatePopup()" class="create-button"><img src="/images/location-icon-light.svg" alt="inventory-icon">Opslaglocatie aanmaken</button>
                </div>
            </div>
            <div :class="{'table-bulkAction-bar': true, 'open':this.selectedLocations.length}">
                <span class="bulkaction-text"> {{this.selectedLocations.length}} verkoopkanalen van de {{ this.locations['data'].length }} geselecteerd. <span @click="selectAllItems()" class="select-all-text">Selecteer alle verkoopkanalen</span></span>
                <button class="bulkaction-button" @click="toggleBulkDeleteUpdate()">Archiveren</button>
            </div>
            <div class="table-content">
                <div v-for="location in locations['data']" :class="{'table-item': true, 'active' : isActive(location.id)}">
                    <div class="table-info" @click="toggleActive(location.id)">
                        <div class="select-name">
                            <input @click="toggleItemById($event.target.checked, location.id)" :checked="itemIsChecked(location.id)" class="select-all" type="checkbox" @click.stop>
                            <span class="orderby">{{ location.name }}</span>
                        </div>
                        <div class="end-info-wrapper">
                            <span class="date">{{formatDate(location.created_at)}}</span>
                            <button @click="opeDenletePopup(location.id)" class="delete-button" @click.stop>
                                <span class="trash-icon" v-html="this.icons.trash"></span> Verwijderen
                            </button>
                            <img class="chevron-down" src="/images/chevron-down-dark.svg" alt="chevron-down" @click.stop="toggleActive(location.id)">
                        </div>
                    </div>
                    <div :key="location.id" class="item-content">
                        <div class="sublocation-grid">
                            <div v-for="(zone, index) in location.location_zones" :key="zone.name + index" class="grid-item">
                                <span @click="deleteZone(location.id,index)" v-html="this.icons.close" class="close-icon"></span>
                                <input v-model="zone.name" type="text"  placeholder="nieuwe zone">
                            </div>
                            <div @click="createNewZone(location.id)"  class="add-zone-button">
                                voeg zone toe
                            </div>
                        </div>
                        <button @click="updateLocation(location.id)" class="save-button"><span class="save-icon" v-html="this.icons.save"></span>save</button>
                    </div>
                </div>
            </div>
            <div class="table-footer">
            </div>
        </div>
        <!-- popups -->
        <div :class="{'create-popup': true,  'visible': this.createPopupIsOpen}">
            <div class="popup">
                <img @click="toggleCreatePopup()"  class="popup-close" src="/images/close-icon.svg" alt="close-popup">
                <div class="popup-content">
                    <div class="popup-header">
                        Locatie aanmaken
                    </div>
                    <div class="create-form">
                        <div class="create-form-inputs">
                            <div class="create-input">
                                <span>Naam:</span>
                                <input v-model="this.locationToCreateName" type="text">
                            </div>
                            <div class="add-button-container">
                                <button @click="createZoneField()" class="add-zone-button">Zone toevoegen</button>
                            </div>
                            <div  v-for="(subLocation, index) in zones" :key="index" class="create-input">
                                <span>Zone:</span>
                                <div class="input-wrapper">
                                    <input
                                    type="text"
                                    v-model="zones[index]"
                                    class="text-input"
                                />
                                <span @click="removeZoneField(index)" v-html="this.icons.close" class="close-icon"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="action-buttons">
                        <button @click="createLocation()"  class="save-button">
                            <span class="save-icon" v-html="this.icons.save"> </span>
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
        <div :class="{'delete-popup':true, 'visible' : this.DeletePopupIsOpen}">
            <div class="popup">
                <img @click="closeDeletePopup()" class="popup-close" src="/images/close-icon.svg" alt="close-popup">
                <img src="/images/warning-icon.svg" alt="warning">
                <span class="title">verwijderen?</span>
                <p>Weet u zeker dat u deze locatie wilt verwijderen?</p>
                <div class="warning-buttons">
                    <button @click="closeDeletePopup()" class="cancel-button">Annuleren</button>
                    <button @click="deleteLocation()" class="confirm-button">Verwijderen</button>
                </div>
            </div>
        </div>
        <div :class="{'bulk-delete-popup':true, 'visible' : this.BulkDeletePopupIsOpen}">
            <div class="popup">
                <img @click="toggleBulkDeleteUpdate()" class="popup-close" src="/images/close-icon.svg" alt="close-popup">
                <img src="/images/warning-icon.svg" alt="warning">
                <span class="title">verwijderen?</span>
                <p>Weet u zeker dat u deze locaties wilt verwijderen?</p>
                <div class="warning-buttons">
                    <button @click="toggleBulkDeleteUpdate()" class="cancel-button">Annuleren</button>
                    <button @click="bulkdeleteSelectedLocations()" class="confirm-button">Verwijderen</button>
                </div>
            </div>
        </div>
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
            createPopupIsOpen: false,
            DeletePopupIsOpen:false,
            BulkDeletePopupIsOpen:false,
            zones: [''],
            locationToDelete: null,
            selectedLocations:[],
            locationToCreateName:'',
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
        deleteLocation(){

            axios.delete(this.route('locations.deletebyid', this.locationToDelete ))
            .then(response => {
                window.location.href = this.route('locations.index');
            });
        },
        createLocation(){
            const data = {
                name: this.locationToCreateName,
                zones: this.zones
            };
            console.log(data);
            axios.post(this.route('locations.store'), data)
            .then(response => {
                window.location.href = this.route('locations.index');
            });
        },
        updateLocation(locationId){
            const list = this.locations['data'].filter(location => location.id == locationId);
            const data = list[0];
            console.log(data);
            axios.put(this.route('locations.update'), data)
            .then(response => {
                window.location.href = this.route('locations.index');
            });
        },
        //handle sublocations for update
        createNewZone(locationId) {
            const locationIndex = this.locations.data.findIndex(loc => loc.id === locationId);
            if (locationIndex !== -1) {
                const location = this.locations.data[locationIndex];

                // Add a new zone
                location.location_zones.push({ name: '' });

                // Force a reactivity update
                this.$forceUpdate();
            }
        },
        deleteZone(locationId, zoneIndex) {
            const location = this.locations.data.find(loc => loc.id === locationId);
            if (location) {
                location.location_zones.splice(zoneIndex, 1);
            }
            this.$forceUpdate();
        },
        //select handeler
        itemIsChecked(id){
            const check = this.selectedLocations.includes(id);
            return check;
        },
        toggleAllItems(checked){
            checked ? this.selectedLocations = this.locations.data.map(location => location.id) : this.selectedLocations = [];
        },
        selectAllItems(){
            this.selectedLocations = this.locations.data.map(item => item.id);
        },
        toggleItemById(checked, value){
            checked ? this.selectedLocations.push(value) : this.selectedLocations = this.selectedLocations.filter(id => id !== value);
        },
        isAllChecked(){
            return this.locations.data.every(sc => this.selectedLocations.includes(sc.id));
        },
        //end select handeler
        //bulkactions
        bulkdeleteSelectedLocations(){
            const data = {locations: this.selectedLocations};
            console.log(data);
            axios.post(this.route('locations.bulkDelete'), data)
            .then(response => {
                window.location.href = this.route('locations.index');
            });
        },
        //create popup handeler
        createZoneField(){ //create a new zone for new location
            this.zones.push('');
            console.log(this.zones);
        },
        removeZoneField(index) {
            this.zones.splice(index, 1);
        },
        //handlepopups
        toggleCreatePopup(){
            this.createPopupIsOpen = !this.createPopupIsOpen;
            this.zones = [''];
        },
        toggleBulkDeleteUpdate(){
            this.BulkDeletePopupIsOpen = !this.BulkDeletePopupIsOpen;
        },
        opeDenletePopup(locationId){
            this.DeletePopupIsOpen = true;
            this.locationToDelete = locationId;
        },
        closeDeletePopup(){
            this.DeletePopupIsOpen = false;
        },
        //end handdle popups
    },
    setup() {
        const route = inject('route'); // Injecting route helper
        return {
            route,
        };
    },
});
</script>
