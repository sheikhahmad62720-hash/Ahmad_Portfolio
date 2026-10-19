<script setup lang="ts">
const props = withDefaults(
  defineProps<{
    eyebrow?: string;
    title: string;
    highlight?: string;
    subtitle?: string;
    align?: 'left' | 'center';
  }>(),
  { align: 'center' },
);
</script>

<template>
  <div :class="align === 'center' ? 'text-center' : 'text-left'">
    <div
      v-if="eyebrow"
      class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-copper/10 border border-copper/20 mb-6"
    >
      <span class="text-sm font-mono text-copper">{{ eyebrow }}</span>
    </div>
    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-bold mb-4">
      {{ title }}
      <span v-if="highlight" class="text-gradient"> {{ highlight }}</span>
    </h2>
    <p v-if="subtitle" class="text-base sm:text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto leading-relaxed">
      {{ subtitle }}
    </p>
  </div>
</template>
