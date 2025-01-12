<template>
    <v-row class="mt-5 mb-5">
        <v-col>
            <v-card class="bg-grey-lighten-4 pt-2 pb-2 pl-2 pr-2">
                <v-card-title class="pl-6">Show / Hide Headers:</v-card-title>
                <v-card-text >
                    <div class="d-flex flex-wrap">
                        <v-checkbox
                            v-for="header in headers"
                            :key="header.key"
                            :label="header.title"
                            :value="header.key"
                            :hide-details="true"
                            :model-value="selectedHeaders"
                            @click="onToggle(header.key)"
                        ></v-checkbox>
                    </div>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>

<script setup>
const props = defineProps({
    headers: {
        type: Array,
        required: true,
    },
    selectedHeaders: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(['update:selectedHeaders']);

const onToggle = (key) => {

    const updatedHeaders = props.selectedHeaders.includes(key)
        ? props.selectedHeaders.filter((headerKey) => headerKey !== key)
        : [...props.selectedHeaders, key];

    console.log(updatedHeaders);

    emit('update:selectedHeaders', updatedHeaders);
};
</script>
