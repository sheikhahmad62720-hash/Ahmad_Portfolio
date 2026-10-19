import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useThemeStore = defineStore('theme', () => {
  const isDark = ref(false);

  function init() {
    if (typeof window === 'undefined') return;
    const saved = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    isDark.value = saved === 'dark' || (!saved && prefersDark);
    apply();
  }

  function toggle() {
    isDark.value = !isDark.value;
    apply();
    localStorage.setItem('theme', isDark.value ? 'dark' : 'light');
  }

  function apply() {
    if (typeof document === 'undefined') return;
    document.documentElement.classList.toggle('dark', isDark.value);
  }

  return { isDark, init, toggle, apply };
});
