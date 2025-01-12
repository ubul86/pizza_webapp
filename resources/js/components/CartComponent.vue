<template>
    <div class="cart-container">
        <router-link to="/cart" class="menu-link cart-icon">
            <v-icon>mdi-cart</v-icon>
        </router-link>
        <span v-if="countedOrder > 0" class="order-badge">{{ countedOrder }}</span>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { useUserOrderStore } from '@/stores/user.order.store.js';
const orderStore = useUserOrderStore();

const countedOrder = computed(() => {
    return orderStore.items.reduce((total, item) => total + item.quantity, 0);
});

</script>

<style>

.cart-container {
    position: relative;
    display: inline-block;
}

.order-badge {
    position: absolute;
    bottom: -5px;
    right: 5px;
    background-color: #4caf50;
    color: white;
    font-size: 0.75rem;
    font-weight: bold;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
}

</style>
