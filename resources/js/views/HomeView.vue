<script setup>
import { ref, onMounted, computed } from 'vue'

import { useCategoryStore } from '@/stores/category.store.js';
    import { useProductStore } from '@/stores/product.store.js'
    import AddToOrderButton from '@/components/AddToOrderButton.vue';

    import HeaderUserComponent from '@/components/HeaderUserComponent.vue'
    import SliderComponent from '@/components/SliderComponent.vue'

    const categoryStore = useCategoryStore();
    const productStore = useProductStore();

    const categories = computed( () => categoryStore.categories);
    const products = computed( () => productStore.products);
    const meta = computed(() => productStore.meta);

    const isLoading = ref(false)
    const loadMoreTrigger = ref(null)

    onMounted(() => {
        categoryStore.fetchCategories();

        fetchProducts()

        const observer = new IntersectionObserver(
            (entries) => {
                if (entries[0].isIntersecting && meta.value.next_cursor) {
                    fetchProducts()
                }
            },
            { rootMargin: '100px' }
        )

        if (loadMoreTrigger.value) {
            observer.observe(loadMoreTrigger.value)
        }

    });

    const selectedCategories = ref([]);

    const selectedProducts = computed(() => {
        if (selectedCategories.value.length === 0) {
            return products.value;
        } else {
            return products.value.filter(product => selectedCategories.value.includes(product.category_id));
        }
    });

    const toggleSelection = (id) => {
        if (selectedCategories.value.includes(id)) {
            selectedCategories.value = selectedCategories.value.filter(catId => catId !== id);
        } else {
            selectedCategories.value.push(id);
        }
    }

    const fetchProducts = async () => {
        if (isLoading.value) {
            return
        }

        isLoading.value = true

        try {
            await productStore.fetchProducts(meta?.value?.next_cursor ?? null)
        } finally {
            isLoading.value = false
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
    position: relative;
}

.observer-trigger {
    height: 1px;
    width: 100%;
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

.v-overlay {
    backdrop-filter: blur(5px);
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
                        <v-col cols=12 v-for="product in selectedProducts" :key="product.id" class="product-card m-2">
                            <v-hover>
                                <template v-slot:default="{ isHovering, props }">
                                    <v-card
                                        class="product-card-item"
                                        :key="product.id"
                                        v-bind="props"
                                        :style="{ backgroundImage: 'url(' + product?.firstImage?.presets?.big + ')' }"
                                    >
                                        <div class="card-content">
                                            <div class="product-name-container">
                                                {{ product.name }}
                                            </div>
                                            <div class="price-container">
                                                {{ product.price }}
                                            </div>
                                        </div>
                                        <v-expand-transition>
                                            <div
                                                v-if="isHovering"
                                                class="d-flex transition-fast-in-fast-out bg-grey-lighten-2 v-card--reveal text-h2"
                                                style="height: 30%;"
                                            >
                                                <AddToOrderButton :product="product" />
                                                <v-btn icon>
                                                    <router-link :to="{ name: 'Product', params: { id: product.id } }" color="white"
                                                                 class="text-black hover-orange"><v-icon size="small" color="black">mdi-pizza</v-icon></router-link>
                                                </v-btn>
                                            </div>
                                        </v-expand-transition>
                                    </v-card>
                                </template>
                            </v-hover>
                        </v-col>
                    </v-row>
                    <div ref="loadMoreTrigger" class="observer-trigger"></div>
                    <v-overlay v-model="isLoading" class="d-flex align-center justify-center">
                        <v-progress-circular indeterminate color="primary" size="64" />
                    </v-overlay>
                </div>
            </v-col>
        </v-row>
    </v-container>
</template>
