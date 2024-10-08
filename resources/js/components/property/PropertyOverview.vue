<template>
    <div class="property-overview">
        <span class="page-title">Eigenschappen</span>
        <div class="property-table">
            <div class="table-header">
                <div class="orderby select-name">
                    <input :checked="isCheckedAll()" @click="toggleCheckedAll($event.target.checked)" type="checkbox" />
                    <span @click="orderByProperties('name')" > Naam </span>
                    <div @click="orderByProperties('name')" :class="{'chevron':true, 'asc': order == 'asc', 'active': orderby == 'name'}">
                        <span v-html="icons['chevron']"></span>
                    </div>
                </div>
                <div class="orderby type">
                    <span @click="orderByProperties('type')" >Type</span>
                    <div @click="orderByProperties('type')" :class="{'chevron':true, 'asc': order == 'asc', 'active': orderby == 'type'}">
                        <span v-html="icons['chevron']"></span>
                    </div>
                </div>
                <div class="orderby date-create">
                    <span  @click="orderByProperties('updated_at')" class="date">
                        Datum
                        <div  @click="orderByProperties('updated_at')" :class="{'chevron':true, 'asc': order == 'asc', 'active': orderby == 'updated_at'}">
                            <span v-html="icons['chevron']"></span>
                        </div>
                    </span>
                    <button @click="isOpenCreateForm = true" class="create-button"> <span class="property-icon" v-html="icons['property']"></span>Eigenschap aanmaken</button>
                </div>
            </div>
            <div :class="{'table-bulkAction-bar': true, 'open': this.selectedProperties.length}">
                <div class="bulkaction-text">
                    <span>{{ this.selectedProperties.length }} leveranciers van de {{ this.properties['data'].length }} geselecteerd. <span @click="toggleCheckedAll(true)" class="select-all-text">Selecteer alle leveranciers </span></span>
                    <button @click="isOpenBulkDeleteSingleWaring = true" class="bulkaction-button">Bulk verwijderen</button>
                </div>
            </div>
            <div class="table-content">
                <div
                    v-for="property in properties['data']"
                    :key="property.id"
                    :class="{ 'table-item': true, 'active': isActive(property.id) && hasOptions(property.type) }"
                >
                    <div @click="toggleActive(property.id, property.type)" class="table-info">
                        <div class="select-name">
                            <input type="checkbox" @click.stop  @click="toggleChecked(property.id)" :checked="isChecked(property.id)"/>
                            <span class="name">{{ property.name }}</span>
                        </div>
                        <div class="type">
                            <span class="name">{{ property.type }}</span>
                        </div>
                        <div class="date">
                            <span class="name">{{ formatDate(property.created_at) }}</span>
                        </div>
                        <div class="delete-open">
                            <button
                                @click.stop
                                @click="openDeleteSinglePopup(property.id)"
                                class="delete-button"
                            >
                                <span class="trash-icon" v-html="icons['trash']"></span>Verwijderen
                            </button>
                            <div class="dropdown-wrapper">
                                <img v-if="hasOptions(property.type)" :class="{'chevron-down': true, 'active' : isActive(property.id)}" src="/images/chevron-down-dark.svg" alt="chevron-down" >
                            </div>
                        </div>
                    </div>
                    <div :key="property.id" class="item-content">
                        <div class="property-options-grid">
                            <div
                                v-for="(option, index) in property.options"
                                :key="index"
                                class="grid-item"
                            >
                                <span @click="removeOption(property, index)" v-html="this.icons.close" class="close-icon"></span>
                                <input type="text" v-model="property.options[index]" placeholder="Optie" />
                            </div>
                            <div @click="addOption(property)" class="add-option-button">
                                Voeg optie toe
                            </div>
                        </div>
                        <button @click="updateProperty(property)" class="save-button">
                            <span class="save-icon" v-html="this.icons.save"></span>
                            Opslaan
                        </button>
                    </div>
                </div>
            </div>
            <div class="table-footer">
                <span>
                    {{ properties.current_page }} van {{ properties.last_page }} pagina's.
                </span>
                <div class="pagination">
                    <div v-for="link in properties.links" :key="link.label">
                        <span v-if="!isNaN(link.label)"
                        @click="changePagination(link)"
                        :class="['link', { 'active-link': link.active }]">
                        <span  v-html="link.label"></span>
                    </span>
                    </div>

                </div>
            </div>
        </div>

         <!-- Popups -->
        <div :class="{'delete-popup' : true, 'visible' : isOpenDeleteSingleWaring}">
            <div class="popup">
                <span v-html="icons['close']" class="popup-close" @click="isOpenDeleteSingleWaring = false"></span>
                <span v-html="icons['warning']"></span>
                <span>Weet je zeker dat je deze eigenschap wilt verwijderen?</span>
                <div class="warning-buttons">
                    <button @click="isOpenDeleteSingleWaring = false" class="cancel-button">Annuleren</button>
                    <button @click="deleteProperty()" class="confirm-button"> Verwijderen</button>
                </div>
            </div>
        </div>
        <div :class="{'delete-popup' : true, 'visible' : isOpenBulkDeleteSingleWaring}">
            <div class="popup">
                <span v-html="icons['close']" class="popup-close" @click="isOpenBulkDeleteSingleWaring = false"></span>
                <span v-html="icons['warning']"></span>
                <span>Weet je zeker dat je deze eigenschappen wilt verwijderen?</span>
                <div class="warning-buttons">
                    <button @click="isOpenBulkDeleteSingleWaring = false" class="cancel-button">Annuleren</button>
                    <button @click="BulkDeleteProperties()" class="confirm-button">Verwijderen</button>
                </div>
            </div>
        </div>

        <div :class="{'create-popup': true, 'visible': isOpenCreateForm }">
            <div class="popup">
                <span v-html="icons['close']" class="popup-close" @click="isOpenCreateForm = false"></span>
                <div class="popup-content">
                    <div class="popup-header">
                        Eigenschap aanmaken
                    </div>
                    <div class="create-form">
                        <div class="form-input">
                            <span>Naam:</span>
                            <input v-model="nameToCreate" type="text">
                        </div>
                        <div class="form-input">
                            <span>Type:</span>
                            <select v-model="selectedType">
                                <option value="bool">bool</option>
                                <option value="number">number</option>
                                <option value="text">text</option>
                                <option value="singleselect">singleselect</option>
                                <option value="multiselect">multiselect</option>

                            </select>
                        </div>
                    </div>
                    <div v-if="selectedType == 'singleselect' || selectedType == 'multiselect'" class="options-grid">
                        <button @click="addOptionToCreate()" class="add-option-button">Optie toevoegen</button>
                        <div v-for="(option, index) in optionsToCreate" :key="index" class="option-wrapper">
                                <input v-model="optionsToCreate[index]" type="text" placeholder="Optie">
                                <span @click="optionsToCreate.splice(index,1)" v-html="icons['close']" class="close-icon"></span>
                        </div>
                    </div>
                    <div class="action-buttons">
                        <button @click="CreateProperty()"  class="save-button">
                            <span class="save-icon" v-html="this.icons.save"> </span>
                            save
                        </button>
                        <button @click="isOpenCreateForm = false" class="cancel-button">
                            <img  class="button-icon" src="/images/close-icon.svg" alt="cross">
                            Annuleren
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <general-notification :messages="messages" :isError="messageIsError" v-if="messages"/>
    </div>
