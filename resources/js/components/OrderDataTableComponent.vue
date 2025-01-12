<template>

    <ToggleHeaderComponent :selectedHeaders="toggleHeaders" :headers="headers" @update:selectedHeaders="toggleHeaders = $event" />

    <v-data-table
        v-model="selected"
        :headers="computedHeaders"
        :items="filteredItems"
        v-model:search="search"
        :filter-keys="['name', 'email']"
        :mobile="smAndDown"
        v-model:sort-by="sortBy"

    >
        <template v-slot:top>
            <v-toolbar flat>
                <v-toolbar-title>Orders</v-toolbar-title>
                <v-divider class="mx-4" inset vertical></v-divider>
                <v-spacer></v-spacer>
                <v-text-field
                    v-model="search"
                    density="compact"
                    label="Search"
                    prepend-inner-icon="mdi-magnify"
                    variant="solo-filled"
                    flat
                    hide-details
                    single-line
                ></v-text-field>

            </v-toolbar>
        </template>
        <template v-slot:[`item.actions`]="{ item }">
            <v-icon class="me-2" size="small" @click="openDialog(item.items)">mdi-eye</v-icon>
        </template>
        <template v-slot:no-data>
            <v-btn color="primary" @click="initialize">Reset</v-btn>
        </template>
        <template v-slot:[`item.total_quantity`]="{ item }">
            {{ totalQuantity(item) }}
        </template>
        <template v-slot:[`item.total_price`]="{ item }">
            {{ totalPrice(item) }}
        </template>
    </v-data-table>

    <v-dialog v-model="dialog" max-width="500px">
        <v-card>
            <v-card-title>
                <span class="text-h6">Order Items</span>
            </v-card-title>
            <v-card-text>
                <v-list>
                    <v-list-item v-for="(item, index) in selectedOrderItems" :key="index">
                        <v-list-item-content>
                            <v-list-item-title>
                                Product ID: {{ item.product_id }} - Name: {{ item.product.name }}
                            </v-list-item-title>
                            <v-list-item-subtitle>
                                Price: {{ item.price }} | Quantity: {{ item.quantity }}
                            </v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn text @click="dialog = false">Close</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { useAdminOrderStore } from '@/stores/admin.order.store.js';

import ToggleHeaderComponent from '@/components/ToggleHeaderComponent.vue'
import { useDisplay } from 'vuetify'

const isMobileView = ref(window.innerWidth < 960);

const sortBy = ref([{ key: 'created_at', order: 'desc' }]);

const dialog = ref(false);
const selectedOrderItems = ref([]);

const openDialog = (items) => {
    selectedOrderItems.value = items;
    dialog.value = true;
};

const checkScreenSize = () => {
    isMobileView.value = window.innerWidth < 960;
};

onMounted(async () => {
    await orderStore.fetchOrders();
    window.addEventListener('resize', checkScreenSize);
});

onBeforeUnmount(() => {
    window.removeEventListener('resize', checkScreenSize);
});


const { smAndDown } = useDisplay()

const orderStore = useAdminOrderStore();

const headers = [
    {
        title: 'Order ID',
        align: 'start',
        key: 'id',
    },
    { title: 'Name', key: 'name', sortable: true },
    { title: 'Email Address', key: 'email_address', sortable: false },
    { title: 'Phone Number', key: 'phone_number', sortable: false },
    { title: 'Total Quantity', key: 'total_quantity', sortable: true },
    { title: 'Total Price', key: 'total_price', sortable: true },
    { title: 'Created At', key: 'created_at' },
    { title: 'Actions', key: 'actions', sortable: false },
]

const selected = ref([])

const search = ref('');

const nameSearch = ref(null);

const toggleHeaders = ref(['id','name', 'email_address', 'phone_number', 'total_price', 'total_quantity', 'created_at', 'actions']);

const computedHeaders = computed(() => {
    return headers.filter(header => toggleHeaders.value.includes(header.key));
})


const filteredItems = computed(() => {
    let filtered = orderStore.items;

    if (nameSearch.value) {
        filtered = filtered.filter((product) => product.name === nameSearch.value);
    }

    return filtered;
});

const totalPrice = ((items) => {
    if (!items) {
        return 0;
    }
    return items.items.reduce((total, item) => total + item.price * item.quantity, 0);
});

const totalQuantity = ((items) => {
    if (!items) {
        return 0;
    }
    return items.items.reduce((total, item) => total + item.quantity, 0);
});

</script>

<style>

.v-data-table__tr:nth-child(odd) {
    background-color: #e5e7eb;
}

.v-data-table__tr:nth-child(even) {
    background-color: #ffffff;
}

.used-time-container {
    align-items: center;
}

@media (max-width: 960px) {
    .used-time-container {
        align-items: flex-end;
    }
}

</style>
