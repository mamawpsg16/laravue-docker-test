<template>
  <div class="flex items-center justify-center h-full">
    <label class="inline-flex items-center cursor-pointer relative">
      <input
        type="checkbox"
        class="sr-only peer"
        :checked="isChecked"
        @change="toggleStatus"
      />
      <div
        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-500 rounded-full peer peer-checked:bg-green-500 transition-all duration-300 relative"
      >
        <div
          class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full shadow transform transition-transform duration-300"
          :class="{ 'translate-x-5': isChecked }"
        ></div>
      </div>
    </label>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps(['params'])
const isChecked = ref(props.params.data.is_active)

// Watch for external data changes and update the local state
watch(() => props.params.data.is_active, (newValue) => {
  isChecked.value = newValue
})

function toggleStatus() {
  // Update local state first
  isChecked.value = !isChecked.value
  const newStatus = isChecked.value ? 'Active' : 'Inactive'
  
  // Update the grid data directly
  props.params.node.setDataValue('is_active', isChecked.value)
  props.params.node.setDataValue('status', newStatus)
  
  // Notify AG Grid that the data has changed
  props.params.api.refreshCells({
    rowNodes: [props.params.node],
    columns: ['status', 'is_active'],
    force: true
  })
  
  console.log('Status updated:', {
    id: props.params.data.id,
    is_active: isChecked.value,
    status: newStatus
  })
}
</script>