<template>
  <div class="container">
    <h2 class="mb-4">Task List</h2>
    
    <form @submit.prevent="submit" class="input-group mb-3">
      <input v-model="newTask" class="form-control" placeholder="Add new task" />
      <button class="btn btn-primary">Add</button>
    </form>

    <ul class="list-group">
      <li class="list-group-item d-flex justify-content-between align-items-center" v-for="(task, i) in tasks" :key="i">
        <div class="form-check">
            <input type="checkbox" v-model="task.done" class="form-check-input me-2" />

            <label class="form-check-label" :class="{ 'text-decoration-line-through': task.done }">
                {{ task.text }}
            </label>
        </div>
        <button class="btn btn-danger btn-sm" @click="removeTask(i)">×</button>
      </li>
    </ul>
  </div>
</template>


<script setup>
import { ref } from 'vue'
import { useTaskStore } from '@/stores/taskStore'

const store = useTaskStore()
const tasks = store.tasks
const removeTask = store.removeTask

const newTask = ref('')
const submit = () => {
    if (newTask.value.trim()) {
        store.addTask(newTask.value)
        newTask.value = ''
    }
}
</script>
