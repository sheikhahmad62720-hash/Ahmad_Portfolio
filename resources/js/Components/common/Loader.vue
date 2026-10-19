<script setup lang="ts">
import { ref, onMounted } from 'vue';

const lines = [
  '$ booting portfolio...',
  '$ loading modules: laravel, vue, inertia',
  '$ mounting components...',
  '$ ready.',
];
const shown = ref<string[]>([]);
const done = ref(false);
const emit = defineEmits<{ (e: 'done'): void }>();

onMounted(() => {
  lines.forEach((line, i) => {
    setTimeout(() => {
      shown.value.push(line);
      if (i === lines.length - 1) {
        setTimeout(() => {
          done.value = true;
          setTimeout(() => emit('done'), 400);
        }, 300);
      }
    }, i * 220);
  });
});
</script>

<template>
  <Transition name="fade">
    <div
      v-if="!done"
      class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0b0f14] font-mono text-sm text-[#e6edf3]"
    >
      <div class="w-full max-w-md px-6">
        <div class="mb-4 text-[#39d353]">ahmad@portfolio:~$</div>
        <div class="space-y-1">
          <div v-for="(l, i) in shown" :key="i" class="text-[#8b949e]">
            <span class="text-[#39d353]">›</span> {{ l }}
          </div>
          <div v-if="shown.length < lines.length" class="text-[#39d353] animate-pulse">█</div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.4s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
