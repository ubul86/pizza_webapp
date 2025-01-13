<template>

    <ToggleHeaderComponent :selectedHeaders="toggleHeaders" :headers="headers" @update:selectedHeaders="toggleHeaders = $event" />

    <EditProductDialogForm :edited-index="editedIndex" :dialog-visible="dialog" @close="close" />

    <v-dialog v-model="dialogBulk" max-width="500px">
        <v-card>
            <v-card-title class="text-h5">{{ dialogBulkText }}</v-card-title>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue-darken-1" variant="text" @click="closeDialogBulk">Cancel</v-btn>
                <v-btn color="blue-darken-1" variant="text" @click="saveDialogBulk">OK</v-btn>
                <v-spacer></v-spacer>
            </v-card-actions>
        </v-card>
    </v-dialog>


    <v-data-table
        v-model="selected"
        :headers="computedHeaders"
        show-select
        :items="filteredItems"
        v-model:search="search"
        :filter-keys="['name', 'price']"
        :mobile="smAndDown"

    >
        <template v-slot:top>
            <v-toolbar flat>
                <v-toolbar-title>Products</v-toolbar-title>
                <v-divider class="mx-4" inset vertical></v-divider>
                <v-spacer></v-spacer>
                <v-text-field
                    v-model="search"
                    density="compact"
                    label="Search"
                    prepend-inner-icon="mdi-magnify"
                    variant="solo-filled"
                    flat
                    hide-details
                    single-line
                ></v-text-field>

                <v-btn color="primary" dark v-bind="props" @click="openDialog">New Product</v-btn>

            </v-toolbar>
        </template>
        <template v-slot:[`item.actions`]="{ item }">
            <v-icon class="me-2" size="small" @click="editItem(item)">mdi-pencil</v-icon>
            <v-icon size="small" @click="openImageDialog(item)">mdi-image</v-icon>
            <v-icon size="small" @click="dialogDelete(item)">mdi-delete</v-icon>
        </template>
        <template v-slot:no-data>
            <v-btn color="primary" @click="initialize">Reset</v-btn>
        </template>
    </v-data-table>

    <v-row v-if="selected.length">
        <v-col>
            <v-btn @click="bulkDelete">Bulk delete</v-btn>
        </v-col>
    </v-row>

    <ProductDialogDeleteComponent
        :is-dialog-delete-open="isDialogDeleteOpen"
        @update:isDialogDeleteOpen="isDialogDeleteOpen = $event"
        :item-id="editedItem?.id"
        @closeDelete="closeDelete"
    />

    <v-dialog v-model="imageDialog" max-width="600px">
        <v-card>
            <v-card-title>Upload Images</v-card-title>
            <v-card-text>

                <div v-if="editedItem.value?.images?.length > 0">
                    <v-row>
                        <v-col
                            v-for="(image, index) in editedItem.value.images"
                            :key="index"
                            class="image-preview"
                        >
                            <v-hover>
                                <template v-slot:default="{ isHovering, props }">
                                    <v-img
                                        v-bind="props"
                                        :src="image?.presets?.actual_small"
                                        alt="Uploaded Image"
                                        :aspect-ratio="16/9"
                                        contain
                                    >
                                        <v-expand-transition>
                                            <div
                                                v-if="isHovering"
                                                class="d-flex transition-fast-in-fast-out bg-grey-darken-2 v-card--reveal text-h2"
                                                style="height: 50%;"
                                            >
                                                <v-btn icon @click="setFirstImage(image)" v-if="!image.first">
                                                    <v-icon size="small">mdi-star</v-icon>
                                                </v-btn>
                                                <v-btn icon @click="deleteImage(image)">
                                                    <v-icon size="small">mdi-delete</v-icon>
                                                </v-btn>
                                            </div>
                                        </v-expand-transition>
                                    </v-img>
                                </template>
                            </v-hover>
                        </v-col>
                    </v-row>
                </div>
                <v-file-input v-model="files" label="Select images" multiple />
                <v-btn color="blue" @click="uploadImages" class="mt-2">Upload</v-btn>
            </v-card-text>
            <v-card-actions>
                <v-btn color="blue" @click="closeImageDialog">Close</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>



</template>

<script setup>
import { ref, reactive, computed, nextTick, onMounted, onBeforeUnmount } from 'vue'
import { useProductStore } from '@/stores/product.store.js';

import { useToast } from 'vue-toastification';

import ToggleHeaderComponent from '@/components/ToggleHeaderComponent.vue'
import { useDisplay } from 'vuetify'

import EditProductDialogForm from '@/components/dialogs/EditProductDialogForm.vue'
import ProductDialogDeleteComponent from '@/components/dialogs/ProductDialogDeleteComponent.vue'


