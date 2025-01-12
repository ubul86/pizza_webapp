import { defineStore } from 'pinia';
import userService from '@/services/user.service.js'

export const useUserStore = defineStore('user', {
    state: () => ({
        users: [],
    }),
    actions: {
        async fetchUsers() {
            try {
                this.users = await userService.getUsers();
            }
            catch(error) {
                console.log(error);
            }
        }
    },
});
