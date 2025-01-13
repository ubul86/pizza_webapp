<template>
    <v-container class="position-relative header-container">
        <v-row>
            <v-col>
                <HeaderUserComponent />
            </v-col>
        </v-row>
        <v-row class="order-successful--header">
            <v-col>
                <h1>Order Successful!</h1>
                <p>Your order reference is: {{ hash }}</p>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="8" xs="12">
                <div class="order-items">
                    <v-row class="order-items--header">
                        <v-col class="align-start">Product</v-col>
                        <v-col>Quantity</v-col>
                        <v-col class="align-end">Price</v-col>
                    </v-row>
                    <v-row v-for="orderItem in orders.items" :key="orderItem.id" class="d-flex justify-center align-center">
                        <v-col class="align-start">
                            <div class="d-flex align-content-space-around">
                                <v-img
                                    :src="orderItem.product.firstImage.presets.actual_small"
                                    alt="Logo"
                                    width="30"
                                    height="30"
                                    max-width="30"
                                ></v-img>
                                <span style="padding-left: 30px">
                                    {{ orderItem.product.name }}
                                </span>
                            </div>

                        </v-col>
                        <v-col>
                            <span>{{ orderItem.quantity }} db</span>
                        </v-col>
                        <v-col class="align-end">{{ orderItem.price }} Ft</v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <hr class="p-2 m-2"/>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>Total Quantity: </v-col>
                        <v-col cols="2" class="text-right">{{ totalQuantity }} db</v-col>
                    </v-row>
                    <v-row>
                        <v-col>Total Price: </v-col>
                        <v-col cols="2" class="text-right">{{ totalPrice }} Ft</v-col>
                    </v-row>
                </div>

            </v-col>
            <v-col cols="4" xs="12">
                <div class="order-user-information">
                    <v-card>
                        <div>
                            <div><span>Name:</span> {{ orders.name }}</div>
                        </div>

                        <div>
                            <div><span>Email Address:</span> {{ orders.email_address }}</div>

                        </div>
                        <div>
                            <div><span>Phone Number:</span> {{ orders.phone_number }}</div>
                        </div>
                        <div>
                            <div><span>Order Date:</span> {{ orders.created_at }}</div>
                        </div>
                    </v-card>
                </div>
            </v-col>
        </v-row>
    </v-container>

</template>

<script setup>
import { ref, computed, defineProps, onMounted } from 'vue'

import { useUserOrderStore } from '@/stores/user.order.store.js';
import HeaderUserComponent from '@/components/HeaderUserComponent.vue'

const props = defineProps({
    hash: {
        type: String,
        required: true,
    },
});

const orderStore = useUserOrderStore();

const orders = ref(null);

onMounted(async () => {
    orders.value = await orderStore.getOrderByUuId(props.hash);
    console.log(orders);
});

const totalPrice = computed(() => {
    return orders.value.items.reduce((total, item) => total + item.price * item.quantity, 0);
});

const totalQuantity = computed(() => {
    return orders.value.items.reduce((total, item) => total + item.quantity, 0);
});

</script>

<style>

.order-successful--header {
    padding-top: 50px;
}

.order-user-information span {
    font-weight: bold;
}

</style>
