// composables/useAuth.js
import { ref } from 'vue';
import api from '@/services/axios'; // Adjust path if needed

const isAuthenticated = ref(false);
const user = ref(null);
const isLoading = ref(false);

async function login(credentials) {
  try {
    await api.get('/sanctum/csrf-cookie');
    const response = await api.post('/login', credentials);

    isAuthenticated.value = true;
    user.value = response.data.user;
    return response;
  } catch (error) {
    throw new Error('Invalid credentials');
  }
}

async function register(userData) {
  try {
    await api.get('/sanctum/csrf-cookie');
    const response = await api.post('/register', userData);

    isAuthenticated.value = true;
    user.value = response.data.user;
    return response;
  } catch (error) {
    throw new Error('Registration failed');
  }
}

async function logout() {
  try {
    await api.post('/logout');
    isAuthenticated.value = false;
    user.value = null;
  } catch (error) {
    throw new Error('Logout failed');
  }
}

function setAuthenticated(status) {
  isAuthenticated.value = status;
}

async function fetchUser() {
  isLoading.value = true;
  try {
    const response = await api.get('/api/user');
    isAuthenticated.value = true;
    user.value = response.data;
  } catch (error) {
    isAuthenticated.value = false;
    user.value = null;
  } finally {
    isLoading.value = false;
  }
}

export function useAuth() {
  return {
    isAuthenticated,
    user,
    isLoading,
    login,
    register,
    logout,
    fetchUser,
    setAuthenticated,
  };
}
