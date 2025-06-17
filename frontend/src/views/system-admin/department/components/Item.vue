<template>
  <div class="px-6 py-4 hover:bg-gray-50 transition-colors duration-150">
    <div class="flex items-center justify-between">
      <div class="flex-1">
        <div class="flex items-center gap-3 mb-2">
          <h3 class="text-lg font-medium text-gray-900">{{ appointment.title }}</h3>
          <span
            :class="badgeClass"
            class="px-2 py-1 text-xs font-medium rounded-full"
          >
            {{ capitalize(appointment.status) }}
          </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
          <div class="flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            {{ appointment.client }}
          </div>
          <div class="flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h3z" />
            </svg>
            {{ formatDate(appointment.date) }}
          </div>
          <div class="flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ appointment.time }}
          </div>
        </div>

        <p v-if="appointment.notes" class="mt-2 text-sm text-gray-500">{{ appointment.notes }}</p>
      </div>

      <div class="flex items-center gap-2 ml-4">
        <button
          @click="$emit('edit', appointment)"
          class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-150"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414
                 a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
          </svg>
        </button>
        <button
          @click="$emit('delete', appointment.id)"
          class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-150"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
                 a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10
                 V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const { appointment } = defineProps(['appointment'])
defineEmits(['edit', 'delete'])

const badgeClass = computed(() => ({
  'bg-green-100 text-green-800': appointment.status === 'confirmed',
  'bg-yellow-100 text-yellow-800': appointment.status === 'pending',
  'bg-red-100 text-red-800': appointment.status === 'cancelled',
}))

const capitalize = (text) => text.charAt(0).toUpperCase() + text.slice(1)
const formatDate = (d) => new Date(d).toLocaleDateString()
</script>
