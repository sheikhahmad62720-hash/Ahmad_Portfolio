<script setup lang="ts">
type Variant = 'copper' | 'electric' | 'success' | 'warning' | 'neutral';

const props = withDefaults(
  defineProps<{
    variant?: Variant;
  }>(),
  { variant: 'copper' },
);

const variantClass: Record<Variant, string> = {
  copper: 'bg-copper/10 text-copper border border-copper/20',
  electric: 'bg-electric/10 text-electric border border-electric/20',
  success: 'bg-green-500/10 text-green-600 dark:text-green-400 border border-green-500/20',
  warning: 'bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 border border-yellow-500/20',
  neutral: 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700',
};
</script>

<template>
  <span
    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-mono font-medium"
    :class="variantClass[variant]"
  >
    <slot />
  </span>
</template>
