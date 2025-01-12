<script setup>
    import HeaderAdminComponent from '@/components/HeaderAdminComponent.vue'
    import ProductDataTableComponent from '@/components/ProductDataTableComponent.vue'
</script>

<template>
    <v-container class="position-relative header-container">
        <v-row>
            <v-col>
                <HeaderAdminComponent></HeaderAdminComponent>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <ProductDataTableComponent></ProductDataTableComponent>
            </v-col>
        </v-row>
    </v-container>


</template>
