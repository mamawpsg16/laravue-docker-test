<template>
    <CSidebar v-if="isAuthenticated" visible unfoldable v-model:visible="visible">
      <CSidebarBrand class="d-flex justify-content-center align-items-center px-3">
        <img src="@/assets/images/xero.svg" alt="Logo" width="120" />
      </CSidebarBrand>
  
      <CSidebarNav>
        <CNavItem :active="isActive('/dashboard')">
            <RouterLink to="/dashboard" class="nav-link d-flex align-items-center">
                <i class="fa-solid fa-house me-2"></i>
                Dashboard
            </RouterLink>
            </CNavItem>

            <CNavItem :active="isActive('/tasks')">
            <RouterLink to="/tasks" class="nav-link d-flex align-items-center">
                <i class="fa-solid fa-list me-2"></i>
                Tasks
            </RouterLink>
            </CNavItem>

            <CNavItem :active="isActive('/features')">
            <RouterLink to="/features" class="nav-link d-flex align-items-center">
                <i class="fa-solid fa-star me-2"></i>
                Features
            </RouterLink>
        </CNavItem>

      </CSidebarNav>
    </CSidebar>
  </template>
  
  <script setup>
  import { computed, ref } from 'vue'
  import { useRoute } from 'vue-router'
  import { useAuthStore } from '@/stores/auth'
  
  import {
    CSidebar,
    CSidebarNav,
    CSidebarBrand,
    CNavItem,
  } from '@coreui/vue'
  
  import { cilSpeedometer, cilList, cilStar } from '@coreui/icons'
  import { CIcon } from '@coreui/icons-vue'
  
  const visible = ref(true)
  const route = useRoute()
  const authStore = useAuthStore()
  
  const user = computed(() => authStore.user)
  const isAuthenticated = computed(() => authStore.isAuthenticated && user.value?.email_verified_at)
  
  const isActive = (path) => route.path.startsWith(path)
  </script>
  