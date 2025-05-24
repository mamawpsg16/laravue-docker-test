<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import {
  CSidebar,
  CSidebarNav,
  CSidebarBrand,
  CNavItem,
} from '@coreui/vue'

// Use defineModel to handle v-model binding for 'visible'
const visible = defineModel<boolean>()

const route = useRoute()
const authStore = useAuthStore()

const user = computed(() => authStore.user)
const isAuthenticated = computed(() => authStore.isAuthenticated && user.value?.email_verified_at)

const isActive = (path: string) => route.path.startsWith(path)
</script>

<template>
  <CSidebar
    v-model:visible="visible"
    color-scheme="light"
    class="bg-light d-flex flex-column vh-100"
  >
    <CSidebarBrand class="d-flex justify-content-center align-items-center px-3 mt-3">
      <img src="@/assets/images/xero.svg" alt="Logo" width="120" />
    </CSidebarBrand>

    <CSidebarNav class="flex-grow-1">
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
