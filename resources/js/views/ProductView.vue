<template>
    <v-container class="position-relative header-container">
        <v-row>
            <v-col>
                <HeaderUserComponent />
            </v-col>
        </v-row>
    </v-container>
    <v-container class="product-view">
        <v-row align="start" justify="center" dense>
            <v-col cols="12" md="4" class="text-center">
                <v-img
                    :src="product?.firstImage?.presets.actual_small"
                    alt="Product Image"
                    max-height="300"
                    contain
                ></v-img>
            </v-col>

            <v-col cols="12" md="8">
                <h1>{{ product?.name || 'Product Name' }}</h1>
                <p>{{ product?.description || 'This is a product description.' }}</p>
                <p class="text-h6 font-weight-bold success--text">
                    {{ product?.price ? `$${product.price}` : 'Price not available' }}
                </p>
                <p class="text-h6 font-weight-bold success--text" v-if="product?.id">
                    <AddToOrderButton :product="product" />
                </p>
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useProductStore } from '@/stores/product.store.js';
import HeaderUserComponent from '@/components/HeaderUserComponent.vue'
import AddToOrderButton from '@/components/AddToOrderButton.vue'

const route = useRoute();
const productStore = useProductStore();

const product = ref(null);

onMounted(async () => {
    const productId = route.params.id;
    product.value = await productStore.show(productId);
});
</script>

