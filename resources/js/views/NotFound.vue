<script setup lang="ts">
// 404 Not Found page
</script>

<template>
  <div class="min-h-screen flex items-center justify-center px-4 bg-gray-50 dark:bg-gray-900">
    <div class="text-center">
      <h1 class="text-9xl font-bold text-gradient mb-4">404</h1>
      <h2 class="text-3xl font-bold mb-4">Page Not Found</h2>
      <p class="text-xl text-gray-600 dark:text-gray-400 mb-8">
        The page you're looking for doesn't exist or has been moved.
      </p>
      <router-link to="/" class="btn-primary px-8 py-3">
        Go Home
      </router-link>
    </div>
  </div>
</template>
