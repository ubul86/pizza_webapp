import { defineStore } from 'pinia';
import orderService from '@/services/order.service.js';

export const useAdminOrderStore = defineStore('adminOrder', {
    state: () => ({
        items: [],
    }),
    actions: {
        async fetchOrders() {
            try {
                this.items = await orderService.getOrders();
            }
            catch(error) {
                console.log(error);
            }
        },
    },
});
