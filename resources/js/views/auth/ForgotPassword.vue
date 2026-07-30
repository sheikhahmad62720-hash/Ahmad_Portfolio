<script setup lang="ts">
import { ref } from 'vue';
import api from '@/utils/api';
import LoadingSpinner from '@/components/LoadingSpinner.vue';

const loading = ref(false);
const errorMessage = ref('');
const successMessage = ref(false);

const form = ref({
  email: '',
});

const errors = ref<Record<string, string>>({});

async function handleSubmit() {
  loading.value = true;
  errorMessage.value = '';
  errors.value = {};

  try {
    const response = await api.post('/auth/forgot-password', form.value);

    if (response.data.success) {
      successMessage.value = true;
    }
  } catch (error: any) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors;
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to send reset link.';
    }
  } finally {
    loading.value = false;
  }
}
</script>

<template>
  <div class="w-full max-w-md">
    <div class="card">
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold mb-2">Forgot Password?</h1>
        <p class="text-gray-600 dark:text-gray-400">
          Enter your email and we'll send you a reset link
        </p>
      </div>

      <form v-if="!successMessage" @submit.prevent="handleSubmit" class="space-y-6">
        <!-- Email -->
        <div>
          <label class="block text-sm font-medium mb-2">Email Address</label>
          <input
            v-model="form.email"
            type="email"
            required
            class="input"
            placeholder="your@email.com"
          />
          <p v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email }}</p>
        </div>

        <!-- Error Message -->
        <div
          v-if="errorMessage"
          class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg"
        >
          <p class="text-red-700 dark:text-red-300 text-sm">{{ errorMessage }}</p>
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          :disabled="loading"
          class="btn-primary w-full"
        >
          <LoadingSpinner v-if="loading" size="sm" />
          <span v-else>Send Reset Link</span>
        </button>
      </form>

      <!-- Success Message -->
      <div
        v-else
        class="p-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-center"
      >
        <div class="text-4xl mb-4">✓</div>
        <h3 class="text-lg font-semibold mb-2 text-green-800 dark:text-green-200">
          Reset Link Sent!
        </h3>
        <p class="text-green-700 dark:text-green-300 text-sm mb-4">
          Check your email for password reset instructions.
        </p>
        <router-link to="/auth/login" class="text-primary-600 hover:underline text-sm">
          Back to Login
        </router-link>
      </div>

      <div class="mt-6 text-center">
        <router-link to="/auth/login" class="text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600">
          ← Back to Login
        </router-link>
      </div>
    </div>
  </div>
</template>
