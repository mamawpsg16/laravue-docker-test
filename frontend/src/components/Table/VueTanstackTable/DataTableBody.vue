<template>
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
            :ref="el => $emit('set-header-ref', header.column.id, el)"
            class="px-4 py-3 font-medium border border-gray-300 bg-gray-50"
            :class="{
              'cursor-pointer select-none': header.column.getCanSort(), // Apply pointer cursor if column is sortable
              'sticky-column': stickyColumns.includes(header.column.id), // Apply sticky styles if column is designated as sticky
              'bg-blue-50': stickyColumns.includes(header.column.id) // Specific background for sticky header cells
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
                    'bi bi-arrow-up': header.column.getIsSorted() === 'asc', // Up arrow for ascending sort
                    'bi bi-arrow-down': header.column.getIsSorted() === 'desc', // Down arrow for descending sort
                    'bi bi-arrow-down-up': !header.column.getIsSorted() // Up-down arrow when not sorted
                  }"
                ></i>
              </span>
            </div>
          </th>
        </tr>
      </thead>

      <tbody v-if="loading" class="divide-y divide-gray-100">
        <tr>
          <td :colspan="tableColumns.length" class="text-center py-8 border border-gray-300">
            <div class="flex flex-col items-center justify-center">
              <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900"></div>
              <p class="mt-4 text-gray-700">Loading data...</p>
            </div>
          </td>
        </tr>
      </tbody>

      <tbody v-else-if="rowCount > 0" class="divide-y divide-gray-100">
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
              'bg-blue-25': stickyColumns.includes(cell.column.id)
            }"
            :style="getStickyColumnStyle(cell.column.id)" >
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
            :colspan="tableColumns.length" class="text-center py-8 border border-gray-300"
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
</template>

<script setup>
import { computed } from 'vue';
import { FlexRender } from '@tanstack/vue-table';

// Props received from DataTable.vue
// These are the properties passed down from the parent component.
const props = defineProps({
  table: {
    type: Object,
    required: true // The TanStack Table instance, essential for rendering.
  },
  rowCount: {
    type: Number,
    required: true // Total number of rows in the table.
  },
  tableColumns: {
    type: Array,
    required: true // Array of column definitions.
  },
  noDataMessage: {
    type: String,
    default: 'No data found.' // Message to display when no data is available.
  },
  tableHeight: {
    type: String,
    default: '670px' // Height of the table container.
  },
  stickyHeader: {
    type: Boolean,
    default: false // Determines if the table header should be sticky.
  },
  stickyColumns: {
    type: Array,
    default: () => [] // Array of column IDs that should be sticky.
  },
  headerRefs: {
    type: Object,
    required: true // Object containing refs to header elements for sticky column calculations.
  },
  stickyColumnPositions: {
    type: Object,
    default: () => ({}) // Object storing the calculated 'left' positions for sticky columns.
  },
  loading: {
    type: Boolean,
    default: false // Default to not loading
  }
});

// Emits an event to the parent component.
const emit = defineEmits(['set-header-ref']); // Emits when a header ref needs to be set in the parent.

/**
 * Provides the inline style object for a given column ID for sticky positioning.
 * This function calculates the `left` CSS property to make columns sticky.
 * @param {string} columnId The ID of the column for which to get sticky styles.
 * @returns {object} An object containing CSS style properties for sticky positioning.
 */
