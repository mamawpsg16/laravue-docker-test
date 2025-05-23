// main.js
import { createApp } from 'vue';
import App from './App.vue';
import router from './router'; // Import the router we defined in router/index.js
import { createPinia } from 'pinia'; // If you're using Pinia
import 'bootstrap/dist/css/bootstrap.min.css'; // Import Bootstrap CSS
import 'bootstrap'; // Optional: import JS for modals, dropdowns, etc.
import axios from '@/services/axios.js'; // Import axios for HTTP requests
import '@/assets/scss/main.scss'


const app = createApp(App);

// Use Pinia (if you're using it for state management)
app.use(createPinia());

// Use the router
app.use(router);

// Example: Set a global function
app.config.globalProperties.$axios = axios; // Make axios available globally (optional, but convenient)
app.config.globalProperties.$hello = () => 'Hello from global!';

app.provide('message', 'hello')

app.config.errorHandler = (err, instance, info) => {
    console.error('Global Error Handler:');
    console.error('Error:', err);
    console.error('Component:', instance);
    console.error('Info:', info);
};

app.config.warnHandler = (msg, instance, trace) => {
    console.warn('Global Warning Handler:');
    console.warn('Warning:', msg);
    console.warn('Component:', instance);
    console.warn('Trace:', trace);
};

/** DIRECTIVES  */
app.directive('focus', {
    mounted(el) {
        console.log(el,'el');
      el.focus();
    }
});
app.directive('permission', {
    mounted(el, binding) {
        console.log("PERMISSION", binding); // <div>...</div>
        console.log("PERMISSION", binding.value);      // "someValue"
        console.log("PERMISSION", binding.arg);        // "hello"
        console.log("PERMISSION", binding.modifiers);  // { modA: true, modB: true }
        console.log("PERMISSION", binding.name);       // "my-directive"

        const userRole = 'admin'; // Normally this comes from store or auth state

        const allowedRoles = binding.value;

        if (!allowedRoles.includes(userRole)) {
        el.style.display = 'none';
        }
    }
});

app.directive('log', {
    created(el, binding) {
      console.log('LOG Created:', binding);
    },
    mounted(el, binding) {
      console.log('LOG Mounted with value:', binding.value);
    },
    updated(el, binding) {
      console.log('LOG Updated value:', binding.value);
      console.log('LOG Old value:', binding.oldValue);
    },
    unmounted(el) {
      console.log('LOG Directive unmounted');
    }
});
// Mount the app to the DOM
app.mount('#app');
