<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';

import useForm from "@/composables/useForm";
import { useRegistrationStore } from '@/stores/registration.store.js';
import HeaderUserComponent from '@/components/HeaderUserComponent.vue'

const route = useRoute();
const router = useRouter();
const { generalError, handleApiError, resetErrors } = useForm();
const registrationStore = useRegistrationStore();

const success = ref(false);
const message = ref('Activating your account...');
const countdown = ref(5);

onMounted(async () => {
    resetErrors();
    const { token } = route.params;
    try {
        const response = await registrationStore.activation(token);

        if (response) {
            success.value = true;
            await startCountdown();
        }
    }
    catch(error) {
        handleApiError(error);
    }

});

const startCountdown = async () => {
    const interval = setInterval(() => {
        if (countdown.value > 0) {
            countdown.value--;
        } else {
            clearInterval(interval);
            router.push("/");
        }
    }, 1000);
};
</script>

<template>
    <v-container class="position-relative header-container">
        <v-row>
            <v-col>
                <HeaderUserComponent></HeaderUserComponent>
            </v-col>
        </v-row>
    </v-container>
    <v-container class="mt-5">
        <v-row justify="center">
            <v-col cols="12" md="8" class="text-center">
                <v-alert v-if="generalError" type="error" dismissible>
                    {{ generalError }}
                </v-alert>

                <v-card>
                    <v-card-title>{{ message }}</v-card-title>
                    <v-alert v-if="success" type="success">
                        Profile activation was successful. Redirecting in {{ countdown }} seconds to the Home page...
                    </v-alert>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
