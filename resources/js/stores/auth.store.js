import { defineStore } from 'pinia';
import AuthService from '@/services/auth.service.js'

export const useAuthStore = defineStore('AuthStore', {
    state: () => ({
        isAuthenticated: !!localStorage.getItem('token'),
        user: null,
    }),
    actions: {
        async login(user) {
            try {
                this.user = await AuthService.login(user);
                this.isAuthenticated = true;
            } catch (error) {
                console.error('Login failed', error);
                throw error;
            }
        },
        async loginToAdmin(user) {
            try {
                this.user = await AuthService.loginToAdmin(user);
                this.isAuthenticated = true;
            } catch (error) {
                console.error('Login failed', error);
                throw error;
            }
        },

        async logout() {
            await AuthService.logout();
            this.isAuthenticated = false;
            this.user = null;
        },

        removeToken() {
            localStorage.removeItem('token');
            this.isAuthenticated = false;
            this.user = null;
        },
        async refreshToken() {
            return await AuthService.refreshToken();
        }
    },
});
