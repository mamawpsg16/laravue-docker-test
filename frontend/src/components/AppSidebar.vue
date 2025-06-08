<template>
  <Teleport to="body">
    <div class="sidebar-wrapper">
      <!-- Backdrop for mobile -->
      <div 
        v-if="props.visible && isMobile" 
        class="sidebar-backdrop"
        @click="emit('update:visible', false)"
      ></div>

      <!-- Sidebar with transition -->
      <Transition name="slide-sidebar" appear>
        <nav 
          v-if="props.visible"
          class="sidebar d-flex flex-column vh-100"
          :class="{ 'sidebar-desktop': !isMobile, 'sidebar-mobile': isMobile }"
          @click.stop
          aria-label="Main sidebar navigation"
        >
          <!-- Brand/logo -->
          <div class="sidebar-brand d-flex justify-content-center align-items-center px-3 mt-3 mb-4">
            <img src="@/assets/images/xero.svg" alt="Logo" width="120" />
          </div>

          <!-- Navigation links -->
          <ul class="nav flex-column px-3">
            <li class="nav-item mb-2">
              <RouterLink 
                to="/dashboard"
                class="nav-link d-flex align-items-center"
                :class="{ active: isActive('/dashboard') }"
              >
                <i class="bi bi-house-fill me-2"></i>
                Dashboard
              </RouterLink>
            </li>

            <li class="nav-item mb-2">
              <RouterLink 
                to="/tasks"
                class="nav-link d-flex align-items-center"
                :class="{ active: isActive('/tasks') }"
              >
                <i class="bi bi-list-check me-2"></i>
                Tasks
              </RouterLink>
            </li>

            <!-- Features with dynamic submenu -->
            <li class="nav-item mb-2">
              <div
                class="nav-link d-flex align-items-center justify-content-between mb-1"
                @click="toggleSubmenu('features')"
                style="cursor: pointer;"
              >
                <span><i class="bi bi-lightning-charge-fill me-2"></i> Features</span>
                <i :class="openSubmenus.features ? 'bi bi-chevron-down' : 'bi bi-chevron-compact-right'"></i>
              </div>
              <ul  v-show="openSubmenus.features"  class="nav flex-column ms-3 submenu">
                <li 
                  v-for="child in featuresChildren" 
                  :key="child.path" 
                  class="nav-item mb-1"
                >
                  <RouterLink 
                    :to="`/features/${child.path}`"
                    class="nav-link"
                    :class="{ active: isActive(`/features/${child.path}`) }"
                  >
                    {{ formatLabel(child.name) }}
                  </RouterLink>
                </li>
              </ul>
            </li>
          </ul>
        </nav>
      </Transition>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter, RouterLink } from 'vue-router'

const props = defineProps({
  visible: {
    type: Boolean,
    required: true
  }
})
const emit = defineEmits(['update:visible'])

const route = useRoute()
const router = useRouter()

// Detect if mobile (window width < 992px)
const isMobile = computed(() => window.innerWidth < 992)

// Function to check active route for link styling
const isActive = (path) => route.path === path

// Submenu open/close state
const openSubmenus = ref({
  features: false
})

// Toggle submenu visibility
function toggleSubmenu(key) {
  openSubmenus.value[key] = !openSubmenus.value[key]
}

// Retrieve children routes for 'features'
const featuresChildren = computed(() => {
  const featuresRoute = router.options.routes.find(r => r.path === '/features')
  return featuresRoute && featuresRoute.children ? featuresRoute.children : []
})

// Format route name for display
function formatLabel(name) {
  return name.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase())
}
</script>

<style scoped>
/* Sidebar wrapper fills viewport height */
.sidebar-wrapper {
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  z-index: 1050;
}

/* Backdrop for mobile overlay */
.sidebar-backdrop {
  position: fixed;
  inset: 0;
  background-color: rgba(0,0,0,0.5);
  z-index: 1040;
}

/* Base sidebar styles */
.sidebar {
  background-color: #f8f9fa;
  color: #212529;
  overflow-y: auto;
  box-shadow: 0 2px 12px rgb(0 0 0 / 0.1);
  transition: transform 0.3s ease-in-out;
}

/* Sidebar on desktop */
.sidebar-desktop {
  width: 250px;
  height: 100vh;
  position: fixed;
  top: 0;
  left: 0;
  transform: translateX(0) !important;
  z-index: 1050;
}

/* Sidebar on mobile */
.sidebar-mobile {
  width: 200px;
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  z-index: 1051;
}

/* Brand/logo */
.sidebar-brand img {
  max-width: 100%;
}

/* Navigation link styles */
.nav-link {
  color: #495057;
  font-weight: 500;
  padding: 0.5rem 0.75rem;
  border-radius: 0.375rem;
  transition: background-color 0.2s ease;
}

.nav-link:hover {
  background-color: #e9ecef;
  color: #212529;
  text-decoration: none;
}

.nav-link.active {
  background-color: #0d6efd;
  color: white !important;
}

/* Submenu styling */
.submenu {
  padding-left: 1rem;
  transition: all 0.3s ease;
}

.submenu .nav-link {
  font-size: 0.9rem;
  padding-left: 1.5rem;
}

/* Transition classes for sidebar slide-in/out */
.slide-sidebar-enter-active,
.slide-sidebar-leave-active {
  transition: transform 0.3s ease-in-out;
}

.slide-sidebar-enter-from,
.slide-sidebar-leave-to {
  transform: translateX(-100%);
}

.slide-sidebar-enter-to,
.slide-sidebar-leave-from {
  transform: translateX(0);
}

</style>
