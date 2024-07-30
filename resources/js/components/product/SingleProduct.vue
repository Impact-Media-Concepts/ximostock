<template>
  <div class="product-view">
    <div class="main-product-info">
      <div class="image-container">
        <img v-if="productData.photos.length" :src="productData.photos[0].url" alt="Product Image">
      </div>
      <div class="form-container">
        <div class="form-group row">
          <label for="title">Title:</label>
          <input type="text" v-model="productData.title" id="title">
        </div>
        <div class="form-group">
          <label for="price">Price:</label>
          <input type="number" v-model="productData.price" id="price" step="0.01">
        </div>
        <div class="form-group">
          <label for="discount">Discount:</label>
          <input type="number" v-model="productData.discount" id="discount" step="0.01">
        </div>
        <div class="form-group">
          <label for="sku">SKU:</label>
          <input type="text" v-model="productData.sku" id="sku">
        </div>
        <div class="form-group">
          <label for="ean">EAN:</label>
          <input type="number" v-model="productData.ean" id="ean">
        </div>
      </div>
    </div>

    <div class="product-content">
      <div class="product-tabs">
        <div class="tab-heading">
          <span
            class="heading"
            v-for="(tab, index) in tabs"
            :key="index"
            @click="activeTab = index"
            :class="{ active: activeTab === index }"
          >
            {{ tab }}
          </span>
        </div>
        <div class="tab-content">
          <div class="content-container" v-if="activeTab === 0">
            <div class="title">
              <h4>Informatie</h4>
            </div>
            <div class="content informatie">
              <section>
                <span>informatie</span>
                <div class="form-group">
                  <label for="title">Title:</label>
                  <input type="text" v-model="productData.title" id="title">
                  <label for="price">Price:</label>
                  <input type="number" v-model="productData.price" id="price" step="0.01">
                  <label for="discount">Discount:</label>
                  <input type="number" v-model="productData.discount" id="discount" step="0.01">
                </div>
              </section>
              <section>
                <span>Lange beschrijving</span>
                <div class="form-group">
                  <textarea v-model="productData.long_description" id="title"></textarea>
                </div>
              </section>
              <section>
                <span>Korte beschrijving</span>
                <div class="form-group">
                  <textarea v-model="productData.short_description" id="title"></textarea>
                </div>
              </section>
            </div>
          </div>
          <div class="content-container" v-if="activeTab === 1">
            <div class="title">
              <h4>Foto's</h4>
            </div>
            <div class="content fotos">
              <section>
                <span>Foto's</span>
                <div class="form-group">

                  <label for="file-upload">Upload Photo:</label>
                  <input type="file" name="file-upload" @change="handleFileUpload">
                  
                  <div class="foto-grid">
                    <div v-for="photo in productData.photos" :key="photo.id" class="foto">
                      <img :src="photo.url" alt="Product Image">
                      <span @click="deletePhoto(photo.id)" class="delete">X</span>
                    </div>
                  </div>
                  

                </div>
              </section>
            </div>
          </div>
          <div class="content-container" v-if="activeTab === 2">
            <div class="title">
              <h4>Categorieën</h4>
            </div>
            <div class="content categorieen"> 

              <section>
                <span>Beschikbare categorieën</span>
                <div class="form-group">
                  <input @input="FilterAvailableCategories()" v-model="filterInputText" type="text" placeholder="Typ hier waarop u de lijst wil filteren">
                  <ul>
                    <li v-for="category in availableCategories" :key="category.id">
                      <input type="checkbox" :value="category.id" @change="moveCategory(category, 'available')">
                      {{ category.name }}
                    </li>
                  </ul>
                </div>
            </section>

            <section>
              <span>Geselecteerde categorieën</span>
              <div class="form-group">
                <ul>
                  <li v-for="category in product.categories" :key="category.id">
                    <input type="checkbox" :value="category.id" @change="moveCategory(category, 'selected')" checked>
                    {{ category.name }}
                  </li>
                </ul>
              </div>
            </section>

            </div>
          </div>
          <div class="content-container" v-if="activeTab === 3">
            <div class="title">
              <h4>Eigenschappen</h4>
            </div>
            <div class="content eigenschappen">
              <section>
                <span>Acties</span>
                <div class="form-group">
                  <button @click="addNewProperty">Nieuwe toevoegen</button>
                  <button @click="addExistingProperty">Bestaande toevoegen</button>
                </div>
              </section>
              <section>
                <span>Eigenschappen</span>
              </section>
              <section v-for="property in this.productData.properties" :key="property.id">
                <span>
                  <strong> {{ property.name }} </strong>
                  <small>type: {{ property.values.type }}</small>
                </span>
                <div class="form-group">
                  <label for="title">Waarden:</label>
                  {{ property.pivot.property_value.value }}
                  <input 
                    type="text"
                    v-model="property.pivot.property_value"
                    @input="updatePropertyValue(property)"
                  />
                </div>
              </section>
            </div>
          </div>
          <div class="content-container" v-if="activeTab === 4">
            <div class="title">
              <h4>Verkoopkanalen</h4>
            </div>
            <div class="content verkoopkanalen"></div>
          </div>
          <div class="content-container" v-if="activeTab === 5">
            <div class="title">
              <h4>Variaties</h4>
            </div>
            <div class="content variaties"></div>
          </div>
          <div class="content-container" v-if="activeTab === 6">
            <div class="title">
              <h4>Voorraad</h4>
            </div>
            <div class="content voorraad"></div>
          </div>
        </div>
      </div>
      <div class="product-actions">
        <button @click="save" class="save">Opslaan</button>
        <button @click="duplicate" class="dupliceren">Dupliceren</button>
        <button @click="exportProduct" class="export">Export</button>
        <button @click="archiveProduct" class="archive">Archiveer</button>
        <button @click="deleteProduct" class="delete">Verwijderen</button>
      </div>
    </div>

    <general-notification v-if="errors" :errors="errors"></general-notification>

  </div>
