<script setup lang="ts">
type Variant = 'primary' | 'secondary' | 'ghost' | 'outline' | 'danger';
type Size = 'sm' | 'md' | 'lg';

const props = withDefaults(
  defineProps<{
    variant?: Variant;
    size?: Size;
    disabled?: boolean;
    type?: 'button' | 'submit' | 'reset';
    loading?: boolean;
  }>(),
  {
    variant: 'primary',
    size: 'md',
    disabled: false,
    type: 'button',
    loading: false,
  },
);

const emit = defineEmits<{ (e: 'click', ev: MouseEvent): void }>();

const variantClass: Record<Variant, string> = {
  primary:
    'bg-gradient-to-r from-copper to-copper-light text-white hover:shadow-[0_8px_30px_rgb(217,119,6,0.3)] hover:scale-[1.02] active:scale-[0.98]',
  secondary:
    'bg-electric text-white hover:bg-electric-light hover:shadow-[0_8px_30px_rgb(6,182,212,0.3)] hover:scale-[1.02] active:scale-[0.98]',
  ghost:
    'border-2 border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:border-copper hover:text-copper hover:bg-copper/5',
  outline:
    'border-2 border-copper text-copper hover:bg-copper hover:text-white hover:scale-[1.02] active:scale-[0.98]',
  danger:
    'bg-gradient-to-r from-red-600 to-red-500 text-white hover:shadow-[0_8px_30px_rgb(220,38,38,0.3)] hover:scale-[1.02] active:scale-[0.98]',
};

const sizeClass: Record<Size, string> = {
  sm: 'px-3 py-1.5 text-sm',
  md: 'px-6 py-3 text-base',
  lg: 'px-8 py-4 text-lg',
};
</script>

<template>
  <button
    :type="type"
    :disabled="disabled || loading"
    class="inline-flex items-center justify-center gap-2 rounded-xl font-medium transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-copper focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transform-gpu"
    :class="[variantClass[variant], sizeClass[size]]"
    @click="emit('click', $event)"
  >
    <span v-if="loading" class="inline-block w-4 h-4 border-2 border-white/40 border-t-white rounded-full animate-spin" />
    <slot />
  </button>
</template>
