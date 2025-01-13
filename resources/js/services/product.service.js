import { publicApi, privateApi } from "./api";

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

    store(item) {
        return privateApi.post(`/product`, item).then((response) => response.data.data)
            .catch((error) => {
                throw error;
            });
    }

    update(item) {
        return privateApi.put(`/product/${item.id}`, item).then((response) => response.data.data)
            .catch((error) => {
                throw error;
            });
    }

    deleteItem(id) {
        return privateApi
            .delete(`/product/${id}`)
            .then((response) => response.data.data)
            .catch((error) => {
                throw error;
            });
    }

    bulkDelete(ids) {
        const idString = ids.join(",");
        return privateApi
            .delete("/product/bulk-destroy", {
                params: { ids: idString }
            })
            .then((response) => response.data.data)
            .catch((error) => {
                throw error;
            });
    }

    uploadImages(itemId, formData) {
        return privateApi
            .post(`/product/upload-images/${itemId}`, formData)
            .then((response) => response.data.data)
            .catch((error) => {
                throw error;
            });
    }

    setImageToFirst(productId, imageId) {
        return privateApi
            .post(`/product/set-image-to-first`, {
                productId,
                imageId
            })
            .then((response) => response.data.data)
            .catch((error) => {
                throw error;
            });
    }

    deleteImage(productId, imageId) {
        return privateApi
            .post(`/product/delete-image`, {
                productId,
                imageId
            })
            .then((response) => response.data.data)
            .catch((error) => {
                throw error;
            });
    }
}

export default new ProductService();
