<script setup lang="ts">
import { onMounted } from 'vue';
import { useThemeStore } from '@/stores/theme';
import { useAuthStore } from '@/stores/auth';

const themeStore = useThemeStore();
const authStore = useAuthStore();

onMounted(async () => {
  themeStore.init();
  // Restore user session if token exists
  if (authStore.token && !authStore.user) {
    await authStore.fetchUser();
  }
});
</script>

<template>
  <router-view />
</template>
