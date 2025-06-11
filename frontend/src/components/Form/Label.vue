<template>
    <label
      :for="transformedLabel"
      class="block text-sm font-medium text-gray-700 mb-1"
    >
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
</template>

<script setup>
import { computed } from 'vue'
const props = defineProps({
  label: String,
  required: {
    type: Boolean,
    default: true
  }
})


const transformedLabel = computed(() => {
  return props.label?.trim().toLowerCase().replace(/\s+/g, '_') || 'input'
})
</script>

<style lang="scss" scoped>

</style>