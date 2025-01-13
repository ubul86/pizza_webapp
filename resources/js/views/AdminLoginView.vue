<script setup>

import { ref } from "vue";
import useForm from "@/composables/useForm";
import { useAuthStore } from "@/stores/auth.store";
import { useUserStore } from '@/stores/user.store.js';
import { useRouter } from 'vue-router'

const router = useRouter();
const { generalError, formErrors, resetErrors, handleApiError } = useForm();
const adminAuthStore = useAuthStore();
const userStore = useUserStore();

const formData = ref({
    email: "",
    password: "",
});
const valid = ref(false);

const handleLogin = async () => {
    resetErrors();
    try {
        await adminAuthStore.loginToAdmin(formData.value);
        await userStore.getAuthenticatedUser();
        await router.push('/admin');
    } catch (error) {
        handleApiError(error);
    }
};

</script>

<style>

</style>

<template>
    <v-container fluid class="d-flex align-center justify-center fill-height">
        <v-card class="pa-6" width="400">
            <v-card-title class="text-h5">Login</v-card-title>
            <v-card-text>
                <v-form ref="form" v-model="valid">
                    <v-text-field
                        v-model="formData.email"
                        label="Email"
                        type="email"
                        :error="!!formErrors.email"
                        :error-messages="formErrors.email || []"
                        required
                    ></v-text-field>
                    <v-text-field
                        v-model="formData.password"
                        label="Password"
                        type="password"
                        :error="!!formErrors.password"
                        :error-messages="formErrors.password || []"
                        required
                    ></v-text-field>
                    <v-alert v-if="generalError" type="error" dense class="mt-4">
                        {{ generalError }}
                    </v-alert>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-btn color="primary" block @click="handleLogin">
                    Login
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-container>
</template>
