<template>
  <div v-if="tableHeaderTitle" class="flex justify-between items-center p-3 rounded-t-lg border-b -mx-4 -mt-4">
    <h2 v-if="tableHeaderTitle" class="text-xl font-semibold">
      {{ tableHeaderTitle }}
    </h2>
    <div v-if="hasTableHeaderActionsSlot" class="flex space-x-2">
      <slot name="table-actions"></slot>
    </div>
  </div>
</template>

<script setup>
// --- Props Definition ---
// Props are custom attributes you can register on a component.
// They are used to pass data from a parent component to a child component.
const props = defineProps({
  tableHeaderTitle: {
    type: String,
    default: '' // The title to be displayed in the header of the data table.
  },
  // Boolean to indicate if the parent has provided content for the 'table-actions' slot.
  // This prop allows the component to decide whether to render the slot container.
  hasTableHeaderActionsSlot: {
    type: Boolean,
    default: false
  }
});
</script>

<style scoped>
/* Scoped styles specific to DataTableHeader.vue. */
/* These styles will only apply to elements within this component to prevent style conflicts. */
/* Currently, most styling is handled by Tailwind CSS utility classes directly in the template. */
</style>