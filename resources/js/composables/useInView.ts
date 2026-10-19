import { ref, onMounted, onUnmounted } from 'vue';

export function useInView(threshold = 0.15) {
  const target = ref<HTMLElement | null>(null);
  const inView = ref(false);

  let observer: IntersectionObserver | null = null;

  onMounted(() => {
    if (!target.value) return;
    observer = new IntersectionObserver(
      (entries) => {
        for (const entry of entries) {
          if (entry.isIntersecting) {
            inView.value = true;
            observer?.unobserve(entry.target);
          }
        }
      },
      { threshold },
    );
    observer.observe(target.value);
  });

  onUnmounted(() => {
    observer?.disconnect();
  });

  return { target, inView };
}
