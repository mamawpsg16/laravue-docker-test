<template>
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
      <!-- Custom sidebar toggle button -->
      <div
        v-if="isAuthenticated"
        class="d-lg-none me-2"
        style="cursor: pointer; padding: 8px; user-select: none;"
        @click.stop.prevent="toggleSidebar"
        role="button"
        tabindex="0"
        aria-label="Toggle sidebar"
        @keydown.enter="toggleSidebar"
        @keydown.space.prevent="toggleSidebar"
      >
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="3" y1="6" x2="21" y2="6"></line>
          <line x1="3" y1="12" x2="21" y2="12"></line>
          <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
      </div>

      <!-- Always visible user dropdown on the right -->
      <div class="ms-auto" v-if="isAuthenticated">
        <div class="dropdown">
          <button 
            class="btn btn-light border-0 d-flex align-items-center gap-2 dropdown-toggle" 
            type="button" 
            data-bs-toggle="dropdown" 
            aria-expanded="false"
          >
            <span class="fw-semibold">{{ user?.name }}</span>
          </button>

          <ul class="dropdown-menu dropdown-menu-end shadow-sm rounded-3 p-2 border">
            <li>
              <RouterLink :to="{ name: 'profile' }" class="dropdown-item py-2 text-decoration-none text-dark">
                Profile
              </RouterLink>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <button @click="logout" class="dropdown-item py-2 text-danger border-0 bg-transparent w-100 text-start">
                Logout
              </button>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const props = defineProps({
  sidebarVisible: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['update:sidebar-visible'])

const router = useRouter()
const authStore = useAuthStore()

const user = computed(() => authStore.user)
const isAuthenticated = computed(() => authStore.isAuthenticated && authStore.user.email_verified_at)

const toggleSidebar = () => {
  console.log('Single toggle called, current:', props.sidebarVisible)
  emit('update:sidebar-visible', !props.sidebarVisible)
}

const logout = async () => {
  await authStore.logout()
  router.push({ name: 'login' })
}
</script>