import axios from 'axios';
import { useAuthStore } from '@/stores/auth.store.js';
import router from '@/routers';

const baseURL = import.meta.env.VITE_API_URL;

const createApi = (requiresAuth = false) => {
    const api = axios.create({ baseURL });

    api.interceptors.request.use(
        async (config) => {
            if (!config.headers.Authorization) {
                const token = localStorage.getItem('token');
                if (requiresAuth && !token) {
                    return Promise.reject({ status: 401, message: "There is no valid token!" });
                }
                if (token) {
                    config.headers.Authorization = `Bearer ${token}`;
                }
            }
            return config;
        },
        (error) => Promise.reject(error)
    );

    api.interceptors.response.use(
        (response) => response,
        async (error) => {
            if (error?.status === 401) {
                const authStore = useAuthStore();

                try {
                    const token = await authStore.refreshToken();
                    if (token) {
                        localStorage.setItem('token', token);
                        error.config.headers.Authorization = `Bearer ${token}`;
                        return api(error.config);
                    } else {
                        authStore.removeToken();
                        return Promise.reject(requiresAuth ? { navigateToLogin: true } : {});
                    }
                } catch (refreshError) {
                    console.log(refreshError);
                    authStore.removeToken();
                    return Promise.reject(requiresAuth ? { navigateToLogin: true } : {});
                }
            }
            return Promise.reject(error);
        },
    );

    return api;
};

const publicApi = createApi();
const privateApi = createApi(true);

privateApi.interceptors.response.use(
    (response) => response,
    async (error) => {
        if (error.navigateToLogin) {
            return router.replace({ name: 'Home' }).then(() => Promise.reject({ navigateToLogin: true }));
        }
        return Promise.reject(error);
    },
);

export { publicApi, privateApi };