</template>

<script>
import { defineComponent, inject } from 'vue';
import axios from 'axios';
import '../../../scss/product/SingleProduct.scss';

export default defineComponent({
  props: {
    product: {
      type: Object,
      default: () => ({
        parent_product_id: null,
        sku: '',
        ean: null,
        title: '',
        short_description: '',
        long_description: '',
        price: null,
        discount: null,
        backorders: false,
        stock_quantity: 0,
        status: false,
        archived: false,
        photos: [],
        categories: [],
        properties: [],
        salesChannels: [],
        childProducts: [],
      }),
      required: true,
    },
    categories: {
      type: Object,
      default: () => [],
    },
  },
  data() {
    return {
      availableCategories: Object.values(this.categories),
      productData: this.product,
      activeTab: 3,
      tabs: ['Informatie', 'Foto\'s', 'Categorieën', 'Eigenschappen', 'Verkoopkanalen', 'Variaties', 'Voorraad'],
      filterInputText: '',
      errors: 0 // Add this line to handle errors
    };
  },
  methods: {
    save() {
      // Create a copy of productData to modify
      let productDataToSend = { ...this.productData };
      productDataToSend.categories = this.productData.categories.map(category => category.id);

      // Stringify property values and ensure work_space_id is included
      productDataToSend.properties = this.productData.properties.map(property => ({
        id: property.id,
        pivot: {
          property_value: JSON.stringify({ value: property.pivot.property_value }),
        }
      }));

      console.log('Product data to send', productDataToSend);

      axios.put(this.route('products.update', this.productData.id), productDataToSend)
        .then(response => {
          console.log('Product saved');
          this.errors = 0; // Reset errors
        })
        .catch(error => {
          if (error.response) {
            this.errors = error.response.data.errors;
          }
        });
    },
    duplicate() {
      axios.post(this.route('products.duplicate', this.productData.id))
        .then(response => {
          window.location.href = this.route('products.index');
        });
    },
    exportProduct() {
      axios.get(this.route('products.exportByid', this.productData.id))
        .then(response => {
          const url = `${window.location.origin}/storage/${response.data.filename}`;
          window.open(url);
        });
    },
    archiveProduct() {
      axios.put(this.route('products.archive', this.productData.id))
        .then(response => {
          window.location.href = this.route('products.index');
        });
    },
    deleteProduct() {
      axios.delete(this.route('products.delete', this.productData.id))
        .then(response => {
          window.location.href = this.route('products.index');
        });
    },
    handleFileUpload(event) {
      var file = event.target.files[0];

      if (!file) {
        return;
      }

      var formData = new FormData();
      formData.append('file', file);
      formData.append('product_id', this.productData.id); // Make sure this.productId is set to the correct product ID

      axios.post(this.route('photos.store'), formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      })
      .then(response => {
        this.productData.photos.push(response.data);
      });
  
    },
    deletePhoto(photoId) {
      axios.delete(this.route('photos.delete', photoId))
        .then(response => {
          this.productData.photos = this.productData.photos.filter(photo => photo.id !== photoId);
        });
    },
    moveCategory(category, from) {
      if (from === 'available') {
        this.availableCategories = this.availableCategories.filter(c => c.id !== category.id);
        this.product.categories.push(category);
      } else {
        this.product.categories = this.product.categories.filter(c => c.id !== category.id);
        this.availableCategories.push(category);
      }
    },
    FilterAvailableCategories() {
      console.log('Filtering categories', this.filterInputText);
      this.availableCategories = Object.values(this.categories).filter(category => category.name.toLowerCase().includes(this.filterInputText.toLowerCase()));
    },
    updatePropertyValue(property) {
      console.log('Updating property value', property);
    },
    parsePropertyValue(value) {
      try {
        return JSON.parse(value).value;
      } catch (e) {
        return value;
      }
    }
  },
  setup() {
    const route = inject('route'); // Injecting route helper
    return {
      route,
    };
  },
  mounted() {
    this.productData.properties.forEach(property => {
      property.values = JSON.parse(property.values);
      property.pivot.property_value = this.parsePropertyValue(property.pivot.property_value);
    });
  },
});
</script>

<style scoped>
/* Add your scoped styles here */
</style>
