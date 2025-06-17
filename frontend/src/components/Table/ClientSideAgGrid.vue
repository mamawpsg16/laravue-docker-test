<template>
  <SharedAgGrid
    :columnDefs="columnDefs"
    :rowModelType="'clientSide'"
    :rowData="data"
    @grid-ready="handleGridReady"
  />
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import SharedAgGrid from './SharedAgGrid.vue'

const data = ref([])
const gridApi = ref(null)

const columnDefs = ref([
  { field: 'id' },
  { field: 'name' },
  { field: 'email' }
])

function handleGridReady(params) {
  gridApi.value = params.api
  gridApi.value.sizeColumnsToFit()
  console.log('Client-side grid ready')
}

function onResize() {
  if (gridApi.value) {
    gridApi.value.sizeColumnsToFit()
  }
}

onMounted(() => {
  data.value = Array.from({ length: 500 }, (_, i) => ({
    id: i + 1,
    name: `Client ${i + 1}`,
    email: `client${i + 1}@demo.com`
  }))
  window.addEventListener('resize', onResize)
})

onUnmounted(() => {
  window.removeEventListener('resize', onResize)
})
</script>
