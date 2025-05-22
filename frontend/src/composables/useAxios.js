// composables/useAxios.js
import { getCurrentInstance } from 'vue';

export function useAxios() {
  const internalInstance = getCurrentInstance();
  if (!internalInstance) {
    throw new Error('useAxios must be used inside setup');
  }

  return internalInstance.appContext.config.globalProperties.$axios;
}
