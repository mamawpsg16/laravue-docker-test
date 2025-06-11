<template>
  <div class="mb-2">
    <label
      v-if="label"
      :for="transformedLabel"
      class="block text-sm font-medium text-gray-700 mb-1"
    >
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    <input
      v-bind="$attrs" 
      :id="transformedLabel"
      :type="type"
      v-model="modelValue"
      class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md py-2 px-3"
      :placeholder="placeholder"
      :autocomplete="autocomplete"
      :name="inputName"
      :aria-label="label || placeholder"
      :disabled="disabled"
      :readonly="readonly"
      :class="{
        'border-gray-300': (!hasFrontendError || !hasBackendError),
        'border-red-500': (hasFrontendError || hasBackendError)
      }"
    />
    <!-- Multiple or single frontendError display -->
    <div v-if="hasFrontendError" class="mt-1 text-sm text-red-600 space-y-0.5">
      <div v-for="(err, index) in frontendErrors" :key="index">
        {{ err }}
      </div>
    </div>

    <div v-if="hasBackendError" class="mt-1 text-sm text-red-600 space-y-0.5">
      <div v-for="(err, index) in backendError" :key="index">
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

const modelValue = defineModel()

const props = defineProps({
  label: String,
  type: { type: String, default: 'text' },
  placeholder: String,
  required: { type:Boolean, default: true},
  autocomplete: String,
  name: String,
  frontendError: String,
  disabled: Boolean,
  readonly: Boolean,
  frontendError: {
    type: [String, Array],
    default: null
  },
  backendError:{
    type: Array,
    default: null
  }
})

const transformedLabel = computed(() => {
  return props.label?.trim().toLowerCase().replace(/\s+/g, '_') || 'input'
})

const inputName = computed(() => props.name || transformedLabel.value)

const frontendErrors = computed(() =>
  Array.isArray(props.frontendError) ? props.frontendError : props.frontendError ? [props.frontendError] : []
)

const hasFrontendError = computed(() => frontendErrors.value.length > 0)

const hasBackendError = computed(() => props?.backendError?.length > 0)
</script>
