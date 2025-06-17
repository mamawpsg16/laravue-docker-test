import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { watch } from 'vue';
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

// Configure NProgress loading bar settings
// NProgress.configure({
//   // showSpinner: true,
//   trickle: false,      // no incremental progress
//   easing: 'ease',
//   speed: 500
// })

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
    component: () => import('@/views/dashboard/Index.vue'),
    meta: { 
      requiresAuth: true, 
      icon: 'bi bi-house-fill',
      showInSidebar: true 
    },
  },
  {
    path: '/appointments',
    name: 'appointments',
    component: () => import('@/views/appointment/Index.vue'),
    meta: { 
      requiresAuth: true, 
      icon: 'bi bi-calendar-check',
      showInSidebar: true 
    },
  },
  {
    path: '/system-admin',
    name: 'system-admin',
    meta: { 
      requiresAuth: true,
      icon: 'bi bi-person-fill-lock',
      showInSidebar: true,
      isParent: true // Flag to identify parent routes with children
    },
    children:[
      {
        path: 'users',
        name: 'users',
        component: () => import('@/views/system-admin/user/Index.vue'),
        meta: { 
          requiresAuth: true,
          icon: 'bi bi-people-fill',
          showInSidebar: true 
        },
      },
      {
        path: 'departments',
        name: 'departments',
        component: () => import('@/views/system-admin/department/Index.vue'),
        meta: { 
          requiresAuth: true,
          icon: 'bi bi-building',
          showInSidebar: true 
        },
      },
      {
        path: 'services',
        name: 'services',
        component: () => import('@/views/system-admin/service/Index.vue'),
        meta: { 
          requiresAuth: true,
          icon: 'bi bi-gear-fill',
          showInSidebar: true 
        },
      },
    ]
  },
  // {
  //   path: '/features',
  //   name: 'features',
  //   component: () => import('@/views/Features.vue'),
  //   redirect: { name: 'async' },
  //   meta: { 
  //     requiresAuth: true,
  //     icon: 'bi bi-lightning-fill',
  //     showInSidebar: true,
  //     isParent: true // Flag to identify parent routes with children
  //   },
  //   children:[
  //     {
  //       path: 'table',
  //       name: 'table',
  //       component: () => import('@/views/features/ag-grid/Index.vue'),
  //       meta: { 
  //         requiresAuth: true,
  //         icon: 'bi bi-table',
  //         showInSidebar: true 
  //       },
  //     },
  //     {
  //       path: 'async',
  //       name: 'async',
  //       component: () => import('@/views/features/async/Index.vue'),
  //       meta: { 
  //         requiresAuth: true,
  //         icon: 'bi bi-arrow-clockwise',
  //         showInSidebar: true 
  //       },
  //     },
  //     {
  //       path: 'slots',
  //       name: 'slots',
  //       component: () => import('@/views/features/slots/Index.vue'),
  //       meta: { 
  //         requiresAuth: true,
  //         icon: 'bi bi-puzzle',
  //         showInSidebar: true 
  //       },
  //     },
  //     {
  //       path: 'provide-inject',
  //       name: 'provide-inject',
  //       component: () => import('@/views/features/provide-inject/Index.vue'),
  //       meta: { 
  //         requiresAuth: true,
  //         icon: 'bi bi-arrow-down-up',
  //         showInSidebar: true 
  //       },
  //     },
  //     {
  //       path: 'next-tick',
  //       name: 'next-tick',
  //       component: () => import('@/views/features/next-tick/Index.vue'),
  //       meta: { 
  //         requiresAuth: true,
  //         icon: 'bi bi-skip-forward',
  //         showInSidebar: true 
  //       },
  //     },
  //     {
  //       path: 'global-api',
  //       name: 'global-api',
  //       component: () => import('@/views/features/global-api/Index.vue'),
  //       redirect: { name: 'properties' },
  //       meta: { 
  //         requiresAuth: true,
  //         icon: 'bi bi-globe',
  //         showInSidebar: true,
  //         isParent: true // Nested parent route
  //       },
  //       children:[
  //         {
  //           path: 'properties',
  //           name: 'properties',
  //           component: () => import('@/views/features/global-api/global-properties.vue'),
  //           meta: { 
  //             requiresAuth: true,
  //             icon: 'bi bi-list-ul',
  //             showInSidebar: true 
  //           },
  //         },
  //         {
  //           path: 'directives',
  //           name: 'directives',
  //           component: () => import('@/views/features/global-api/directives.vue'),
  //           meta: { 
  //             requiresAuth: true,
  //             icon: 'bi bi-code-slash',
  //             showInSidebar: true 
  //           },
  //         },
  //       ]
  //     },
  //     {
  //       path: 'composables',
  //       name: 'composables',
  //       component: () => import('@/views/features/composables/Index.vue'),
  //       meta: { 
  //         requiresAuth: true,
  //         icon: 'bi bi-puzzle-fill',
  //         showInSidebar: true 
  //       },
  //     },
  //     {
  //       path: 'teleport',
  //       name: 'teleport',
  //       component: () => import('@/views/features/teleport/Index.vue'),
  //       meta: { 
  //         requiresAuth: true,
  //         icon: 'bi bi-cursor',
  //         showInSidebar: true 
  //       },
  //     },
  //     {
  //       path: 'reactivity',
  //       name: 'reactivity',
  //       component: () => import('@/views/features/reactivity/Index.vue'),
  //       meta: { 
  //         requiresAuth: true,
  //         icon: 'bi bi-arrow-repeat',
  //         showInSidebar: true 
  //       },
  //     },
  //     {
  //       path: 'generic-and-proptyping',
  //       name: 'generic-and-proptyping',
  //       component: () => import('@/views/features/generic-and-proptyping/Index.vue'),
  //       meta: { 
  //         requiresAuth: true,
  //         icon: 'bi bi-type',
  //         showInSidebar: true 
  //       },
  //     },
  //   ]
  // },
];

