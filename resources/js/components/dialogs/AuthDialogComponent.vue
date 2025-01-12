<template>

    <v-btn color="green" @click="logout" v-if="isAuthenticated">Logout</v-btn>

    <v-btn @click="dialog = true" v-if="!isAuthenticated">Login / Registration</v-btn>
    <v-dialog v-model="dialog" max-width="500px">
        <v-card>
            <v-card-title>
                <v-tabs
                    v-model="activeTab"
                    background-color="blue lighten-4"
                    centered
                >
                    <v-tab value="0">Login</v-tab>
                    <v-tab value="1">Registration</v-tab>
                </v-tabs>
            </v-card-title>

            <v-card-text>
                <v-tabs-window v-model="activeTab">
                    <v-tabs-window-item value="0">
                        <LoginFormComponent :dialog="dialog" @update:dialog="dialog = $event" />
                    </v-tabs-window-item>
                    <v-tabs-window-item value="1">
                        <RegistrationFormComponent :dialog="dialog" @update:dialog="dialog = $event" />
                    </v-tabs-window-item>
                </v-tabs-window>
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" @click="dialog = false">
                    Close
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup>
import { ref, computed } from "vue";
import LoginFormComponent from "@/components/forms/LoginFormComponent.vue";
import RegistrationFormComponent from "@/components/forms/RegistrationFormComponent.vue";

import { useAuthStore } from '@/stores/auth.store.js';

const authStore = useAuthStore();

const isAuthenticated = computed(() => {
    return authStore.isAuthenticated;
})

const logout = async () => {
    try {
        await authStore.logout();
    }
    catch(error) {
        console.log(error);
    }
};

const dialog = ref(false);
const activeTab = ref(0);
</script>
