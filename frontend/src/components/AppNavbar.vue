<template>
  <nav class="bg-indigo-600 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
      <div class="flex items-center justify-between h-16">
        <!-- Logo -->
        <div class="flex items-center space-x-10">
          <router-link :to="{name:'home'}" class="flex items-center space-x-2">
            <svg class="w-8 h-8 text-white" viewBox="0 0 48 48" fill="none">
              <circle cx="24" cy="24" r="22" fill="#FFFFFF" />
              <path d="M16 32L24 16L32 32" stroke="#4F46E5" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span class="text-2xl font-extrabold text-white tracking-tight">MyShop</span>
          </router-link>
          <!-- Products Mega Menu -->
          <div class="group relative">
            <button class="flex items-center space-x-1 font-medium text-white hover:text-indigo-300">
              <span>Products</span>
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>

            <!-- Left-Aligned Mega Menu -->
            <div
              class="absolute hidden group-hover:grid grid-cols-2 lg:grid-cols-3 gap-6 top-full left-0 bg-white text-gray-800 shadow-lg rounded-md w-[500px] p-6 z-50"
            >
              <!-- Example Column 1 -->
              <div>
                <h3 class="font-bold text-indigo-600 mb-2">Lighting</h3>
                <ul class="space-y-1 text-sm">
                  <li><a href="#" class="hover:text-indigo-600">Lamps</a></li>
                  <li><a href="#" class="hover:text-indigo-600">Fixtures</a></li>
                  <li><a href="#" class="hover:text-indigo-600">Emergency</a></li>
                </ul>
              </div>

              <!-- Example Column 2 -->
              <div>
                <h3 class="font-bold text-indigo-600 mb-2">Electrical</h3>
                <ul class="space-y-1 text-sm">
                  <li><a href="#" class="hover:text-indigo-600">Switches</a></li>
                  <li><a href="#" class="hover:text-indigo-600">Extension Cords</a></li>
                  <li><a href="#" class="hover:text-indigo-600">PVC Box</a></li>
                </ul>
              </div>

              <!-- Example Column 3 -->
              <div class="hidden lg:block">
                <h3 class="font-bold text-indigo-600 mb-2">Kitchen</h3>
                <ul class="space-y-1 text-sm">
                  <li><a href="#" class="hover:text-indigo-600">Blender</a></li>
                  <li><a href="#" class="hover:text-indigo-600">Air Fryer</a></li>
                  <li><a href="#" class="hover:text-indigo-600">Ice Maker</a></li>
                </ul>
              </div>
            </div>
          </div>

        </div>

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center space-x-6">

          <!-- Desktop Search -->
          <div class="relative ml-6 hidden lg:block">
            <input
              type="text"
              placeholder="Search for products..."
              class="pl-10 pr-4 py-2 w-64 border border-indigo-300 bg-indigo-700 text-white placeholder-indigo-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-300"
            />
            <span class="absolute left-3 top-2.5 text-indigo-300">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </span>
          </div>

          <!-- Auth Links -->
          <template v-if="!isAuthenticated">
            <router-link to="/login" class="text-white font-semibold hover:bg-indigo-500 px-4 py-2 rounded transition" >Login</router-link>
            <router-link to="/register" class="bg-white text-indigo-600 font-semibold px-4 py-2 rounded shadow hover:bg-indigo-100 transition">Register</router-link>
          </template>
        </div>

        <!-- Mobile Hamburger -->
        <div class="md:hidden">
          <button @click="menuOpen = !menuOpen" class="text-white focus:outline-none">
            <svg v-if="!menuOpen" class="w-full h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Floating Menu -->
    <transition name="fade">
      <div
        v-if="menuOpen"
        class="absolute bg-white text-indigo-700  shadow-lg py-4 px-6 space-y-2 z-50 w-full"
      >
        <router-link @click="menuOpen = false" to="/products" class="block py-2 hover:bg-indigo-50 rounded">Products</router-link>
        <router-link @click="menuOpen = false" to="/login" class="block py-2 hover:bg-indigo-50 rounded">Login</router-link>
        <router-link @click="menuOpen = false" to="/register" class="block py-2 hover:bg-indigo-50 rounded">Register</router-link>
      </div>
    </transition>
  </nav>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth'
import { computed } from 'vue';

const authStore = useAuthStore()
const isAuthenticated = computed(() => authStore.isAuthenticated)
import { ref } from 'vue'
const menuOpen = ref(false)
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
` `