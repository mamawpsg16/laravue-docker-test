<template>
  <div id="app" class="d-flex">
    <AppSidebar v-if="isAuthenticated" />

    <div class="flex-grow-1">
      <AppNavbar @toggleSidebar="toggleSidebar" />
      <main class="p-3">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import AppSidebar from '@/components/AppSidebar.vue';
import AppNavbar from '@/components/AppNavbar.vue';
import { onMounted, ref, computed } from 'vue';
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();

const isAuthenticated = computed(() => authStore.isAuthenticated && authStore.user?.email_verified_at)

// Sidebar visibility state can be handled here if you want to pass down or use provide/inject
const toggleSidebar = () => {
  // implement toggle logic if needed
};

onMounted(() => {
  authStore.fetchUser();
});
</script>
