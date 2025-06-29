<template>
  <input
    type="checkbox"
    class="indeterminate-checkbox"
    :checked="checked"
    :disabled="disabled"
    @change="$emit('change', $event)"
    ref="checkbox"
  />
</template>

<script setup>
import { watch, ref, onMounted } from 'vue'

// --- Props Definition ---
// Props are custom attributes to pass data into this component.
const props = defineProps({
  checked: Boolean,      // Controls whether the checkbox is checked.
  indeterminate: Boolean, // Controls whether the checkbox is in an indeterminate state.
  disabled: Boolean,     // Controls whether the checkbox is disabled.
})

// --- Emits Definition ---
// Defines the custom events this component can emit to its parent.
const emit = defineEmits(['change']) // Emits a 'change' event when the checkbox's value changes.

// --- Reactive Reference ---
// `ref` is used to create a reactive reference to a DOM element (the checkbox input).
const checkbox = ref(null)

// --- Watcher for `indeterminate` prop ---
// `watch` is used to reactively perform side effects when a reactive source changes.
// Here, it watches the `props.indeterminate` value.
watch(
  () => props.indeterminate, // The source to watch: the `indeterminate` prop.
  val => {
    // The callback function executed when `props.indeterminate` changes.
    // It directly sets the `indeterminate` property of the native checkbox element.
    if (checkbox.value) checkbox.value.indeterminate = val
  },
  { immediate: true } // `immediate: true` makes the watcher run once immediately when the component is mounted.
)

// --- Lifecycle Hook: `onMounted` ---
// `onMounted` hook runs after the component has been mounted to the DOM.
onMounted(() => {
  // Ensures the indeterminate state is set correctly when the component first mounts.
  // This is important because the watcher might not catch initial render for some cases
  // or if the `immediate` option was not used.
  if (checkbox.value) checkbox.value.indeterminate = props.indeterminate
})
</script>

<style scoped>
@import '@/assets/css/indeterminate-checkbox.css';
</style>