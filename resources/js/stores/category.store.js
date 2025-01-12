import { defineStore } from 'pinia';
import categoryService from '@/services/category.service.js'

export const useCategoryStore = defineStore('category', {
    state: () => ({
        categories: [],
    }),
    actions: {
        async fetchCategories() {
            try {
                this.categories = await categoryService.getCategories();
            }
            catch(error) {
                console.log(error);
            }
        }
    },
});
