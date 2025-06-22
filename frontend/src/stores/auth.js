// stores/auth.js
import { defineStore } from 'pinia';
import { useAuth } from '@/composables/useAuth';

export const useAuthStore = defineStore('auth', () => {
  const {
    isAuthenticated,
    user,
    isLoading,
    isLoggingOut,
    login,
    register,
    logout,
    fetchUser,
    setAuthenticated,
    userRole,
    initialize
  } = useAuth();

  return {
    isAuthenticated,
    user,
    isLoading,
    login,
    register,
    logout,
    fetchUser,
    userRole,
    setAuthenticated,
    initialize,
    isLoggingOut
  };
});
