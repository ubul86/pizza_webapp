import { publicApi, privateApi } from "./api";

class OrderService {
    store(item) {
        return publicApi.post(`/order`, item).then((response) => response.data.data)
            .catch((error) => {
                throw error;
            });
    }

    getOrders() {
        return privateApi
            .get("/order")
            .then((response) => response.data.data)
            .catch((error) => {
                console.error("Failed to fetch orders:", error);
                throw error;
            });
    }

    getOrderByUuId(uuid) {
        return publicApi
            .get(`/order/${uuid}`)
            .then((response) => response.data.data)
            .catch((error) => {
                console.error("Failed to get order:", error);
                throw error;
            });
    }

}
export default new OrderService();
