import { defineStore } from 'pinia';
import productService from '@/services/product.service.js'

export const useProductStore = defineStore('product', {
    state: () => ({
        products: [],
    }),
    actions: {
        async fetchProducts() {
            try {
                this.products = await productService.getProducts();
            }
            catch(error) {
                console.log(error);
            }
        },

        async show(id) {
            return await productService.show(id);
        },
    },
});
