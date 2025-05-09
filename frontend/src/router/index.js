import { createRouter, createWebHistory } from 'vue-router';
import Login from '@/views/authentication/Login.vue';
import Register from '@/views/authentication/Register.vue';
import Dashboard from '@/views/Dashboard.vue';
import PageNotFound from '@/views/error/PageNotFound.vue';
import { useAuthStore } from '@/stores/auth';
import { watch } from 'vue';

const routes = [
  {
    path: '/:catchAll(.*)', // 👈 Catch-all route for 404 pages
    name: 'NotFound',
    component: PageNotFound,
  },
  {
    path: '/',
    redirect: '/login', // Redirect root path to login
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: { requiresAuth: false },
  },
  {
    path: '/register',
    name: 'register',
    component: Register,
    meta: { requiresAuth: false },
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: Dashboard,
    meta: { requiresAuth: true },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// This function runs before every route change
router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore(); // Access the Pinia auth store

  // Wait for fetchUser() to finish if it's still loading
  if (authStore.isLoading) {
    await new Promise(resolve => {
      // Watch the isLoading state
      const unwatch = watch(
        () => authStore.isLoading,
        (val) => {
          if (!val) {
            // Once loading is false, stop watching and resolve the promise
            unwatch(); // 👈 Stop watching when loading is finished
            resolve(); // Let the route guard proceed
          }
        }
      );
    });
  }

  const isAuthenticated = authStore.isAuthenticated; // Get current auth status

  // 🛑 If the user is already logged in and trying to visit login or register
  if ((to.name === 'login' || to.name === 'register') && isAuthenticated) {
    next({ name: 'dashboard' }); // Redirect them to the dashboard
  }
  // 🛡️ If the route requires auth and the user is NOT authenticated
  else if (to.meta.requiresAuth && !isAuthenticated) {
    next({ name: 'login' }); // Redirect them to login
  } else {
    // ✅ Otherwise, allow navigation to proceed
    next();
  }
});


export default router;
