import { ref, watch } from 'vue';
import api from '@/services/axios'; // Adjust path if needed

const isAuthenticated = ref(false);
const user = ref(null);
const isLoading = ref(false);
const userRole = ref(null);

// Automatically sync userRole when user changes
watch(user, (val) => {
  userRole.value = val?.role || null;
});

async function login(credentials) {
  try {
    await api.get('/sanctum/csrf-cookie', {
      baseURL: 'http://localhost:8002'
    });
    const response = await api.post('/login', credentials);

    isAuthenticated.value = true;
    user.value = response.data.user; // userRole updates via watch
    return response;
  } catch (error) {
    throw error;
  }
}

async function register(userData) {
  try {
    await api.get('/sanctum/csrf-cookie', {
      baseURL: 'http://localhost:8002'
    });
    const response = await api.post('/register', userData);

    isAuthenticated.value = true;
    user.value = response.data.user; // userRole updates via watch
    return response;
  } catch (error) {
    throw error;
  }
}

async function logout() {
  try {
    await api.post('/logout');
    isAuthenticated.value = false;
    user.value = null;
    userRole.value = null;
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
    const response = await api.get('/user');
    isAuthenticated.value = true;
    user.value = response.data; // userRole updates via watch
  } catch (error) {
    isAuthenticated.value = false;
    user.value = null;
    userRole.value = null;
  } finally {
    isLoading.value = false;
  }
}

export function useAuth() {
  return {
    isAuthenticated,
    user,
    userRole,
    isLoading,
    login,
    register,
    logout,
    fetchUser,
    setAuthenticated,
  };
}
