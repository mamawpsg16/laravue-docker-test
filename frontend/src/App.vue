<template>
  <div id="app" class="d-flex">
    <AppSidebar v-if="isAuthenticated" v-model:visible="sidebarVisible" />

    <div class="flex-grow-1">
      <AppNavbar
        :visible="sidebarVisible"
        @toggleSidebar="toggleSidebar"
      />
      <main class="p-3">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import AppSidebar from '@/components/AppSidebar.vue'
import AppNavbar from '@/components/AppNavbar.vue'
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()

const isAuthenticated = computed(() => authStore.isAuthenticated && authStore.user?.email_verified_at)

const sidebarVisible = ref(true)

const toggleSidebar = () => {
  sidebarVisible.value = !sidebarVisible.value
}

onMounted(() => {
  authStore.fetchUser()
})
</script>
