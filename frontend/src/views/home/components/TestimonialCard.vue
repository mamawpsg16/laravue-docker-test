<template>
  <div class="bg-white p-6 rounded-lg shadow text-center">
    <img :src="testimonial.avatar" alt="avatar" class="mx-auto w-16 h-16 rounded-full mb-4 object-cover" />
    <p class="italic text-gray-600 mb-2">"{{ testimonial.text }}"</p>
    <p class="font-semibold text-indigo-600">{{ testimonial.name }}</p>
  </div>
</template>

<script setup>
defineProps({
  testimonial: Object
})
</script>
