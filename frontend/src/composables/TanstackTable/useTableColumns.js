// Handles column definitions and transformations
// Converts props.columns into TanStack Table column definitions
// Adds selection column when row selection is enabled

import { computed, toValue, h } from 'vue';
import { createColumnHelper } from '@tanstack/vue-table';
import IndeterminateCheckbox from '@/components/Table/VueTanstackTable/IndeterminateCheckbox.vue'; // Adjust path as needed

export function useTableColumns(props) {
  const columnHelper = createColumnHelper();

  // ==================================================
  // SELECTION COLUMN DEFINITION
  // ==================================================
  const SelectionColumn = columnHelper.display({
    id: 'select',
    header: ({ table: currentTableInstance }) => {
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
    enableHiding: false, // Selection column should NOT be hidable by the user
  });

  // ==================================================
  // INITIAL COLUMN VISIBILITY COMPUTATION
  // ==================================================

  // CORRECTED: initialColumnVisibilityState is now a computed property
  const initialColumnVisibilityState = computed(() => {
    const visibility = {};
    const rawColumns = toValue(props.columns); // Ensure columns are unwrapped

    if (!Array.isArray(rawColumns)) {
      return visibility; // Return empty if columns are not an array
    }

    rawColumns.forEach(col => {
      const columnId = col.id || col.field;
      // If 'visible' is explicitly false, mark it as hidden initially.
      // Otherwise, it will default to true (visible) by TanStack Table if not in this object.
      if (col.visible === false) {
        visibility[columnId] = false;
      }
    });
    return visibility;
  });

  // ==================================================
  // MAIN COLUMN DEFINITIONS COMPUTATION
  // ==================================================

  const tableColumns = computed(() => {
    const rawColumns = toValue(props.columns);
    if (!Array.isArray(rawColumns)) return [];

    const dataColumns = rawColumns.map(col => {
      const columnId = col.id || col.field;

      const columnDef = {
        id: columnId,
        header: col.label,
        cell: col.cell ? col.cell : info => info.getValue(),
        enableSorting: col.sortable !== false,
        sortAs: col.sortAs ? col.sortAs : undefined,
        meta: {
          textAlign: col.textAlign || 'left'
        }
      };

      if (typeof col.enableHiding === 'boolean') {
        columnDef.enableHiding = col.enableHiding;
      } else {
        columnDef.enableHiding = true; // Default to hidable
      }

      // The 'visible' logic is now handled in initialColumnVisibilityState computed property above
      // So no need for `if (col.visible === false)` here anymore

      if (col.footer !== undefined) {
        columnDef.footer = col.footer;
      }

      return columnHelper.accessor(col.field, columnDef);
    });

    return props.enableRowSelection ? [SelectionColumn, ...dataColumns] : dataColumns;
  });

  return {
    tableColumns,
    initialColumnVisibilityState // Now this is a computed ref (a reactive object with a .value property)
  };
}