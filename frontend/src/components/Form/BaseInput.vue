<!-- components/BaseInput.vue -->
<template>
  <div class="mb-3">
    <label class="form-label" v-if="label" :for="transformedLabel">{{ label }}</label>
    <input
      :type="type"
      :value="modelValue"
      class="form-control"
      :placeholder="placeholder"
      :required="required"
      :autocomplete="autocomplete"
      :name="name"
      @input="updateValue($event.target.value)"
    />
  </div>
</template>

<script setup>
import { computed } from 'vue';

// Define props
const props = defineProps({
  modelValue: [String, Number],
  label: String,
  type: {
    type: String,
    default: 'text'
  },
  placeholder: String,
  required: {
    type: Boolean,
    default: false
  },
  autocomplete: String,
  name: String
});

// Declare emit function
const emit = defineEmits(['update:modelValue']);

// Method to update the value
const updateValue = (value) => {
  emit('update:modelValue', value); // Emit updated value
};

// Computed property for label transformation
const transformedLabel = computed(() => {
  let newLabel = props.label ? props.label.toLowerCase().replace(/\s+/g, '-') : ''; 
  return `${newLabel}-input`; // Lowercase and replace spaces with hyphens
});
</script>
