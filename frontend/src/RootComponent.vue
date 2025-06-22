<template>
    <div v-if="isLoading" class="loading-screen">
      <div class="spinner"></div>
      <p>Loading</p>
    </div>
    <App v-else />
  </template>
  
  <script setup>
  import { computed, onMounted } from 'vue';
  import { useAuthStore } from '@/stores/auth.js';
  import App from './App.vue';
  
  const authStore = useAuthStore();
  
  onMounted(() => {
    authStore.initialize();
  });
  
  const isLoading = computed(() => authStore.isLoading);
  </script>
  
  <style scoped>
  .loading-screen {
    /* your loading screen styles */
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
  }
  .spinner {
    /* spinner styles */
    width: 50px;
    height: 50px;
    border: 5px solid lightgray;
    border-top-color: #42b983;
    border-radius: 50%;
    animation: spin 1s linear infinite;
  }
  @keyframes spin {
    to { transform: rotate(360deg); }
  }
  </style>
  