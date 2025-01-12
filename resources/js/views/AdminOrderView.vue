<script setup>
import HeaderAdminComponent from '@/components/HeaderAdminComponent.vue'
import OrderDataTableComponent from '@/components/OrderDataTableComponent.vue'

</script>

<style>

</style>

<template>
    <v-container class="position-relative header-container">
        <v-row>
            <v-col>
                <HeaderAdminComponent></HeaderAdminComponent>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <OrderDataTableComponent />
            </v-col>
        </v-row>
    </v-container>


</template>
