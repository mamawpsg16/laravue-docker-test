// Handles all pagination-related logic and calculations
// Converts between TanStack's 0-based indexing and user-friendly 1-based display

import { computed } from 'vue'

export function usePagination(table, tableState) {
  // ==================================================
  // COMPUTED PROPERTIES - Auto-update when dependencies change
  // ==================================================

  const pageIndex = computed(() => tableState.pagination.pageIndex)    // Current page (0-based)
  const pageSize = computed(() => tableState.pagination.pageSize)      // Rows per page
  const pageCount = computed(() => table.value ? table.value.getPageCount() : 0)  // Total pages
  const rowCount = computed(() => table.value ? table.value.getRowCount() : 0)    // Total rows

  // Calculate the starting row number for current page (1-based for display)
  // Example: Page 2 with 10 rows per page = row 11
  const pageStart = computed(() => {
    if (rowCount.value === 0) return 0
    return pageIndex.value * pageSize.value + 1
  })

  // Calculate the ending row number for current page (1-based for display)  
  // Example: Page 2 with 10 rows per page, 25 total rows = row 20
  const pageEnd = computed(() => {
    if (rowCount.value === 0) return 0
    const end = (pageIndex.value + 1) * pageSize.value
    return Math.min(end, rowCount.value)          // Don't exceed total row count
  })

  // Calculate which page numbers to show in pagination controls
  // Handles ellipsis (...) for large page counts
  // IMPORTANT: Converts from 0-based (internal) to 1-based (display) indexing
  const visiblePages = computed(() => {
    const pages = []
    const totalPages = pageCount.value
    const currentPage = pageIndex.value           // 0-based from TanStack Table
    const maxVisiblePages = 7                     // Maximum page numbers to show

    if (totalPages <= maxVisiblePages) {
      // If we have few pages, show them all
      // Convert to 1-based for user display: [1, 2, 3, 4, 5]
      for (let i = 0; i < totalPages; i++) {
        pages.push(i + 1)                         // +1 converts 0-based to 1-based
      }
    } else {
      // For many pages, show current page + surrounding pages with ellipsis
      let startPage = Math.max(0, currentPage - Math.floor(maxVisiblePages / 2))
      let endPage = Math.min(totalPages - 1, startPage + maxVisiblePages - 1)

      // Adjust if we're near the end
      if (endPage - startPage + 1 < maxVisiblePages) {
        startPage = Math.max(0, endPage - maxVisiblePages + 1)
      }

      // Add first page if not in range
      if (startPage > 0) {
        pages.push(1)                             // First page (1-based)
        if (startPage > 1) {
          pages.push('...')                       // Ellipsis if gap exists
        }
      }

      // Add pages in range (convert to 1-based)
      for (let i = startPage; i <= endPage; i++) {
        pages.push(i + 1)                         // +1 converts 0-based to 1-based
      }

      // Add last page if not in range
      if (endPage < totalPages - 1) {
        if (endPage < totalPages - 2) {
          pages.push('...')                       // Ellipsis if gap exists
        }
        pages.push(totalPages)                    // Last page (1-based)
      }
    }
    return pages
  })

  // ==================================================
  // NAVIGATION FUNCTIONS
  // These functions handle page navigation
  // ==================================================

  const goToPage = (page) => {
    // Navigate to specific page
    // IMPORTANT: Convert from 1-based display page to 0-based TanStack page
    // User clicks "3" → we call setPageIndex(2)
    const zeroBasedPage = typeof page === 'number' ? page - 1 : page
    if (table.value && typeof zeroBasedPage === 'number') {
      table.value.setPageIndex(zeroBasedPage)
    }
  }

  const previousPage = () => {
    // Go to previous page (TanStack handles bounds checking)
    if (table.value) table.value.previousPage()
  }

  const nextPage = () => {
    // Go to next page (TanStack handles bounds checking)
    if (table.value) table.value.nextPage()
  }

  const firstPage = () => {
    // Go to first page (index 0)
    if (table.value) table.value.setPageIndex(0)
  }

  const lastPage = () => {
    // Go to last page (total pages - 1, since 0-based)
    if (table.value) table.value.setPageIndex(table.value.getPageCount() - 1)
  }

  return {
    pageIndex,
    pageSize,
    pageCount,
    rowCount,
    pageStart,
    pageEnd,
    visiblePages,
    goToPage,
    previousPage,
    nextPage,
    firstPage,
    lastPage
  }
}