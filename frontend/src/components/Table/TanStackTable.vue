<!-- components/Table/TanstackTable.vue -->
<template>
  <div class="p-4 h-full flex flex-col gap-4" v-bind="$attrs">
    <!-- Search and Page Size Controls -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">

      <!-- Page Size Selector -->
      <div class="flex items-center gap-2">
        <label class="text-sm text-gray-600">Show</label>
        <select
          class="px-3 border bg-white border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500"
          :value="pageSize"
          @change="onPageSizeChange"
        >
          <option v-for="size in pageSizeOptions" :key="size" :value="size">
            {{ size }}
          </option>
        </select>
        <span class="text-sm text-gray-600">entries</span>
      </div>

      <input
        type="text"
        v-model="globalFilter"
        :placeholder="globalSearchPlaceHolder"
        class="w-full sm:max-w-sm px-3 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300"
      />

    </div>

    <!-- Table -->
    <div class="flex-1 overflow-auto max-h-full ">
      <table class="table-wrapper w-full text-left text-sm ">
        <thead class="text-gray-700 uppercase text-xs sticky top-0 z-10">
          <tr v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
            <th
              v-for="header in headerGroup.headers"
              :key="header.id"
              class="px-4 py-3 font-medium border border-gray-300"
              :class="{
                'cursor-pointer select-none': header.column.getCanSort()
              }"
              @click="header.column.getToggleSortingHandler()?.($event)"
            >
              <div class="flex items-center gap-1">
                <FlexRender
                  :render="header.column.columnDef.header"
                  :props="header.getContext()"
                />
                <span class="cursor-pointer" v-if="header.column.getCanSort()">
                  <i
                    :class="{
                      'bi bi-arrow-up': header.column.getIsSorted() === 'asc',
                      'bi bi-arrow-down': header.column.getIsSorted() === 'desc',
                      'bi bi-arrow-down-up': !header.column.getIsSorted()
                    }"
                  ></i>
                </span>
              </div>
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-for="row in table.getRowModel().rows" :key="row.id" class="hover:bg-gray-50 transition">
            <td v-for="cell in row.getVisibleCells()" :key="cell.id" class="px-4 py-3 border border-gray-300">
              <FlexRender
                :render="cell.column.columnDef.cell"
                :props="cell.getContext()"
              />
            </td>
          </tr>
        </tbody>
        <tfoot v-if="hasFooter">
          <tr v-for="footerGroup in table.getFooterGroups()" :key="footerGroup.id">
            <td
              v-for="footer in footerGroup.headers"
              :key="footer.id"
              class="px-4 py-2 font-medium border border-gray-300"
            >
              <FlexRender :render="footer.column.columnDef.footer" :props="footer.getContext()" />
            </td>
          </tr>
        </tfoot>
      </table>
    </div>

    <!-- Pagination Controls -->
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
      <!-- Entry Info -->
      <div class="text-sm text-gray-600">
        Showing {{ pageStart }} to {{ pageEnd }} of {{ rowCount }} entries
      </div>

      <!-- Controls -->
      <div class="flex items-center gap-1 flex-wrap text-sm">
        <!-- First & Previous -->
        <button
          class="px-3 py-1 border rounded-md hover:bg-gray-100 disabled:opacity-50"
          :disabled="pageIndex === 0"
          @click="firstPage"
        >
          First
        </button>
        <button
          class="px-3 py-1 border rounded-md hover:bg-gray-100 disabled:opacity-50"
          :disabled="pageIndex === 0"
          @click="previousPage"
        >
          Prev
        </button>

        <!-- Page Numbers -->
        <div class="flex items-center gap-1">
          <button
            v-for="page in visiblePages"
            :key="page"
            class="px-3 py-1 border rounded-md hover:bg-blue-200"
            :class="page === pageIndex + 1 ? 'bg-blue-500 text-white hover:bg-blue-600' : ''"
            @click="goToPage(page)"
          >
            {{ page }}
          </button>
        </div>

        <!-- Next & Last -->
        <button
          class="px-3 py-1 border rounded-md hover:bg-gray-100 disabled:opacity-50"
          :disabled="pageIndex >= pageCount - 1"
          @click="nextPage"
        >
          Next
        </button>
        <button
          class="px-3 py-1 border rounded-md hover:bg-gray-100 disabled:opacity-50"
          :disabled="pageIndex >= pageCount - 1"
          @click="lastPage"
        >
          Last
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import {
  useVueTable,
  FlexRender,
  getCoreRowModel,
  createColumnHelper,
  getSortedRowModel,
  getPaginationRowModel,
  getFilteredRowModel
} from '@tanstack/vue-table'
import { ref, toValue, computed, toRefs, h, watch } from 'vue' // 'watch' is essential for external state updates
import IndeterminateCheckbox from './IndeterminateCheckbox.vue'

