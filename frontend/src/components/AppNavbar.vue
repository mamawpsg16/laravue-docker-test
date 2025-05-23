<!-- src/components/AppNavbar.vue -->
<template>
    <CNavbar expand="lg" color-scheme="light" class="bg-light">
      <CContainer fluid>
        <RouterLink to="/" class="navbar-brand">
          <img src="@/assets/images/xero.svg" alt="Logo" width="100" height="50" />
        </RouterLink>
  
        <CNavbarToggler v-if="isAuthenticated" aria-label="Toggle navigation" :aria-expanded="visible" @click="visible = !visible" />
        <CCollapse class="navbar-collapse" :visible="visible">
            <CNavbarNav class="me-auto mb-2 mb-lg-0" v-if="isAuthenticated">
                <CNavItem>
                <RouterLink :to="{ name: 'dashboard' }" class="nav-link">Dashboard</RouterLink>
                </CNavItem>
                <CNavItem>
                <RouterLink :to="{ name: 'tasks' }" class="nav-link">Tasks</RouterLink>
                </CNavItem>
                <CNavItem>
                <RouterLink :to="{ name: 'features' }" class="nav-link">Features</RouterLink>
                </CNavItem>
            </CNavbarNav>
  
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
  import { ref, computed } from 'vue';
  import { useRouter, RouterLink } from 'vue-router';
  import { useAuthStore } from '@/stores/auth';
  import {
    CNavbar, CContainer, CNavbarToggler, CCollapse,
    CNavbarNav, CNavItem, CDropdown, CDropdownToggle, CDropdownMenu, CDropdownItem, CDropdownDivider
  } from '@coreui/vue';
  
  const visible = ref(false);
  const router = useRouter();
  const authStore = useAuthStore();
  
  const user = computed(() => authStore.user);
  
  const isAuthenticated = computed(() => {
    return authStore.isAuthenticated && authStore.user.email_verified_at;
  });
  const logout = async () => {
    await authStore.logout();
    router.push({ name: 'login' });
  };
  </script>
  