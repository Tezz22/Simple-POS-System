import { defineStore } from 'pinia'
import api from '@/services/api'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('access_token') || null,
        loading: false,
        error: null
    }),

    getters:{
        isAuthenticated: (state) => !!state.token,
        isAdmin: (state) => state.user?.role?.name === 'admin',
        isKasir: (state) => state.user?.role?.name === 'kasir',
    },

    actions: {
        async login(username, password) {
            this.loading = true;
            this.error = null;
            try {
                const response = await api.post('/login', {username, password});
                this.token = response.data.access_token;
                this.user = response.data.user;

                localStorage.setItem('access_token', this.token);

                return response.data;
            } catch (err) {
                this.error = err.response?.data?.message || 'Login failed';
                throw err;
            } finally {
                this.loading = false;
            }
        },
        
        async logout() {
            try {
                await api.post('/logout');
            } catch (err) {
                console.error('Logout error:', err);
            } finally {
                this.token = null;
                this.user = null;
                localStorage.removeItem('access_token');
            }
        },

        async fetchCurrentUser() {
            if (!this.token) return;
            try {
                const response = await api.get('/me');
                this.user = response.data.data;
            } catch (err) {
                this.logout();
            }
        }
    }
});