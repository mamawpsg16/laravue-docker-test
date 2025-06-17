<template>
  <div class="section-header">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="section-title">{{ title }}</h1>
        <p v-if="subtitle" class="section-subtitle">{{ subtitle }}</p>
      </div>
      <slot name="action" />
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  subtitle: String
});
</script>
