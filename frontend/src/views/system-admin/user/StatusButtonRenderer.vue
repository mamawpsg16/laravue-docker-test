<template>
  <section class="space-x-2">
    <button @click="toggleStatus" class="px-3 py-1 rounded" :class="{'bg-green-500': isActive, 'bg-red-500': !isActive}">{{ isActive ? 'Active' : 'Inactive' }}</button>
    <button @click="editRow" class="px-3 py-1 rounded bg-blue-500">Edit</button>
    <button @click="deleteRow" class="px-3 py-1 rounded bg-red-500 ">Delete</button>
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
