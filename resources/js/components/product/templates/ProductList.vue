<template>
    <div class="product-container">
        <div class="product-table-header">
            <h1 class="title">Alle producten</h1>
            <div class="actions">
                <a class="button import">
                    <img src="/images/import.svg" alt="" class="icon">
                    Importeren
                </a>
                <a class="button export" :href="route('products.export')">
                    <img src="/images/export.svg" alt="" class="icon">
                    Exporteren
                </a>
            </div>
        </div>
        <div v-if="selectedProducts.length > 0" class="bulk-actions">
            {{ selectedProducts.length }} product(en) geselecteerd van de {{ filteredProducts.length }} producten.
            <span @click="SelectAllProducts()" class="select-all">Selecteer alle {{ filteredProducts.length }}
                variatie(s)</span>
            <div class="actions">
                <button @click="switchStatus()" class="button Status">Status veranderen</button>
                <button @click="updateArchived()" class="button Archived">Archiveren</button>
                <button @click="deleteProducts()" class="button Delete">Verwijderen</button>
                <button @click="toggleSaleschannelsLinkPopup()" class="button Link">Koppel</button>
                <button @click="toggleSaleschannelsUnlinkPopup()" class="button">Ontkoppel</button>
                <button @click="toggleDiscountPopup()" class="button">Korting</button>
            </div>
        </div>

        <div v-if="filteredProducts.length > 0" class="product-table">
            <a :href="route('products.show', product.id)" class="product" v-for="product in filteredProducts"
                :key="product.id">
                <div class="checkbox">
                    <input type="checkbox" :id="product.id" :value="product.id" :checked="isSelected(product.id)"
                        @change="toggleBulkSelection(product.id)">
                    {{ product.id }}
                </div>
                <div class="image">
                    <img v-for="photo in product.photos" :key="photo.id" :src="photo.url" alt="Product Photo">
                </div>
                
                <div class="ean">{{ product.sku }}</div>

                <div class="title">{{ product.title }}</div>
                <div class="price">
                    <span v-if="product.discount != null">
                        <span class="crossed">{{ product.price }}</span>
                        <strong class="price">{{ product.discount }}</strong>
                    </span>
                    <span v-else>
                        <strong class="price">{{ product.price }}</strong>
                    </span>
                </div>
                <div class="stock">
                    <span v-if="product.stock_quantity > 0">{{ product.stock_quantity }} Stuks</span>
                    <span v-else>Uitverkocht</span>
                </div>
                <div class="status">
                    <ul>
                        <li v-if="product.status == 1" class="on">Online</li>
                        <li v-else class="off">Offline</li>
                    </ul>
                </div>
                <div class="last-updated">
                    {{ formatDate(product.updated_at) }}
                </div>
            </a>
        </div>
        <p v-else> Geen producten gevonden</p>

        <div class="product-table-footer">
            asd
        </div>
        <div :class="['popup-container', {visable: isLinkSaleschannelPopupVisible}]">
            <div class="popup">
                <img @click="toggleSaleschannelsLinkPopup()" class="popup-close" src="/images/close-icon.svg" alt="close-discount">
                <div class="saleschannels">
                    <div class="saleschannels-header">
                        Selecteer verkoopkanalen om te koppelen.
                    </div>
                    <div class="saleschannels-content">
                        <div v-for="saleschannel in saleschannels" :key="saleschannel.id" class="saleschannel">
                            <input  :value="saleschannel.id" type="checkbox" >
                            {{saleschannel.name}}
                        </div>
                    </div>
                    
                </div>
                <div class="action-buttons">
                    <button class="submit">
                        koppel
                    </button>
                    <button @click="toggleSaleschannelsLinkPopup()" class="cancel">
                        Anuleren
                    </button>
                </div>
            </div>
            
        </div>
        <div :class="['popup-container', {visable: isUnlinkSaleschannelPopupVisible}]">
            <div class="popup">
                <img @click="toggleSaleschannelsUnlinkPopup()" class="popup-close" src="/images/close-icon.svg" alt="close-discount">
                <div class="saleschannels">
                    <div class="saleschannels-header">
                        Selecteer verkoopkanalen om te ontkoppelen.
                    </div>
                    <div class="saleschannels-content">
                        <div v-for="saleschannel in saleschannels" :key="saleschannel.id" class="saleschannel">
                            <input  :value="saleschannel.id" type="checkbox" >
                            {{saleschannel.name}}
                        </div>
                    </div>
                    
                </div>
                <div class="action-buttons">
                    <button class="submit">
                        ontkoppel
                    </button>
                    <button @click="toggleSaleschannelsUnlinkPopup()" class="cancel">
                        Anuleren
                    </button>
                </div>
            </div>
            
        </div>
        <div :class="['popup-container', {visable: isDiscountPopupVisible}]">
            <div class="popup">
                <img @click="toggleDiscountPopup()" class="popup-close" src="/images/close-icon.svg" alt="close-discount">
                <div class="discountform">
                    <div class="discount-header">
                        Korting
                    </div>
                    <div class="discount-content">
                        <div class="inputs">
                            <input class="textform" min="0" max="100" v-model.number="discountPercentage" type="number" placeholder="Kortingspercentage" >
                            <span class="checkbox"><input type="checkbox" v-model="decimalRound" id="decimalRound" value="1"><label class="label" for="decimalRound">Afronden op decimalen?</label></span>
                            <input class="textform" min="0" max="99" v-model.number="discountDecemals" type="number" placeholder="Decimalen" >
                        </div>
                        <div class="action-buttons">
                            <button @click="discountProducts()" class="submit">
                                Save
                            </button>
                            <button @click="toggleDiscountPopup()" class="cancel">
                                Anuleren
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent, inject, watch } from 'vue';
import { format } from 'date-fns';
import axios from 'axios';
import '../../../../scss/product/templates/ProductList.scss';

