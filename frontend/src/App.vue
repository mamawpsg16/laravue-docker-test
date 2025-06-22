<template>
  <div id="app" class="h-screen flex flex-col bg-gray-50">
    <!-- Show Navbar and Footer only if NOT authenticated -->
    <AppNavbar v-if="!isAuthenticated" />
    
    <div>
      <!-- Sidebar only for authenticated users -->
      <AppSidebar v-if="isAuthenticated" v-model:visible="sidebarVisible" />

      <!-- Main Content -->
      <div 
        class="transition-all duration-300 ease-in-out flex-1 flex flex-col"
        :class="mainContentClasses"
      >
        <!-- Mobile Toggle Header - Only for authenticated users -->
        <div v-if="isAuthenticated && isMobile" class="p-4 bg-white shadow-md flex items-center lg:hidden">
          <button @click="sidebarVisible = true" class="text-gray-700 focus:outline-none">
            <i class="bi bi-list text-2xl"></i>
          </button>
          <span class="ml-4 text-lg font-semibold">Menu</span>
        </div>

        <main class="flex-1 bg-white">
          <router-view />
        </main>
      </div>
    </div>

    <AppFooter v-if="!isAuthenticated" />
    <BackToTop v-if="!isAuthenticated"/>
  </div>
</template>

<script setup>
import AppSidebar from '@/components/AppSidebar.vue'
import AppNavbar from '@/components/AppNavbar.vue'
import AppFooter from '@/components/AppFooter.vue'
import BackToTop from '@/components/BackToTop.vue'
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()
const isAuthenticated = computed(() => authStore.isAuthenticated)

const sidebarVisible = ref(false)
const isMobile = ref(false)
const isMounted = ref(false)

const updateIsMobile = () => {
  const nowMobile = window.innerWidth < 992

  if (!nowMobile && isMobile.value) {
    sidebarVisible.value = true
  }

  isMobile.value = nowMobile

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
  // ❌ Remove this line - auth initialization now happens in router
  // authStore.fetchUser()
  
  updateIsMobile()
  isMounted.value = true
  window.addEventListener('resize', updateIsMobile)
})

onBeforeUnmount(() => {
  window.removeEventListener('resize', updateIsMobile)
})
</script>