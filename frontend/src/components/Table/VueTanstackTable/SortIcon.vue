<template>
  <svg
    v-if="iconType === 'asc'"
    xmlns="http://www.w3.org/2000/svg"
    width="16"
    height="16"
    fill="currentColor"
    class="sort-arrow sort-arrow-up"
    viewBox="0 0 16 16"
  >
    <path d="M8 2l3 3h-2v9h-2V5H5l3-3z"/>
  </svg>

  <svg
    v-else-if="iconType === 'desc'"
    xmlns="http://www.w3.org/2000/svg"
    width="16"
    height="16"
    fill="currentColor"
    class="sort-arrow sort-arrow-down"
    viewBox="0 0 16 16"
  >
    <path d="M8 14l-3-3h2V2h2v9h2l-3 3z"/>
  </svg>

  <svg
    v-else
    xmlns="http://www.w3.org/2000/svg"
    width="16"
    height="16"
    fill="currentColor"
    class="sort-arrow sort-arrow-none"
    viewBox="0 0 16 16"
  >
    <!-- Up arrow (top half) -->
    <path d="M8 2l3 3h-2v3h-2V5H5l3-3z"/>
    <!-- Down arrow (bottom half) -->
    <path d="M8 14l-3-3h2V8h2v3h2l-3 3z"/>
  </svg>
</template>

<script setup>
defineProps({
  iconType: {
    type: String,
    validator: (value) => ['asc', 'desc', 'none'].includes(value),
    required: true
  }
});
</script>