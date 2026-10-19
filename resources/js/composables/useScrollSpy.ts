import { ref, onMounted, onUnmounted } from 'vue';

export function useScrollSpy(sectionIds: string[]) {
  const activeId = ref<string>(sectionIds[0] ?? '');

  function onScroll() {
    let current = sectionIds[0] ?? '';
    for (const id of sectionIds) {
      const el = document.getElementById(id);
      if (el) {
        const rect = el.getBoundingClientRect();
        if (rect.top <= 120) {
          current = id;
        }
      }
    }
    activeId.value = current;
  }

  onMounted(() => {
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  });

  onUnmounted(() => {
    window.removeEventListener('scroll', onScroll);
  });

  return { activeId };
}
