// Handles sticky column positioning and calculations
// Manages DOM references and calculates left positions for CSS sticky positioning

import { ref, watch, nextTick, onMounted } from 'vue'

export function useStickyColumns(props, table) {
  // ==================================================
  // REACTIVE REFERENCES
  // ==================================================

  const headerRefs = ref({})                      // Store DOM references to header <th> elements: { 'columnId': HTMLElement }
  const stickyColumnPositions = ref({})           // Store calculated positions: { 'columnId': { left: 100, width: 50 } }

  // ==================================================
  // HELPER FUNCTIONS
  // ==================================================

  // Generate default position objects for sticky columns
  // Ensures every sticky column has a position entry, even before DOM measurement
  const generateDefaultStickyPositions = (stickyCols) => {
    return stickyCols.reduce((acc, colId) => {
      acc[colId] = { left: 0, width: 0 }          // Default to left: 0, width: 0
      return acc
    }, {})
  }

  // Callback function used with template refs to store header DOM elements
  // Called from template: <th :ref="(el) => setHeaderRef(column.id, el)">
  const setHeaderRef = (columnId, el) => {
    if (el) {
      // Store the DOM element reference
      headerRefs.value[columnId] = el
      // Ensure position entry exists
      if (!stickyColumnPositions.value[columnId]) {
        stickyColumnPositions.value[columnId] = { left: 0, width: 0 }
      }
    } else {
      // Clean up when element is removed
      delete headerRefs.value[columnId]
    }
  }

  // ==================================================
  // MAIN CALCULATION FUNCTION
  // ==================================================

  // Calculate the exact left position and width for each sticky column
  // This is called whenever columns, data, or sticky settings change
  const calculateStickyPositions = () => {
    const currentStickyColumns = props.stickyColumns || [];

    // Early exit if no table instance is available yet
    if (!table.value) {
      stickyColumnPositions.value = generateDefaultStickyPositions(currentStickyColumns);
      return;
    }

    // Get the current column visibility state from TanStack Table
    // This will be { 'columnId': false } for hidden columns, or undefined/true for visible ones.
    const currentColumnVisibility = table.value.getState().columnVisibility || {};

    // Filter sticky columns: only consider those that are currently visible
    const visibleStickyColumns = currentStickyColumns.filter(colId => {
      // A column is considered visible if it's not explicitly set to `false` in `columnVisibility`
      return currentColumnVisibility[colId] !== false;
    });

    // Ensure all *visible* sticky columns have position entries
    const tempPositions = { ...stickyColumnPositions.value };
    visibleStickyColumns.forEach(colId => {
      if (!tempPositions[colId]) {
        tempPositions[colId] = { left: 0, width: 0 };
      }
    });

    // If no *visible* sticky columns, clear positions and return
    if (visibleStickyColumns.length === 0) {
      stickyColumnPositions.value = {};
      return;
    }

    // ==================================================
    // POSITION CALCULATION ALGORITHM
    // ==================================================

    let cumulativeWidth = 0;                       // Running total of widths for left positioning
    const newPositions = {};

    // Get column order from TanStack Table (respects column ordering)
    const allColumns = table.value.getAllLeafColumns();

    // Filter all columns to only include those that are *both* in stickyColumns *and* currently visible
    const columnsToProcessForSticky = allColumns.filter(col =>
      visibleStickyColumns.includes(col.id) && col.getIsVisible()
    );

    // Process columns in display order (only those that are visible and sticky)
    for (const column of columnsToProcessForSticky) {
      const columnId = column.id;
      const headerEl = headerRefs.value[columnId];

      if (headerEl) {
        // Measure actual DOM element width
        const width = headerEl.getBoundingClientRect().width;
        newPositions[columnId] = {
          width: width,
          left: cumulativeWidth               // Position starts where previous column ended
        };
        cumulativeWidth += width;              // Add this column's width for next column
      } else {
        // This case should now be much rarer, as we filtered for visible columns.
        // It might happen if `getIsVisible()` is true but DOM hasn't caught up, or an unexpected race condition.
        newPositions[columnId] = stickyColumnPositions.value[columnId] || { left: 0, width: 0 };
      }
    }

    // Update reactive positions
    stickyColumnPositions.value = newPositions;
  };

  // ==================================================
  // STYLE GENERATION FUNCTION
  // ==================================================

  // Generate CSS style object for a given column to make it sticky
  // Used in template: :style="getStickyColumnStyle(column.id)"
  const getStickyColumnStyle = (columnId) => {
    // Only apply sticky styles to columns marked as sticky by props
    if (!(props.stickyColumns || []).includes(columnId)) {
      return {};                                   // Return empty object for non-sticky columns
    }

    // Also check if the column is currently visible before applying sticky styles
    const isColumnVisible = table.value?.getColumn(columnId)?.getIsVisible();
    if (!isColumnVisible) {
      // If the column is hidden, it shouldn't have sticky styles
      return {};
    }

    const stickyInfo = stickyColumnPositions.value[columnId];

    if (!stickyInfo) {
      // Fallback if position info is missing (should be rare with new logic)
      return {
        position: 'sticky',
        left: '0px',
        zIndex: 5                                 // Ensure sticky columns appear above regular content
      };
    }

    // Return CSS properties for sticky positioning
    return {
      position: 'sticky',                         // CSS sticky positioning
      left: `${stickyInfo.left + 2}px`,           // FIXED: Was 'lef' instead of 'left'
      zIndex: 5                                   // Stack above regular columns
    };
  };

  // ==================================================
  // WATCHERS AND LIFECYCLE
  // ==================================================

  // OPTIMIZED: More selective watchers to prevent unnecessary recalculations
  // Watch only for structural changes, not data changes
  watch(
    () => [
      props.stickyColumns || [],
      table.value?.getState()?.columnVisibility // Only watch column visibility and sticky config
    ],
    () => {
      // Recalculate after DOM updates
      nextTick(() => {
        calculateStickyPositions();
      });
    },
    { deep: true, immediate: true }               // Watch deeply and run immediately
  );

  // Watch for TanStack Table column sizing changes (e.g., user resizing columns)
  watch(
    () => table.value?.getState()?.columnSizingInfo,
    () => {
      // Recalculate positions when column sizes change
      nextTick(() => {
        calculateStickyPositions();
      })
    },
    { deep: true }
  )

  // ADDED: Watch for column order changes (if columns can be reordered)
  watch(
    () => table.value?.getState()?.columnOrder,
    () => {
      nextTick(() => {
        calculateStickyPositions();
      })
    },
    { deep: true }
  )

  // Recalculate positions after component is mounted and DOM is ready
  onMounted(() => {
    nextTick(() => {
      calculateStickyPositions();
    })
  })

  // ==================================================
  // INITIAL SETUP
  // ==================================================

  // Initialize positions with defaults for initial sticky columns
  // This will be immediately overwritten by the `watch`'s `immediate: true` run.
  stickyColumnPositions.value = generateDefaultStickyPositions(props.stickyColumns || [])

  return {
    headerRefs,
    stickyColumnPositions,
    setHeaderRef,
    calculateStickyPositions,
    getStickyColumnStyle
  }
}