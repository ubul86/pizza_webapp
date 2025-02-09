<script setup>

import { useAdminProductStore } from '@/stores/admin.product.store.js';
import { useToast } from 'vue-toastification';
import { ref, watch } from 'vue'

const toast = useToast();
const productStore = useAdminProductStore();

const props = defineProps({
    isDialogDeleteOpen: {
        type: Boolean,
        required: true,
    },
    itemId: {
        type: Number,
        required: true,
    },
});

const emit = defineEmits(['update:isDialogDeleteOpen', 'closeDelete']);

const localDialogOpen = ref(props.isDialogDeleteOpen);

watch(() => props.isDialogDeleteOpen, (newValue) => {
    localDialogOpen.value = newValue;
});

watch(localDialogOpen, (newValue) => {
    emit('update:isDialogDeleteOpen', newValue);
});

const deleteItemConfirm = async () => {
    try {
        await productStore.deleteItem(props.itemId);
        emit('closeDelete');
        toast.success('You have successfully deleted the item!');
    } catch (error) {
        toast.error(error.response?.data?.message || 'Failed to delete the item.');
    }
};

const cancelDelete = () => {
    emit('closeDelete');
};
</script>

<template>
    <v-dialog v-model="localDialogOpen" max-width="500px">
        <v-card>
            <v-card-title class="text-h5">Are you sure you want to delete this product?</v-card-title>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue-darken-1" variant="text" @click="cancelDelete">Cancel</v-btn>
                <v-btn color="blue-darken-1" variant="text" @click="deleteItemConfirm">OK</v-btn>
                <v-spacer></v-spacer>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