// Allows attributes not explicitly defined as props to be passed to the root element
defineOptions({
  inheritAttrs: false
})

// Define the props that this component accepts
const props = defineProps({
  enableRowSelection: {
    type: Boolean,
    default: false // Whether to enable row selection checkboxes
  },
  data: {
    type: Array,
    default: () => [], // The data array for the table
  },
  columns: {
    type: Array,
    default: () => [], // The column definitions for the table
  },
  globalSearchPlaceHolder: {
    type: String,
    default: 'Search all columns...' // Placeholder text for the global search input
  },
  pageSizeOptions: {
    type: Array,
    default: () => [5, 10, 25, 50, 100], // Options for number of rows per page
  },
  defaultPageSize: {
    type: Number,
    default: 10, // Default number of rows per page
  },
  modelValue: {
    type: Object, // Used for v-model binding of the raw TanStack Table row selection state { [rowId]: boolean }
    default: () => ({})
  },
  selectAllMode: {
    type: String,
    default: 'all', // 'all' or 'page'. Controls if header checkbox selects all data or just current page
    validator: (value) => ['all', 'page'].includes(value) // Ensures valid options
  }
})

// Define the custom events that this component can emit
const emit = defineEmits([
  'update:selectedRows', // Emits an array of the actual selected row data objects
  'update:modelValue' // Emits the raw TanStack Table row selection object for v-model binding
]);

// Reactive state for column visibility (e.g., to hide/show columns)
const columnVisibility = ref({})
// Reactive state for the global filter (search input)
const globalFilter = ref('')
// Reactive state for row selection (managed by TanStack Table internally, exposed via v-model)
const rowSelection = ref(props.modelValue); // Initialize with the `modelValue` prop

// Watch for changes in the `modelValue` prop from the parent.
// This allows the parent component to programmatically set/clear selections.
watch(() => props.modelValue, (newVal) => {
  // Check if the new value is different from the current internal state
  // This prevents an infinite loop if both parent and child are updating simultaneously
  if (JSON.stringify(newVal) !== JSON.stringify(rowSelection.value)) {
    rowSelection.value = newVal; // Update internal rowSelection ref
  }
}, { deep: true }); // `deep: true` is crucial for objects to detect nested changes

// Helper function to build the initial column visibility state
function buildVisibility(columns) {
  const visibility = {}
  columns.forEach(col => {
    // Set visibility based on 'visible' property, defaulting to true
    visibility[col.key] = col.visible !== false
  })
  return visibility
}

// Initialize column visibility state based on props
columnVisibility.value = buildVisibility(props.columns)

// Create a column helper instance for defining table columns
const columnHelper = createColumnHelper()

