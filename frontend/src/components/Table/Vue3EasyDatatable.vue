<template>
  <EasyDataTable
    :headers="computedHeaders"
    :items="computedItems"
    :border-cell="borderCell"
    :buttons-pagination="buttonsPagination"
    :fixed-header="fixedHeader"
    :rows-per-page="rowsPerPage"
    :show-index="showIndex"
    :table-class-name="tableClassName"
    :theme-color="themeColor"
    :loading="loading"
  >
    <!-- Forward only known slots -->
    <template v-if="slots['item-action']" #item-status="slotProps">
      <slot name="item-action" v-bind="slotProps" />
    </template>

    <template v-if="slots['item-name']" #item-name="slotProps">
      <slot name="item-name" v-bind="slotProps" />
    </template>

    <!-- Add more slot forwarding as needed -->
  </EasyDataTable>
</template>

<script setup>
import EasyDataTable from 'vue3-easy-data-table'
import { computed, useSlots } from 'vue'

const slots = useSlots()

const props = defineProps({
  headers: {
    type: Array,
    default: () => ([
      { text: 'Name', value: 'name' },
      { text: 'Email', value: 'email' },
      { text: 'Status', value: 'status' },
    ]),
  },
  items: {
    type: Array,
    default: () => [],
  },
  borderCell: { type: Boolean, default: true },
  buttonsPagination: { type: Boolean, default: true },
  fixedHeader: { type: Boolean, default: true },
  rowsPerPage: { type: Number, default: 10 },
  showIndex: { type: Boolean, default: false },
  tableClassName: { type: String, default: '' },
  themeColor: { type: String, default: '#42b883' },
  loading: { type: Boolean, default: false },
})

const computedHeaders = computed(() => props.headers)
const computedItems = computed(() => props.items)
</script>
