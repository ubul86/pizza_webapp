import { publicApi } from "./api";

class ProductService {
    getProducts() {
        return publicApi
            .get("/product")
            .then((response) => response.data.data)
            .catch((error) => {
                console.error("Failed to fetch products:", error);
                throw error;
            });
    }
    show(id) {
        return publicApi.get(`/product/${id}`).then((response) => response.data.data)
            .catch((error) => {
                console.error("Failed to get authenticated user:", error);
                throw error;
            });
    }

}

export default new ProductService();