// Define the special selection column
const SelectionColumn = columnHelper.display({
  id: 'select', // Unique ID for this column
  header: ({ table }) => {
    // Determine if all rows (or all page rows) are selected for the header checkbox
    const isAllRowsSelected = props.selectAllMode === 'page'
      ? table.getIsAllPageRowsSelected() // For 'page' mode, check if all visible page rows are selected
      : table.getIsAllRowsSelected();    // For 'all' mode, check if all filtered rows are selected

    // Determine if some rows (or some page rows) are selected for indeterminate state
    const isSomeRowsSelected = props.selectAllMode === 'page'
      ? table.getIsSomePageRowsSelected() && !isAllRowsSelected // Some page rows, but not all
      : table.getIsSomeRowsSelected() && !isAllRowsSelected;    // Some filtered rows, but not all

    return h(IndeterminateCheckbox, {
      checked: isAllRowsSelected,
      indeterminate: isSomeRowsSelected,
      // *** THE CRITICAL FIX IS HERE ***
      // Use the correct handler based on selectAllMode for the header checkbox
      onChange: props.selectAllMode === 'page'
        ? table.getToggleAllPageRowsSelectedHandler() // Toggles selection of only page rows
        : table.getToggleAllRowsSelectedHandler(),    // Toggles selection of all filtered rows
    });
  },
  cell: ({ row }) =>
    h(IndeterminateCheckbox, {
      checked: row.getIsSelected(), // Checkbox state for individual row
      disabled: !row.getCanSelect(), // If row cannot be selected (e.g., due to specific logic)
      onChange: row.getToggleSelectedHandler(), // Handler for individual row checkbox
    }),
  enableSorting: false, // Selection column should not be sortable
})

// Computed property to process the raw columns into TanStack Table column definitions
const tableColumns = computed(() => {
  const rawColumns = toValue(props.columns) // Ensure rawColumns is unwrapped if it's a ref
  if (!Array.isArray(rawColumns)) return []

  // Map user-defined columns to TanStack Table column definitions
  const dataColumns = rawColumns.map(col => {
    const columnDef = {
      header: col.label, // Column header text
      cell: col.cell ? col.cell : info => info.getValue(), // Cell rendering function, defaults to value
      enableSorting: col.sortable !== false, // Enable/disable sorting
      sortAs: col.sortAs ? col.sortAs : undefined // Custom sort accessor if needed
    }
    if (col.footer !== undefined) {
      columnDef.footer = col.footer // Add footer if defined
    }
    return columnHelper.accessor(col.field, columnDef) // Create accessor column
  })

  // Prepend the selection column if enableRowSelection is true
  return props.enableRowSelection ? [SelectionColumn, ...dataColumns] : dataColumns
})

// Destructure some props into refs for direct use if preferred, though `props.` access is also reactive
const { pageSizeOptions, defaultPageSize } = toRefs(props)

// Reactive state for sorting
const sorting = ref([])
// Reactive state for current page index (0-based)
const pageIndex = ref(0)
// Reactive state for current page size
const pageSize = ref(defaultPageSize.value)

// Initialize the TanStack Table instance
const table = useVueTable({
  // Provide data via a getter for reactivity
  get data() {
    return props.data;
  },
  // Provide column definitions
  columns: tableColumns.value,
  // Enable/disable row selection feature
  enableRowSelection: props.enableRowSelection,
  // Define and expose various table states
  state: {
    columnVisibility: columnVisibility.value,
    get sorting() { // Getter for sorting state
      return sorting.value
    },
    get globalFilter() { // Getter for global filter state
      return globalFilter.value
    },
    get pagination() { // Getter for pagination state
      return {
        pageIndex: pageIndex.value,
        pageSize: pageSize.value,
      }
    },
    get rowSelection() { // Getter for row selection state (crucial for reactivity)
      return rowSelection.value
    },
  },
  // Handlers for state changes:
  onSortingChange: updaterOrValue => {
    sorting.value =
      typeof updaterOrValue === 'function'
        ? updaterOrValue(sorting.value)
        : updaterOrValue
  },
  onPaginationChange: updaterOrValue => {
    const newPagination =
      typeof updaterOrValue === 'function'
        ? updaterOrValue({ pageIndex: pageIndex.value, pageSize: pageSize.value })
        : updaterOrValue

    pageIndex.value = newPagination.pageIndex
    pageSize.value = newPagination.pageSize
  },
  onGlobalFilterChange: updaterOrValue => {
    globalFilter.value =
      typeof updaterOrValue === 'function'
        ? updaterOrValue(globalFilter.value)
        : updaterOrValue
  },
  onRowSelectionChange: updaterOrValue => {
    // Update the internal rowSelection ref
    rowSelection.value =
      typeof updaterOrValue === 'function'
        ? updaterOrValue(rowSelection.value)
        : updaterOrValue;

    // Emit the raw rowSelection object for v-model binding in parent
    emit('update:modelValue', rowSelection.value);

     // *** THE FIX IS HERE FOR selectedRows EMIT ***
    // Always use getFilteredSelectedRowModel() to get ALL selected rows
    // that pass current filters, regardless of pagination.
    // This ensures that selections from different pages are accumulated.
    const selectedFlatRows = table.getFilteredSelectedRowModel().flatRows.map(row => row.original);

    // Emit the array of actual selected data objects to the parent
    emit('update:selectedRows', selectedFlatRows);
  },
  // Core table row model
  getCoreRowModel: getCoreRowModel(),
  // Sorted row model
  getSortedRowModel: getSortedRowModel(),
  // Pagination row model
  getPaginationRowModel: getPaginationRowModel(),
  // Filtered row model (for global search)
  getFilteredRowModel: getFilteredRowModel(),
  // Filtered selected row model (for getting all selected rows that pass filters)
  getFilteredSelectedRowModel: getFilteredRowModel(),
  // This is used by `table.getIsAllPageRowsSelected()` and `table.getIsSomePageRowsSelected()`
  // It gives access to rows on the current page for selection purposes.
  // We need to explicitly provide this for these getters to work.
  getToggleAllPageRowsSelectedHandler: () => table.getToggleAllPageRowsSelectedHandler(),
})

