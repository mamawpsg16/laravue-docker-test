<template>
    <div class="p-6 max-w-xl mx-auto bg-white rounded shadow">
      <h2 class="text-xl font-bold mb-4">Upload Document</h2>
      <form @submit.prevent="uploadDocument" class="space-y-4">
        <div>
          <label class="block text-sm font-medium">Title</label>
          <input v-model="form.title" type="text" class="w-full p-2 border rounded" required />
        </div>
        <div>
          <label class="block text-sm font-medium">File (JPG, PNG, PDF)</label>
          <input @change="handleFileChange" type="file" class="w-full p-2 border rounded" required />
        </div>
        <button
          type="submit"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
          :disabled="isSubmitting"
        >
          {{ isSubmitting ? 'Uploading...' : 'Upload' }}
        </button>
      </form>
  
      <div v-if="message" class="mt-4 text-green-600">{{ message }}</div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import axios from 'axios'
  import { apiV1 } from '@/services/axios';

  const form = ref({
    title: '',
    file: null
  })
  
  const isSubmitting = ref(false)
  const message = ref('')
  
  function handleFileChange(event) {
    form.value.file = event.target.files[0]
  }
  
  async function uploadDocument() {
    if (!form.value.file) return
  
    const formData = new FormData()
    formData.append('title', form.value.title)
    formData.append('file', form.value.file)
  
    isSubmitting.value = true
    message.value = ''
  
    try {
      const response = await apiV1.post('/documents/upload', formData)
      message.value = 'Upload successful!'
      form.value.title = ''
      form.value.file = null
    } catch (error) {
      message.value = 'Upload failed.'
      console.error(error)
    } finally {
      isSubmitting.value = false
    }
  }
  </script>
  