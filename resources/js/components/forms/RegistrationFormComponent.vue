<script setup>
import { ref } from "vue";

const name = ref(null);
const email = ref(null);
const password = ref(null);

const registration = async() => {
    console.log('registration')
};

</script>

<template>
    <v-card>
        <v-card-text>
            <v-form @submit.prevent="registration">
                <v-text-field
                    v-model="name"
                    label="Name"
                    required
                ></v-text-field>
                <v-text-field
                    v-model="email"
                    label="Email"
                    required
                ></v-text-field>
                <v-text-field
                    v-model="password"
                    label="Password"
                    type="password"
                    required
                ></v-text-field>
                <v-card-actions>
                    <v-btn color="green" type="submit">Registration</v-btn>
                </v-card-actions>
            </v-form>
        </v-card-text>
    </v-card>
</template>

<style scoped>

</style>
