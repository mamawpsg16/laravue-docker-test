<template>
  <div class="table-controls">
    <!-- Left: Show entries -->
    <div class="entries-section">
      <span>Show</span>
      <select
        :value="pageSize"
        @change="onPageSizeChange"
        class="page-size-select"
      >
        <option v-for="option in pageSizeOptions" :key="option" :value="option">
          {{ option }}
        </option>
      </select>
      <span>entries</span>
    </div>

    <!-- Right: Columns dropdown and Global Search side by side -->
    <div class="controls-right">
      <!-- Columns dropdown -->
      <div class="column-dropdown-container" v-if="toggableColumnVisibility">
        <button
          type="button"
          @click="toggleColumnDropdown"
          class="column-dropdown-button"
          id="options-menu"
          aria-haspopup="true"
          :aria-expanded="isColumnDropdownOpen ? 'true' : 'false'"
        >
          Show/Hide Columns
          <span class="sr-only">Toggle column visibility</span>
          <svg class="dropdown-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </button>

        <div
          v-if="isColumnDropdownOpen"
          class="column-dropdown-menu"
          role="menu"
          aria-orientation="vertical"
          aria-labelledby="options-menu"
          @click.stop="() => {}"
        >
          <div class="dropdown-menu-content" role="none">
            <label class="dropdown-item">
              <input
                type="checkbox"
                :checked="getIsAllColumnsVisible()"
                @change="(e) => getToggleAllColumnsVisibilityHandler()(e)"
                class="dropdown-checkbox"
              />
              <span class="checkbox-label">Toggle All</span>
            </label>
            <div class="dropdown-divider"></div>

            <label
              v-for="column in allColumns.filter(col => col.getCanHide())"
              :key="column.id"
              class="dropdown-item"
            >
              <input
                type="checkbox"
                :checked="column.getIsVisible()"
                @change="(e) => column.getToggleVisibilityHandler()(e)"
                class="dropdown-checkbox"
              />
              <span class="checkbox-label">{{ column.columnDef.header }}</span>
            </label>
          </div>
        </div>
      </div>

      <!-- Global Search input -->
      <div class="search-container">
        <input
          type="text"
          :value="globalFilter"
          @input="onGlobalFilterChange"
          :placeholder="globalSearchPlaceHolder"
          class="global-search-input"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  pageSize: Number,
  pageSizeOptions: Array,
  globalFilter: String,
  globalSearchPlaceHolder: String,
  allColumns: Array, // TanStack Table's array of column objects
  getIsAllColumnsVisible: Function, // Function to check if all columns are visible
  getToggleAllColumnsVisibilityHandler: Function, // Function to toggle all columns visibility
  setColumnVisibility: Function, // Function to directly set column visibility (less used for UI, but good to have)
  toggableColumnVisibility: Boolean
});

const emit = defineEmits(['update:pageSize', 'update:globalFilter']);

const isColumnDropdownOpen = ref(false);

const onPageSizeChange = (event) => {
  emit('update:pageSize', Number(event.target.value));
};

const onGlobalFilterChange = (event) => {
  emit('update:globalFilter', event.target.value);
};

const toggleColumnDropdown = () => {
  isColumnDropdownOpen.value = !isColumnDropdownOpen.value;
};

// Close dropdown when clicking outside
const handleClickOutside = (event) => {
  if (isColumnDropdownOpen.value && !event.target.closest('.column-dropdown-container')) {
    isColumnDropdownOpen.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>

<style scoped>
@import '@/assets/css/datatable-controls.css';
</style>