</template>


<script>
import { defineComponent, inject } from 'vue';
import '../../../scss/property/PropertyOverview.scss';
import { add, format, formatDate, set } from 'date-fns';
import axios from 'axios';
import GeneralNotification from '../GeneralNotification.vue';
import { is } from 'date-fns/locale';
export default defineComponent({
    components: {
        GeneralNotification,
    },
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
            isOpenDeleteSingleWaring: false,
            isOpenBulkDeleteSingleWaring: false,
            isOpenCreateForm: false,
            propertyToDelete: null,
            selectedProperties: [],
            optionsToCreate: [''],
            nameToCreate: '',
            selectedType: '',
            messages: null,
            messageIsError: false,
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
        isChecked(id) {
            return this.selectedProperties.includes(id);
        },
        toggleChecked(id) {
            if (this.selectedProperties.includes(id)) {
                this.selectedProperties = this.selectedProperties.filter((item) => item !== id);
            } else {
                this.selectedProperties.push(id);
            }
        },
        isCheckedAll() {
            return this.selectedProperties.length === this.properties['data'].length;
        },
        toggleCheckedAll(checked) {
            if (checked) {
                this.selectedProperties = this.properties['data'].map((item) => item.id);
            } else {
                this.selectedProperties = [];
            }
        },

        //orderby functionality
        orderByProperties(column) {
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
            const url = this.route('properties.index', params);
            window.location.href = url;
        },
        //methods
        addOption(property){
            console.log(property);
            property.options.push('');
            this.$forceUpdate();
        },
        removeOption(property, index){
            property.options.splice(index, 1);
            this.$forceUpdate();
        },
        updateProperty(property){
            const data = {
                name: property.name,
                options: property.options,
                type: property.type,
            };
            console.log(data);
            axios.put(this.route('property.update', property.id), data)
                .then((response) => {
                    this.messageIsError = false;
                    this.messages = response.data.message;
                })
                .catch((error) => {
                    this.messageIsError = true;
                    this.messages = error.response.data.errors;
                }
            );
        },
        openDeleteSinglePopup(id){
            this.propertyToDelete = id;
            this.isOpenDeleteSingleWaring = true;
        },
        deleteProperty(){
            console.log(this.propertyToDelete);
            axios.delete(this.route('property.deleteById', this.propertyToDelete))
                .then((response) => {
                    window.location.href = this.route('properties.index');
                })
                .catch((error) => {
                    console.log(error);
                }
            );
        },
        BulkDeleteProperties(){
            const data = {
                properties: this.selectedProperties,
            };
            console.log(data);
            axios.delete(this.route('property.bulkDelete', data))
                .then((response) => {
                    window.location.href = this.route('properties.index');
                })
                .catch((error) => {
                    console.log(error);
                }
            );
        },
        addOptionToCreate(){
            this.optionsToCreate.push('');
        },
        CreateProperty(){
            const data = {
                name: this.nameToCreate,
                type: this.selectedType,
                options: this.optionsToCreate,
            };
            console.log(data);
            axios.post(this.route('property.store'), data)
                .then((response) => {
                    window.location.href = this.route('properties.index');
                })
                .catch((error) => {
                    console.log(error);
                }
            );
        }
        ,
        changePagination(link) {
            // Check if the link is not active (to prevent reloading the same page)
            window.location.href = link.url;
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
