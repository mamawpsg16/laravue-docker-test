<!-- GridSwitcher.vue -->
<template>
  <div class="p-4">
    <div class="mb-4">
      <button @click="mode = 'client'" class="mr-2 px-4 py-2 bg-blue-500 text-white rounded">Client Side</button>
      <button @click="mode = 'server'" class="px-4 py-2 bg-green-500 text-white rounded">Server Side</button>
    </div>
    <ClientSideGrid v-if="mode === 'client'" />
    <ServerSideGrid v-else />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import ClientSideGrid from '@/components/Table/ClientsideAgGrid.vue'
import ServerSideGrid from '@/components/Table/ServerSideAgGrid.vue'

const mode = ref('client')
</script>
