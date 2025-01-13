import { privateApi, publicApi } from './api'

class UserService {
    getUsers() {
        return publicApi
            .get("/user")
            .then((response) => response.data.data)
            .catch((error) => {
                console.error("Failed to fetch users:", error);
                throw error;
            });
    }

    getAuthenticatedUser() {
        return privateApi.get(`/user/get-authenticated-user`).then((response) => response.data.data)
            .catch((error) => {
                console.error("Failed to get authenticated user:", error);
                throw error;
            });
    }
}

export default new UserService();
