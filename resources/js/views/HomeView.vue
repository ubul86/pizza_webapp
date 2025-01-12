<script setup>
    import { ref, computed, onMounted } from 'vue';
    import { useCategoryStore } from '@/stores/category.store.js';
    import { useProductStore } from '@/stores/product.store.js'
    import { useUserOrderStore } from '@/stores/user.order.store.js';

    import HeaderUserComponent from '@/components/HeaderUserComponent.vue'
    import SliderComponent from '@/components/SliderComponent.vue'

    const categoryStore = useCategoryStore();
    const productStore = useProductStore();
    const orderStore = useUserOrderStore();

    const categories = computed( () => categoryStore.categories);
    const products = computed( () => productStore.products);

    onMounted(() => {
        categoryStore.fetchCategories();
        productStore.fetchProducts();
    });

    const selectedCategories = ref([]);

    const selectedProducts = computed(() => {
        if (selectedCategories.value.length === 0) {
            return products.value;
        } else {
            return products.value.filter(product => selectedCategories.value.includes(product.category_id));
        }
    });

    const addToOrder = (product) => {
        orderStore.addItem(product);
    };

    const toggleSelection = (id) => {
        if (selectedCategories.value.includes(id)) {
            selectedCategories.value = selectedCategories.value.filter(catId => catId !== id);
        } else {
            selectedCategories.value.push(id);
        }
    }
</script>

<style>
.header-container {
    z-index: 10;
}

.active {
    background-color: #1976d2 !important;
    color: white !important;
}

.products-container {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
}

.product-card .v-card {
    width: 100% !important;
    height: 225px !important;
    border: 1px solid #ccc;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    background: transparent url('/images/pizza1.png') center center no-repeat;
    background-color: #f5f5f5;
    background-size: 80%;
}

.product-card .product-card-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    width: 100%;
}

.product-name-container {
    position: absolute;
    top: 10px;
    right: 0px;
    background-color: rgba(244, 219, 165, 0.9);
    padding: 5px 10px;
    border-radius: 5px;
    min-width: 205px;
    height: 50px;
    display: flex;
    align-items: center;
    font-weight: bold;
    color: #333;
    text-align: left;
}

.price-container {
    position: absolute;
    top: 50px;
    right: 10px;
    background-color: #FF7202;
    padding: 5px 10px;
    border-radius: 5px;
    min-width: 15px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    font-weight: bold;
    color: #fff;
    box-shadow: 0 3px 4px #aaa;
}

.product-name-text {
    text-align: right;
}

</style>

<template>
    <v-container class="position-relative header-container">
        <v-row>
            <v-col>
                <HeaderUserComponent />
            </v-col>
        </v-row>
    </v-container>
    <SliderComponent />
    <v-container>
        <v-row>
            <v-col>
                <div class="categories-container d-flex align-center">
                    <div>Categories:</div>
                    <div v-for="category in categories" :key="category.id" class="pl-2 pr-2">
                        <v-chip
                            :class="{ active: selectedCategories.includes(category.id) }"
                            @click="toggleSelection(category.id)"
                        >
                            {{ category.name }}
                        </v-chip>
                    </div>
                </div>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <div class="products-container">
                    <v-row>
                        <v-col cols=12 sm="4" md="4" lg="4" v-for="product in selectedProducts" :key="product.id" class="product-card m-2">
                            <v-card
                                class="product-card-item"
                                :key="product.id"
                                @click="addToOrder(product)"
                                :style="{ backgroundImage: 'url(' + product.firstImage?.path + ')' }"
                            >
                                <div class="card-content">
                                    <div class="product-name-container">
                                        {{ product.name }}
                                    </div>
                                    <div class="price-container">
                                        {{ product.price }}
                                    </div>
                                </div>
                            </v-card>
                        </v-col>
                    </v-row>
                </div>
            </v-col>
        </v-row>
    </v-container>
</template>
