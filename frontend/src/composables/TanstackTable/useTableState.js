// Manages all the internal state for TanStack Table including:
// - Sorting state
// - Pagination state
// - Global filtering
// - Row selection
// - Column visibility

import { reactive, watch } from 'vue'

export function useTableState(props, emit, initialColumnVisibilityState) { // Renamed param for clarity

  // Create reactive state object that mirrors TanStack Table's expected state structure
  const tableState = reactive({
    sorting: [],
    globalFilter: '',
    pagination: {
      pageIndex: 0,
      pageSize: props.defaultPageSize,
    },
    rowSelection: props.modelValue || {},
    // CORRECTED: Initialize columnVisibility with the .value of the computed property
    columnVisibility: { ...initialColumnVisibilityState.value },
  })

  // Watch for external changes to modelValue (v-model binding for row selection)
  watch(
    () => props.modelValue,
    (newVal) => {
      tableState.rowSelection = newVal || {}
    },
    { deep: true, immediate: true }
  )

  // CORRECTED: Watch for changes in the .value of the computed initialColumnVisibilityState
  // This is crucial because initialColumnVisibilityState is derived from props.columns
  // and might change if props.columns changes *after* initial setup.
  watch(
    () => initialColumnVisibilityState.value, // WATCH THE .VALUE
    (newVisibility) => {
      // Merge new visibility with current state, or replace if preferred.
      // Here, we're merging to ensure user-toggled visibility isn't lost if data/columns change.
      tableState.columnVisibility = { ...tableState.columnVisibility, ...newVisibility };
    },
    { deep: true, immediate: true } // immediate to apply on initial load
  )


  // ==================================================
  // STATE UPDATE HANDLERS
  // ==================================================

  const onSortingChange = (updaterOrValue) => {
    tableState.sorting = typeof updaterOrValue === 'function'
      ? updaterOrValue(tableState.sorting)
      : updaterOrValue
  }

  const onPaginationChange = (updaterOrValue) => {
    tableState.pagination = typeof updaterOrValue === 'function'
      ? updaterOrValue(tableState.pagination)
      : updaterOrValue
  }

  const onGlobalFilterChange = (updaterOrValue) => {
    tableState.globalFilter = typeof updaterOrValue === 'function'
      ? updaterOrValue(tableState.globalFilter)
      : updaterOrValue
  }

  const onRowSelectionChange = (table) => (updaterOrValue) => {
    tableState.rowSelection = typeof updaterOrValue === 'function'
      ? updaterOrValue(tableState.rowSelection)
      : updaterOrValue

    emit('update:modelValue', tableState.rowSelection)

    const selectedRowData = table?.getSelectedRowModel().flatRows.map(row => row.original) || [];
    emit('update:selectedRows', selectedRowData)
  }

  const onColumnVisibilityChange = (updaterOrValue) => {
    tableState.columnVisibility = typeof updaterOrValue === 'function'
      ? updaterOrValue(tableState.columnVisibility)
      : updaterOrValue

    // This emits the object like { 'columnId1': true, 'columnId2': false, ... }
    emit('update:columnVisibility', tableState.columnVisibility);
  }


  return {
    tableState,
    onSortingChange,
    onPaginationChange,
    onGlobalFilterChange,
    onRowSelectionChange,
    onColumnVisibilityChange,
  }
}