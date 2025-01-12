<template>
    <v-card>
        <v-layout>
            <v-app-bar
                class="header-app-bar"
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
                            <router-link to="/" class="menu-link">Home</router-link>
                            <router-link to="/shop" class="menu-link">Shop</router-link>
                            <router-link to="/contact" class="menu-link">Contact</router-link>
                            <router-link to="/about" class="menu-link">About</router-link>
                            <CartComponent />
                        </v-col>
                        <v-col cols="auto" class="d-lg-none">
                            <v-app-bar-nav-icon variant="text" @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
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
                        <router-link to="/" class="menu-drawer-link">Home</router-link>
                    </v-list-item>
                    <v-list-item>
                        <router-link to="/shop" class="menu-drawer-link">Shop</router-link>
                    </v-list-item>
                    <v-list-item>
                        <router-link to="/contact" class="menu-drawer-link">Contact</router-link>
                    </v-list-item>
                    <v-list-item>
                        <router-link to="/about" class="menu-drawer-link">About</router-link>
                    </v-list-item>
                    <v-list-item>
                        <CartComponent />
                    </v-list-item>
                </v-list>
            </v-navigation-drawer>
        </v-layout>
    </v-card>
</template>

<script setup>
    import { ref } from 'vue'
    import CartComponent from '@/components/CartComponent.vue'

    const drawer = ref(false);
</script>

<style>
.header-app-bar {
    background: #ff2b06 !important;
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
