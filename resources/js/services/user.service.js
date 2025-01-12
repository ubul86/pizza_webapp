import { publicApi } from "./api";

class UserService {
    getUsers() {
        return publicApi
            .get("/users")
            .then((response) => response.data.data)
            .catch((error) => {
                console.error("Failed to fetch users:", error);
                throw error;
            });
    }
}

export default new UserService();
