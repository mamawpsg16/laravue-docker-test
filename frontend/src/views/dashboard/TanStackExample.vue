<template>
  <DataTable :data="data" :columns="columns"/>
</template>

<script setup>
import { ref, h } from 'vue'
import DataTable from '@/components/Table/TanStackTable.vue' // Adjust path as needed
import SwitchButton from './components/SwitchButton.vue'
// Client-side data

const data = ref([
  { id: 1, name: 'Alice', age: 24, status: 'active'  },
  { id: 2, name: 'Bob', age: 30, status: 'inactive'  },
  { id: 3, name: 'Charlie', age: 28, status: 'inactive'  },
])

const columns = ref([
  { 
    key: 'name',
    label: 'Full Name',

  },
  { 
    key: 'age',
    label: 'Age',
    // footer: (info) => 'Sum: ' + info.table.getFilteredRowModel().rows.reduce((sum, row) => sum + row.original.age, 0),
    sortAs:'alphanumeric',
    // visible:false
  },
  {
    key: 'status',
    label: 'Status',
    cell: (info) => h(SwitchButton, {
      status: info.row.original.status,
      rowData: info.row.original,
      onUpdateStatus: (updatedRow) => {
        // Find the row in data and update status
        const index = data.value.findIndex(r => r.id === updatedRow.id)
        if (index !== -1) {
          data.value[index].status = updatedRow.status
        }
      }
    }),
  }
  // {
  //   key: 'actions',
  //   label: 'Actions',
  //   cell: (info) => h(EditButton, {
  //     name: info.row.original.name,
  //     rowData: info.row.original,
  //     onEdit: (row) => {
  //       // This is the event handler in the parent component
  //       alert(`Edit clicked for ${row.name}`)
  //       // You can also call other functions or update state here
  //     }
  //   }),
  // }
])


</script>