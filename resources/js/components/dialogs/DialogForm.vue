<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
    dialogVisible: Boolean,
    title: String,
    fields: Array,
    formData: Object,
});

const emit = defineEmits(['update:dialogVisible','update:formData', 'submit', 'cancel']);

const localDialogVisible = ref(props.dialogVisible);
const localFormData = ref({...props.formData});

watch(
    () => props.dialogVisible,
    (newVal) => {
        localDialogVisible.value = newVal;
    }
);

watch(
    () => props.formData,
    (newVal) => {
        localFormData.value = {...newVal};
    },
    {deep: true}
);

const closeDialog = () => {
    localDialogVisible.value = false;
    emit('update:modelValue', false);
    emit('cancel');
};

const handleSubmit = () => {
    emit('submit', localFormData.value);
};
</script>

<template>
    <v-dialog v-model="localDialogVisible" max-width="500px">
        <v-card>
            <v-card-title>
                <span class="text-h5">{{ title }}</span>
            </v-card-title>
            <v-card-text>
                <v-container>
                    <v-row v-for="(row, rowIndex) in fields" :key="rowIndex">
                        <v-col
                            v-for="(field, colIndex) in Array.isArray(row) ? row : [row]"
                            :key="colIndex"
                            :cols="field.cols || 12"
                        >
                            <component
                                :is="field.component"
                                v-model="localFormData[field.model]"
                                v-bind="field.props"
                            />
                        </v-col>
                    </v-row>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-spacer />
                <v-btn color="blue-darken-1" variant="text" @click="closeDialog">Cancel</v-btn>
                <v-btn color="blue-darken-1" variant="text" @click="handleSubmit">Save</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