const getStickyColumnStyle = (columnId) => {
  // 1. Ensure stickyColumns is always treated as an array.
  // This guards against potential issues if `props.stickyColumns` is not an array.
  const stickyColumns = Array.isArray(props.stickyColumns) ? props.stickyColumns : [];

  // 2. Exit early for non-sticky columns.
  // If the current column is not designated as sticky, return an empty style object.
  if (!stickyColumns.includes(columnId)) return {};

  // 3. Safely access positions with fallbacks.
  // `props.stickyColumnPositions` holds the calculated 'left' offsets for sticky columns.
  // Provides an empty object as a fallback if `props.stickyColumnPositions` is null/undefined.
  const positions = props.stickyColumnPositions || {};
  // Gets the sticky information for the specific column, defaulting to { left: 0, width: 0 } if not found.
  const stickyInfo = positions[columnId] || { left: 0, width: 0 };

  // 4. Validate left value before use.
  // Ensures that `stickyInfo.left` is a number before using it in the style.
  const leftPos = typeof stickyInfo.left === 'number' ? stickyInfo.left : 0;

  // Return the CSS style object for sticky columns.
  return {
    position: 'sticky', // Makes the element sticky.
    left: `${leftPos}px`, // Sets the left offset for sticky positioning.
    zIndex: 5 // Ensures sticky columns appear above non-sticky content when scrolling.
  };
};

/**
 * Computed property to determine if the table has any footer content to display.
 * It checks if any footer group has at least one header with a defined footer.
 */
const hasFooter = computed(() =>
  props.table.getFooterGroups().some(group =>
    group.headers.some(header => header.column.columnDef.footer)
  )
);
</script>

<style scoped>
/* --- STYLES FOR THE TABLE BODY AND STICKY FEATURES --- */
/* These styles are crucial for the table's appearance, especially scrolling and sticky behavior. */

/* Table container - handles both vertical and horizontal overflow */
.table-container {
  overflow: auto; /* Enables scrolling when content overflows */
  border: 1px solid #e5e7eb; /* Light gray border */
  border-radius: 0.375rem; /* Rounded corners */
  position: relative; /* Needed for sticky positioning within this container */
}

/* Table wrapper - ensures proper table layout */
.table-wrapper {
  min-width: 100%; /* Ensures table takes at least full width of container */
  width: max-content; /* Allows table to grow beyond 100% and enable horizontal scroll */
  border-collapse: collapse; /* Collapses cell borders for a cleaner look */
}

/* Sticky header styling */
.sticky-header {
  position: sticky; /* Makes the header sticky */
  top: 0; /* Sticks to the top of its scrolling container */
  z-index: 10; /* Ensures it stays above regular table content when scrolling */
}

/* Sticky column styling - 'left' position is set dynamically by inline style */
.sticky-column {
  /* No 'left' property here; it's handled by getStickyColumnStyle via inline styles */
  border-right: 2px solid #e5e7eb; /* Visual separator for sticky columns */
}

/* Higher z-index for sticky header + sticky column intersection */
/* This ensures the corner cell (top-left sticky) is on top of everything else */
.sticky-header .sticky-column {
  z-index: 15; /* Ensures sticky header columns are above regular sticky columns and scrolled content */
}

/* Custom scrollbar styles for webkit browsers (Chrome, Safari, Edge) */
.table-container::-webkit-scrollbar {
  width: 8px;  /* Width of the vertical scrollbar */
  height: 8px; /* Height of the horizontal scrollbar */
}

.table-container::-webkit-scrollbar-track {
  background: #f1f5f9; /* Light track color */
  border-radius: 4px; /* Rounded corners for the track */
}

.table-container::-webkit-scrollbar-thumb {
  background: #cbd5e1; /* Gray scrollbar thumb */
  border-radius: 4px; /* Rounded corners for the thumb */
}

.table-container::-webkit-scrollbar-thumb:hover {
  background: #94a3b8; /* Darker gray on hover */
}

.table-container::-webkit-scrollbar-corner {
  background: #f1f5f9; /* Corner area where scrollbars meet */
}

/* Ensure table cells don't wrap unnecessarily by default */
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

/* Utility class to allow text wrapping for specific cells if needed */
.table-wrapper .wrap-text {
  white-space: normal;
  word-wrap: break-word;
  max-width: 200px; /* Example: for cells that should wrap text */
}

/* Basic spinner animation */
@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}
</style>