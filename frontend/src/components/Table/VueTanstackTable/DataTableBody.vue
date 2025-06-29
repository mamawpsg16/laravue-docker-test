<template>
  <div
    class="datatable-body-container"
    :style="{ height: tableHeight }"
    ref="tableContainer"
  >
    <table class="datatable-body-wrapper">
      <thead
        class="datatable-body-thead"
        :class="{ 'sticky-header': stickyHeader }"
      >
        <tr v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
          <th
            v-for="header in headerGroup.headers"
            :key="header.id"
            :ref="el => $emit('set-header-ref', header.column.id, el)"
            class="datatable-header-cell"
            :class="{
              'datatable-header-cell-sortable': header.column.getCanSort(),
              'sticky-column': stickyColumns.includes(header.column.id),
              'sticky-header-column-bg': stickyColumns.includes(header.column.id)
            }"
            :style="getStickyColumnStyle(header.column.id)"
            @click="header.column.getToggleSortingHandler()?.($event)"
          >
            <div class="datatable-header-cell-content">
              <FlexRender
                :render="header.column.columnDef.header"
                :props="header.getContext()"
              />
              <span class="datatable-sort-icon-wrapper" v-if="header.column.getCanSort()">
                <SortIcon
                  :iconType="
                    header.column.getIsSorted() === 'asc'
                      ? 'asc'
                      : header.column.getIsSorted() === 'desc'
                      ? 'desc'
                      : 'none'
                  "
                />
              </span>
            </div>
          </th>
        </tr>
      </thead>

      <tbody v-if="loading" class="datatable-body-tbody datatable-body-loader-row">
        <tr>
          <td :colspan="tableColumns.length" class="datatable-loader-cell">
            <div class="datatable-loader-content">
              <div class="datatable-spinner"></div>
              <p class="datatable-loader-text">Loading data...</p>
            </div>
          </td>
        </tr>
      </tbody>

      <tbody v-else-if="rowCount > 0" class="datatable-body-tbody">
        <tr v-for="row in table.getRowModel().rows" :key="row.id" class="datatable-body-row">
          <td
            v-for="cell in row.getVisibleCells()"
            :key="cell.id"
            class="datatable-body-cell"
            :class="{
              'text-left': cell.column.columnDef.meta?.textAlign === 'left',
              'text-right': cell.column.columnDef.meta?.textAlign === 'right',
              'text-center': cell.column.columnDef.meta?.textAlign === 'center',
              'sticky-column': stickyColumns.includes(cell.column.id),
              'sticky-body-cell-bg': stickyColumns.includes(cell.column.id)
            }"
            :style="getStickyColumnStyle(cell.column.id)" >
            <FlexRender
              :render="cell.column.columnDef.cell"
              :props="cell.getContext()"
            />
          </td>
        </tr>
      </tbody>

      <tbody v-else class="datatable-body-tbody datatable-body-no-data">
        <tr>
          <td
            :colspan="tableColumns.length" class="datatable-no-data-cell"
          >
            <p class="datatable-no-data-message">{{ noDataMessage }}</p>
          </td>
        </tr>
      </tbody>

      <tfoot v-if="hasFooter" class="datatable-body-tfoot">
        <tr v-for="footerGroup in table.getFooterGroups()" :key="footerGroup.id">
          <td
            v-for="footer in footerGroup.headers"
            :key="footer.id"
            class="datatable-footer-cell"
            :class="{
              'text-left': footer.column.columnDef.meta?.textAlign === 'left',
              'text-right': footer.column.columnDef.meta?.textAlign === 'right',
              'text-center': footer.column.columnDef.meta?.textAlign === 'center',
              'sticky-column': stickyColumns.includes(footer.column.id),
              'sticky-footer-cell-bg': stickyColumns.includes(footer.column.id)
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
import SortIcon from './SortIcon.vue';

const props = defineProps({
  table: { type: Object, required: true },
  rowCount: { type: Number, required: true },
  tableColumns: { type: Array, required: true },
  noDataMessage: { type: String, default: 'No data found.' },
  tableHeight: { type: String, default: '670px' },
  stickyHeader: { type: Boolean, default: false },
  stickyColumns: { type: Array, default: () => [] },
  headerRefs: { type: Object, required: true },
  stickyColumnPositions: { type: Object, default: () => ({}) },
  loading: { type: Boolean, default: false }
});

const emit = defineEmits(['set-header-ref']);

const getStickyColumnStyle = (columnId) => {
  const stickyColumns = Array.isArray(props.stickyColumns) ? props.stickyColumns : [];
  if (!stickyColumns.includes(columnId)) return {};

  const positions = props.stickyColumnPositions || {};
  const stickyInfo = positions[columnId] || { left: 0, width: 0 };
  const leftPos = typeof stickyInfo.left === 'number' ? stickyInfo.left : 0;

  return {
    position: 'sticky',
    left: `${leftPos}px`,
    zIndex: 5
  };
};

const hasFooter = computed(() =>
  props.table.getFooterGroups().some(group =>
    group.headers.some(header => header.column.columnDef.footer)
  )
);
</script>

<style scoped>
@import '@/assets/css/datatable-body.css';
</style>