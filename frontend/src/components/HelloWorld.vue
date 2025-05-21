<script setup>
import { ref, onMounted } from 'vue';
import axios from '@/axios.js';

defineProps({
  msg: {
    type: String,
    required: true,
  },
});

// Create a reactive variable to hold API data
const apiData = ref(null);
const errorMessage = ref(null);

// Fetch the CSRF cookie from Sanctum
const getCsrfToken = async () => {
  try {
    await axios.get('http://localhost:8002/sanctum/csrf-cookie', { withCredentials: true });
    console.log('CSRF cookie set');
  } catch (error) {
    console.error('Failed to get CSRF token:', error);
    errorMessage.value = 'Failed to get CSRF token';
  }
};

// Create a function to fetch data from your API
const fetchData = async () => {
  try {
    await getCsrfToken(); // Must call this first
    const response = await axios.get('/users');
    console.log('Fetched data:', response.data);
    apiData.value = response.data; // Set the API data
  } catch (error) {
    console.error('Error fetching data:', error);
    errorMessage.value = `Error: ${error.response?.status} ${error.response?.statusText}`;
  }
};

// Call the fetchData function when the component is mounted
onMounted(() => {
  fetchData();
});
</script>

<template>
  <div class="greetings">
    <h1 class="green">{{ msg }}</h1>
    
    <!-- Display API data if available -->
    <div v-if="apiData">
      <pre>{{ apiData }}</pre> <!-- Format the API data nicely -->
    </div>
    <div v-else-if="errorMessage">
      <p class="error">{{ errorMessage }}</p> <!-- Show error message -->
    </div>
    <div v-else>
      <p>Loading...</p> <!-- Loading state while data is being fetched -->
    </div>
    
    <h3>
      You've successfully created a project with
      <a href="https://vite.dev/" target="_blank" rel="noopener">Vite V3</a> +
      <a href="https://vuejs.org/" target="_blank" rel="noopener">Vue 3</a>.
    </h3>
  </div>
</template>

<style scoped>
h1 {
  font-weight: 500;
  font-size: 2.6rem;
  position: relative;
  top: -10px;
}

h3 {
  font-size: 1.2rem;
}

.error {
  color: red;
  font-weight: bold;
}

.greetings h1,
.greetings h3 {
  text-align: center;
}

@media (min-width: 1024px) {
  .greetings h1,
  .greetings h3 {
    text-align: left;
  }
}
</style>