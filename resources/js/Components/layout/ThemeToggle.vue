<script setup lang="ts">
import { useThemeStore } from '@/stores/themeStore';
import { SunIcon, MoonIcon } from '@heroicons/vue/24/outline';

const themeStore = useThemeStore();
</script>

<template>
  <button
    type="button"
    class="rounded-xl p-2 text-gray-600 transition-colors hover:bg-copper/10 hover:text-copper dark:text-gray-300"
    aria-label="Toggle theme"
    @click="themeStore.toggle()"
  >
    <SunIcon v-if="themeStore.isDark" class="h-5 w-5" />
    <MoonIcon v-else class="h-5 w-5" />
  </button>
</template>
