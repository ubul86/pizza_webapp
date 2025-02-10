import { createRouter, createWebHistory } from 'vue-router';
import Home from '@/views/HomeView.vue';
import NotFoundView from '@/views/NotFoundView.vue';
import CartView from '@/views/CartView.vue';
import OrderSuccessView from '@/views/OrderSuccessView.vue';
import AdminLoginView from '@/views/AdminLoginView.vue';
import AdminHomeView from '@/views/AdminHomeView.vue';

import { useAuthStore } from '@/stores/auth.store';
import { useUserStore } from '@/stores/user.store.js'
import AdminProductView from '@/views/AdminProductView.vue';
import AdminOrderView from '@/views/AdminOrderView.vue';
import ActivationView from '@/views/ActivationView.vue'
import ProductView from '@/views/ProductView.vue'

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home,
        meta: { requiresAuth: false, needAdminPermission: false },
    },
    {
        path: '/activation/:token',
        name: 'Activation',
        component: ActivationView,
        meta: { requiresAuth: false, needAdminPermission: false },
    },
    {
        path: '/product/:id',
        name: 'Product',
        component: ProductView,
        meta: { requiresAuth: false, needAdminPermission: false },
    },
    {
        path: '/cart',
        name: 'Cart',
        component: CartView,
        meta: { requiresAuth: false, needAdminPermission: false },
    },
    {
        path: '/admin/login',
        name: 'AdminLogin',
        component: AdminLoginView,
        meta: { requiresAuth: false, needAdminPermission: false },
    },
    {
        path: '/admin',
        name: 'AdminHome',
        component: AdminHomeView,
        meta: { requiresAuth: true, needAdminPermission: true },
    },
    {
        path: '/admin/product',
        name: 'AdminProduct',
        component: AdminProductView,
        meta: { requiresAuth: true, needAdminPermission: true },
    },
    {
        path: '/admin/order',
        name: 'AdminOrder',
        component: AdminOrderView,
        meta: { requiresAuth: true, needAdminPermission: true },
    },
    {
        path: '/order-success/:hash',
        name: 'OrderSuccess',
        component: OrderSuccessView,
        props: true,
        meta: { requiresAuth: false, needAdminPermission: false },
    },
    {
        path: '/:catchAll(.*)',
        name: 'NotFound',
        component: NotFoundView,
        meta: { requiresAuth: false, needAdminPermission: false },
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

router.beforeEach(async (to, from, next) => {
    console.log(from);
    const authStore = useAuthStore();
    const userStore = useUserStore();
    const isAuthenticated = authStore.isAuthenticated;

    if (isAuthenticated && !userStore.user) {
        await userStore.getAuthenticatedUser();
    }

    const isAdmin = userStore.user?.is_admin || false;

    if (to.name === 'AdminLogin' && isAuthenticated && isAdmin) {
        return next({ name: 'AdminHome' });
    }

    if (to.name === 'AdminLogin' && isAuthenticated && !isAdmin) {
        return next({ name: 'AdminLogin' });
    }

    if (to.meta.needAdminPermission && (!isAuthenticated || !isAdmin)) {
        return next({ name: 'AdminLogin' });
    }

    next();
});

export default router;
