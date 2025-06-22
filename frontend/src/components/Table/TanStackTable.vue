<template>
  <div class="p-4 h-full flex flex-col gap-4">
    <!-- Search and Page Size Controls -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <!-- Global Search -->
 

      <!-- Page Size Selector -->
      <div class="flex items-center gap-2">
        <label class="text-sm text-gray-600">Show</label>
        <select
          class="px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <option value="5">5</option>
          <option value="10">10</option>
          <option value="25">25</option>
          <option value="50">50</option>
        </select>
        <span class="text-sm text-gray-600">entries</span>
      </div>

      <input
        type="text"
        placeholder="Search all columns..."
        class="w-full sm:max-w-sm px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
      />
    </div>

    <!-- Table -->
    <div class="flex-1 overflow-auto max-h-full">
      <table class="table-wrapper w-full text-left text-sm">
        <thead class="bg-gray-100 text-gray-700 uppercase text-xs sticky top-0 z-10">
          <tr>
            <th v-for="col in 10" :key="col" class="px-4 py-3 font-medium border border-gray-300">
              <div class="flex items-center gap-1">
                Column {{ col }}
                <span class="cursor-pointer">⬍</span>
              </div>
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-for="row in 5" :key="row" class="hover:bg-gray-50 transition">
            <td v-for="cell in 10" :key="cell" class="px-4 py-3 border border-gray-300">
              Row {{ row }}, Cell {{ cell }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination Controls -->
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
      <!-- Entry Info -->
      <div class="text-sm text-gray-600">
        Showing 1 to 5 of 50 entries
      </div>

      <!-- Controls -->
      <div class="flex items-center gap-1 flex-wrap text-sm">
        <!-- First & Previous -->
        <button class="px-3 py-1 border rounded-md hover:bg-gray-100 disabled:opacity-50" disabled>
          First
        </button>
        <button class="px-3 py-1 border rounded-md hover:bg-gray-100 disabled:opacity-50" disabled>
          Prev
        </button>

        <!-- Page Numbers -->
        <div class="flex items-center gap-1">
          <button
            v-for="page in [1, 2, 3]"
            :key="page"
            class="px-3 py-1 border rounded-md hover:bg-blue-200"
            :class="page === 1 ? 'bg-blue-500 text-white hover:bg-blue-600' : ''"
          >
            {{ page }}
          </button>
        </div>

        <!-- Next & Last -->
        <button class="px-3 py-1 border rounded-md hover:bg-gray-100">
          Next
        </button>
        <button class="px-3 py-1 border rounded-md hover:bg-gray-100">
          Last
        </button>
      </div>

    </div>
  </div>
</template>

<style scoped>
.table-wrapper {
  display: block;
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
