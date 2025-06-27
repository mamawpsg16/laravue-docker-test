<template>
  <div class="flex flex-col sm:flex-row items-center justify-between p-2 bg-gray-50 rounded-md shadow-sm gap-4">
    <!-- Left: Show entries -->
    <div class="flex items-center space-x-2 flex-shrink-0">
      <span>Show</span>
      <select
        :value="pageSize"
        @change="onPageSizeChange"
        class="form-select block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
      >
        <option v-for="option in pageSizeOptions" :key="option" :value="option">
          {{ option }}
        </option>
      </select>
      <span>entries</span>
    </div>

    <!-- Right: Columns dropdown and Global Search side by side -->
    <div class="flex items-center space-x-3 flex-1 justify-end min-w-0">
      <!-- Columns dropdown -->
      <div class="relative inline-block text-left flex-shrink-0" v-if="toggableColumnVisibility">
        <button
          type="button"
          @click="toggleColumnDropdown"
          class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-100"
          id="options-menu"
          aria-haspopup="true"
          :aria-expanded="isColumnDropdownOpen ? 'true' : 'false'"
        >
          Show/Hide Columns
          <span class="sr-only">Toggle column visibility</span>
          <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </button>

        <div
          v-if="isColumnDropdownOpen"
          class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
          role="menu"
          aria-orientation="vertical"
          aria-labelledby="options-menu"
          @click.stop="() => {}"
        >
          <div class="py-1" role="none">
            <label class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
              <input
                type="checkbox"
                :checked="getIsAllColumnsVisible()"
                @change="(e) => getToggleAllColumnsVisibilityHandler()(e)"
                class="form-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded"
              />
              <span class="ml-2">Toggle All</span>
            </label>
            <div class="border-t border-gray-200 my-1"></div>

            <label
              v-for="column in allColumns.filter(col => col.getCanHide())"
              :key="column.id"
              class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer"
            >
              <input
                type="checkbox"
                :checked="column.getIsVisible()"
                @change="(e) => column.getToggleVisibilityHandler()(e)"
                class="form-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded"
              />
              <span class="ml-2">{{ column.columnDef.header }}</span>
            </label>
          </div>
        </div>
      </div>

      <!-- Global Search input -->
      <div class="flex-1 min-w-0 max-w-md">
        <input
          type="text"
          :value="globalFilter"
          @input="onGlobalFilterChange"
          :placeholder="globalSearchPlaceHolder"
          class="form-input block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded-md transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
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
  if (isColumnDropdownOpen.value && !event.target.closest('.relative.inline-block')) {
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
/* Add any specific styles here if needed, or rely on Tailwind CSS */
</style>