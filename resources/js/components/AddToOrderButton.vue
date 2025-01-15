<template>
    <v-btn icon class="mr-2" @click="handleAddToOrder">
        <v-icon size="small">mdi-plus</v-icon>
    </v-btn>
</template>

<script setup>
import { useUserOrderStore } from '@/stores/user.order.store.js';

const orderStore = useUserOrderStore();

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const handleAddToOrder = () => {
    orderStore.addItem(props.product);
};
</script>
