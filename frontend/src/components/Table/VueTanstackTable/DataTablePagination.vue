<template>
  <div class="pagination-container">
    <div class="pagination-info">
      Showing {{ pageStart }} to {{ pageEnd }} of {{ rowCount }} entries
    </div>

    <div class="pagination-controls">
      <button
        class="pagination-button"
        :disabled="pageIndex === 0"
        @click="$emit('first-page')"
      >
        First
      </button>
      <button
        class="pagination-button"
        :disabled="pageIndex === 0"
        @click="$emit('previous-page')"
      >
        Prev
      </button>

      <div class="pagination-pages">
        <button
          v-for="page in visiblePages"
          :key="page"
          class="page-number-button"
          :class="{ active: page === pageIndex + 1 }"
          @click="$emit('go-to-page', page)"
        >
          {{ page }}
        </button>
      </div>

      <button
        class="pagination-button"
        :disabled="pageIndex >= pageCount - 1"
        @click="$emit('next-page')"
      >
        Next
      </button>
      <button
        class="pagination-button"
        :disabled="pageIndex >= pageCount - 1"
        @click="$emit('last-page')"
      >
        Last
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

// --- Props Definition ---
// Props are custom attributes you can register on a component.
// They are used to pass data from a parent component to a child component.
const props = defineProps({
  pageIndex: {
    type: Number,
    required: true // Current page index (0-indexed, meaning the first page is 0, second is 1, etc.)
  },
  pageCount: {
    type: Number,
    required: true // Total number of available pages.
  },
  rowCount: {
    type: Number,
    required: true // Total number of filtered rows across all pages.
  },
  pageSize: {
    type: Number,
    required: true // Number of entries displayed per page.
  },
  visiblePages: {
    type: Array,
    required: true // An array of page numbers that should be displayed in the pagination controls (e.g., [1, 2, 3, 4, 5]).
  }
});

// --- Emits Definition ---
// Emits are used to send custom events from a child component to its parent.
// These events trigger corresponding actions in the parent `DataTable.vue`.
const emit = defineEmits([
  'go-to-page',     // Emits when a specific page number button is clicked.
  'previous-page',  // Emits when the "Prev" button is clicked.
  'next-page',      // Emits when the "Next" button is clicked.
  'first-page',     // Emits when the "First" button is clicked.
  'last-page'       // Emits when the "Last" button is clicked.
]);

// --- Computed Properties ---
// Computed properties are reactive, cached values that only re-evaluate when their dependencies change.

/**
 * Calculates the starting entry number displayed on the current page.
 * Example: On page 0 with page size 10, it's (0 * 10) + 1 = 1.
 * On page 1 with page size 10, it's (1 * 10) + 1 = 11.
 */
const pageStart = computed(() => (props.pageIndex * props.pageSize) + 1);

/**
 * Calculates the ending entry number displayed on the current page.
 * Uses `Math.min` to ensure it doesn't exceed the total `rowCount`.
 * Example: On page 0 with page size 10, it's (0 + 1) * 10 = 10.
 * If total rows are 7 and page size is 10, it's min(7, (0 + 1) * 10) = 7.
 */
const pageEnd = computed(() => Math.min(props.rowCount, (props.pageIndex + 1) * props.pageSize));
</script>

<style scoped>
@import '@/assets/css/datatable-pagination.css';
</style>