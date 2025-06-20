<template>
  <div class="border rounded-lg p-4 flex flex-col items-center hover:shadow-lg transition cursor-pointer">
    <img :src="product.image" :alt="product.name" class="w-full h-48 object-cover mb-4 rounded" />
    <h3 class="font-semibold text-lg text-gray-800">{{ product.name }}</h3>
    <p class="text-indigo-600 font-bold mt-2">${{ product.price.toFixed(2) }}</p>
  </div>
</template>

<script setup>
defineProps({
  product: Object
})
</script>
