import { createRouter, createWebHistory } from 'vue-router';
import Home from '@/views/HomeView.vue';
import NotFoundView from '@/views/NotFoundView.vue';
import CartView from '@/views/CartView.vue';
import OrderSuccessView from '@/views/OrderSuccessView.vue';
import AdminLoginView from '@/views/AdminLoginView.vue';
import AdminHomeView from '@/views/AdminHomeView.vue';

import { useAuthStore } from '@/stores/auth.store';
import AdminProductView from '@/views/AdminProductView.vue';
import AdminOrderView from '@/views/AdminOrderView.vue';
import ActivationView from '@/views/ActivationView.vue'

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home,
        meta: { requiresAuth: false },
    },
    {
        path: '/activation/:token',
        name: 'Activation',
        component: ActivationView,
        meta: { requiresAuth: false },
    },
    {
        path: '/cart',
        name: 'Cart',
        component: CartView,
        meta: { requiresAuth: false },
    },
    {
        path: '/admin/login',
        name: 'AdminLogin',
        component: AdminLoginView,
        meta: { requiresAuth: false },
    },
    {
        path: '/admin',
        name: 'AdminHome',
        component: AdminHomeView,
        meta: { requiresAuth: true },
    },
    {
        path: '/admin/product',
        name: 'AdminProduct',
        component: AdminProductView,
        meta: { requiresAuth: true },
    },
    {
        path: '/admin/order',
        name: 'AdminOrder',
        component: AdminOrderView,
        meta: { requiresAuth: true },
    },
    {
        path: '/order-success/:hash',
        name: 'OrderSuccess',
        component: OrderSuccessView,
        props: true,
        meta: { requiresAuth: false },
    },
    {
        path: '/:catchAll(.*)',
        name: 'NotFound',
        component: NotFoundView,
        meta: { requiresAuth: false },
        beforeEnter: (to, from, next) => {
            if (!to.path.startsWith('/api')) {
                next();
            } else {
                next(false);
            }
        },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const adminAuthStore = useAuthStore();
    const isAuthenticated = adminAuthStore.isAuthenticated;
    if (to.name === 'AdminLogin' && isAuthenticated) {
        return next({ name: 'AdminHome' });
    }

    if (to.meta.requiresAuth === true && isAuthenticated === false) {
        return next({ name: 'AdminLogin' });
    }
    next();
});

export default router;
