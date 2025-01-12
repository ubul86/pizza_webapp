import { privateApi, publicApi } from "./api";

class AuthService {
    async login(user) {
        const response = await publicApi.post('/auth/login', {
            email: user.email,
            password: user.password,
        });
        if (response.data.data.token) {
            localStorage.setItem('token', response.data.data.token);
            return response.data.data.token;
        }
        return response.data.data;
    }

    async loginToAdmin(user) {
        const response = await publicApi.post('/admin/auth/login', {
            email: user.email,
            password: user.password,
        });
        if (response.data.data.token) {
            localStorage.setItem('token', response.data.data.token);
            return response.data.data.token;
        }
        return response.data.data;
    }

    async logout() {
        await privateApi.post('/auth/logout');
        localStorage.removeItem('token');
    }

    getToken() {
        return localStorage.getItem('token');
    }
}

export default new AuthService();
