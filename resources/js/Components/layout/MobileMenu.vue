<script setup lang="ts">
import { watch } from 'vue';
import { XMarkIcon } from '@heroicons/vue/24/outline';
import { useThemeStore } from '@/stores/themeStore';

const props = defineProps<{ open: boolean }>();
const emit = defineEmits<{ (e: 'close'): void }>();

const themeStore = useThemeStore();

const items = [
  { name: 'Home', path: '/', num: '01' },
  { name: 'About', path: '/about', num: '02' },
  { name: 'Portfolio', path: '/portfolio', num: '03' },
  { name: 'Blog', path: '/blog', num: '04' },
  { name: 'Contact', path: '/contact', num: '05' },
];

watch(
  () => props.open,
  (v) => {
    if (typeof document !== 'undefined') {
      document.body.style.overflow = v ? 'hidden' : '';
    }
  },
);

function onKeydown(e: KeyboardEvent) {
  if (e.key === 'Escape') emit('close');
}
</script>

<template>
  <Teleport to="body">
    <Transition name="menu">
      <div
        v-if="open"
        class="fixed inset-0 z-[70] flex flex-col bg-navy/95 backdrop-blur-2xl"
        @keydown="onKeydown"
        tabindex="-1"
      >
        <div class="flex items-center justify-between px-6 py-6">
          <span class="font-display text-xl font-bold text-white">Muhammad Ahmad</span>
          <button
            type="button"
            class="rounded-xl p-2 text-gray-300 hover:text-white transition-colors"
            aria-label="Close menu"
            @click="emit('close')"
          >
            <XMarkIcon class="h-6 w-6" />
          </button>
        </div>
        <nav class="flex flex-1 flex-col justify-center gap-2 px-8">
          <router-link
            v-for="item in items"
            :key="item.path"
            :to="item.path"
            class="group flex items-baseline gap-4 py-3 text-4xl font-display font-bold text-gray-400 transition-colors hover:text-white"
            @click="emit('close')"
          >
            <span class="font-mono text-sm text-copper/70">{{ item.num }}</span>
            <span class="relative">
              {{ item.name }}
              <span
                class="absolute -bottom-1 left-0 h-0.5 w-0 bg-copper transition-all duration-300 group-hover:w-full"
              />
            </span>
          </router-link>
        </nav>
        <div class="px-8 pb-8 flex items-center justify-between">
          <button
            type="button"
            class="rounded-xl p-2 text-gray-300 hover:text-white transition-colors"
            aria-label="Toggle theme"
            @click="themeStore.toggle()"
          >
            <span class="text-sm font-mono">Toggle theme</span>
          </button>
          <a href="mailto:sheikhahmad62720@gmail.com" class="text-sm text-gray-400 hover:text-copper">
            sheikhahmad62720@gmail.com
          </a>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<style scoped>
.menu-enter-active,
.menu-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}
.menu-enter-from,
.menu-leave-to {
  opacity: 0;
  transform: translateY(-12px);
}
</style>
