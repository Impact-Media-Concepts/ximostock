<template>
    <div class="product-images">
        <div class="content fotos">
            <section>
                <span>Foto's</span>
                <div class="form-group">

                    <label for="file-upload">Upload Photo:</label>
                    <input type="file" name="file-upload" @change="handleFileUpload">

                    <div class="foto-grid">
                        <div v-for="photo in localProduct.photos" :key="photo.id" class="foto">
                            <img :src="photo.url" alt="Product Image">
                            <span @click="deletePhoto(photo.id)" class="delete">X</span>
                        </div>
                    </div>


                </div>
            </section>
        </div>
    </div>
</template>

<script>
import { inject } from 'vue';
import axios from 'axios';

export default {
    props: {
        product: {
            type: Object,
        }
    },
    data() {
        return {
            localProduct: { ...this.product }
        }
    },
    watch: {
        localProduct: {
            handler(newData) {
                this.$emit('updateProduct', newData);
            },
            deep: true
        }
    },
    methods: {
        // handleFileUpload(event) {
        //     var file = event.target.files[0];

        //     if (!file) {
        //         return;
        //     }

        //     var formData = new FormData();
        //     formData.append('file', file);
        //     formData.append('product_id', this.localProduct.id); // Make sure this.productId is set to the correct product ID

        //     axios.post(this.route('photos.store'), formData, {
        //         headers: {
        //             'Content-Type': 'multipart/form-data',
        //         },
        //     })
        //         .then(response => {
        //             this.localProduct.photos.push(response.data);
        //         });

        // },
    },
    setup() {
        const route = inject('route'); // Injecting route helper
        return {
            route,
        };
    }
};
</script>