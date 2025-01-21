import { privateApi, publicApi } from "./api";

class AuthService {
    async login(user) {
        const response = await publicApi.post('/auth/login', {
            email: user.email,
            password: user.password,
        });
        if (response.data.data) {
            const jwtToken = response.data.data.token;
            const refreshToken = response.data.data.refresh_token;
            localStorage.setItem('token', jwtToken);
            localStorage.setItem('refreshToken', refreshToken);
            return response.data.data.token;
        }
        return response.data.data;
    }

    async loginToAdmin(user) {
        const response = await publicApi.post('/admin/auth/login', {
            email: user.email,
            password: user.password,
        });
        if (response.data.data) {
            const jwtToken = response.data.data.token;
            const refreshToken = response.data.data.refresh_token;
            localStorage.setItem('token', jwtToken);
            localStorage.setItem('refreshToken', refreshToken);
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

    refreshToken()
    {
        const refreshToken = localStorage.getItem('refreshToken');
        return privateApi.get('/auth/refresh-token', {
            headers: {
                'Authorization': `Bearer ${refreshToken}`
            }
        }).then((response) => {
            return response.data.data.token;
        })
            .catch((error) => {
                console.error("Failed to fetch token:", error);
                throw error;
            });
    }
}

export default new AuthService();
