<template>
  <div id="app">
    <!-- Sidebar shown only if authenticated -->
    <AppSidebar v-if="isAuthenticated" v-model:visible="sidebarVisible" />

    <!-- Main content -->
    <div 
      class="flex-grow-1 main-content" 
      :class="{ 'sidebar-open': isAuthenticated && sidebarVisible }"
    >
      <AppNavbar 
        :sidebar-visible="sidebarVisible"
        @update:sidebar-visible="sidebarVisible = $event"
      />
      <main>
        
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import AppSidebar from '@/components/AppSidebar.vue'
import AppNavbar from '@/components/AppNavbar.vue'
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore()

const isAuthenticated = computed(() => authStore.isAuthenticated)

const sidebarVisible = ref(window.innerWidth >= 992)
const isManualToggle = ref(false)

const handleResize = () => {
  if (isManualToggle.value) return
  sidebarVisible.value = window.innerWidth >= 992
}

onMounted(() => {
  console.log('ON MOUNTED');
  authStore.fetchUser()
  window.addEventListener('resize', handleResize)
})

onBeforeUnmount(() => {
  window.removeEventListener('resize', handleResize)
})
</script>

<style scoped>
body {
  font-family: 'Ancizar Serif', serif !important;
}

#app {
  height: 100vh;
  overflow: hidden;
}

/* Sidebar width fixed */
.app-sidebar {
  width: 250px;
  transition: all 0.3s ease;
  /* Rounded edges for sidebar */
  border-top-right-radius: 12px;
  border-bottom-right-radius: 12px;
  box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
}

/* Main content styles */
.main-content {
  flex-grow: 1;
  transition: margin-left 0.3s ease;
  margin-left: 0; /* default */
}


/* When sidebar is open on desktop, push main content */
.sidebar-open {
  margin-left: 250px;
}
/* Push main content when sidebar is open on desktop */
@media (min-width: 992px) {
  .sidebar-open {
    margin-left: 280px; /* match sidebar width */
  }
}

/* On smaller screens, sidebar overlays content, so no margin */
@media (max-width: 991.98px) {
  .main-content,
  .sidebar-open {
    margin-left: 0 !important;
    width: 100%;
  }
}
/* Optional: adjust the router-view area height */
main.p-3 {
  min-height: calc(100vh - 56px); /* Adjust if navbar height changes */
  overflow-y: auto;
}
</style>
