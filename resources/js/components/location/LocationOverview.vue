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
                    <button @click="toggleCreatePopup()" class="create-button"><img src="/images/location-icon-light.svg" alt="inventory-icon">Opslaglocatie aanmaken</button>
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
                            <button @click="opeDenletePopup(location.id)" class="delete-button" @click.stop>
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
        <div :class="{'create-popup': true,  'visable': this.createPopupIsOpen}">
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
                                <input  type="text">
                            </div>
                            <div></div>
                            <div  v-for="(subLocation, index) in subLocations" :key="index" class="create-input">
                                <span>sublocatie:</span>
                                <input
                                    type="text"
                                    v-model="subLocations[index]"
                                    class="text-input"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="action-buttons">
                        <button   class="save-button">
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
        <div :class="{'delete-popup':true, 'visable' : this.DeletePopupIsOpen}">
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
            subLocations: [''],
            locationToDelete: null,
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
        toggleCreatePopup(){
            this.createPopupIsOpen = !this.createPopupIsOpen;
        },
        opeDenletePopup(locationId){
            this.DeletePopupIsOpen = true;
            this.locationToDelete = locationId;
        },
        closeDeletePopup(){
            this.DeletePopupIsOpen = false;
        },
        deleteLocation(){
            console.log(this.locationToDelete);
            axios.delete(this.route('locations.deletebyid', this.locationToDelete ))
            .then(response => {
                window.location.href = this.route('locations.index');
            });
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