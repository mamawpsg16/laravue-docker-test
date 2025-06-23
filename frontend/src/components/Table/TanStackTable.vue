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
import { ref, toValue, computed, toRefs } from 'vue'

defineOptions({
  inheritAttrs: false
})

const props = defineProps({
  data: {
    type: Array,
    default: () => [],
  },
  columns: {
    type: Array,
    default: () => [],
  },
  globalSearchPlaceHolder:{
    type:String,
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
})


const columnVisibility = ref({})
const globalFilter = ref('')

function buildVisibility(columns) {
  const visibility = {}
  columns.forEach(col => {
    visibility[col.key] = col.visible !== false // default true if undefined
  })
  return visibility
}

// Initialize visibility state
columnVisibility.value = buildVisibility(props.columns)

const tableColumns = computed(() => {
  const rawColumns = toValue(props.columns)
  return Array.isArray(rawColumns)
    ? rawColumns.map((col) => {
        const columnDef = {
          header: col.label,
          cell: col.cell ? col.cell : (info) => info.getValue(),
          enableSorting: col.sortable !== false,
          sortAs: col.sortAs ? col.sortAs : undefined
        }
        if (col.footer !== undefined) {
          columnDef.footer = col.footer
        }
        return createColumnHelper().accessor(col.field, columnDef)
      })
    : []
})

const { pageSizeOptions, defaultPageSize } = toRefs(props)

const sorting = ref([])
const pageIndex = ref(0)  // current page index (0-based)
const pageSize = ref(defaultPageSize.value)  // default page size

const table = useVueTable({
  get data() {
    return props.data;
  },
  columns: toValue(tableColumns),
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
    }
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
  getCoreRowModel: getCoreRowModel(),
  getSortedRowModel: getSortedRowModel(),
  getPaginationRowModel: getPaginationRowModel(),
  getFilteredRowModel: getFilteredRowModel(),
})

const hasFooter = computed(() =>
  table.getFooterGroups().some(group =>
    group.headers.some(header => header.column.columnDef.footer)
  )
)

// Computed for pagination info display
const pageCount = computed(() => table.getPageCount())
const rowCount = computed(() => table.getFilteredRowModel().rows.length)
const pageStart = computed(() => (pageIndex.value * pageSize.value) + 1)
const pageEnd = computed(() => Math.min(rowCount.value, (pageIndex.value + 1) * pageSize.value))

// Helper to generate page numbers for pagination buttons (simple example)
const visiblePages = computed(() => {
  const total = pageCount.value
  const current = pageIndex.value + 1
  const pages = []

  // Show up to 5 pages centered around current page
  let start = Math.max(1, current - 2)
  let end = Math.min(total, current + 2)

  // Adjust if near start or end
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

// Pagination control handlers
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
  pageIndex.value = pageCount.value - 1
}

function onPageSizeChange(event) {
  pageSize.value = Number(event.target.value)
  pageIndex.value = 0 // reset page index on page size change
}
</script>



<style scoped>
.table-wrapper {
  overflow-x: auto;
  white-space: nowrap;
  min-width: max-content;
}

.table-wrapper::-webkit-scrollbar {
  height: 8px;
}
.table-wrapper::-webkit-scrollbar-track {
  background: #f1f1f1;
}
.table-wrapper::-webkit-scrollbar-thumb {
  background: #c1c1c1;
}
.table-wrapper::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}
</style>
