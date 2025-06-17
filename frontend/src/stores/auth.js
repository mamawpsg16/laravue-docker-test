// stores/auth.js
import { defineStore } from 'pinia';
import { useAuth } from '@/composables/useAuth';

export const useAuthStore = defineStore('auth', () => {
  const {
    isAuthenticated,
    user,
    isLoading,
    login,
    register,
    logout,
    fetchUser,
    setAuthenticated,
    userRole,
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
  };
});
