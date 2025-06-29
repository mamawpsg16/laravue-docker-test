<template>
  <div v-if="tableHeaderTitle || hasTableHeaderActionsSlot" class="datatable-header-container">
    <h2 v-if="tableHeaderTitle" class="datatable-header-title">
      {{ tableHeaderTitle }}
    </h2>
    <div v-if="hasTableHeaderActionsSlot" class="datatable-header-actions">
      <slot name="table-actions"></slot>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  tableHeaderTitle: {
    type: String,
    default: ''
  },
  hasTableHeaderActionsSlot: {
    type: Boolean,
    default: false
  }
});
</script>

<style scoped>
@import '@/assets/css/datatable-header.css';
/* Remove the border-bottom from here.
   It belongs in datatable-header.css to maintain alignment and centralize. */

/* If you need any truly component-specific, isolated styles that you absolutely do not want in the central CSS,
   they would go here. Otherwise, leave this block empty or consider removing it if there are no scoped styles needed. */
</style>