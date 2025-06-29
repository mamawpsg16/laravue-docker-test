<template>
  <div class="datatable-container-wrapper" v-bind="$attrs">
    <DataTableHeader
      :tableHeaderTitle="tableHeaderTitle"
      :hasTableHeaderActionsSlot="hasTableHeaderActionsSlot"
    >
      <template v-if="hasTableHeaderActionsSlot" #table-actions>
        <slot name="table-actions"></slot>
      </template>
    </DataTableHeader>
    <DataTableControls
      :pageSize="tableState.pagination.pageSize"
      :pageSizeOptions="pageSizeOptions"
      :globalFilter="tableState.globalFilter"
      :globalSearchPlaceHolder="globalSearchPlaceHolder"
      @update:pageSize="onPageSizeChangeFromControls"
      @update:globalFilter="onGlobalFilterChangeFromControls"
      :allColumns="table.getAllLeafColumns()"
      :getIsAllColumnsVisible="table.getIsAllColumnsVisible"
      :getToggleAllColumnsVisibilityHandler="table.getToggleAllColumnsVisibilityHandler"
      :setColumnVisibility="table.setColumnVisibility"
      :toggableColumnVisibility="toggableColumnVisibility"
    />

    <DataTableBody
      :table="table"
      :rowCount="rowCount"
      :tableColumns="tableColumns"
      :noDataMessage="noDataMessage"
      :tableHeight="tableHeight"
      :stickyHeader="stickyHeader"
      :stickyColumns="stickyColumns"
      :headerRefs="headerRefs"
      :stickyColumnPositions="stickyColumnPositions"
      :loading="loading"
      @set-header-ref="setHeaderRef"
    />

    <DataTablePagination
      :pageIndex="tableState.pagination.pageIndex" :pageCount="pageCount"
      :rowCount="rowCount"
      :pageSize="tableState.pagination.pageSize" :visiblePages="visiblePages"
      @go-to-page="goToPage"
      @previous-page="previousPage"
      @next-page="nextPage"
      @first-page="firstPage"
      @last-page="lastPage"
    />
  </div>
</template>

<script setup>
import {
  useVueTable,
  getCoreRowModel,
  getSortedRowModel,
  getPaginationRowModel,
  getFilteredRowModel
} from '@tanstack/vue-table'
import { computed, useSlots, ref, watch, onMounted, nextTick } from 'vue'

// --- Import Child Components ---
import DataTableHeader from './DataTableHeader.vue'
import DataTableControls from './DataTableControls.vue'
import DataTableBody from './DataTableBody.vue'
import DataTablePagination from './DataTablePagination.vue'

// --- Import Composables ---
import { useTableState } from '@/composables/TanstackTable/useTableState'
import { useStickyColumns } from '@/composables/TanstackTable/useStickyColumns'
import { usePagination } from '@/composables/TanstackTable/usePagination'
import { useTableColumns } from '@/composables/TanstackTable/useTableColumns'

// Vue setup options for attributes inheritance
defineOptions({
  inheritAttrs: false
})

// --- Props Definition ---
const props = defineProps({
  noDataMessage:{
    type: String,
    default: 'No data found.'
  },
  enableRowSelection: {
    type: Boolean,
    default: false
  },
  loading: {
    type: Boolean,
    default: false
  },
  data: {
    type: Array,
    default: () => [],
  },
  columns: { // This prop is crucial as it now carries initial visibility info
    type: Array,
    default: () => [],
  },
  globalSearchPlaceHolder: {
    type: String,
    default: 'Search all columns...'
  },
  pageSizeOptions: {
    type: Array,
    default: () => [5, 10, 25, 50, 100],
  },
  defaultPageSize: {
    type: Number,
    default: 10,
  },
  modelValue: {
    type: Object,
    default: () => ({})
  },
  selectAllMode: {
    type: String,
    default: 'all',
    validator: (value) => ['all', 'page'].includes(value)
  },
  tableHeaderTitle: {
    type: String,
    default: ''
  },
  tableHeight: {
    type: String,
    default: ''
  },
  stickyHeader: {
    type: Boolean,
    default: false
  },
  stickyColumns: {
    type: Array,
    default: () => []
  },
  toggableColumnVisibility:{
    type: Boolean,
    default: false
  }
})

// --- Emits Definition ---
const emit = defineEmits([
  'update:selectedRows',
  'update:modelValue',
  'update:columnVisibility', // Keep this if parent needs the raw state object
  'update:visibleColumns'    // NEW: Emit for the derived list of visible IDs
]);

// Check for the presence of the 'table-actions' slot
const slots = useSlots();
const hasTableHeaderActionsSlot = computed(() => !!slots['table-actions']);

// --- Use Composables ---
// CORRECTED: Destructure initialColumnVisibilityState from useTableColumns
const { tableColumns, initialColumnVisibilityState } = useTableColumns(props)

