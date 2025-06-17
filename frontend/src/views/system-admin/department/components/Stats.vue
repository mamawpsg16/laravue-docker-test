<template>
    <div class="border rounded-lg border-gray-200  mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-2xl font-bold text-gray-900">Appointment Manager</h1>
              <p class="text-gray-600 mt-1">Manage your appointments and schedule</p>
            </div>
            <button
              @click="openCreateModal"
              class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center gap-2"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
              </svg>
              New Appointment
            </button>
          </div>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
              <div class="bg-blue-50 rounded-lg p-4">
                <div class="text-blue-600 text-sm font-medium">Total Appointments</div>
                <div class="text-2xl font-bold text-blue-900">{{ total }}</div>
              </div>
              <div class="bg-green-50 rounded-lg p-4">
                <div class="text-green-600 text-sm font-medium">Confirmed</div>
                <div class="text-2xl font-bold text-green-900">{{ confirmed }}</div>
              </div>
              <div class="bg-yellow-50 rounded-lg p-4">
                <div class="text-yellow-600 text-sm font-medium">Pending</div>
                <div class="text-2xl font-bold text-yellow-900">{{ pending }}</div>
              </div>
              <div class="bg-red-50 rounded-lg p-4">
                <div class="text-red-600 text-sm font-medium">Cancelled</div>
                <div class="text-2xl font-bold text-red-900">{{ cancelled }}</div>
              </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps(['total', 'confirmed', 'pending', 'cancelled'])

const emit = defineEmits(['openModal'])

function openCreateModal() {
  emit('openModal')
}
</script>
