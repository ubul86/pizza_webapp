import { publicApi } from "./api";

class ProductService {
    getProducts(params) {
        return publicApi
            .get('/product', {params})
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
