<template>
    <DialogForm
        :title="title"
        :fields="fields"
        v-model:formData="editedItem"
        :dialog-visible="localDialogVisible"
        @cancel="handleCancel"
        @submit="handleSubmit"
    />
</template>

<script setup>
import { computed, ref, watch, onMounted } from 'vue'
import DialogForm from './DialogForm.vue';
import useForm from '@/composables/useForm.js';
import { useAdminProductStore } from '@/stores/admin.product.store.js'
import { useCategoryStore } from '@/stores/category.store.js';
import { useToast } from 'vue-toastification';

const { formErrors, resetErrors, handleApiError } = useForm();

const productStore = useAdminProductStore();
const categoryStore = useCategoryStore();

const toast = useToast()

const props = defineProps({
    dialogVisible: Boolean,
    editedIndex: Number,
});

const localDialogVisible = ref(props.dialogVisible);

const emit = defineEmits(['update:dialogVisible', 'save', 'close']);

const title = ref('New Product');


const editedItem = ref({
    name: null,
    category_id: '',
    price: null,
    description: null,
})

const defaultItem = {
    name: null,
    category_id: '',
    price: null,
    description: null,
}

onMounted(async () => {
    await categoryStore.fetchCategories();
});


watch(
    () => props.dialogVisible,
    (newVal) => {
        localDialogVisible.value = newVal;
    }
);

watch(
    () => props.editedIndex,
    (newVal) => {
        title.value = newVal < 0 ? 'New Product' : 'Edit Product';

        editedItem.value = {
            ...defaultItem,
        };

        if (newVal >= 0) {
            const product = productStore.products[newVal];

            if (product) {
                editedItem.value = {
                    ...product,
                };
            }
        }
    }
);

const categories = computed (() => {
  return categoryStore.categories;
});

const categoryItems = computed(() =>
    categories.value.map(category => ({ id: category.id, name: category.name, title: category.name }))
);

const handleCancel = () => {
    localDialogVisible.value = false;
    editedItem.value = { ...defaultItem };
    emit('close');
};

const handleSubmit = async (itemToSubmit) => {

    console.log(itemToSubmit);

    resetErrors();

    try {
        if (props.editedIndex > -1) {
            await productStore.update(props.editedIndex, itemToSubmit);
            toast.success('You have successfully edited the item!');
        } else {
            await productStore.store(itemToSubmit)
            toast.success('You have successfully created a new item!');
        }
        localDialogVisible.value = false;
        editedItem.value = { ...defaultItem };
        emit('close');
    }
    catch(error) {
        handleApiError(error);
        toast.error(error.response.data.message);
    }

};


const fields = computed(() => [
    { model: 'name', component: 'v-text-field', props: { label: 'name', error: !!formErrors.value.name, 'error-messages': formErrors.value.name || [] } },
    { model: 'description', component: 'v-text-field', props: { label: 'Description', error: !!formErrors.value.description, 'error-messages': formErrors.value.description || [] } },
    { model: 'category_id', component: 'v-select', props: { label: 'Categories', items: categoryItems.value, 'item-value': 'id', 'item-title': 'name', error: !!formErrors.value.category_id, 'error-messages': formErrors.value.category_id || [] } },
    { model: 'price', component: 'v-text-field', props: { label: 'price', error: !!formErrors.value.price, 'error-messages': formErrors.value.price || [] } },
]);

</script>
