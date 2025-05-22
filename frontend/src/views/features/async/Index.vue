<script setup>
import { defineAsyncComponent, ref } from 'vue'
import LoadingSpinner from '@/components/AsyncComponents/LoadingSpinner.vue'
import ErrorDisplay from '@/components/AsyncComponents/ErrorDisplay.vue'

const retryKey = ref(0) // used to force reload component on retry

const AsyncComponent = defineAsyncComponent({
  loader: () => new Promise((resolve) => {
    // simulate slow loading (2 seconds)
    setTimeout(() => resolve(import('./MyAsyncContent.vue')), 2000)
  }),
  loadingComponent: LoadingSpinner,
  errorComponent: ErrorDisplay,
  delay: 200,
  timeout: 4000,
})

function retry() {
  retryKey.value++ // changing key forces component reload
}
</script>

<template>
  <div>
    <h3>ASYNC</h3>
    <AsyncComponent :key="retryKey" @retry="retry" />
  </div>
</template>
