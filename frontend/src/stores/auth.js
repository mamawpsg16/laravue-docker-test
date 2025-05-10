import { defineStore } from 'pinia';
import api from '@/services/axios'; // Adjust path if needed

export const useAuthStore = defineStore('auth', {
  state: () => ({
    isAuthenticated: false,
    user: null,
  }),
  actions: {
    async login(credentials) {
      try {
        await api.get('/sanctum/csrf-cookie');
        const response = await api.post('/login', credentials);

        this.isAuthenticated = true;
        this.user = response.data.user;
        return response;
      } catch (error) {
        throw new Error('Invalid credentials');
      }
    },

    async register(userData) {
      try {
        await api.get('/sanctum/csrf-cookie');
        const response = await api.post('/register', userData);

        this.isAuthenticated = true;
        this.user = response.data.user;
        return response;
      } catch (error) {
        throw new Error('Registration failed');
      }
    },

    async logout() {
      try {
        await api.post('/logout');
        this.isAuthenticated = false;
        this.user = null;
      } catch (error) {
        throw new Error('Logout failed');
      }
    },

    setAuthenticated(status) {
      this.isAuthenticated = status;
    },

    async fetchUser() {
      this.isLoading = true;
      try {
        const response = await api.get('/api/user');
        this.isAuthenticated = true;
        this.user = response.data;
      } catch (error) {
        this.isAuthenticated = false;
        this.user = null;
      } finally {
        this.isLoading = false;
      }
    }
  },
});
