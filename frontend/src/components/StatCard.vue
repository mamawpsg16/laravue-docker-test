<template>
  <div class="card">
    <div class="flex items-center">
      <div :class="['p-3 rounded-lg', bgColor]">
        <slot name="icon" />
      </div>
      <div class="ml-4">
        <p class="text-sm font-medium text-gray-600">{{ label }}</p>
        <p class="text-2xl font-bold text-gray-900">{{ value }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  label: String,
  value: [String, Number],
  bgColor: String
});
</script>
