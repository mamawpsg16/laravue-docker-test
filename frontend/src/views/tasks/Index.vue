<template>
  <div>
    <router-link :to="{ name: 'dashboard' }" class="btn btn-primary mt-3">Go to Dashboard</router-link>
    <TaskList />
  </div>
</template>

<script setup>
import TaskList from '@/components/TaskList.vue'
</script>
