<template>
  <div>
    <button @click="pinFirstColumn">Pin First Column Left</button>
    <button @click="unpinAll">Unpin All Columns</button>

    <table>
      <thead>
        <tr v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
          <th
            v-for="header in headerGroup.headers"
            :key="header.id"
            :style="getPinStyle(header.column.columnDef.id)"
          >
            {{ header.isPlaceholder ? null : header.column.columnDef.header }}
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="row in table.getRowModel().rows" :key="row.id">
          <td
            v-for="cell in row.getVisibleCells()"
            :key="cell.id"
            :style="getPinStyle(cell.column.columnDef.id)"
          >
            {{ cell.getValue() }}
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import {
  createTable,
  useVueTable,
  getCoreRowModel,
} from '@tanstack/vue-table'

// Define table columns
const columns = [
  {
    id: 'firstName',
    header: 'First Name',
    accessorKey: 'firstName',
  },
  {
    id: 'lastName',
    header: 'Last Name',
    accessorKey: 'lastName',
  },
  {
    id: 'age',
    header: 'Age',
    accessorKey: 'age',
  },
]

// Sample data
const data = ref([
  { firstName: 'John', lastName: 'Doe', age: 28 },
  { firstName: 'Jane', lastName: 'Smith', age: 34 },
  { firstName: 'Alice', lastName: 'Johnson', age: 45 },
])

// Column pinning state
const columnPinning = ref({
  left: [],
  right: [],
})

// Create the table instance
const table = useVueTable({
  data: data.value,
  columns,
  state: {
    columnPinning: columnPinning.value,
  },
  onColumnPinningChange: (updater) => {
    columnPinning.value =
      typeof updater === 'function' ? updater(columnPinning.value) : updater
  },
  getCoreRowModel: getCoreRowModel(),
})

// Helper to apply sticky positioning for pinned columns
function getPinStyle(columnId) {
  if (columnPinning.value.left.includes(columnId)) {
    return {
      position: 'sticky',
      left: '0px',
      background: 'white',
      zIndex: 1,
      borderRight: '1px solid #ccc',
    }
  }
  if (columnPinning.value.right.includes(columnId)) {
    return {
      position: 'sticky',
      right: '0px',
      background: 'white',
      zIndex: 1,
      borderLeft: '1px solid #ccc',
    }
  }
  return {}
}

// Example functions to pin/unpin columns
function pinFirstColumn() {
  columnPinning.value = { left: ['firstName'], right: [] }
}

function unpinAll() {
  columnPinning.value = { left: [], right: [] }
}
</script>

<style>
table {
  border-collapse: collapse;
  width: 100%;
}

th,
td {
  border: 1px solid #ddd;
  padding: 8px;
  white-space: nowrap;
}

th {
  background-color: #f4f4f4;
  text-align: left;
}
</style>
