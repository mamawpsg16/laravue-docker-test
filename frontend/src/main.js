import { createApp } from 'vue';
import RootComponent from './RootComponent.vue';  // import the new root component
import router from './router';
import { createPinia } from 'pinia';
import Toast from 'vue-toastification';

import '@/assets/scss/main.scss';
import '@/assets/main.css';

import { ModuleRegistry, AllCommunityModule } from 'ag-grid-community';
ModuleRegistry.registerModules([AllCommunityModule]);

const app = createApp(RootComponent);

app.use(createPinia());
app.use(router);
app.use(Toast);

// Global properties and directives (unchanged)
app.config.globalProperties.$hello = () => 'Hello from global!';
app.provide('message', 'hello');

app.config.errorHandler = (err, instance, info) => {
  console.error('Global Error Handler:', err, instance, info);
};

app.config.warnHandler = (msg, instance, trace) => {
  console.warn('Global Warning Handler:', msg, instance, trace);
};

app.directive('focus', {
  mounted(el) {
    el.focus();
  }
});

app.directive('permission', {
  mounted(el, binding) {
    const userRole = 'admin'; // Normally from store or auth
    const allowedRoles = binding.value;
    if (!allowedRoles.includes(userRole)) {
      el.style.display = 'none';
    }
  }
});

app.directive('log', {
  created(el, binding) { console.log('LOG Created:', binding); },
  mounted(el, binding) { console.log('LOG Mounted:', binding.value); },
  updated(el, binding) { console.log('LOG Updated:', binding.value, binding.oldValue); },
  unmounted(el) { console.log('LOG Directive unmounted'); }
});

app.mount('#app');
