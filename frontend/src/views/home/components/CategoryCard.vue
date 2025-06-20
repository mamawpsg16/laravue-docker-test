<template>
  <div class="cursor-pointer group rounded-lg overflow-hidden shadow hover:shadow-lg transition">
    <img :src="category.image" :alt="category.name" class="w-full h-32 object-cover group-hover:scale-105 transition-transform" />
    <div class="p-4 bg-white text-center font-semibold text-gray-700 group-hover:text-indigo-600">
      {{ category.name }}
    </div>
  </div>
</template>

<script setup>
defineProps({
  category: Object
})
</script>
