<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';

const progress = ref(0);

function update() {
  const h = document.documentElement;
  const scrollTop = h.scrollTop || document.body.scrollTop;
  const scrollHeight = h.scrollHeight - h.clientHeight;
  progress.value = scrollHeight > 0 ? (scrollTop / scrollHeight) * 100 : 0;
}

onMounted(() => {
  window.addEventListener('scroll', update, { passive: true });
  update();
});

onUnmounted(() => {
  window.removeEventListener('scroll', update);
});
</script>

<template>
  <div class="fixed top-0 left-0 right-0 z-[60] h-0.5 pointer-events-none">
    <div
      class="h-full bg-gradient-to-r from-copper via-copper-light to-electric transition-[width] duration-150 ease-out"
      :style="{ width: progress + '%' }"
    />
  </div>
</template>
