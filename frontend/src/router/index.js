import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import NProgress from 'nprogress';
import { watch } from 'vue';


// ... your routes array stays the same ...
const routes = [
  {
    path: '/:catchAll(.*)', // 👈 Catch-all route for 404 pages
    name: 'NotFound',
    component: () => import('@/views/error/PageNotFound.vue'),
  },
  {
    path: '/',
    name:'home',
    component: () => import('@/views/home/Index.vue'),
    meta: { requiresAuth: false },
  },
  {
    path: '/upload',
    name: 'upload',
    component: () => import('@/views/Upload.vue'),
    meta: { requiresAuth: true,  icon: 'bi bi-person-fill-lock', },
  },
  {
    path: '/ocr',
    name: 'ocr',
    component: () => import('@/views/OcrReader.vue'),
    meta: { requiresAuth: true,  icon: 'bi bi-person-fill-lock', },
  },
  {
    path: '/document',
    name: 'document',
    component: () => import('@/views/Document.vue'),
    meta: { requiresAuth: true,  icon: 'bi bi-person-fill-lock', },
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
    meta: { requiresAuth: true, icon: 'bi bi-house-fill', showInSidebar: true },
  },
  {
    path: '/appointments',
    name: 'appointments',
    component: () => import('@/views/appointment/Index.vue'),
    meta: { requiresAuth: true, icon: 'bi bi-calendar-check', showInSidebar: true },
  },
  {
    path: '/system-admin',
    name: 'system-admin',
    meta: {
      requiresAuth: true,
      icon: 'bi bi-person-fill-lock',
      showInSidebar: true,
      isParent: true
    },
    children: [
      {
        path: 'users',
        name: 'users',
        component: () => import('@/views/system-admin/user/Index.vue'),
        meta: { requiresAuth: true, icon: 'bi bi-people-fill', showInSidebar: true },
      },
      {
        path: 'departments',
        name: 'departments',
        component: () => import('@/views/system-admin/department/Index.vue'),
        meta: { requiresAuth: true, icon: 'bi bi-building', showInSidebar: true },
      },
      {
        path: 'services',
        name: 'services',
        component: () => import('@/views/system-admin/service/Index.vue'),
        meta: { requiresAuth: true, icon: 'bi bi-gear-fill', showInSidebar: true },
      },
    ]
  },
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
 * 👈 **MUCH SIMPLER** Navigation guard - auth is already initialized in main.js
 */


router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore();

  if (authStore.isLoading) {
    await new Promise(resolve => {
      const stop = watch(() => authStore.isLoading, (loading) => {
        if (!loading) {
          stop();
          resolve();
        }
      });
    });
  }

  const isAuthenticated = authStore.isAuthenticated;

  if ((to.name === 'login' || to.name === 'register') && isAuthenticated) {
    next({ name: 'dashboard' });
  } else if (to.meta.requiresAuth && !isAuthenticated) {
    next({ name: 'login' });
  } else if (to.path === '/' && isAuthenticated) {
    next({ name: 'dashboard' });
  } else {
    next();
  }
});
/**
 * Navigation guard that runs after every route change
 */
router.afterEach(() => {
  // Stop the loading progress bar
  // NProgress.done();
});

/**
 * Utility function to get the previous route
 */
export function getPreviousRoute() {
  return previousRoute;
}

/**
 * Utility function to get main navigation links for sidebar
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
 */
export function getChildNavigationLinks(parentPath) {
  const parentRoute = routes.find(route => route.path === parentPath);
  return parentRoute?.children?.filter(child => 
    child.meta?.requiresAuth && 
    child.meta?.showInSidebar
  ) || [];
}

export default router;