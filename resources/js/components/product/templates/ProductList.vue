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
            <span @click="selectAllProducts()" class="select-all">
                Selecteer alle {{ filteredProducts.length }} variatie(s)
            </span>
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
        <p v-else>Geen producten gevonden</p>

        <div class="product-table-footer">
            <span>
                {{ currentPagination.current_page }} van {{ currentPagination.last_page }} pagina's.
            </span>
            <div class="pagination">
                <span v-for="link in currentPagination.links" :key="link.label"
                      @click="changePagination(link)"
                      :class="['link', { 'active-link': link.active }]">
                    {{ link.label }}
                </span>
            </div>
        </div>

        <!-- Saleschannel Link Popup -->
        <div :class="['popup-container', { visible: isLinkSaleschannelPopupVisible }]">
            <div class="popup">
                <img @click="toggleSaleschannelsLinkPopup()" class="popup-close" src="/images/close-icon.svg"
                    alt="close-discount">
                <div class="saleschannels">
                    <div class="saleschannels-header">
                        Selecteer verkoopkanalen om te koppelen.
                    </div>
                    <div class="saleschannels-content">
                        <div v-for="saleschannel in saleschannels" :key="saleschannel.id" class="saleschannel">
                            <input :checked="isSalechannelSelected(saleschannel.id)" @change="toggleSaleschannel($event.target.checked, saleschannel.id)" type="checkbox">
                            {{ saleschannel.name }}
                        </div>
                    </div>
                </div>
                <div class="action-buttons">
                    <button @click="uploadProducts()" class="submit">
                        Koppel
                    </button>
                    <button @click="toggleSaleschannelsLinkPopup()" class="cancel">
                        Annuleren
                    </button>
                </div>
            </div>
        </div>

        <!-- Saleschannel Unlink Popup -->
        <div :class="['popup-container', { visible: isUnlinkSaleschannelPopupVisible }]">
            <div class="popup">
                <img @click="toggleSaleschannelsUnlinkPopup()" class="popup-close" src="/images/close-icon.svg"
                    alt="close-discount">
                <div class="saleschannels">
                    <div class="saleschannels-header">
                        Selecteer verkoopkanalen om te ontkoppelen.
                    </div>
                    <div class="saleschannels-content">
                        <div v-for="saleschannel in saleschannels" :key="saleschannel.id" class="saleschannel">
                            <input :value="saleschannel.id" type="checkbox">
                            {{ saleschannel.name }}
                        </div>
                    </div>
                </div>
                <div class="action-buttons">
                    <button class="submit">
                        Ontkoppel
                    </button>
                    <button @click="toggleSaleschannelsUnlinkPopup()" class="cancel">
                        Annuleren
                    </button>
                </div>
            </div>
        </div>

        <!-- Discount Popup -->
        <div :class="['popup-container', { visible: isDiscountPopupVisible }]">
            <div class="popup">
                <img @click="toggleDiscountPopup()" class="popup-close" src="/images/close-icon.svg"
                    alt="close-discount">
                <div class="discountform">
                    <div class="discount-header">
                        Korting
                    </div>
                    <div class="discount-content">
                        <div class="inputs">
                            <!-- Discount input fields go here -->
                        </div>
                        <div class="action-buttons">
                            <button @click="discountProducts()" class="submit">
                                Save
                            </button>
                            <button @click="toggleDiscountPopup()" class="cancel">
                                Annuleren
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent, inject } from 'vue';
import { format } from 'date-fns';
import axios from 'axios';
import '../../../../scss/product/templates/ProductList.scss';

export default defineComponent({
    props: {
        filteredProducts: {
            type: [Array, Object],
            required: true,
        },
        saleschannels: {
            type: [Array, Object],
            required: true,
        },
        pagination: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            selectedProducts: [],
            isLinkSaleschannelPopupVisible: false,
            isUnlinkSaleschannelPopupVisible: false,
            isDiscountPopupVisible: false,
            currentPagination: this.pagination, // Initialize with the prop value
            selectedSalesChannels: [],
        };
    },
    watch: {
        pagination: {
            handler(newPagination) {
                this.currentPagination = newPagination; // Sync with parent prop
            },
            immediate: true,
            deep: true,
        },
        filteredProducts(newFilteredProducts) {
            this.selectedProducts = this.selectedProducts.filter(productId =>
                newFilteredProducts.some(product => product.id === productId)
            );
            this.$emit('update:selectedProducts', this.selectedProducts);
        }
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
            this.$emit('update:selectedProducts', this.selectedProducts);
        },
        selectAllProducts() {
            this.selectedProducts = this.filteredProducts.map(product => product.id);
            this.$emit('update:selectedProducts', this.selectedProducts);
        },
        formatDate(date) {
            return format(new Date(date), 'yyyy-MM-dd HH:mm:ss');
        },
        changePagination(link) {
            this.$emit('update:pagination', link);
        },
        toggleSaleschannelsLinkPopup() {
            this.isLinkSaleschannelPopupVisible = !this.isLinkSaleschannelPopupVisible;
        },
        toggleSaleschannelsUnlinkPopup() {
            this.isUnlinkSaleschannelPopupVisible = !this.isUnlinkSaleschannelPopupVisible;
        },
        toggleDiscountPopup() {
            this.isDiscountPopupVisible = !this.isDiscountPopupVisible;
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
        deleteProducts() {
            const data = {
                selectedProducts: this.selectedProducts
            };
            console.log(data);


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
        discountProducts() {
            const data = {
                product_ids: this.selectedProducts,
                // Additional discount-related fields go here
            };

            if (this.selectedProducts.length === 0) {
                this.showUserMessage('No products selected', 'warning');
                return;
            }

            axios.post(this.route('products.bulkDiscount', data))
                .then(response => {
                    window.location.href = this.route('products.index');
                });
        },
        sendRequest(routeName, method, data, successMessage, errorMessage) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(this.route(routeName), {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken, // Include the CSRF token in the headers
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
        uploadProducts() {
            const data = {
                'sales_channel_ids': this.selectedSalesChannels,
                'product_ids': this.selectedProducts,
            };
            console.log(data);
            axios.post(this.route('products.bulkLinkSalesChannel'), data)
                .then(response => {
                    // Handle the response
                })
                .catch(error => {
                    // Handle the error
                });
        },
        toggleSaleschannel(checked, saleschannelId) {
            if (checked) {
                this.selectedSalesChannels.push(saleschannelId);
            } else {
                this.selectedSalesChannels = this.selectedSalesChannels.filter(id => id !== saleschannelId);
            }
        },
        isSalechannelSelected(saleschannelId) {
            return this.selectedSalesChannels.includes(saleschannelId);
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
    }
});
</script>
