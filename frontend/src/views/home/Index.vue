<template>
  <DefaultLayout>
    <!-- Featured Products Carousel -->
    <!-- <button class="text-sm bg-blue-500 p-2 rounded hover:bg-blue-600 " @click.prevent="requestTrack">Request Track</button> -->
    <section class="mb-12">
      <h2 class="text-3xl font-bold mb-6">Featured Products</h2>
      <!-- <GalleryCarousel :images="productImages" :maxWidth="'max-w-7xl'" /> -->
      <BannerCarousel 
        :slides="slides" 
        @button-click="handleButtonClick"
      />
      

    </section>

    <!-- New Arrivals -->
    <section class="mb-12 max-w-7xl mx-auto px-4">
      <h2 class="text-2xl font-semibold mb-6">New Arrivals</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        <ProductCard v-for="product in newArrivals" :key="product.id" :product="product" />
      </div>
    </section>

    <!-- Best Sellers -->
    <section class="mb-12 max-w-7xl mx-auto px-4">
      <h2 class="text-2xl font-semibold mb-6">Best Sellers</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        <ProductCard v-for="product in bestSellers" :key="product.id" :product="product" />
      </div>
    </section>

    <!-- Promotional Banner -->
    <section class="bg-indigo-600 text-white py-12 rounded-lg max-w-7xl mx-auto px-6 mb-12">
      <div class="text-center max-w-3xl mx-auto">
        <h3 class="text-3xl font-bold mb-2">Summer Sale - Up to 50% Off!</h3>
        <p class="mb-6">Don't miss out on our biggest sale of the season. Shop now and save big on your favorite items.</p>
        <button class="bg-white text-indigo-600 px-6 py-3 rounded-md font-semibold hover:bg-gray-100 transition">
          Shop Now
        </button>
      </div>
    </section>

    <!-- Customer Testimonials -->
    <section class="max-w-7xl mx-auto px-4 mb-12">
      <h2 class="text-2xl font-semibold mb-6 text-center">What Our Customers Say</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <TestimonialCard
          v-for="testimonial in testimonials"
          :key="testimonial.id"
          :testimonial="testimonial"
        />
      </div>
    </section>

    <!-- Newsletter Signup -->
    <section class="bg-gray-100 py-12 rounded-lg max-w-7xl mx-auto px-6 text-center">
      <h3 class="text-2xl font-bold mb-4">Subscribe to Our Newsletter</h3>
      <p class="mb-6 max-w-xl mx-auto">Get the latest updates and offers delivered straight to your inbox.</p>
      <form @submit.prevent="subscribe" class="flex justify-center max-w-md mx-auto space-x-2">
        <input
          v-model="email"
          type="email"
          placeholder="Enter your email"
          required
          class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500"
        />
        <button
          type="submit"
          class="bg-indigo-600 text-white px-6 py-2 rounded-md font-semibold hover:bg-indigo-500 transition"
        >
          Subscribe
        </button>
      </form>
    </section>
  </DefaultLayout>
</template>

<script setup>
import api from '@/services/axios';
import { useRouter } from 'vue-router'
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import GalleryCarousel from '@/components/GalleryCarousel.vue'
import BannerCarousel from '@/components/BannerCarousel.vue'
import ProductCard from './components/ProductCard.vue'
import TestimonialCard from './components/TestimonialCard.vue'
import { ref } from 'vue'


const router = useRouter()

const track = ref(null);
// Sample data
const productImages = Array.from({ length: 6 }, (_, index) => ({
  id: index,
  url: `https://picsum.photos/seed/product-${index}/800/600`
}))

// Create multiple slides for the carousel to work properly
const slides = [
  {
    id: 'summer-collection',
    image: 'https://picsum.photos/seed/banner-1/1200/400',
    alt: 'Summer Sale Banner',
    title: 'Summer Collection',
    subtitle: 'Up to 70% Off',
    buttonText: 'Shop Now',
    width_redirect:true,
  },
  {
    id: 'new-arrivals',
    image: 'https://picsum.photos/seed/banner-2/1200/400',
    alt: 'New Arrivals Banner',
    title: 'New Arrivals',
    subtitle: 'Fresh Styles Just In',
    buttonText: 'Explore',
    width_redirect:true,
  },
  {
    id: 'free-shipping',
    image: 'https://picsum.photos/seed/banner-3/1200/400',
    alt: 'Free Shipping Banner',
    title: 'Free Shipping',
    subtitle: 'On Orders Over $50',
    buttonText: 'Start Shopping',
    width_redirect:false,
  }
]

const handleButtonClick = (slide) => {
  router.push({name:'login'});
  if (slide.id) {
    router.push(`/product/${slide.id}`)
  }
}

const newArrivals = [
  { id: 101, name: 'Eco-friendly Water Bottle', price: 19.99, image: 'https://picsum.photos/seed/waterbottle/300/300' },
  { id: 102, name: 'Wireless Charger', price: 29.99, image: 'https://picsum.photos/seed/charger/300/300' },
  { id: 103, name: 'Bluetooth Speaker', price: 49.99, image: 'https://picsum.photos/seed/speaker/300/300' },
  { id: 104, name: 'Yoga Mat', price: 39.99, image: 'https://picsum.photos/seed/yogamat/300/300' }
]

const bestSellers = [
  { id: 1, name: 'Wireless Headphones', price: 99.99, image: 'https://picsum.photos/seed/headphones/300/300' },
  { id: 2, name: 'Smart Watch', price: 149.99, image: 'https://picsum.photos/seed/watch/300/300' },
  { id: 3, name: 'Sneakers', price: 89.99, image: 'https://picsum.photos/seed/sneakers/300/300' },
  { id: 4, name: 'Coffee Maker', price: 59.99, image: 'https://picsum.photos/seed/coffeemaker/300/300' }
]

const testimonials = [
  { id: 1, name: 'Alice Johnson', text: 'Great products and fast shipping!', avatar: 'https://randomuser.me/api/portraits/women/44.jpg' },
  { id: 2, name: 'Mark Smith', text: 'Excellent customer service and quality.', avatar: 'https://randomuser.me/api/portraits/men/46.jpg' },
  { id: 3, name: 'Sophie Lee', text: 'I love shopping here! Highly recommended.', avatar: 'https://randomuser.me/api/portraits/women/65.jpg' }
]

const email = ref('')

function subscribe() {
  alert(`Subscribed with email: ${email.value}`)
  email.value = ''
}


async function requestTrack(){
  const response = await api.get('/track');

  console.log(response,'response');
}
</script>
