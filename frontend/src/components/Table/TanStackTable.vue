I understand\! Here's the complete, updated `Tanstacktable.vue` component with all the fixes for sticky columns and the error handling, ready for you to paste into your project.

I've ensured the following:

1.  **Error Handling:** Added checks for `table.value` before accessing its methods in `calculateStickyPositions`.
2.  **Sticky Columns:** Implemented the cumulative `left` positioning for multiple sticky columns, ensuring they stack correctly without overlapping.
3.  **`watch` and `onMounted` Improvements:** Made sure the sticky column positions are recalculated reliably on initial load and whenever relevant props (data, columns, sticky columns) change.
4.  **CSS:** Minor adjustments to ensure sticky column styles are correctly applied and to provide better visual separation.

<!-- end list -->

```vue
<template>
  <div class="p-4 h-full flex flex-col gap-4 border border-gray-300 rounded-md" v-bind="$attrs">
    <div v-if="tableHeaderTitle" class="flex justify-between items-center p-3 rounded-t-lg border-b  -mx-4 -mt-4">
      <h2 v-if="tableHeaderTitle" class="text-xl font-semibold">
        {{ tableHeaderTitle }}
      </h2>
      <div v-if="hasTableHeaderActionsSlot" class="flex space-x-2">
        <slot name="table-actions"></slot>
      </div>
    </div>
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
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

    <div 
      class="table-container"
      :style="{ height: tableHeight }"
      ref="tableContainer"
    >
      <table class="table-wrapper w-full text-left text-base">
        <thead 
          class="text-gray-700 text-base"
          :class="{ 'sticky-header': stickyHeader }"
        >
          <tr v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
            <th
              v-for="header in headerGroup.headers"
              :key="header.id"
              :ref="el => setHeaderRef(header.column.id, el)"
              class="px-4 py-3 font-medium border border-gray-300 bg-gray-50"
              :class="{
                'cursor-pointer select-none': header.column.getCanSort(),
                'sticky-column': stickyColumns.includes(header.column.id),
                'bg-blue-50': stickyColumns.includes(header.column.id) // Background for sticky header cells
              }"
              :style="getStickyColumnStyle(header.column.id)"
              @click="header.column.getToggleSortingHandler()?.($event)"
            >
              <div class="flex items-center gap-2">
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
        <tbody v-if="rowCount > 0" class="divide-y divide-gray-100">
          <tr v-for="row in table.getRowModel().rows" :key="row.id" class="hover:bg-gray-50 transition">
            <td 
              v-for="cell in row.getVisibleCells()" 
              :key="cell.id" 
              class="px-4 py-3 border border-gray-300"
              :class="{
                'text-left': cell.column.columnDef.meta?.textAlign === 'left',
                'text-right': cell.column.columnDef.meta?.textAlign === 'right',
                'text-center': cell.column.columnDef.meta?.textAlign === 'center',
                'sticky-column': stickyColumns.includes(cell.column.id),
                'bg-blue-25': stickyColumns.includes(cell.column.id) // Background for sticky body cells
              }"
              :style="getStickyColumnStyle(cell.column.id)"
            >
              <FlexRender
                :render="cell.column.columnDef.cell"
                :props="cell.getContext()"
              />
            </td>
          </tr>
        </tbody>
        <tbody v-else class="divide-y divide-gray-100">
          <tr>
            <td
              :colspan="tableColumns.length"
              class="text-center py-8 border border-gray-300"
            >
              <p class="text-gray-500 text-lg">{{ noDataMessage }}</p>
            </td>
          </tr>
        </tbody>
        <tfoot v-if="hasFooter">
          <tr v-for="footerGroup in table.getFooterGroups()" :key="footerGroup.id">
            <td
              v-for="footer in footerGroup.headers"
              :key="footer.id"
              class="px-4 py-2 font-medium border border-gray-300 bg-gray-50"
              :class="{
                'text-left': footer.column.columnDef.meta?.textAlign === 'left',
                'text-right': footer.column.columnDef.meta?.textAlign === 'right',
                'text-center': footer.column.columnDef.meta?.textAlign === 'center',
                'sticky-column': stickyColumns.includes(footer.column.id),
                'bg-blue-100': stickyColumns.includes(footer.column.id) // Background for sticky footer cells
              }"
              :style="getStickyColumnStyle(footer.column.id)"
            >
              <FlexRender :render="footer.column.columnDef.footer" :props="footer.getContext()" />
            </td>
          </tr>
        </tfoot>
      </table>
    </div>

    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
      <div class="text-sm text-gray-600">
        Showing {{ pageStart }} to {{ pageEnd }} of {{ rowCount }} entries
      </div>

      <div class="flex items-center gap-1 flex-wrap text-sm">
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
import { ref, toValue, computed, toRefs, h, watch, useSlots, onMounted, nextTick } from 'vue'
import IndeterminateCheckbox from './IndeterminateCheckbox.vue'

// Allows attributes not explicitly defined as props to be passed to the root element
defineOptions({
  inheritAttrs: false
})

const props = defineProps({
  noDataMessage:{
    type: String,
    default: 'No data found.'
  },
  enableRowSelection: {
    type: Boolean,
    default: false
  },
  data: {
    type: Array,
    default: () => [],
  },
  columns: {
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
    require:true,
    default: ''
  },
  tableActions: {
    type: Array,
    default: () => []
  },
  tableHeight: {
    type: String,
    default: '670px'
  },
  stickyHeader: {
    type: Boolean,
    default: false
  },
  stickyColumns: {
    type: Array,
    default: () => []
  }
})

const slots = useSlots();
const hasTableHeaderActionsSlot = computed(() => !!slots['table-actions']);

const headerRefs = ref({})
const stickyColumnPositions = ref({})

const setHeaderRef = (columnId, el) => {
  if (el) {
    headerRefs.value[columnId] = el
  }
}

const calculateStickyPositions = () => {
  // Check that the table instance is ready AND that column headers have rendered elements
  if (!table || Object.keys(headerRefs.value).length === 0) {
    // console.warn("calculateStickyPositions: Table instance or header refs not fully available yet. Retrying...");
    return;
  }

  const positions = {}
  let cumulativeWidth = 0
  
  // Get the CURRENT ORDER of columns from the table.
  const columnOrder = table.getAllColumns().map(col => col.id) // Note: `table` is no longer a ref here, directly the object
  
  const definedStickyColumns = props.stickyColumns;

  for (const columnId of columnOrder) {
    if (definedStickyColumns.includes(columnId)) {
      const headerEl = headerRefs.value[columnId]
      if (headerEl) {
        const width = headerEl.getBoundingClientRect().width
        positions[columnId] = {
          width: width,
          left: cumulativeWidth
        }
        cumulativeWidth += width
      } else {
        console.warn(`Header element for sticky column '${columnId}' not found during position calculation. It might be hidden or not rendered yet.`);
      }
    }
  }
  
  stickyColumnPositions.value = positions;
}

const getStickyColumnStyle = (columnId) => {
  if (!props.stickyColumns.includes(columnId)) {
    return {}
  }
  
  const stickyInfo = stickyColumnPositions.value[columnId]
  if (!stickyInfo) {
    // Fallback if stickyInfo isn't calculated yet. This should be rare with the new watch.
    return {
      position: 'sticky',
      left: '0px',
      zIndex: 5
    }
  }
  
  return {
    position: 'sticky',
    left: `${stickyInfo.left}px`,
    zIndex: 5
  }
}

const emit = defineEmits([
  'update:selectedRows',
  'update:modelValue'
]);

const columnVisibility = ref({})
const globalFilter = ref('')
const rowSelection = ref(props.modelValue);

watch(() => props.modelValue, (newVal) => {
  if (JSON.stringify(newVal) !== JSON.stringify(rowSelection.value)) {
    rowSelection.value = newVal;
  }
}, { deep: true });

function buildVisibility(columns) {
  const visibility = {}
  columns.forEach(col => {
    visibility[col.key] = col.visible !== false
  })
  return visibility
}

columnVisibility.value = buildVisibility(props.columns)

const columnHelper = createColumnHelper()

const SelectionColumn = columnHelper.display({
  id: 'select',
  header: ({ table: currentTableInstance }) => { // Rename `table` to avoid conflict
    const isAllRowsSelected = props.selectAllMode === 'page'
      ? currentTableInstance.getIsAllPageRowsSelected()
      : currentTableInstance.getIsAllRowsSelected();

    const isSomeRowsSelected = props.selectAllMode === 'page'
      ? currentTableInstance.getIsSomePageRowsSelected() && !isAllRowsSelected
      : currentTableInstance.getIsSomeRowsSelected() && !isAllRowsSelected;

    return h(IndeterminateCheckbox, {
      checked: isAllRowsSelected,
      indeterminate: isSomeRowsSelected,
      onChange: props.selectAllMode === 'page'
        ? currentTableInstance.getToggleAllPageRowsSelectedHandler()
        : currentTableInstance.getToggleAllRowsSelectedHandler(),
    });
  },
  cell: ({ row }) =>
    h(IndeterminateCheckbox, {
      checked: row.getIsSelected(),
      disabled: !row.getCanSelect(),
      onChange: row.getToggleSelectedHandler(),
    }),
  enableSorting: false,
})

const tableColumns = computed(() => {
  const rawColumns = toValue(props.columns)
  if (!Array.isArray(rawColumns)) return []

  const dataColumns = rawColumns.map(col => {
    const columnId = col.id || col.field

    const columnDef = {
      id: columnId,
      header: col.label,
      cell: col.cell ? col.cell : info => info.getValue(),
      enableSorting: col.sortable !== false,
      sortAs: col.sortAs ? col.sortAs : undefined,
       meta: {
        textAlign: col.textAlign || 'left'
      }
    }
    if (col.footer !== undefined) {
      columnDef.footer = col.footer
    }
    return columnHelper.accessor(col.field, columnDef)
  })

  return props.enableRowSelection ? [SelectionColumn, ...dataColumns] : dataColumns
})

const { pageSizeOptions, defaultPageSize } = toRefs(props)

const sorting = ref([])
const pageIndex = ref(0)
const pageSize = ref(defaultPageSize.value)

// Initialize the TanStack Table instance
// NOTE: 'table' is a variable holding the reactive object returned by useVueTable, NOT a Vue ref.
const table = useVueTable({
  get data() {
    return props.data;
  },
  columns: tableColumns.value,
  enableRowSelection: props.enableRowSelection,
  state: {
    columnVisibility: columnVisibility.value,
    get sorting() {
      return sorting.value
    },
    get globalFilter() {
      return globalFilter.value
    },
    get pagination() {
      return {
        pageIndex: pageIndex.value,
        pageSize: pageSize.value,
      }
    },
    get rowSelection() {
      return rowSelection.value
    },
  },
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
    rowSelection.value =
      typeof updaterOrValue === 'function'
        ? updaterOrValue(rowSelection.value)
        : updaterOrValue;

    emit('update:modelValue', rowSelection.value);

    // Use `table` directly here, as it's the `useVueTable` object
    const selectedFlatRows = table.getFilteredSelectedRowModel().flatRows.map(row => row.original);
    emit('update:selectedRows', selectedFlatRows);
  },
  getCoreRowModel: getCoreRowModel(),
  getSortedRowModel: getSortedRowModel(),
  getPaginationRowModel: getPaginationRowModel(),
  getFilteredRowModel: getFilteredRowModel(),
  getFilteredSelectedRowModel: getFilteredRowModel(),
  // Directly access table methods if `table` is not a ref
  getToggleAllPageRowsSelectedHandler: () => table.getToggleAllPageRowsSelectedHandler(),
})

// *** CRITICAL FIX FOR "Invalid watch source" ***
watch(
  // Watch a getter function that returns the 'table' object
  // This tells Vue to watch the *contents* of the object returned by useVueTable.
  () => table,
  (newTableInstance, oldTableInstance) => {
    // Only recalculate if the table instance is ready AND we have sticky columns defined
    if (newTableInstance && props.stickyColumns.length > 0) {
      nextTick(() => {
        calculateStickyPositions();
      });
    }
  },
  { deep: true, immediate: true } // deep to watch for changes within the table instance, immediate to run on setup
);

// Watch `props.columns` and `props.data` separately if changes to these should trigger recalculation
// even if the `table` instance reference itself doesn't change.
// The `table` watcher above covers changes that cause `useVueTable` to implicitly rebuild its internal state.
watch(
  () => [props.columns, props.data, props.stickyColumns],
  () => {
    // Ensure table is ready and DOM is updated
    if (table && props.stickyColumns.length > 0) {
       nextTick(() => {
         calculateStickyPositions();
       });
    }
  },
  { deep: true } // deep is important for nested changes in columns/data
);

// onMounted is still okay for first render, but the watchers are more comprehensive.
onMounted(() => {
  nextTick(() => {
    calculateStickyPositions();
  });
});


const hasFooter = computed(() =>
  table.getFooterGroups().some(group =>
    group.headers.some(header => header.column.columnDef.footer)
  )
)

const pageCount = computed(() => table.getPageCount())
const rowCount = computed(() => table.getFilteredRowModel().rows.length)
const pageStart = computed(() => (pageIndex.value * pageSize.value) + 1)
const pageEnd = computed(() => Math.min(rowCount.value, (pageIndex.value + 1) * pageSize.value))

const visiblePages = computed(() => {
  const total = pageCount.value
  const current = pageIndex.value + 1
  const pages = []

  let start = Math.max(1, current - 2)
  let end = Math.min(total, current + 2)

  if (current <= 3) {
    end = Math.min(5, total)
  }
  if (current >= total - 2) {
    start = Math.max(1, total - 4)
  }

  for (let i = start; i <= end; i++) {
    pages.push(i)
  }
  return pages
})

function goToPage(page) {
  if (page < 1 || page > pageCount.value) return
  pageIndex.value = page - 1
}

function previousPage() {
  if (pageIndex.value > 0) pageIndex.value -= 1
}

function nextPage() {
  if (pageIndex.value < pageCount.value - 1) pageIndex.value += 1
}

function firstPage() {
  pageIndex.value = 0
}

function lastPage() {
  pageIndex.value = table.getPageCount() - 1
}

function onPageSizeChange(event) {
  pageSize.value = Number(event.target.value)
  pageIndex.value = 0
}
</script>

<style scoped>
/* Table container - handles both vertical and horizontal overflow */
.table-container {
  overflow: auto;
  border: 1px solid #e5e7eb;
  border-radius: 0.375rem;
  position: relative;
}

/* Table wrapper - ensures proper table layout */
.table-wrapper {
  min-width: 100%;
  width: max-content; /* Allows content to define width, enabling horizontal scroll */
  border-collapse: collapse;
}

/* Sticky header styling */
.sticky-header {
  position: sticky;
  top: 0;
  z-index: 10; /* Higher than regular table content but lower than sticky header + sticky column */
}

/* Sticky column styling - position will be set dynamically by inline style */
.sticky-column {
  /* No 'left' property here; it's handled by getStickyColumnStyle via inline styles */
  border-right: 2px solid #e5e7eb; /* Visual separator for sticky columns */
}

/* Higher z-index for sticky header + sticky column intersection */
.sticky-header .sticky-column {
  z-index: 15; /* Ensures sticky header columns are above regular sticky columns and scrolled content */
}

/* Custom scrollbar styles for webkit browsers */
.table-container::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

.table-container::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 4px;
}

.table-container::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}

.table-container::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

.table-container::-webkit-scrollbar-corner {
  background: #f1f5f9;
}

/* Ensure table cells don't wrap unnecessarily */
.table-wrapper td,
.table-wrapper th {
  white-space: nowrap; /* Prevents text from wrapping within cells */
}

/* Custom background colors for sticky elements for better visual distinction */
.bg-blue-25 {
  background-color: #f8faff; /* Lightest blue for sticky body cells */
}
.bg-blue-50 {
  background-color: #eef2ff; /* Slightly darker for sticky header cells */
}
.bg-blue-100 {
  background-color: #dbeafe; /* Even darker for sticky footer cells */
}

.table-wrapper .wrap-text {
  white-space: normal;
  word-wrap: break-word;
  max-width: 200px; /* Example: for cells that should wrap text */
}
</style> 
export function useTableState(props, emit) {
  const sorting = ref([])
  const pageIndex = ref(0)
  const pageSize = ref(props.defaultPageSize)
  const globalFilter = ref('')
  const rowSelection = ref(props.modelValue)
  
  // All state management logic here
  return { sorting, pageIndex, pageSize, globalFilter, rowSelection }
}
