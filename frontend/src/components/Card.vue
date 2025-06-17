<template>
    <div class="card" v-bind="attrs">
      <header v-if="$slots.header">
        <slot name="header" :headerMessage="headerName" />

      </header>
  
      <main>
        <slot />
      </main>
  
      <footer v-if="$slots.footer">
        <slot name="footer" :footerMessage="footerName" />
      </footer>
    </div>
  </template>
  
  <script setup lang="ts">
  import { useAttrs, defineSlots } from 'vue'
  
  const footerName = 'This is the footer name'
  const headerName = 'This is the header name'
  const attrs = useAttrs()
  
  defineSlots<{
    header?: (props: { name: string }) => any
    default: () => any
    footer?: (props: { name: string }) => any
  }>()
  </script>
  
  <style scoped>
  .card {
    border: 1px solid #ddd;
    padding: 1rem;
    border-radius: 8px;
  }
  header, footer {
    background: #f5f5f5;
    padding: 0.5rem;
  }
  </style>
  