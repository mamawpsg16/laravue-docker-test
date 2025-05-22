import { getCurrentInstance } from 'vue';

let cachedGlobals;

export function useGlobalProperties() {
  if (cachedGlobals) return cachedGlobals;

  const internalInstance = getCurrentInstance();
  if (!internalInstance) {
    throw new Error('useGlobalProperties must be used inside setup');
  }

  cachedGlobals = internalInstance.appContext.config.globalProperties;
  return cachedGlobals;
}