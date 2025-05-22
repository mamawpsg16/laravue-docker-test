<!-- components/BaseInput.vue -->
<template>
  <div class="mb-3">
    <label class="form-label" v-if="label" :for="transformedLabel">{{ label }}</label>
    <input
      :type="type"
      v-model="newModelValue"
      class="form-control"
      :placeholder="placeholder"
      :required="required"
      :autocomplete="autocomplete"
      :name="name"
    />
    <!-- <input
      :type="type"
      :value="modelValue"
      class="form-control"
      :placeholder="placeholder"
      :required="required"
      :autocomplete="autocomplete"
      :name="name"
      @input="updateValue($event.target.value)"
    /> -->
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

// Define the v-model binding with type support (string or number)
const newModelValue = defineModel<string | number>()

// Define other props with types and defaults
const props = withDefaults(defineProps<{
  label?: string
  type?: string
  placeholder?: string
  required?: boolean
  autocomplete?: string
  name?: string
}>(), {
  label: '',
  type: 'text',
  placeholder: '',
  required: false,
  autocomplete: '',
  name: '',
})

// Generate a label `for` attribute based on label text
const transformedLabel = computed(() => {
  const base = props.label?.trim().toLowerCase().replace(/\s+/g, '-') || 'input'
  return `${base}-input`
})
</script>