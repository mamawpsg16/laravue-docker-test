<template>
  <SharedAgGrid
    class="ag-theme-alpine w-full h-full"
    :columnDefs="columnDefs"
    :rowModelType="rowModelType"
    :rowData="rowData"
    :pagination="pagination"
    @grid-ready="onGridReady"
    :components="components"
  />
</template>

<script setup>
import SharedAgGrid from './SharedAgGrid.vue'

defineProps({
  columnDefs: Array,
  rowData: Array,
  rowModelType: {
    type: String,
    default: 'clientSide',
  },
  pagination: {
    type: Boolean,
    default: false,
  },
  components: Object,
})

const emit = defineEmits(['grid-ready'])

function onGridReady(params) {
  emit('grid-ready', params)
}
</script>