// Create the router instance with configuration
const router = createRouter({
  linkActiveClass: 'text-primary-emphasis',
  linkExactActiveClass: 'text-primary fw-bold',
  history: createWebHistory(),
  routes,
});

// Variable to store the previous route for navigation history
let previousRoute = null;

/**
 * Navigation guard that runs before every route change
 * Handles authentication checks and redirects
 */
router.beforeEach(async (to, from, next) => {
  // Start the loading progress bar
  // NProgress.start();
  
  // Store the previous route for potential use
  previousRoute = from;
  
  // Get the authentication store instance
  const authStore = useAuthStore();

  // Wait for any ongoing authentication checks to complete
  if (authStore.isLoading) {
    await new Promise(resolve => {
      // Watch the isLoading state until it becomes false
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

  // Get current authentication status
  const isAuthenticated = authStore.isAuthenticated;
  
  // 🛑 Redirect authenticated users away from login/register pages
  if ((to.name === 'login' || to.name === 'register') && isAuthenticated) {
    next({ name: 'dashboard' }); // Redirect them to the dashboard
  }
  // 🛡️ Protect routes that require authentication
  else if (to.meta.requiresAuth && !isAuthenticated) {
    next({ name: 'login' }); // Redirect to login if not authenticated
  } else {
    // ✅ Allow navigation to proceed
    next();
  }
});

/**
 * Navigation guard that runs after every route change
 * Handles cleanup tasks like stopping the progress bar
 */
router.afterEach(() => {
  // Stop the loading progress bar
  // NProgress.done();
});

/**
 * Utility function to get the previous route
 * Useful for "back" functionality or navigation history
 * @returns {Object|null} The previous route object or null if none
 */
export function getPreviousRoute() {
  return previousRoute;
}

/**
 * Utility function to get main navigation links for sidebar
 * Filters routes that should be shown in the main navigation
 * @returns {Array} Array of route objects for main navigation
 */
export function getMainNavigationLinks() {
  return routes.filter(route => 
    route.meta?.requiresAuth && 
    route.meta?.showInSidebar && 
    !route.children
  );
}

/**
 * Utility function to get parent routes with children for sidebar
 * Filters routes that have children and should be shown as expandable menus
 * @returns {Array} Array of parent route objects with children
 */
export function getParentNavigationLinks() {
  return routes.filter(route => 
    route.meta?.requiresAuth && 
    route.meta?.showInSidebar && 
    route.meta?.isParent &&
    route.children
  );
}

/**
 * Utility function to get a specific parent route's children
 * @param {string} parentPath - The path of the parent route
 * @returns {Array} Array of child route objects
 */
export function getChildNavigationLinks(parentPath) {
  const parentRoute = routes.find(route => route.path === parentPath);
  return parentRoute?.children?.filter(child => 
    child.meta?.requiresAuth && 
    child.meta?.showInSidebar
  ) || [];
}

export default router;