const isMobileView = ref(window.innerWidth < 960);

const checkScreenSize = () => {
    isMobileView.value = window.innerWidth < 960;
};

onMounted(async () => {
    await productStore.fetchProducts();
    window.addEventListener('resize', checkScreenSize);
});

onBeforeUnmount(() => {
    window.removeEventListener('resize', checkScreenSize);
});

const files = ref([])
const imageDialog = ref(false)

const openImageDialog = async (item) => {
    editedItem.value = item;
    imageDialog.value = true;
    await nextTick();
};

const closeImageDialog = () => {
    editedItem.value = null;
    imageDialog.value = false
}

const { smAndDown } = useDisplay()

const dialog = ref(false)

const dialogBulk = ref(false);
const dialogBulkType = ref('delete');
const dialogBulkText = 'Are you sure you want to delete all the selected items?';

const toast = useToast()

const productStore = useProductStore();

const headers = [
    {
        title: 'Product ID',
        align: 'start',
        key: 'id',
    },
    { title: 'Product Name', key: 'name', sortable: false },
    { title: 'Product Category', key: 'category', sortable: false },
    { title: 'Product Price', key: 'price' },
    { title: 'Created At', key: 'created_at' },
    { title: 'Updated At', key: 'updated_at' },
    { title: 'Actions', key: 'actions', sortable: false },
]

const selected = ref([])

const editedIndex = ref(-1)
const editedItem = reactive({
    name: null,
    price: '',
    category_id: null,
})

const defaultItem = {
    name: null,
    price: '',
    category_id: null,
}

const search = ref('');

const nameSearch = ref(null);

const toggleHeaders = ref(['id','name', 'price', 'category', 'created_at', 'updated_at', 'actions']);

const computedHeaders = computed(() => {
    return headers.filter(header => toggleHeaders.value.includes(header.key));
})


const filteredItems = computed(() => {
    let filtered = productStore.products;

    if (nameSearch.value) {
        filtered = filtered.filter((product) => product.name === nameSearch.value);
    }

    return filtered;
});

const editItem = (item) => {
    editedIndex.value = productStore.products.indexOf(item)
    dialog.value = true
}

const openDialog = () => {
    dialog.value = true;
}

const close = async () => {
    dialog.value = false
    editedIndex.value = -1
}

const closeDialogBulk = async () => {
    dialogBulkType.value = '';
    dialogBulk.value = false;
}

const emptySelected = () => {
    selected.value = [];
}

const bulkDelete = () => {
    dialogBulkType.value = "delete";
    dialogBulk.value = true;
};

const saveDialogBulk = async () => {
    try {
        if (dialogBulkType.value === "delete") {
            await productStore.bulkDelete(selected.value);
            toast.success('You have successfully deleted all the selected items!');
        }
        emptySelected();
        await closeDialogBulk();
    }
    catch(error) {
        toast.error(error.response.data.message)
    }
}


const isDialogDeleteOpen = ref(false);

const dialogDelete = (item) => {
    isDialogDeleteOpen.value = true;
    editedIndex.value = productStore.products.indexOf(item)
    Object.assign(editedItem, item)
};

const closeDelete = async() => {
    isDialogDeleteOpen.value = false;
    await nextTick();
    Object.assign(editedItem, defaultItem)
    editedIndex.value = -1
};

const uploadImages = async () => {
    try {
        if (files.value.length > 0) {
            const formData = new FormData()
            files.value.forEach(file => formData.append('images[]', file))
            const product = await productStore.uploadImages(editedItem.value.id, formData);
            toast.success('Images uploaded successfully!')
            files.value = null;
            editedItem.value = { ...product, images: product.images };

        } else {
            toast.error('No files selected!')
        }
    } catch (error) {
        console.log(error)
        toast.error('Failed to upload images!')
    }
}

const setFirstImage = async (image) => {
    try {
        await productStore.setImageToFirst(editedItem.value.id, image);
    }
    catch(error) {
        console.log(error);
    }

}

const deleteImage = async (image) => {
    try {
        await productStore.deleteImage(editedItem.value.id, image);
    }
    catch(error) {
        console.log(error);
    }
}

</script>

<style>

.v-data-table__tr:nth-child(odd) {
    background-color: #e5e7eb;
}

.v-data-table__tr:nth-child(even) {
    background-color: #ffffff;
}

.used-time-container {
    align-items: center;
}

@media (max-width: 960px) {
    .used-time-container {
        align-items: flex-end;
    }
}

.v-card--reveal {
    align-items: center;
    bottom: 0;
    justify-content: space-evenly;
    opacity: .9;
    position: absolute;
    width: 100%;
}

</style>
