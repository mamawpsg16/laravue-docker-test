<template>
  <section class="space-x-2">
    <div
      title="Status"
      @click="toggleStatus"
      class="relative inline-flex h-6 w-11 items-center rounded-full cursor-pointer transition-colors"
      :class="{'bg-green-500': isActive, 'bg-red-500': !isActive}"
    >
      <span
        class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-300 ease-in-out"
        :class="isActive ? 'translate-x-6' : 'translate-x-1'"
      />
    </div>
    <button @click="editRow" title="Edit" class="px-3 py-1 rounded text-blue-600 text-lg"><i class="bi bi-pencil-square"></i></button>
    <button @click="deleteRow" title="Delete" class="px-3 py-1 rounded text-red-600 text-lg"><i class="bi bi-trash3"></i></button>
  </section>
</template>

<script setup>
import { defineProps, defineEmits, computed } from 'vue'
import { ref, watch } from 'vue'

const props = defineProps({
  rowData: { type: Object, required: true },
})

const status = ref(props.rowData.is_active)

watch(() => props.rowData.is_active, (newVal) => {
  status.value = newVal
})


const emit = defineEmits(['update-status', 'edit-row', 'delete-row'])

const isActive = computed(() => !!props.rowData.is_active)

function toggleStatus() {
  const newStatus = !status.value
  const statusLabel = newStatus ? 'Active' : 'Inactive';
  status.value = newStatus  // update local state immediately
  emit('update-status', { ...props.rowData, is_active: newStatus, status: statusLabel})
}
function editRow() {
  emit('edit-row', props.rowData)
}
function deleteRow() {
  emit('delete-row', props.rowData)
}
</script>
