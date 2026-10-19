<script setup lang="ts">
const props = withDefaults(
  defineProps<{
    padding?: 'sm' | 'md' | 'lg';
    hover?: boolean;
  }>(),
  { padding: 'md', hover: false },
);

const paddingClass = {
  sm: 'p-4',
  md: 'p-6',
  lg: 'p-8',
};
</script>

<template>
  <div
    class="bg-white/5 backdrop-blur-xl border border-white/10 shadow-2xl rounded-2xl"
    :class="[paddingClass[padding], hover ? 'transition-all duration-300 hover:-translate-y-1 hover:shadow-3xl' : '']"
  >
    <slot />
  </div>
</template>
