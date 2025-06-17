import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useTaskStore = defineStore('task', () => {
  const tasks = ref([])

  const addTask = (text) => {
    tasks.value.push({ text, done: false })
  }

  const removeTask = (index) => {
    tasks.value.splice(index, 1)
  }

  return { tasks, addTask, removeTask }
})
