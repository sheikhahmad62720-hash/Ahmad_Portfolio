<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import api from '@/utils/api';
import LoadingSpinner from '@/components/LoadingSpinner.vue';

const router = useRouter();
const loading = ref(false);
const errorMessage = ref('');

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: false,
});

const errors = ref<Record<string, string>>({});

async function handleRegister() {
  loading.value = true;
  errorMessage.value = '';
  errors.value = {};

  try {
    const response = await api.post('/auth/register', form.value);

    if (response.data.success) {
      router.push('/auth/login');
    }
  } catch (error: any) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors;
    } else {
      errorMessage.value = error.response?.data?.message || 'Registration failed. Please try again.';
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
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Create Account</h1>
        <p class="text-gray-600 dark:text-gray-400">Sign up for a new account</p>
      </div>

      <form @submit.prevent="handleRegister" class="space-y-6">
        <!-- Name -->
        <div>
          <label class="block text-sm font-medium mb-2">Full Name</label>
          <input
            v-model="form.name"
            type="text"
            required
            class="input"
            placeholder="Muhammad Ahmad"
          />
          <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</p>
        </div>

        <!-- Email -->
        <div>
          <label class="block text-sm font-medium mb-2">Email</label>
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
          <label class="block text-sm font-medium mb-2">Password</label>
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

        <!-- Terms -->
        <label class="flex items-start">
          <input
            v-model="form.terms"
            type="checkbox"
            required
            class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 mt-1"
          />
          <span class="ml-2 text-sm">
            I agree to the <a href="#" class="text-primary-600 hover:underline">Terms of Service</a> 
            and <a href="#" class="text-primary-600 hover:underline">Privacy Policy</a>
          </span>
        </label>

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
          class="btn-primary w-full h-12"
        >
          <span v-if="loading" class="flex items-center justify-center h-6">
            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
          </span>
          <span v-else>Create Account</span>
        </button>
      </form>

      <div class="mt-6 text-center">
        <p class="text-sm text-gray-600 dark:text-gray-400">
          Already have an account? 
          <router-link to="/auth/login" class="text-primary-600 hover:underline">
            Sign in
          </router-link>
        </p>
      </div>
    </div>
  </div>
</template>
