import { defineStore } from 'pinia';
import adminProductService from '@/services/admin.product.service.js'

export const useAdminProductStore = defineStore('adminProduct', {
    state: () => ({
        products: [],
    }),
    actions: {
        async fetchProducts(params) {
            try {
                const result = await adminProductService.getProducts(params);
                this.products = result.items;
            }
            catch(error) {
                console.log(error);
            }
        },

        async show(id) {
            return await adminProductService.show(id);
        },

        async store(item) {
            const storedItem = await adminProductService.store(item);
            this.products.push(storedItem);
        },

        async update(index, item) {
            this.products[index] = await adminProductService.update(item);
        },

        async deleteItem(id) {
            await adminProductService.deleteItem(id);
            this.products = this.products.filter(product => product.id !== id);
        },

        async bulkDelete(ids) {
            await adminProductService.bulkDelete(ids);
            this.products = this.products.filter(product => !ids.includes(product.id));
        },

        async uploadImages(itemId, formData) {
            try {
                const product = await adminProductService.uploadImages(itemId, formData);

                const updatedProduct = this.products.find(product => product.id === itemId);

                if (updatedProduct) {
                    Object.assign(updatedProduct, product);
                }

                return product;
            } catch (error) {
                console.error('Failed to upload images', error);
                throw error;
            }
        },

        async setImageToFirst(productId, image) {
            await adminProductService.setImageToFirst(productId, image.id)

            const productIndex = this.products.findIndex((p) => p.id === productId);

            if (productIndex !== -1) {
                const product = { ...this.products[productIndex] };
                console.log(product);

                product.images.forEach((image) => {
                    image.first = 0;
                });

                const targetImageIndex = product.images.findIndex((img) => img.id === image.id);

                if (targetImageIndex !== -1) {
                    product.images[targetImageIndex].first = 1;
                    this.products.splice(productIndex, 1, product);
                }
            }
        },

        async deleteImage(productId, image) {
            await adminProductService.deleteImage(productId, image.id)

            const productIndex = this.products.findIndex((p) => p.id === productId);

            if (productIndex !== -1) {
                const product = { ...this.products[productIndex] };
                console.log(product);

                product.images.forEach((image) => {
                    image.first = 0;
                });

                const targetImageIndex = product.images.findIndex((img) => img.id === image.id);

                if (targetImageIndex !== -1) {
                    product.images.splice(targetImageIndex, 1);
                    this.products.splice(productIndex, 1, product);
                }
            }
        }
    },
});
