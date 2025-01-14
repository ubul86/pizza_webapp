<script setup>

    import { ref, computed } from 'vue';
    import { useUserOrderStore } from '@/stores/user.order.store.js';
    import { useUserStore } from '@/stores/user.store.js';
    import useForm from '@/composables/useForm.js';

    import HeaderUserComponent from '@/components/HeaderUserComponent.vue'
    import { useRouter } from 'vue-router'
    const router = useRouter();

    const { formErrors, resetErrors, handleApiError } = useForm();


    const orderStore = useUserOrderStore();
    const userStore = useUserStore();

    const defaultUser = {
        name: null,
        email: null,
    };

    const loggedUser = computed(() => userStore.user || defaultUser);

    const name = ref(loggedUser.value.name);
    const email_address = ref(loggedUser.value.email);
    const phone_number = ref(loggedUser.value.phone_number);

    const orderItems = computed(() => orderStore.items);

    const updateQuantity = (orderItem) => {
        const { item, quantity } = orderItem;
        orderStore.updateItemQuantity(item, quantity);
    };

    const handleCreateOrder = async () => {
        resetErrors();
        const userObj = {
            name: name.value,
            email_address: email_address.value,
            phone_number: phone_number.value,
        };

        try {
            const orderHash = await orderStore.createOrder(userObj);
            await router.push(`/order-success/${orderHash}`);
        } catch (error) {
            console.log(error);
            handleApiError(error);
        }
    };

    const totalPrice = computed(() => {
        return orderItems.value.reduce((total, item) => total + item.item.price * item.quantity, 0);
    });

    const totalQuantity = computed(() => {
        return orderItems.value.reduce((total, item) => total + item.quantity, 0);
    });

    const deleteFromCart = (item) => {
        try {
            orderStore.removeItemAll(item);
        }
        catch(error) {
            console.log(error)
        }
    };

</script>

<style>

.order-items, .order-user-information {
    padding: 5px;
}

.order-user-information .v-card {
    background: #F4DBA5;
    padding: 10px !important;
}

.order-items .v-field__input, .order-user-information .v-card .v-input__control {
    background-color: #fff;
}

.order-item-container {
    margin-top: 50px;
}

.order-items--header {
    border-bottom: 1px solid #ddd;
    color: #555;
}

.v-input__details {
    padding: 10px 0px !important;
}

</style>

<template>
    <v-container class="position-relative header-container">
        <v-row>
            <v-col>
                <HeaderUserComponent />
            </v-col>
        </v-row>
    </v-container>
    <v-container class="order-item-container" v-if="!orderItems.length">
        There are no items in your cart!
    </v-container>
    <v-container class="order-item-container" v-if="orderItems.length > 0">
        <v-row>
            <v-col xs="12" sm="12" md="8" lg="8" order-sm="2" order-xs="2" order-md="1" order-lg="1">
                <div class="order-items">
                    <v-row class="order-items--header">
                        <v-col class="align-start">Product</v-col>
                        <v-col>Quantity</v-col>
                        <v-col class="align-end">Price</v-col>
                    </v-row>
                    <v-row v-for="orderItem in orderItems" :key="orderItem.id" class="d-flex justify-center align-center">
                        <v-col class="align-start">
                            <div class="d-flex align-center" style="padding-left: 10px;">
                                <v-img
                                    :src="orderItem.item.firstImage.presets.actual_small"
                                    alt="Logo"
                                    width="50"
                                    max-width="50"
                                    style="margin-right: 10px;"
                                ></v-img>
                                <span style="padding-left: 30px;">
                                    {{ orderItem.item.name }}
                                </span>
                            </div>
                        </v-col>
                        <v-col>
                            <v-text-field
                                v-model.number="orderItem.quantity"
                                @input="updateQuantity(orderItem)"
                                type="number"
                                min="1"
                            ></v-text-field>
                        </v-col>
                        <v-col class="align-end text-end">{{ orderItem.item.price }} Ft</v-col>
                        <v-col class="align-end text-center">
                            <v-icon class="me-2" size="small" @click="deleteFromCart(orderItem.item)">mdi-delete</v-icon>
                        </v-col>
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
            <v-col xs="12" sm="12" md="4" lg="4" order-sm="1" order-xs="1" order-md="2" order-lg="2">
                <div class="order-user-information">
                    <v-card>
                        <div>
                            <div>Name:</div>
                            <v-text-field v-model="name" :error="!!formErrors.name" :error-messages="formErrors.name || []" ></v-text-field>
                        </div>

                        <div>
                            <div>Email Address:</div>
                            <v-text-field v-model="email_address" :error="!!formErrors.email_address" :error-messages="formErrors.email_address || []"></v-text-field>
                        </div>

                        <div>
                            <div>Phone Number:</div>
                            <v-text-field v-model="phone_number" :error="!!formErrors.phone_number" :error-messages="formErrors.phone_number || []"></v-text-field>
                        </div>

                        <div>
                            <v-btn color="green" @click="handleCreateOrder">Send Order</v-btn>
                        </div>

                    </v-card>
                </div>
            </v-col>
        </v-row>
    </v-container>
</template>
