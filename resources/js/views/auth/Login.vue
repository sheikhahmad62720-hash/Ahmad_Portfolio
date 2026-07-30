<script setup lang="ts">
import { ref, computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import LoadingSpinner from '@/components/LoadingSpinner.vue';

const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();

const form = ref({
  email: '',
  password: '',
  remember: false,
});

const errors = ref<Record<string, string>>({});
const showPassword = ref(false);

async function handleLogin() {
  errors.value = {};

  const success = await authStore.login(form.value);

  if (success) {
    const redirect = route.query.redirect as string || '/admin';
    router.push(redirect);
  }
}
</script>

<template>
  <div class="w-full max-w-md">

    <div class="glass-card p-8 border-2 border-gray-800/50 mt-20">
      <div class="text-center mb-8">
        <h1 class="text-3xl sm:text-4xl font-display font-bold mb-2">
          Welcome <span class="text-gradient">Back</span>
        </h1>
        <p class="text-gray-400">Sign in to your account</p>
      </div>

      <form @submit.prevent="handleLogin" class="space-y-6">
        <!-- Email -->
        <div>
          <label class="block text-sm font-medium mb-2 text-gray-300">Email</label>
          <input
            v-model="form.email"
            type="email"
            required
            class="input bg-gray-800/50 border-gray-700 text-white placeholder-gray-500 focus:border-copper"
            placeholder="admin@portfolio.com"
          />
          <p v-if="errors.email" class="text-red-400 text-sm mt-1">{{ errors.email }}</p>
        </div>

        <!-- Password -->
        <div>
          <label class="block text-sm font-medium mb-2 text-gray-300">Password</label>
          <div class="relative">
            <input
              v-model="form.password"
              :type="showPassword ? 'text' : 'password'"
              required
              class="input bg-gray-800/50 border-gray-700 text-white placeholder-gray-500 focus:border-copper w-full pr-12"
              placeholder="••••••••"
            />
            <button type="button" @click="showPassword = !showPassword"
              class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition-colors">
              <svg v-if="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
              </svg>
            </button>
          </div>
          <p v-if="errors.password" class="text-red-400 text-sm mt-1">{{ errors.password }}</p>
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
          <label class="flex items-center cursor-pointer group">
            <input
              v-model="form.remember"
              type="checkbox"
              class="w-4 h-4 rounded border-gray-700 bg-gray-800 text-copper focus:ring-copper focus:ring-offset-0"
            />
            <span class="ml-2 text-sm text-gray-300 group-hover:text-white transition-colors">Remember me</span>
          </label>
          <router-link to="/auth/forgot-password" class="text-sm text-copper hover:text-copper-light transition-colors">
            Forgot password?
          </router-link>
        </div>

        <!-- Error Message -->
        <div
          v-if="authStore.error"
          class="p-4 bg-red-500/10 border border-red-500/30 rounded-xl"
        >
          <p class="text-red-400 text-sm">{{ authStore.error }}</p>
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          :disabled="authStore.loading"
          class="btn-primary w-full h-12"
        >
          <span v-if="authStore.loading" class="flex items-center justify-center h-6">
            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
          </span>
          <span v-else>Sign In</span>
        </button>
      </form>

      <div class="mt-8 text-center">
        <router-link to="/" class="text-sm text-gray-400 hover:text-copper transition-colors inline-flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
          </svg>
          Back to Home
        </router-link>
      </div>
    </div>

    <!-- Footer Note -->
    <div class="mt-6 text-center">
      <p class="text-xs text-gray-500">
        Protected by advanced security measures
      </p>
    </div>
  </div>
</template>
