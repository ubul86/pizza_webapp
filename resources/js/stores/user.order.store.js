import { defineStore } from 'pinia';
import OrderService from '@/services/order.service.js'

export const useUserOrderStore = defineStore('userOrder', {
    state: () => ({
        items: JSON.parse(localStorage.getItem('userOrderItems')) || [],
        name: null,
        email_address: null,
        phone_number: null,
    }),
    actions: {
        saveToLocalStorage() {
            localStorage.setItem('userOrderItems', JSON.stringify(this.items));
        },

        addItem(product) {
            const existingItem = this.items.find((item) => item.item.id === product.id);

            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                this.items.push({
                    item: product,
                    quantity: 1,
                });
            }

            this.saveToLocalStorage();
        },

        removeItemAll(product) {
            this.items = this.items.filter((item) => item.item.id !== product.id);
            this.saveToLocalStorage();
        },

        removeItem(product) {
            const existingItem = this.items.find((item) => item.item.id === product.id);

            if (existingItem) {
                if (existingItem.quantity > 1) {
                    existingItem.quantity -= 1;
                } else {
                    this.items = this.items.filter((item) => item.item.id !== product.id);
                }
            }

            this.saveToLocalStorage();
        },

        emptyItems() {
            this.items = [];
            this.saveToLocalStorage();
        },

        updateItemQuantity(item, quantity) {
            const existingItem = this.items.find((localItem) => localItem.item.id === item.id);
            if (existingItem) {
                existingItem.quantity = quantity;
                if (existingItem.quantity <= 0) {
                    this.removeItem(item);
                }
            }

            this.saveToLocalStorage();
        },

        async createOrder(userObj) {
            if (!this.items.length) {
                throw new Error('The cart is empty.');
            }

            const payload = {
                name: userObj.name,
                email_address: userObj.email_address,
                phone_number: userObj.phone_number,
                items: this.items.map((item) => ({
                    product_id: item.item.id,
                    quantity: item.quantity,
                    price: item.item.price,
                })),
            };

            const response = await OrderService.store(payload);
            this.emptyItems();
            return response.uuid;
        },

        async getOrderByUuId(uuid) {
            try {
                return await OrderService.getOrderByUuId(uuid);
            }
            catch(error) {
                console.log(error);
            }
        }
    },
});
