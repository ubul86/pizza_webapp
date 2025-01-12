import { publicApi } from "./api";

class RegistrationService {
    async registration(user) {
        return publicApi.post(`/registration`, {
            name: user.name,
            email: user.email,
            password: user.password,
        }).then((response) => response.data.data)
            .catch((error) => {
                throw error;
            });
    }

    async activation(token) {
        return publicApi.post(`/activation`, {token}).then((response) => response.data.data)
            .catch((error) => {
                throw error;
            });
    }

}

export default new RegistrationService();
