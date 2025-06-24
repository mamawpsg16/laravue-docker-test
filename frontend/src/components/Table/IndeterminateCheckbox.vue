<template>
  <input
    type="checkbox"
    :checked="checked"
    :disabled="disabled"
    @change="$emit('change', $event)"
    ref="checkbox"
  />
</template>

<script setup>
import { watch, ref, onMounted } from 'vue'

const props = defineProps({
  checked: Boolean,
  indeterminate: Boolean,
  disabled: Boolean,
})

const emit = defineEmits(['change'])

const checkbox = ref(null)

watch(
  () => props.indeterminate,
  val => {
    if (checkbox.value) checkbox.value.indeterminate = val
  },
  { immediate: true }
)

onMounted(() => {
  if (checkbox.value) checkbox.value.indeterminate = props.indeterminate
})
</script>
