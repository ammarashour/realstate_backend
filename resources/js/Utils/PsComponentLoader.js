import { defineAsyncComponent } from 'vue';

export function loadComponent(path) {
//   return defineAsyncComponent(() => import('../Components/'+ path +'.vue'));
  return defineAsyncComponent(() => path);
}
