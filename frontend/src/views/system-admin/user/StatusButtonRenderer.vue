<template>
  <button
    :class="isActive ? 'bg-green-500' : 'bg-gray-400'"
    class="px-3 py-1 rounded text-white"
    @click="toggleStatus"
  >
    {{ isActive ? 'Active' : 'Inactive' }}
  </button>
</template>

<script setup>
import { defineProps, defineEmits, computed } from 'vue'

const props = defineProps({
  rowData: {
    type: Object,
    required: true,
  },
})

const emit = defineEmits(['update-status'])

// Compute active state from rowData.is_active (assuming boolean or 0/1)
const isActive = computed(() => !!props.rowData.is_active)

function toggleStatus() {
  const newStatus = isActive.value ? 0 : 1 // or false/true depending on your data
  emit('update-status', { ...props.rowData, is_active: newStatus })
}
</script>
