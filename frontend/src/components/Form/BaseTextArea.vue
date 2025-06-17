<template>
  <div class="w-full">
    <label v-if="label" :for="id" class="block text-sm font-medium text-gray-700 mb-1">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>

    <textarea
      :id="id"
      :rows="rows"
      :placeholder="placeholder"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      :disabled="disabled"
      :readonly="readonly"
      :class="[
        'w-full border rounded-md px-4 py-2 focus:outline-none focus:ring-2 mb-3',
        hasFrontendError || hasBackendError
          ? 'border-red-500 focus:ring-red-500'
          : 'border-gray-300 focus:ring-blue-500',
        textareaClass
      ]"
    ></textarea>

    <!-- Frontend Errors -->
    <div v-if="hasFrontendError" class="mt-0.5 text-xs text-red-600 space-y-0">
      <div v-for="(err, index) in frontendErrors" :key="'fe-' + index">
        {{ err }}
      </div>
    </div>

    <!-- Backend Errors -->
    <div v-if="hasBackendError" class="mt-0.5 text-xs text-red-600 space-y-0">
      <div v-for="(err, index) in backendError" :key="'be-' + index">
        {{ err }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

defineOptions({
  inheritAttrs: false
})

const props = defineProps({
  modelValue: String,
  label: String,
  id: {
    type: String,
    default: () => `textarea-${Math.random().toString(36).substr(2, 9)}`
  },
  placeholder: {
    type: String,
    default: ''
  },
  rows: {
    type: Number,
    default: 3
  },
  required: {
    type: Boolean,
    default: false
  },
  textareaClass: {
    type: String,
    default: ''
  },
  disabled: Boolean,
  readonly: Boolean,
  frontendError: {
    type: [String, Array],
    default: null
  },
  backendError: {
    type: Array,
    default: null
  }
})

defineEmits(['update:modelValue'])

const frontendErrors = computed(() =>
  Array.isArray(props.frontendError)
    ? props.frontendError
    : props.frontendError
      ? [props.frontendError]
      : []
)

const hasFrontendError = computed(() => frontendErrors.value.length > 0)
const hasBackendError = computed(() => props?.backendError?.length > 0)
</script>
