<template>
  <vue-good-table
    ref="table"
    :columns="columns"
    :rows="data"
    :pagination-options="paginationOptions"
    :search-options="searchOptions"
    :max-height="'560px'"
  >
    <template #table-row="props">
      <slot :props="props"></slot>
    </template>
  </vue-good-table>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { VueGoodTable } from 'vue-good-table-next'

// Define the ref
const table = ref(null)

// Define props
const props = defineProps({
  data: {
    type: Array,
    default: () => []
  },
  columns: {
    type: Array,
    default: () => []
  },
  paginationOptions: {
    type: Object,
    default: () => ({
      enabled: true,
      perPage: 5
    })
  },
  searchOptions: {
    type: Object,
    default: () => ({
      enabled: true,
      placeholder: 'Search...'
    })
  },
  sidebarVisible: {
    type: Boolean,
    default: false
  }
})

// Access ref on mount
onMounted(() => {
  console.log(table.value) // <-- this is your vue-good-table instance
})
</script>
