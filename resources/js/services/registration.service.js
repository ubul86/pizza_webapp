import { publicApi } from "./api";

class RegistrationService {
    async registration(user) {
        const response = await publicApi.post('/registration', {
            name: user.name,
            email: user.email,
            password: user.password,
        });
        return response.data.data;
    }

}

export default new RegistrationService();
