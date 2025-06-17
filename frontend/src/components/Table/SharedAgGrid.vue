<template>
  <ag-grid-vue
    class="ag-theme-alpine"
    style="height: 520px;"
    :columnDefs="columnDefs"
    :rowModelType="rowModelType"
    :datasource="datasource"
    :rowData="rowData"
    :pagination="true"
    :paginationPageSize="20"
    :enableSorting="true"
    :enableFilter="true"
    :animateRows="true"
    @grid-ready="emitGridReady"
    :defaultColDef="defaultColDef"
    :theme="'legacy'"
    @cell-value-changed="onCellValueChanged"
    @selectionChanged="onSelectionChanged"
    :autoSizeStrategy="autoSizeStrategy"
    :components="components"
    :suppressClickEdit="true"
  />
</template>

<script setup>
import { AgGridVue } from 'ag-grid-vue3'
import 'ag-grid-community/styles/ag-grid.css'
import 'ag-grid-community/styles/ag-theme-alpine.css'
import { ref } from 'vue'

defineProps([
  'columnDefs',
  'rowModelType',
  'datasource',
  'rowData',
  'components'
])

const rowSelection = ref({
  mode: 'multiRow',
})

const emit = defineEmits(['grid-ready', 'cell-value-changed'])

function onCellValueChanged(event) {
  console.log('Cell value changed:', {
    field: event.colDef.field,
    oldValue: event.oldValue,
    newValue: event.newValue,
    data: event.data
  })
  
  // Emit the event to parent components
  emit('cell-value-changed', event)
}

function onSelectionChanged(event) {
  console.log('Selection Changed', event)
}

function emitGridReady(params) {
  emit('grid-ready', params)
}

const defaultColDef = {
  sortable: true,
  filter: true,
  resizable: true,
  editable: false,
  suppressSizeToFit: false,
}

const autoSizeStrategy = ref({
  type: "fitGridWidth",
  defaultMinWidth: 100,
})
</script>