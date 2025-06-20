<template>
  <div :class="['w-full mx-auto px-4', props.maxWidth]">
    <!-- Main Gallery Carousel -->
    <Carousel
      v-bind="mergedGalleryConfig"
      v-model="currentSlide"
      class="rounded-xl overflow-hidden shadow-lg w-full"
    >
      <Slide v-for="image in images" :key="image.id">
        <img
          :src="image.url"
          alt="Gallery Image"
          class="w-full h-full object-cover rounded-xl"
        />
      </Slide>
    </Carousel>

    <!-- Thumbnails Carousel -->
    <Carousel
      v-bind="mergedThumbnailsConfig"
      v-model="currentSlide"
      class="mt-4 w-full"
    >
      <Slide v-for="image in images" :key="image.id">
        <template #default="{ currentIndex, isActive }">
          <div
            @click="slideTo(currentIndex)"
            :class="[
              'cursor-pointer rounded-md overflow-hidden border transition-opacity duration-300 h-full',
              isActive ? 'opacity-100 border-white' : 'opacity-60 border-transparent hover:opacity-80'
            ]"
          >
            <img
              :src="image.url"
              alt="Thumbnail"
              class="w-full h-full object-cover"
            />
          </div>
        </template>
      </Slide>

      <template #addons>
        <Navigation />
      </template>
    </Carousel>
  </div>
</template>

<script setup>
import { Carousel, Slide, Navigation } from 'vue3-carousel'
import { ref, computed } from 'vue'
import 'vue3-carousel/carousel.css'

// Props
const props =defineProps({
  images: {
    type: Array,
    required: true
  },
  galleryConfig: {
    type: Object,
    default: () => ({})
  },
  thumbnailsConfig: {
    type: Object,
    default: () => ({})
  },
  maxWidth: {
    type: String,
    default: 'max-w-4xl' // You can override it when using the component
  }
})

const currentSlide = ref(0)

const slideTo = (index) => {
  currentSlide.value = index
}

// Default config values (merged with props)
const defaultGalleryConfig = {
  itemsToShow: 1,
  wrapAround: true,
  slideEffect: 'fade',
  mouseDrag: false,
  touchDrag: false,
  height: 320,
  autoplay: 2000,
  pauseAutoplayOnHover: true
}

const defaultThumbnailsConfig = {
  height: 80,
  itemsToShow: 5,
  wrapAround: true,
  touchDrag: false,
  gap: 4
}

const mergedGalleryConfig = computed(() => ({
  ...defaultGalleryConfig,
  ...props.galleryConfig
}))

const mergedThumbnailsConfig = computed(() => ({
  ...defaultThumbnailsConfig,
  ...props.thumbnailsConfig
}))
</script>

<style scoped>
.carousel {
  --vc-nav-background: rgba(255, 255, 255, 0.7);
  --vc-nav-border-radius: 100%;
}
</style>
