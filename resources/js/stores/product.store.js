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

        async store(item) {
            const storedItem = await productService.store(item);
            this.products.push(storedItem);
        },

        async update(index, item) {
            this.products[index] = await productService.update(item);
        },

        async deleteItem(id) {
            await productService.deleteItem(id);
            this.products = this.products.filter(product => product.id !== id);
        },

        async bulkDelete(ids) {
            await productService.bulkDelete(ids);
            this.products = this.products.filter(product => !ids.includes(product.id));
        },

        async uploadImages(itemId, formData) {
            try {
                const product = await productService.uploadImages(itemId, formData);

                const updatedProduct = this.products.find(product => product.id === itemId);

                if (updatedProduct) {
                    Object.assign(updatedProduct, product);
                }

                return product;
            } catch (error) {
                console.error('Failed to upload images', error);
                throw error;
            }
        }
    },
});