export default defineComponent({
    props: {
        filteredProducts: {
            type: [Array, Object],
            required: true,
        },
        saleschannels:{
            type: [Array, Object],
            required: true,
        }
    },
    data() {
        
        return {
            selectedProducts: [],
            isLinkSaleschannelPopupVisible:false,
            isUnlinkSaleschannelPopupVisible:false,
            isDiscountPopupVisible:false,
            selectedSaleschannels: [],
            
        };
    },
    methods: {
        isSelected(productId) {
            return this.selectedProducts.includes(productId);
        },
        toggleBulkSelection(productId) {
            if (this.isSelected(productId)) {
                this.selectedProducts = this.selectedProducts.filter(id => id !== productId);
            } else {
                this.selectedProducts.push(productId);
            }
            // Emit the updated selections to parent if needed
            this.$emit('update:selectedProducts', this.selectedProducts);
        },
        SelectAllProducts() {
            this.selectedProducts = this.filteredProducts.map(product => product.id);
            this.$emit('update:selectedProducts', this.selectedProducts);
        },
        formatDate(date) {
            return format(new Date(date), 'yyyy-MM-dd HH:mm:ss');
        },
        sendRequest(routeName, method, data, successMessage, errorMessage) {
            fetch(this.route(routeName), {
                method: method,
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (response.ok) {
                    this.showUserMessage(successMessage, 'success');
                    window.location.reload();
                } else {
                    this.showUserMessage(errorMessage, 'error');
                }
            })
            .catch(error => {
                this.showUserMessage('Network error', 'error');
            });
        },

        switchStatus() {
            const data = {
                selectedProducts: this.selectedProducts
            };

            if (this.selectedProducts.length === 0) {
                this.showUserMessage('No products selected', 'warning');
                return;
            }

            this.sendRequest(
                'products.switchStatus',
                'POST',
                data,
                'Status updated successfully',
                'Failed to change status'
            );
        },
        updateArchived() {
            const data = {
                selectedProducts: this.selectedProducts
            };

            if (this.selectedProducts.length === 0) {
                this.showUserMessage('No products selected', 'warning');
                return;
            }

            this.sendRequest(
                'products.archiveProducts',
                'POST',
                data,
                'Products archived successfully',
                'Failed to archive products'
            );
        },
        toggleSaleschannelsLinkPopup(){
            this.isLinkSaleschannelPopupVisible= !this.isLinkSaleschannelPopupVisible;
        },
        toggleSaleschannelsUnlinkPopup(){
            this.isUnlinkSaleschannelPopupVisible= !this.isUnlinkSaleschannelPopupVisible;   
        },
        toggleDiscountPopup(){
            this.isDiscountPopupVisible= !this.isDiscountPopupVisible;   
        },
        deleteProducts() {
            const data = {
                selectedProducts: this.selectedProducts
            };

            if (this.selectedProducts.length === 0) {
                this.showUserMessage('No products selected', 'warning');
                return;
            }

            this.sendRequest(
                'products.deleteProducts',
                'DELETE',
                data,
                'Products deleted successfully',
                'Failed to delete products'
            );
        },
        uploadProducts() {
            const data = {
                product_ids: this.selectedProducts,
                
            };

            if (this.selectedProducts.length === 0) {
                this.showUserMessage('No products selected', 'warning');
                return;
            }

            console.log(data);

            axios.post(this.route('products.bulkLinkSalesChannel', data))
            .then(response => {
            window.location.href = this.route('products.index');
            });
        },
        discountProducts(){
            const data = {
                product_ids: this.selectedProducts,
                discount: this.discountPercentage,
                cents: this.decimalRound ? this.discountDecemals : null,
                round: this.decimalRound ? true : false,
            };

            if (this.selectedProducts.length === 0) {
                this.showUserMessage('No products selected', 'warning');
                return;
            }
            console.log(data);

            axios.post(this.route('products.bulkDiscount', data))
            .then(response => {
            window.location.href = this.route('products.index');
            });

        },

        showUserMessage(message, type) {
            // You can use a library like toastr for better UI feedback
            // console.info(`[${type.toUpperCase()}] ${message}`);
        }
    },
    setup() {
        const route = inject('route'); // Injecting route helper
        return {
            route,
        };
    },
    watch: {
        filteredProducts(newFilteredProducts) {
            this.selectedProducts = this.selectedProducts.filter(productId =>
                newFilteredProducts.some(product => product.id === productId)
            );
            this.$emit('update:selectedProducts', this.selectedProducts);
        }
    }
});
</script>
