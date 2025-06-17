<template>
    <div class="border rounded-lg border-gray-200  mb-6">
        <div class="px-6 py-4">
            <div class="flex flex-col sm:flex-row gap-4">
                 <div class="flex-1">
                    <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Search appointments..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    />
                 </div>
                 <select
                    v-model="statusFilter"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                    <option value="">All Status</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="pending">Pending</option>
                    <option value="cancelled">Cancelled</option>
                </select>
             </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  search: String,
  status: String
})

const emit = defineEmits(['update:search', 'update:status'])

const searchQuery = computed({
  get: () => props.search,
  set: (value) => emit('update:search', value),
})

const statusFilter = computed({
  get: () => props.status,
  set: (value) => emit('update:status', value),
})
</script>
