import { publicApi, privateApi } from "./api";

class AdminProductService {
    getProducts(params) {
        return publicApi
            .get("/admin/product", {params})
            .then((response) => response.data.data)
            .catch((error) => {
                console.error("Failed to fetch products:", error);
                throw error;
            });
    }

    store(item) {
        return privateApi.post(`/admin/product`, item).then((response) => response.data.data)
            .catch((error) => {
                throw error;
            });
    }

    show(id) {
        return publicApi.get(`/admin/product/${id}`).then((response) => response.data.data)
            .catch((error) => {
                console.error("Failed to get authenticated user:", error);
                throw error;
            });
    }

    update(item) {
        return privateApi.put(`/admin/product/${item.id}`, item).then((response) => response.data.data)
            .catch((error) => {
                throw error;
            });
    }

    deleteItem(id) {
        return privateApi
            .delete(`/admin/product/${id}`)
            .then((response) => response.data.data)
            .catch((error) => {
                throw error;
            });
    }

    bulkDelete(ids) {
        const idString = ids.join(",");
        return privateApi
            .delete("/admin/product/bulk-destroy", {
                params: { ids: idString }
            })
            .then((response) => response.data.data)
            .catch((error) => {
                throw error;
            });
    }

    uploadImages(itemId, formData) {
        return privateApi
            .post(`/admin/product/upload-images/${itemId}`, formData)
            .then((response) => response.data.data)
            .catch((error) => {
                throw error;
            });
    }

    setImageToFirst(productId, imageId) {
        return privateApi
            .post(`/admin/product/set-image-to-first`, {
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
            .post(`/admin/product/delete-image`, {
                productId,
                imageId
            })
            .then((response) => response.data.data)
            .catch((error) => {
                throw error;
            });
    }
}

export default new AdminProductService();
