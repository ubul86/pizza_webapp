import { defineStore } from 'pinia';
import productService from '@/services/product.service.js'

export const useProductStore = defineStore('product', {
    state: () => ({
        products: [],
        meta: [],
    }),
    actions: {
        async fetchProducts(params) {
            try {
                const result = await productService.getProducts(params);
                this.products.push(...result.items);
                this.meta = result.meta;
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
