<script setup>
import { ref, defineProps, defineEmits } from "vue";
import useForm from "@/composables/useForm";
import { useAuthStore } from '@/stores/auth.store.js';
import { useUserStore } from '@/stores/user.store.js';
import { useToast } from 'vue-toastification';

const { generalError, formErrors, resetErrors, handleApiError } = useForm();
const authStore = useAuthStore();
const userStore = useUserStore();

const formData = ref({
    email: "",
    password: "",
});

const toast = useToast()

defineProps({
    dialog: Boolean,
});

const emit = defineEmits(['update:dialog']);

const login = async() => {
    resetErrors();
    try {
        await authStore.login(formData.value);
        await userStore.getAuthenticatedUser();
        toast.success('Login successful!');
        emit('update:dialog', false);
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
