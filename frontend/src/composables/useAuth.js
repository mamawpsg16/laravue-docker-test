import { ref, watch } from 'vue';
import { authApi, apiV1 } from '@/services/axios'; // Adjust path if needed

const isAuthenticated = ref(false);
const user = ref(null);
const isLoading = ref(false);
const userRole = ref(null);
const isLoggingOut = ref(false);

// Automatically sync userRole when user changes
watch(user, (val) => {
  userRole.value = val?.role || null;
});

async function login(credentials) {
  try {
    await authApi.get('/sanctum/csrf-cookie');
    const response = await apiV1.post('/login', credentials);

    isAuthenticated.value = true;
    user.value = response.data.user; // userRole updates via watch
    return response;
  } catch (error) {
    throw error;
  }
}

async function register(userData) {
  try {
    await authApi.get('/sanctum/csrf-cookie');
    const response = await apiV1.post('/register', userData);

    isAuthenticated.value = true;
    user.value = response.data.user; // userRole updates via watch
    return response;
  } catch (error) {
    throw error;
  }
}

async function logout() {
  try {
    isLoggingOut.value = true;
    await apiV1.post('/logout');
    isAuthenticated.value = false;
    user.value = null;
    userRole.value = null;
    isLoggingOut.value = false;
  } catch (error) {
    isAuthenticated.value = false;
    user.value = null;
    userRole.value = null;
    isLoggingOut.value = false;
    throw new Error('Logout failed');
  }
}

function setAuthenticated(status) {
  isAuthenticated.value = status;
}

async function fetchUser() {
  // 👈 Prevent multiple simultaneous calls
  if (isLoading.value) {
    return;
  }

  isLoading.value = true;
  try {
    const response = await apiV1.get('/user');
    isAuthenticated.value = true;
    user.value = response.data; // userRole updates via watch
  } catch (error) {
    // Session might be expired or user not authenticated
    isAuthenticated.value = false;
    user.value = null;
    userRole.value = null;
    console.log('User not authenticated or session expired');
  } finally {
    isLoading.value = false;
  }
}



async function initialize() {
  await this.fetchUser()
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
    initialize,
    isLoggingOut
  };
}