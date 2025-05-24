<template>
  <CNavbar expand="lg" color-scheme="light">
    <CContainer fluid>
      <!-- <RouterLink to="/" class="navbar-brand">
        <img src="@/assets/images/xero.svg" alt="Logo" width="100" height="50" />
      </RouterLink> -->

      <CNavbarToggler
        v-if="isAuthenticated"
        aria-label="Toggle navigation"
        :aria-expanded="visible"
        @click="$emit('toggleSidebar')"
      />

      <CCollapse class="navbar-collapse" :visible="visible">
        <CNavbarNav class="ms-auto" v-if="isAuthenticated">
          <CDropdown variant="nav-item" placement="bottom-end">
            <CDropdownToggle color="secondary" class="nav-link px-2 py-1">
              Hi, {{ user?.name }}
            </CDropdownToggle>
            <CDropdownMenu>
              <CDropdownItem class="px-2 py-1">
                <RouterLink :to="{ name: 'profile' }" class="dropdown-item px-0 py-0">Profile</RouterLink>
              </CDropdownItem>

              <CDropdownDivider />
              <CDropdownItem @click="logout">Logout</CDropdownItem>
            </CDropdownMenu>
          </CDropdown>
        </CNavbarNav>
      </CCollapse>
    </CContainer>
  </CNavbar>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import {
  CNavbar,
  CContainer,
  CNavbarToggler,
  CCollapse,
  CNavbarNav,
  CNavItem,
  CDropdown,
  CDropdownToggle,
  CDropdownMenu,
  CDropdownItem,
  CDropdownDivider,
} from '@coreui/vue'

// Accept visible prop from parent to control collapse state
const props = defineProps({
  visible: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['toggleSidebar'])

const router = useRouter()
const authStore = useAuthStore()

const user = computed(() => authStore.user)
const isAuthenticated = computed(() => authStore.isAuthenticated && authStore.user.email_verified_at)

const logout = async () => {
  await authStore.logout()
  router.push({ name: 'login' })
}
</script>
