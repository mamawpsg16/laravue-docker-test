import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { watch, defineAsyncComponent } from 'vue';
import NProgress from 'nprogress';
// import LoadingSpinner from '@/components/AsyncComponents/LoadingSpinner.vue'
// import ErrorDisplay from '@/components/AsyncComponents/ErrorDisplay.vue'

// function delay(ms) {
//   return new Promise(resolve => setTimeout(resolve, ms))
// }

// const AsyncRegister = defineAsyncComponent({
//   loader: async () => {
//     await delay(3000) // simulate slow load (2s)
//     return import('@/views/authentication/Register.vue')
//   },
//   loadingComponent: LoadingSpinner,
//   errorComponent: ErrorDisplay,
//   delay: 200,
//   timeout: 5000,
// })

NProgress.configure({
  showSpinner: true,
  trickle: false,      // no incremental progress
  easing: 'ease',
  speed: 500
})

const routes = [
  {
    path: '/:catchAll(.*)', // 👈 Catch-all route for 404 pages
    name: 'NotFound',
    component: () => import('@/views/error/PageNotFound.vue'),
  },
  {
    path: '/',
    redirect: '/login', // Redirect root path to login
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/authentication/Login.vue'),
    meta: { requiresAuth: false },
  },
  {
    path: '/register',
    name: 'register',
    component: () => import('@/views/authentication/Register.vue'),
    meta: { requiresAuth: false },
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: () => import('@/views/Dashboard.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/tasks',
    name: 'tasks',
    component: () => import('@/views/tasks/Index.vue'),
    meta: { requiresAuth: true },
  },
  {
    path:'/profile',
    name: 'profile',
    component: () => import('@/views/user/Profile.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/features',
    name: 'features',
    component: () => import('@/views/Features.vue'),
    redirect: { name: 'async' },
    meta: { requiresAuth: true },
    children:[
      {
        path: 'async',
        name: 'async',
        component: () => import('@/views/features/async/Index.vue'),
        meta: { requiresAuth: true },
      },
      {
        path: 'slots',
        name: 'slots',
        component: () => import('@/views/features/slots/Index.vue'),
        meta: { requiresAuth: true },
      },
      {
        path: 'provide-inject',
        name: 'provide-inject',
        component: () => import('@/views/features/provide-inject/Index.vue'),
        meta: { requiresAuth: true },
      },
      {
        path: 'next-tick',
        name: 'next-tick',
        component: () => import('@/views/features/next-tick/Index.vue'),
        meta: { requiresAuth: true },
      },
      {
        path: 'global-api',
        name: 'global-api',
        component: () => import('@/views/features/global-api/Index.vue'),
        redirect: { name: 'properties' },
        meta: { requiresAuth: true },
        children:[
          {
            path: 'properties',
            name: 'properties',
            component: () => import('@/views/features/global-api/global-properties.vue'),
            meta: { requiresAuth: true },
          },
          {
            path: 'directives',
            name: 'directives',
            component: () => import('@/views/features/global-api/directives.vue'),
            meta: { requiresAuth: true },
          },
        ]
      },
      {
        path: 'composables',
        name: 'composables',
        component: () => import('@/views/features/composables/Index.vue'),
        meta: { requiresAuth: true },
      },
      {
        path: 'teleport',
        name: 'teleport',
        component: () => import('@/views/features/teleport/Index.vue'),
        meta: { requiresAuth: true },
      },
      {
        path: 'reactivity',
        name: 'reactivity',
        component: () => import('@/views/features/reactivity/Index.vue'),
        meta: { requiresAuth: true },
      },
      {
        path: 'generic-and-proptyping',
        name: 'generic-and-proptyping',
        component: () => import('@/views/features/generic-and-proptyping/Index.vue'),
        meta: { requiresAuth: true },
      },
    ]
  },
 
];

const router = createRouter({
  linkActiveClass: 'text-primary-emphasis',
  linkExactActiveClass: 'text-primary fw-bold',
  history: createWebHistory(),
  routes,
});

let previousRoute = null;

// This function runs before every route change
router.beforeEach(async (to, from, next) => {
  NProgress.start();
  previousRoute = from;
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

router.afterEach(() => {
  NProgress.done();
});


// Export a function to access previous route
export function getPreviousRoute() {
  return previousRoute;
}


export default router;
