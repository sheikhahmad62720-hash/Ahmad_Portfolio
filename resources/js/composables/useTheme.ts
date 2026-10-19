import { useThemeStore } from '@/stores/themeStore';

export function useTheme() {
  const store = useThemeStore();

  function init() {
    store.init();
  }

  return {
    isDark: store.isDark,
    toggle: store.toggle,
    init,
  };
}
