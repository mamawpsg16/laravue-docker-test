<template>
    <vue-good-table
      :columns="columns"
      :rows="data"
      :pagination-options="paginationOptions"
      :search-options="searchOptions"
    >
      <!-- custom column slot -->
      <template #table-row="props">
        <slot :props="props"></slot>
      </template>
    </vue-good-table>
</template>

<script setup>
import { VueGoodTable } from 'vue-good-table-next'

const props = defineProps({
  data:{
    type:Array,
    default:[]
  },
  columns:{
    type:Array,
    default:[]
  },
  paginationOptions:{
    type:Object,
    default:{ 
      enabled: true,
      perPage: 5
    } 
  },
  searchOptions:{
    type:Object,
    default:{
      enabled:true,
      placeholder: 'Search...',
    }
  }
})
</script>
