<template>
  <div :class="['w-full mx-auto px-4', maxWidth]">
    <div class="relative">
      <Carousel
        v-bind="mergedGalleryConfig"
        v-model="currentSlide"
        class="rounded-xl overflow-hidden shadow-lg w-full"
      >
        <Slide v-for="(slide, i) in slides" :key="i">
          <div class="carousel__item">
            <div
              class="relative w-full h-[250px] md:h-[350px] lg:h-[400px] rounded-xl overflow-hidden cursor-pointer group"
              @click="handleSlideClick(slide, i)"
            >
              <img 
                :src="slide.image || slide.bg" 
                :alt="slide.alt || `Banner ${i + 1}`"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
              />
              
              <!-- Optional overlay for better text readability -->
              <div 
                v-if="slide.overlay !== false" 
                class="absolute inset-0 bg-black/20 transition-opacity duration-300 group-hover:bg-black/10"
              ></div>
              
              <!-- Optional content overlay -->
              <div 
                v-if="slide.title || slide.subtitle || slide.description" 
                class="absolute inset-0 flex items-center justify-start p-6 ml-10 md:p-8"
              >
                <div class="text-white max-w-md">
                  <h2 
                    v-if="slide.title" 
                    class="text-2xl md:text-4xl font-bold mb-2 drop-shadow-lg"
                  >
                    {{ slide.title }}
                  </h2>
                  <p 
                    v-if="slide.subtitle" 
                    class="text-lg md:text-xl font-semibold mb-2 drop-shadow-lg"
                  >
                    {{ slide.subtitle }}
                  </p>
                  <p 
                    v-if="slide.description" 
                    class="text-sm md:text-base mb-4 drop-shadow-lg opacity-90"
                  >
                    {{ slide.description }}
                  </p>
                  <button 
                    v-if="slide.width_redirect" 
                    @click.stop="handleButtonClick(slide, i)"
                    class="bg-white/90 backdrop-blur text-gray-900 px-6 py-2 rounded-lg font-semibold hover:bg-white transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105"
                  >
                    {{ slide.buttonText }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </Slide>
      </Carousel>
      
      <!-- Navigation Arrows -->
      <button
        v-if="slides.length > 1"
        @click="prevSlide"
        class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/80 backdrop-blur hover:bg-white text-gray-800 w-10 h-10 rounded-full flex items-center justify-center shadow-lg transition-all duration-300 hover:scale-110 z-10"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      
      <button
        v-if="slides.length > 1"
        @click="nextSlide"
        class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/80 backdrop-blur hover:bg-white text-gray-800 w-10 h-10 rounded-full flex items-center justify-center shadow-lg transition-all duration-300 hover:scale-110 z-10"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>
      
      <!-- Dot Navigation -->
      <div 
        v-if="slides.length > 1" 
        class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2"
      >
        <button
          v-for="(slide, index) in slides"
          :key="index"
          @click="currentSlide = index"
          :class="[
            'w-3 h-3 rounded-full transition-all duration-300',
            currentSlide === index 
              ? 'bg-white shadow-lg scale-125' 
              : 'bg-white/50 hover:bg-white/75'
          ]"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { Carousel, Slide } from 'vue3-carousel'
import { ref, computed } from 'vue'
import 'vue3-carousel/dist/carousel.css'

// Props
const props = defineProps({
  slides: {
    type: Array,
    required: true,
    default: () => []
  },
  galleryConfig: {
    type: Object,
    default: () => ({})
  },
  maxWidth: {
    type: String,
    default: 'max-w-7xl'
  }
})

// Emits
const emit = defineEmits(['slide-click', 'button-click'])

const currentSlide = ref(0)

const defaultGalleryConfig = {
  itemsToShow: 1,
  wrapAround: true,
  transition: 600,
  mouseDrag: true,
  touchDrag: true,
  autoplay: 4000,
  pauseAutoplayOnHover: true,
  snapAlign: 'center'
}

const mergedGalleryConfig = computed(() => ({
  ...defaultGalleryConfig,
  ...props.galleryConfig
}))

const maxWidth = computed(() => props.maxWidth)
const slides = computed(() => props.slides)

// Methods
const nextSlide = () => {
  if (currentSlide.value < slides.value.length - 1) {
    currentSlide.value++
  } else {
    currentSlide.value = 0
  }
}

const prevSlide = () => {
  if (currentSlide.value > 0) {
    currentSlide.value--
  } else {
    currentSlide.value = slides.value.length - 1
  }
}

const handleSlideClick = (slide, index) => {
  emit('slide-click', { slide, index })
}

const handleButtonClick = (slide, index) => {
  emit('button-click', { slide, index })
}
</script>

<style scoped>
.carousel__item {
  width: 100%;
  height: 100%;
}

/* Override default carousel styles */
:deep(.carousel__slide) {
  padding: 0;
}

:deep(.carousel__viewport) {
  border-radius: 0.75rem;
}

:deep(.carousel__track) {
  align-items: stretch;
}
</style>