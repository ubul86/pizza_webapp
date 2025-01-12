import { publicApi } from "./api";

class CategoryService {
    getCategories() {
        return publicApi
            .get("/category")
            .then((response) => response.data.data)
            .catch((error) => {
                console.error("Failed to fetch categories:", error);
                throw error;
            });
    }
}

export default new CategoryService();
