<!-- FieldWrapper.vue -->
<template>
  <div class="mb-2">
    <Label :label="props.label"/>
    <div :class="['w-full', (hasFrontendError || hasBackendError)  ? 'border border-red-500 rounded' : '']">
        <slot />
    </div>

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
import Label from '@/components/Form/Label.vue'
import { computed } from 'vue';

const props = defineProps({
  label: String,
  frontendError: String,
  backendError:{
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
