<script setup>
const modelValue = defineModel()  // automatically binds to v-model
</script>

<template>
  <input v-model="modelValue" />
</template>
