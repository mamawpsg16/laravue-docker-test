// main.js
import { createApp } from 'vue';
import App from './App.vue';
import router from './router'; // Import the router we defined in router/index.js
import { createPinia } from 'pinia'; // If you're using Pinia
import 'bootstrap/dist/css/bootstrap.min.css'; // Import Bootstrap CSS
import 'bootstrap'; // Optional: import JS for modals, dropdowns, etc.
import axios from 'axios'; // Import axios for HTTP requests

const app = createApp(App);

// Use Pinia (if you're using it for state management)
app.use(createPinia());

// Use the router
app.use(router);

app.config.globalProperties.$axios = axios; // Make axios available globally (optional, but convenient)

// Mount the app to the DOM
app.mount('#app');
