<template>
  <Teleport to="body">
    <div class="sidebar-wrapper">
      <!-- Backdrop for mobile -->
      <div 
        v-if="props.visible && isMobile" 
        class="fixed inset-0 bg-black bg-opacity-50 z-40"
        @click="emit('update:visible', false)"
      ></div>

      <!-- Sidebar -->
      <Transition name="slide-sidebar" appear>
        <nav 
          v-if="props.visible"
          class="sidebar flex flex-col h-screen"
          :class="{ 'sidebar-desktop': !isMobile, 'sidebar-mobile': isMobile }"
          @click.stop
        >
          <!-- Brand/logo -->
          <div class="flex flex-col px-3 pt-6 pb-1 border-b border-gray-200">
            <div class="flex flex-col items-center grow">
              <img src="@/assets/images/xero.svg" alt="User Profile" class="h-24 w-24 rounded-full object-cover" />
              <div class="flex-grow" />
              <p class="text-lg font-medium">{{ user }}</p>
            </div>
          </div>

          <!-- Navigation links -->
          <ul class="flex flex-col px-3 py-4 space-y-1">
            <li v-for="link in mainLinks" :key="link.path">
              <RouterLink 
                :to="link.path"
                class="nav-link flex items-center"
                :class="{ active: isActive(link.path) }"
              >
                <i v-if="link.meta?.icon" :class="`${link.meta.icon} mr-3 text-base`"></i>
                <span class="text-base font-medium">{{ formatLabel(link.name) }}</span>
              </RouterLink>
            </li>

            <!-- System Admin Menu -->
            <li>
              <div
                class="nav-link flex items-center justify-between cursor-pointer"
                @click="toggleSubmenu('system_admin')"
              >
                <span class="flex items-center">
                  <i class="bi bi-person-fill-lock mr-3 text-base"></i>
                  <span class="text-base font-medium">System Admin</span>
                </span>
                <i 
                  class="text-sm transition-transform duration-200"
                  :class="openSubmenus.system_admin ? 'bi bi-chevron-down' : 'bi bi-chevron-right'"
                ></i>
              </div>
              <ul v-show="openSubmenus.system_admin" class="flex flex-col ml-6 mt-1 space-y-1 submenu">
                <li 
                  v-for="child in systemAdminChildren" 
                  :key="child.path"
                >
                  <RouterLink 
                    :to="`/system-admin/${child.path}`"
                    class="nav-link block flex items-center"
                    :class="{ active: isActive(`/system-admin/${child.path}`) }"
                  >
                    <i v-if="child.meta?.icon" :class="`${child.meta.icon} mr-2 text-sm`"></i>
                    <span class="text-sm font-medium">{{ formatLabel(child.name) }}</span>
                  </RouterLink>
                </li>
              </ul>
            </li>
          </ul>

          <!-- Logout -->
          <button
            @click="logout"
            class="mt-auto m-3 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
          >
            Logout
          </button>
        </nav>
      </Transition>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { useRoute, useRouter, RouterLink } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const props = defineProps({
  visible: Boolean
})
const emit = defineEmits(['update:visible'])

const route = useRoute()
const router = useRouter()

const isMobile = ref(window.innerWidth < 992)

const handleResize = () => {
  isMobile.value = window.innerWidth < 992
}

onMounted(() => {
  handleResize()
  window.addEventListener('resize', handleResize)
})

const isActive = (path) => route.path === path

const openSubmenus = ref({
  features: false,
  system_admin: false
})
function toggleSubmenu(key) {
  openSubmenus.value[key] = !openSubmenus.value[key]
}

const mainLinks = computed(() => {
  return router.options.routes.filter(r => 
    r.meta?.requiresAuth && !r.children
  )
})

const systemAdminChildren = computed(() => {
  const systemAdminRoute = router.options.routes.find(r => r.path === '/system-admin')
  return systemAdminRoute?.children || []
})

function formatLabel(name) {
  return name.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase())
}

const authStore = useAuthStore();
const user = authStore.user.full_name;
const logout = async () => {
  await authStore.logout()
  router.push({ name: 'login' })
}

// Auto-close sidebar on mobile route change
watch(() => route.path, () => {
  if (isMobile.value) emit('update:visible', false)
})
</script>

<style scoped>
.sidebar-wrapper {
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  z-index: 50;
}

.sidebar {
  @apply bg-gray-50 text-gray-800 overflow-y-auto shadow-xl;
  transition: transform 0.3s ease-in-out;
}

.sidebar-desktop {
  @apply w-64 h-screen fixed top-0 left-0 z-50;
  transform: translateX(0) !important;
}

.sidebar-mobile {
  @apply w-52 fixed top-0 left-0 h-screen z-50;
}

.nav-link {
  @apply text-gray-700 font-normal py-3 px-3 rounded-lg transition-all duration-200;
}

.nav-link:hover {
  @apply bg-gray-100 text-gray-900 no-underline;
}

.nav-link.active {
  @apply bg-blue-500 text-white shadow-sm;
}

.submenu {
  @apply transition-all duration-300;
}

.submenu .nav-link {
  @apply py-2.5 px-3 rounded-md;
}

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
