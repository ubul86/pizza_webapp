<template>
    <v-card>
        <v-layout>
            <v-app-bar
                class="header-admin-app-bar"
                prominent
            >

                <v-container>
                    <v-row align="center" justify="space-between">
                        <v-col cols="auto">
                            <router-link to="/">
                                <v-img
                                    src="/images/logo.jpg"
                                    alt="Logo"
                                    height="50"
                                    width="100"
                                    class="logo"
                                ></v-img>
                            </router-link>
                        </v-col>
                        <v-col cols="auto" class="d-none d-lg-flex align-center">
                            <router-link to="/admin/product" class="menu-link">Products</router-link>
                            <router-link to="/admin/order" class="menu-link">Orders</router-link>
                            <v-btn color="green" @click="logout">Logout</v-btn>
                        </v-col>
                    </v-row>
                </v-container>
            </v-app-bar>
            <v-navigation-drawer
                v-model="drawer"
                app
                temporary
                class="d-lg-none"
            >
                <v-list>
                    <v-list-item>
                        <router-link to="/admin/product" class="menu-link">Products</router-link>
                        <router-link to="/admin/order" class="menu-link">Orders</router-link>
                        <router-link to="/about" class="menu-drawer-link">Logout</router-link>
                        <v-btn color="green" @click="logout">Logout</v-btn>
                    </v-list-item>
                </v-list>
            </v-navigation-drawer>
        </v-layout>
    </v-card>
</template>

<script setup>
    import { ref } from 'vue'
    import { useAuthStore } from '@/stores/auth.store.js';
    import { useRouter } from 'vue-router'

    const router = useRouter();

    const adminAuthStore = useAuthStore();

    const drawer = ref(false);
    const logout = async () => {
        try {
            await adminAuthStore.logout();
            await router.push('/admin/login');
        }
        catch(error) {
            console.log(error);
        }
    };

</script>

<style>
.header-admin-app-bar {
    background: #0000ff !important;
    color: #fff !important;
}

.menu-link {
    text-decoration: none;
    color: white;
    padding-left: 15px;
    padding-right: 15px;
    position: relative;
}

.menu-link:not(.cart-icon)::after {
    content: '';
    display: inline-block;
    height: 15px;
    width: 1px;
    background-color: #fff;
    position: absolute;
    top: 50%;
    margin-left: 15px;
    transform: translateY(-50%);
}


.menu-link:hover {
    text-decoration: underline;
    color: #ffc107;
}

.menu-drawer-link {
    display: block;
    color: black;
    text-decoration: none;
    margin: 10px 0;
    color: #000;
}

.menu-drawer-link:hover {
    text-decoration: underline;
}

</style>
