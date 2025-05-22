<!-- CounterComponent.vue -->
<template>
    <div>
      <p>Count: {{ count }}</p>
      <button @click="increment">+</button>
      <button @click="decrement">−</button>
    </div>
  </template>
  
  <script setup>
  import { useCounter } from '@/composables/useCounter';
  
  const { count, increment, decrement } = useCounter();
  </script>
  