<script setup lang="ts">
import AppHeader from '@/components/layout/AppHeader.vue';
import AppFooter from '@/components/layout/AppFooter.vue';
import ScrollProgress from '@/components/common/ScrollProgress.vue';
</script>

<template>
  <div class="min-h-screen bg-white dark:bg-navy">
    <ScrollProgress />
    <AppHeader />
    <main class="pt-20">
      <router-view />
    </main>
    <AppFooter />
  </div>
</template>
