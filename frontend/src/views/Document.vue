<template>
  <div class="p-6 max-w-4xl mx-auto">
    <h2 class="text-xl font-bold mb-4">Your Uploaded Documents</h2>

    <div v-if="loading" class="text-gray-600">Loading documents...</div>
    <div v-else-if="documents.length === 0" class="text-gray-500">No documents found.</div>

    <div v-else class="grid gap-4">
      <div
        v-for="doc in documents"
        :key="doc.id"
        class="p-4 border rounded bg-white shadow hover:bg-gray-50"
      >
        <div class="font-semibold text-lg">{{ doc.title }}</div>
        <div class="text-sm text-gray-500">Uploaded: {{ formatDate(doc.created_at) }}</div>
        <div v-if="doc.type" class="text-sm mt-1">Type: {{ doc.type }}</div>
        <div v-if="doc.extracted_text" class="text-sm mt-2 text-gray-700">
          <strong>AI Text:</strong> {{ doc.extracted_text.slice(0, 100) }}...
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { apiV1 } from '@/services/axios';

const documents = ref([])
const loading = ref(true)

onMounted(async () => {
  try {
    const response = await apiV1.get('/documents/index')
    documents.value = response.data
  } catch (err) {
    console.error('Error fetching documents:', err)
  } finally {
    loading.value = false
  }
})

function formatDate(dateStr) {
  return new Date(dateStr).toLocaleString()
}
</script>
