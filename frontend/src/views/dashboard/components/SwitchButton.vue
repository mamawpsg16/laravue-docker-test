<!-- SwitchButton.vue -->
<template>
  <button
    :class="status === 'active' ? 'bg-green-500' : 'bg-gray-400'"
    class="px-3 py-1 rounded text-white"
    @click="toggleStatus"
  >
    {{ status }}
  </button>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue'

const props = defineProps({
  status: String,
  rowData: Object,
})

const emit = defineEmits(['update-status'])

function toggleStatus() {
  const newStatus = props.status === 'active' ? 'inactive' : 'active'
  emit('update-status', { ...props.rowData, status: newStatus })
}
</script>
