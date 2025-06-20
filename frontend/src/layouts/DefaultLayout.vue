<template>
  <div class="flex flex-col min-h-screen bg-gradient-to-b from-indigo-50 via-white to-gray-50  text-gray-900">
    <!-- <AppNavbar /> -->

    <!-- Main Content Area -->
    <main class="flex-grow py-10">
      <div class="max-w-7xl mx-auto px-4">
        <slot />
      </div>
    </main>

    <!-- <AppFooter /> -->
  </div>
</template>

<script setup>
import AppNavbar from '@/components/AppNavbar.vue'
import AppFooter from '@/components/AppFooter.vue'
</script>