// Computed property to check if any column has a footer defined
const hasFooter = computed(() =>
  table.getFooterGroups().some(group =>
    group.headers.some(header => header.column.columnDef.footer)
  )
)

// Computed properties for pagination display information
const pageCount = computed(() => table.getPageCount()) // Total number of pages
const rowCount = computed(() => table.getFilteredRowModel().rows.length) // Total rows after filtering
const pageStart = computed(() => (pageIndex.value * pageSize.value) + 1) // Starting entry number for current page
const pageEnd = computed(() => Math.min(rowCount.value, (pageIndex.value + 1) * pageSize.value)) // Ending entry number for current page

// Computed property to generate an array of visible page numbers for pagination buttons
const visiblePages = computed(() => {
  const total = pageCount.value
  const current = pageIndex.value + 1 // Current page (1-based)
  const pages = []

  // Logic to show up to 5 page numbers centered around the current page
  let start = Math.max(1, current - 2)
  let end = Math.min(total, current + 2)

  // Adjust `start` and `end` if near the beginning or end of pages
  if (current <= 3) {
    end = Math.min(5, total)
  }
  if (current >= total - 2) {
    start = Math.max(1, total - 4)
  }

  // Populate the pages array
  for (let i = start; i <= end; i++) {
    pages.push(i)
  }
  return pages
})

// Pagination control functions
function goToPage(page) {
  if (page < 1 || page > pageCount.value) return // Prevent going out of bounds
  pageIndex.value = page - 1 // Update page index (0-based)
}

function previousPage() {
  if (pageIndex.value > 0) pageIndex.value -= 1 // Decrement if not on first page
}

function nextPage() {
  if (pageIndex.value < pageCount.value - 1) pageIndex.value += 1 // Increment if not on last page
}

function firstPage() {
  pageIndex.value = 0 // Go to the first page
}

function lastPage() {
  pageIndex.value = table.getPageCount() - 1 // Go to the last page
}

// Handler for when the page size (entries per page) is changed
function onPageSizeChange(event) {
  pageSize.value = Number(event.target.value) // Update page size
  pageIndex.value = 0 // Reset to the first page when page size changes
}
</script>

<style scoped>
/* Scoped styles for the table wrapper and scrollbar */
.table-wrapper {
  overflow-x: auto; /* Enables horizontal scrolling if content overflows */
  white-space: nowrap; /* Prevents text from wrapping within cells */
  min-width: max-content; /* Ensures table takes at least the width of its content */
}

/* Custom scrollbar styles for webkit browsers */
.table-wrapper::-webkit-scrollbar {
  height: 8px; /* Height of the horizontal scrollbar */
}
.table-wrapper::-webkit-scrollbar-track {
  background: #f1f1f1; /* Background of the scrollbar track */
}
.table-wrapper::-webkit-scrollbar-thumb {
  background: #c1c1c1; /* Color of the scrollbar thumb */
}
.table-wrapper::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8; /* Color of the scrollbar thumb on hover */
}
</style>