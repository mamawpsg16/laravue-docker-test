<template>
    <label
      :for="props.for"
      class="text-sm font-medium text-gray-700 mb-1"
    >
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
</template>

<script setup>
const props = defineProps({
  label: String,
  required: {
    type: Boolean,
    default: true
  },
  for:{
    type:String,
    default:''
  },
})

</script>

<style lang="scss" scoped>

</style>