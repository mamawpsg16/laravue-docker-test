<template>
    <h3 for="next-tick">Next Tick</h3>
    <p>Previous Count: {{ prevCount }}</p>
    <p>Current Count: {{ currentCount }}</p>
    <button id="counter" @click="increment">{{ count }}</button>
</template>

<script setup>
import { ref, nextTick } from 'vue'
const count = ref(0)
const prevCount = ref(0)
const currentCount = ref(0)

async function increment() {
  count.value++

  // DOM not yet updated
  prevCount.value = document.getElementById('counter').textContent;

  await nextTick()
  // DOM is now updated
  currentCount.value = document.getElementById('counter').textContent;
}
</script>