// CORRECTED: Pass initialColumnVisibilityState to useTableState
const {
  tableState, // Reactive state object for TanStack Table (sorting, pagination, filtering, selection).
  onSortingChange, // Handler for sorting changes.
  onPaginationChange, // Handler for pagination changes.
  onGlobalFilterChange, // Handler for global filter changes.
  onRowSelectionChange, // Handler for row selection changes.
  onColumnVisibilityChange // CORRECTED: Get the column visibility handler
} = useTableState(props, emit, initialColumnVisibilityState) // Pass initial visibility

// Store table reference
const table = ref(null)

// Initialize TanStack Table
table.value = useVueTable({
  get data() {
    return props.data;
  },
  columns: tableColumns.value, // The computed column definitions.
  enableRowSelection: props.enableRowSelection, // Enables/disables row selection.
  state: { // CORRECTED: Use tableState getters to connect to reactive state
    get columnVisibility() { return tableState.columnVisibility; },
    get sorting() { return tableState.sorting; },
    get globalFilter() { return tableState.globalFilter; },
    get pagination() { return tableState.pagination; },
    get rowSelection() { return tableState.rowSelection; },
  },
  // CORRECTED: Bind all state change handlers from useTableState
  onSortingChange: onSortingChange,
  onPaginationChange: onPaginationChange,
  onGlobalFilterChange: onGlobalFilterChange,
  onRowSelectionChange: onRowSelectionChange(table),
  onColumnVisibilityChange: onColumnVisibilityChange, // CORRECTED: Bind the column visibility handler
  getCoreRowModel: getCoreRowModel(), // Required for basic table functionality (getting rows).
  getSortedRowModel: getSortedRowModel(), // Enables sorting of rows.
  getPaginationRowModel: getPaginationRowModel(), // Enables pagination.
  getFilteredRowModel: getFilteredRowModel(), // Enables general filtering.
  getFilteredSelectedRowModel: getFilteredRowModel(), // Enables filtering of selected rows.
});

// Use pagination composable
const {
  pageIndex, // Current page index (0-based).
  pageSize, // Number of rows per page.
  pageCount, // Total number of pages.
  rowCount, // Total number of rows.
  pageStart, // Index of the first row on the current page.
  pageEnd, // Index of the last row on the current page.
  visiblePages, // Array of page numbers to display in the pagination controls.
  goToPage, // Function to navigate to a specific page.
  previousPage, // Function to go to the previous page.
  nextPage, // Function to go to the next page.
  firstPage, // Function to go to the first page.
  lastPage // Function to go to the last page.
} = usePagination(table, tableState)

// Use sticky columns composable
const {
  headerRefs, // Reactive object storing references to header cells for calculating sticky positions.
  stickyColumnPositions, // Reactive object storing calculated `left` positions for sticky columns.
  setHeaderRef, // Function to set a header cell reference.
  calculateStickyPositions, // Function to calculate sticky column positions (usually called on mount/resize).
  getStickyColumnStyle // Function to generate inline style for sticky columns.
} = useStickyColumns(props, table)

// --- Event Handlers for Child Components ---
/**
 * Handles the `update:pageSize` event from `DataTableControls`.
 * Updates the table's page size using TanStack Table's `setPageSize` method.
 * @param {number} newSize The new page size.
 */
function onPageSizeChangeFromControls(newSize) {
  table.value.setPageSize(Number(newSize)); // Ensure newSize is a number.
}

/**
 * Handles the `update:globalFilter` event from `DataTableControls`.
 * Updates the table's global filter using TanStack Table's `setGlobalFilter` method.
 * @param {string} newFilter
 */
function onGlobalFilterChangeFromControls(newFilter) {
  table.value.setGlobalFilter(newFilter);
}

const loading = computed(() => props.loading );

// Watchers for Sticky Columns and Visibility
watch(
  () => [props.columns, props.data, props.stickyColumns, tableState.columnVisibility], // Watch tableState.columnVisibility
  () => {
    if (props.stickyColumns.length > 0) {
      nextTick(() => {
        calculateStickyPositions();
      });
    }

      // NEW LOGIC: Emit derived visible column IDs when columnVisibility changes
    if (table.value) {
      const visibleColumns = table.value.getVisibleLeafColumns().map(col => col.id);
      emit('update:visibleColumns', visibleColumns);
    }
  },
  { deep: true, immediate: true } // `immediate: true` will emit on initial load
);

onMounted(() => {
  nextTick(() => {
    if (props.stickyColumns.length > 0) {
      calculateStickyPositions();
    }
  });
});

</script>

<style scoped>
/* No more scoped styles here. All styles are external in datatable.css */
@import '@/assets/css/datatable.css';
</style>