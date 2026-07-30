<script setup lang="ts">
import { computed } from 'vue';

interface Props {
  size?: 'sm' | 'md' | 'lg';
  fullScreen?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  size: 'md',
  fullScreen: false,
});

const sizeClass = computed(() => {
  const sizes = {
    sm: 'w-6 h-6',
    md: 'w-10 h-10',
    lg: 'w-16 h-16',
  };
  return sizes[props.size];
});

const containerClass = computed(() => {
  if (props.fullScreen) return 'min-h-screen';
  if (props.size === 'sm') return '';
  return 'p-8';
});
</script>

<template>
  <div class="flex items-center justify-center" :class="containerClass">
    <div 
      class="animate-spin rounded-full border-4 border-gray-200 dark:border-gray-700 border-t-primary-600"
      :class="sizeClass"
    ></div>
  </div>
</template>
