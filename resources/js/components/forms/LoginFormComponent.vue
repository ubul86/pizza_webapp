<script setup>
import { ref } from "vue";
import useForm from "@/composables/useForm";
import { useAuthStore } from '@/stores/auth.store.js';

const { generalError, formErrors, resetErrors, handleApiError } = useForm();
const authStore = useAuthStore();

const formData = ref({
    email: "",
    password: "",
});

const login = async() => {
    resetErrors();
    try {
        await authStore.login(formData.value);
    } catch (error) {
        handleApiError(error);
    }
};

</script>

<template>
    <v-card>
        <v-card-text>
            <v-form @submit.prevent="login">
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
                <v-card-actions>
                    <v-btn color="green" type="submit">Login</v-btn>
                </v-card-actions>
            </v-form>
        </v-card-text>
    </v-card>
</template>

<style scoped>

</style>
