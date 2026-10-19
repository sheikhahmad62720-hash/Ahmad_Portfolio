<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useThemeStore } from '@/stores/theme';
import { useAuthStore } from '@/stores/auth';
import Loader from '@/components/common/Loader.vue';

const themeStore = useThemeStore();
const authStore = useAuthStore();
const loading = ref(true);

onMounted(async () => {
  themeStore.init();
  if (authStore.token && !authStore.user) {
    await authStore.fetchUser();
  }
});
</script>

<template>
  <Loader v-if="loading" @done="loading = false" />
  <Transition name="fade" mode="out-in">
    <router-view v-if="!loading" />
  </Transition>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
