<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '@/utils/api';
import LoadingSpinner from '@/components/LoadingSpinner.vue';

const route = useRoute();
const router = useRouter();
const loading = ref(false);
const errorMessage = ref('');

const form = ref({
  token: '',
  email: '',
  password: '',
  password_confirmation: '',
});

const errors = ref<Record<string, string>>({});

async function handleSubmit() {
  loading.value = true;
  errorMessage.value = '';
  errors.value = {};

  try {
    const response = await api.post('/auth/reset-password', form.value);

    if (response.data.success) {
      router.push('/auth/login');
    }
  } catch (error: any) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors;
    } else {
      errorMessage.value = error.response?.data?.message || 'Failed to reset password.';
    }
  } finally {
    loading.value = false;
  }
}

onMounted(() => {
  form.value.token = route.params.token as string;
});
</script>

<template>
  <div class="w-full max-w-md">
    <div class="card">
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold mb-2">Reset Password</h1>
        <p class="text-gray-600 dark:text-gray-400">Enter your new password</p>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-6">
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

        <!-- Password -->
        <div>
          <label class="block text-sm font-medium mb-2">New Password</label>
          <input
            v-model="form.password"
            type="password"
            required
            class="input"
            placeholder="••••••••"
          />
          <p v-if="errors.password" class="text-red-500 text-sm mt-1">{{ errors.password }}</p>
        </div>

        <!-- Confirm Password -->
        <div>
          <label class="block text-sm font-medium mb-2">Confirm Password</label>
          <input
            v-model="form.password_confirmation"
            type="password"
            required
            class="input"
            placeholder="••••••••"
          />
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
          <span v-else>Reset Password</span>
        </button>
      </form>

      <div class="mt-6 text-center">
        <router-link to="/auth/login" class="text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600">
          ← Back to Login
        </router-link>
      </div>
    </div>
  </div>
</template>
