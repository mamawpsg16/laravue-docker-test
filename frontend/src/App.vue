<template>
  <div id="app" class="h-screen bg-gray-50 overflow-hidden">
    <AppSidebar v-if="isAuthenticated" v-model:visible="sidebarVisible" />

    <div 
      class="transition-all duration-300 ease-in-out h-full"
      :class="mainContentClasses"
    >
      <!-- ✅ Mobile Toggle Header -->
      <div v-if="isAuthenticated && isMobile" class="p-4 bg-white shadow-md flex items-center lg:hidden">
        <button @click="sidebarVisible = true" class="text-gray-700 focus:outline-none">
          <i class="bi bi-list text-2xl"></i>
        </button>
        <span class="ml-4 text-lg font-semibold">Menu</span>
      </div>

      <main class="h-full overflow-y-auto bg-white">
        <div class="p-4 w-full">
          <router-view />
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import AppSidebar from '@/components/AppSidebar.vue'
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()
const isAuthenticated = computed(() => authStore.isAuthenticated)

const sidebarVisible = ref(false)
const isMobile = ref(false)
const isMounted = ref(false)

const updateIsMobile = () => {
  const nowMobile = window.innerWidth < 992

  // If switching from mobile to desktop, show the sidebar
  if (!nowMobile && isMobile.value) {
    sidebarVisible.value = true
  }

  isMobile.value = nowMobile

  // Initial sidebar visibility
  if (!isMounted.value) {
    sidebarVisible.value = !nowMobile
  }
}

const mainContentClasses = computed(() => {
  if (!isAuthenticated.value) return 'w-full'
  return [
    'flex-1',
    {
      'ml-64': sidebarVisible.value && !isMobile.value,
      'ml-0': !sidebarVisible.value || isMobile.value
    }
  ]
})

onMounted(() => {
  authStore.fetchUser()
  updateIsMobile()
  isMounted.value = true
  window.addEventListener('resize', updateIsMobile)
})

onBeforeUnmount(() => {
  window.removeEventListener('resize', updateIsMobile)
})
</script>

<style scoped>
main::-webkit-scrollbar {
  width: 6px;
}
main::-webkit-scrollbar-track {
  background: #f1f5f9;
}
main::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 3px;
}
main::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
@media (max-width: 991px) {
  .main-content {
    margin-left: 0 !important;
  }
}
</style>