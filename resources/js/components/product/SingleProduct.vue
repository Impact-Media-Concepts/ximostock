<template>
  <div class="product-view">
    <div class="product-type-toggle">
      <h4 class="title">Type product</h4>
      <label class="switch">
        <input type="checkbox" @change="toggleProductType" :checked="this.checked">
        <span class="slider round"></span>
        <span class="type">{{ this.productData.type }}</span>
      </label>
    </div>
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
        <div class="form-group" v-if="!this.checked">
          <label for="sku">SKU:</label>
          <input type="text" v-model="productData.sku" id="sku">
        </div>
        <div class="form-group" v-if="!this.checked">
          <label for="ean">EAN:</label>
          <input type="number" v-model="productData.ean" id="ean">
        </div>
      </div>
    </div>

    <div class="product-content">
      <div class="product-tabs">
        <div class="tab-heading">
          <span class="heading" v-for="(tab, index) in filteredTabs" :key="index" @click="activeTab = index"
            :class="{ active: activeTab === index }">
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
                  <input @input="FilterAvailableCategories()" v-model="filterInputText" type="text"
                    placeholder="Typ hier waarop u de lijst wil filteren">
                  <ul>
                    <li v-for="category in filteredCategories" :key="category.id">
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
                    <li v-for="category in productData.categories" :key="category.id">
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
                  <!-- <button @click="addExistingProperty">Bestaande toevoegen</button> -->
                </div>
              </section>
              <section>
                <span>Eigenschappen</span>
              </section>
              <section v-for="property in this.productData.properties" :key="property.id">
                <span>
                  <strong> {{ property.name }} </strong>
                  <small>type: {{ property.options.type }}</small>
                  <img @click="removeProperty(property)" src="/images/cross-white.svg" alt="" class="remove">
                </span>
                <div class="form-group">
                  <label for="title">Waarden:</label>
                  {{ property.pivot.property_value }}
                  <input type="text" v-model="property.pivot.property_value" @input="updatePropertyValue(property)" />
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
          <div class="content-container" v-if="activeTab === 5 && checked === true">
            <div class="title">
              <h4>Variaties</h4>
            </div>
            <div class="content variaties">
              <section>
                <span>Acties</span>
                <div class="form-group">
                  <button @click="addNewVariation()">Nieuwe toevoegen</button>
                </div>
              </section>
              <section v-for="variation in this.productData.child_products">
                <span>{{ variation.title }}</span>
                <div class="form-group">
                  <h3>Id: {{ variation.id }}</h3>
                  <h3>Parent: {{ variation.parent_product_id }}</h3>
                  <h3>Sku: {{ variation.sku }}</h3>
                  <h3>Ean: {{ variation.ean }}</h3>
                  <h3>Price: {{ variation.price }}</h3>
                  <h3>Discount: {{ variation.discount }}</h3>
                  <h3>Stock Quantity: {{ variation.stock_quantity }}</h3>
                  <h3>Backorders: {{ variation.backorders }}</h3>
                  <h3>Status: {{ variation.status }}</h3>
                  <div v-for="property in variation.properties">
                    <span>name: {{ property.name }}</span><br>
                    <span>value:{{ property.pivot.property_value }}</span>
                    <input type="text" v-model="property.pivot.property_value">
                  </div>
                </div>
              </section>
            </div>
          </div>
          <div class="content-container" v-if="activeTab === 6">            
            <div class="title">
              <h4>Voorraad beheren</h4>
            </div>
            <div class="content voorraad">
              <section>
                <span>Huidige status</span>
                <div class="form-group">
                  <div>
                    <label for="stock_quantity">Stock Quantity:</label>
                    <input type="number" v-model="productData.stock_quantity" id="stock_quantity">
                  </div>
                  <div>
                    <label for="backorders">Backorders:</label>
                    <input type="checkbox" v-model="productData.backorders" id="backorders"
                      :checked="productData.backorders">
                  </div>
                </div>
              </section>
            </div>
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
      filteredCategories: Object.values(this.categories),
      productData: this.product,
      activeTab: 0,
      tabs: {
        0: 'Informatie',
        1: 'Foto\'s',
        2: 'Categorieën',
        3: 'Eigenschappen',
        4: 'Verkoopkanalen',
        5: 'Variaties',
        6: 'Voorraad'
      },
      filterInputText: '',
      errors: null,
      checked: false
    };
  },
  computed: {
    filteredTabs() {
      // Convert the tabs object to an array of keys
      const tabKeys = Object.keys(this.tabs);

      // Find the key of the 'Variaties' tab in the tabs object
      const variatiesKey = tabKeys.find(key => this.tabs[key] === 'Variaties');

      // If the 'Variaties' tab is currently active and checked is false, reset activeTab to 0
      if (!this.checked && this.activeTab == variatiesKey) {
        this.activeTab = 0;
      }

      // If checked is false, filter out the 'Variaties' tab
      if (!this.checked) {
        return tabKeys.filter(key => this.tabs[key] !== 'Variaties').map(key => this.tabs[key]);
      }

      // Otherwise, return the original tabs array
      return tabKeys.map(key => this.tabs[key]);
    }
  },
  methods: {
    save() {
      // Create a copy of productData to modify
      let productDataToSend = JSON.parse(JSON.stringify(this.productData));
      productDataToSend.categories = this.productData.categories.map(category => category.id);

      // Stringify property values
      productDataToSend.properties = this.productData.properties.map(property => ({
        id: property.id,
        name: property.name,
        pivot: {
          property_value: JSON.stringify({ value: property.pivot.property_value }),
        }
      }));

      // Stringify child product property values
      productDataToSend.child_products.map(childProduct => {
        childProduct.properties = childProduct.properties.map(property => ({
          id: property.id,
          name: property.name,
          pivot: {
            property_value: JSON.stringify({ value: property.pivot.property_value }),
          }
        }));
      });

      console.log('Product data to send', productDataToSend);


      axios.put(this.route('products.update', this.productData.id), productDataToSend)
        .then(response => {

          this.productData = response.data.product;
          this.parsePropertyValues();

          this.errors = null; // Reset errors
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
        // Remove category from available
        this.availableCategories = this.availableCategories.filter(c => c.id !== category.id);
        this.filteredCategories = this.filteredCategories.filter(c => c.id !== category.id);
        // Add category to selected
        this.productData.categories.push(category);
      } else {
        this.productData.categories = this.productData.categories.filter(c => c.id !== category.id);
        this.filteredCategories.push(category);
        this.availableCategories.push(category);
      }
    },
    FilterAvailableCategories() {
      console.log('Filtering categories', this.filterInputText);
      // Filter results into the new array
      this.filteredCategories = this.availableCategories.filter(category =>
        category.name.toLowerCase().includes(this.filterInputText.toLowerCase())
      );
    },
    updatePropertyValue(property) {
      console.log('Updating property value', property);
    },
    removeProperty(property) {
      this.productData.properties = this.productData.properties.filter(p => p !== property);
    },
    addNewProperty() {
      var currentPropertyLength = this.productData.properties.length - 1;
      if (this.productData.properties[currentPropertyLength]) {
        var newProperty = JSON.parse(JSON.stringify(this.productData.properties[currentPropertyLength]));
        newProperty.name = newProperty.name + ' (copy)';
        newProperty.id = null;
        this.productData.properties.push(newProperty);
      } else {
        this.productData.properties.push({
          id: null,
          name: 'Nieuwe eigenschap',
          options: {
            type: 'text',
          },
          pivot: {
            property_value: '',
          },
        });
      }
    },
    toggleProductType() {
      this.productData.type = this.productData.type == 'variable' ? 'simple' : 'variable';
      this.checked = !this.checked;
      this.productData.sku = '';
      this.productData.ean = '';
    },
    addNewVariation() {

      // Create a non-reference copy of properties to add to child product
      var propertiesToAdd = JSON.parse(JSON.stringify(this.productData.properties));
      propertiesToAdd.forEach(property => {
        property.id = null;
      });

      // Create a new child product
      var newChildProduct = {
        id: null,
        work_space_id: this.productData.work_space_id,
        parent_product_id: this.productData.id,
        type: 'simple',
        sku: '',
        ean: '',
        title: 'Nieuwe title',
        short_description: this.productData.short_description,
        long_description: this.productData.long_description,
        price: this.productData.price,
        discount: this.productData.discount,
        backorders: this.productData.backorders,
        stock_quantity: 0,
        created_at: new Date(),
        updated_at: new Date(),
        properties: propertiesToAdd, // Add properties to child product
      };

      // Add new child product to product data
      this.productData.child_products.push(newChildProduct);

    },
    parsePropertyValues() {
      this.productData.properties.forEach(property => {
        property.pivot.property_value = this.parsePropertyValue(property.pivot.property_value);
      });

      this.productData.child_products.forEach(childProduct => {
        childProduct.properties.forEach(property => {
          property.pivot.property_value = this.parsePropertyValue(property.pivot.property_value);
        });
      });
    },
    parsePropertyValue(value) {
      try {
        return JSON.parse(value).value;
      } catch (e) {
        return value;
      }
    },
  },
  setup() {
    const route = inject('route'); // Injecting route helper
    return {
      route,
    };
  },
  mounted() {
    console.log('Product data', this.productData);

    this.parsePropertyValues();

    this.productData.backorders = this.productData.backorders === 1 ? true : false;
    this.checked = this.productData.type === 'simple' ? false : true;
  },
});
</script>