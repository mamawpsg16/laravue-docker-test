<!-- BackToTop.vue -->
<template>
  <button
    v-show="isVisible"
    @click="scrollToTop"
    class="fixed bottom-6 right-6 z-50 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-full shadow-lg transition-all duration-300"
  >
    ↑ {{ progress }}%
  </button>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const isVisible = ref(false)
const progress = ref(0)

const handleScroll = () => {
  const scrollTop = window.scrollY
  const docHeight = document.documentElement.scrollHeight - window.innerHeight
  const scrolled = (scrollTop / docHeight) * 100

  progress.value = Math.min(Math.round(scrolled), 100)
  isVisible.value = scrollTop > 300
}

const scrollToTop = () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth',
  })
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})
</script>
