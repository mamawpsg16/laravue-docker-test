<template>
    <input
      v-bind="$attrs" 
      :type="type"
      v-model="modelValue"
      :disabled="disabled"
      :readonly="readonly"
     :class="[
        type === 'checkbox'
          ? 'h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded'
          : 'w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none mb-3',
        {
          'border-gray-300  focus:ring-2 focus:ring-blue-500': !hasFrontendError && !hasBackendError,
          'border-red-500': hasFrontendError || hasBackendError
        },
        $attrs.class // ← allow user-defined class to override
      ]"
    />
    <!-- Multiple or single frontendError display -->
    <div v-if="hasFrontendError" class="mt-0.5 text-xs text-red-600 space-y-0"> 
      <div v-for="(err, index) in frontendErrors" :key="index">
        {{ err }}
      </div>
    </div>

    <div v-if="hasBackendError" class="mt-0.5 text-xs text-red-600 space-y-0"> <!-- reduced from mt-1, text-sm, space-y-0.5 -->
      <div v-for="(err, index) in backendError" :key="index">
        {{ err }}
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
  type: { type: String, default: 'text' },
  required: { type: Boolean, default: true },
  name: String,
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

const frontendErrors = computed(() =>
  Array.isArray(props.frontendError) ? props.frontendError : props.frontendError ? [props.frontendError] : []
)

const hasFrontendError = computed(() => frontendErrors.value.length > 0)

const hasBackendError = computed(() => props?.backendError?.length > 0)
</script>