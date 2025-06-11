<template>
  <div class="bg-gray-50 min-h-screen flex justify-center py-12">
    <div class="w-full max-w-xl" >
      <div class="bg-white shadow-lg rounded-lg p-4"  v-bind="$attrs">
        <slot />
      </div>
    </div>
  </div>
</template>


<script setup>
defineOptions({
  inheritAttrs: false
})
</script>