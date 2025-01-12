import axios from 'axios';
import { useAuthStore } from '@/stores/auth.store.js';
import router from '@/routers';

const publicApi = axios.create({
    baseURL: import.meta.env.VITE_API_URL,
});

publicApi.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    },
);


const privateApi = axios.create({
    baseURL: import.meta.env.VITE_API_URL,
});

privateApi.interceptors.request.use(
    async (config) => {
        const token = localStorage.getItem('token');

        if (!token) {
            return Promise.reject({ status: 401, message: "Nincs érvényes token" });
        }

        config.headers.Authorization = `Bearer ${token}`;
        return config;
    },
    (error) => {
        return Promise.reject(error);
    },
);

privateApi.interceptors.response.use(
    (response) => response,
    async (error) => {
        if (error && error.status === 401) {
            const authStore = useAuthStore();
            authStore.removeToken();
            return Promise.reject({ navigateToLogin: true });
        }
        return Promise.reject(error);
    },
);

privateApi.interceptors.response.use(
    (response) => response,
    async (error) => {
        if (error.navigateToLogin) {
            return router.replace({ name: 'AdminLogin' }).then(() => {
                return Promise.reject({ navigateToLogin: true });
            });
        }
        return Promise.reject(error);
    },
);

export { publicApi, privateApi };
