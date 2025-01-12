import { defineStore } from 'pinia';
import RegistrationService from '@/services/registration.service.js'

export const useRegistrationStore = defineStore('RegistrationStore', {
    state: () => ({

    }),
    actions: {
        async registration(user) {
            try {
                await RegistrationService.registration(user);
            } catch (error) {
                console.error('Login failed', error);
                throw error;
            }
        },

        async activation(token) {
            return await RegistrationService.activation(token);
        }
    },
});
