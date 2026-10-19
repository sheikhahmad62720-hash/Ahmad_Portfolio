<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import { useRouter } from 'vue-router';
import { MagnifyingGlassIcon as SearchIcon, XMarkIcon } from '@heroicons/vue/24/outline';

const router = useRouter();
const open = ref(false);

function openPalette() {
  open.value = true;
  query.value = '';
}

defineExpose({ openPalette });
const query = ref('');

const items = [
  { label: 'Home', path: '/' },
  { label: 'About', path: '/about' },
  { label: 'Portfolio', path: '/portfolio' },
  { label: 'Blog', path: '/blog' },
  { label: 'Contact', path: '/contact' },
  { label: 'Admin Dashboard', path: '/admin' },
];

const filtered = computed(() => {
  const q = query.value.toLowerCase().trim();
  if (!q) return items;
  return items.filter((i) => i.label.toLowerCase().includes(q));
});

function toggle() {
  open.value = !open.value;
  if (!open.value) query.value = '';
}

function go(path: string) {
  router.push(path);
  open.value = false;
  query.value = '';
}

function onKeydown(e: KeyboardEvent) {
  if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
    e.preventDefault();
    toggle();
  }
  if (e.key === 'Escape' && open.value) {
    open.value = false;
  }
}

watch(open, (v) => {
  if (typeof document !== 'undefined') document.body.style.overflow = v ? 'hidden' : '';
});

onMounted(() => window.addEventListener('keydown', onKeydown));
onUnmounted(() => window.removeEventListener('keydown', onKeydown));
</script>

<template>
  <Teleport to="body">
    <Transition name="cp">
      <div v-if="open" class="fixed inset-0 z-[80] flex items-start justify-center pt-24 px-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="open = false" />
        <div
          class="relative w-full max-w-lg rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-graphite shadow-2xl overflow-hidden"
        >
          <div class="flex items-center gap-3 px-4 py-3 border-b border-gray-200 dark:border-gray-800">
            <SearchIcon class="h-5 w-5 text-gray-400" />
            <input
              v-model="query"
              type="text"
              placeholder="Search pages…"
              class="flex-1 bg-transparent outline-none text-sm"
              autofocus
            />
            <kbd class="text-xs font-mono text-gray-400 border border-gray-300 dark:border-gray-700 rounded px-1.5 py-0.5">
              ESC
            </kbd>
          </div>
          <ul class="max-h-72 overflow-y-auto py-2">
            <li v-for="item in filtered" :key="item.path">
              <button
                type="button"
                class="w-full text-left px-4 py-2.5 text-sm hover:bg-copper/10 hover:text-copper transition-colors"
                @click="go(item.path)"
              >
                {{ item.label }}
              </button>
            </li>
            <li v-if="filtered.length === 0" class="px-4 py-6 text-center text-sm text-gray-400">
              No results
            </li>
          </ul>
          <div class="px-4 py-2 border-t border-gray-200 dark:border-gray-800 text-xs text-gray-400 font-mono flex items-center gap-3">
            <span>↑↓ navigate</span>
            <span>↵ open</span>
            <span>⌘K toggle</span>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<style scoped>
.cp-enter-active,
.cp-leave-active {
  transition: opacity 0.2s ease;
}
.cp-enter-from,
.cp-leave-to {
  opacity: 0;
}
</style>
