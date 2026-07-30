<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showPassword = ref(false);

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />

    <div class="min-h-screen bg-navy flex items-center justify-center px-4 sm:px-6">
        <div class="w-full max-w-md py-20">
            <div class="glass-card p-8 border-2 border-gray-800/50">
                <div class="text-center mb-8">
                    <Link href="/" class="inline-flex items-center gap-2 mb-6">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-copper to-copper-light flex items-center justify-center text-white font-display font-bold text-lg shadow-lg mx-auto">
                            MA
                        </div>
                    </Link>
                    <h1 class="text-3xl font-display font-bold mb-2 text-white">
                        Welcome <span class="text-gradient">Back</span>
                    </h1>
                    <p class="text-gray-400">Sign in to your account</p>
                </div>

                <div v-if="status" class="mb-4 text-sm font-medium text-green-400 bg-green-500/10 border border-green-500/30 rounded-xl p-3">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-300">Email</label>
                        <input
                            id="email"
                            type="email"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                            class="input bg-gray-800/50 border-gray-700 text-white placeholder-gray-500 focus:border-copper w-full"
                            placeholder="admin@portfolio.com"
                        />
                        <p v-if="form.errors.email" class="text-red-400 text-sm mt-1">{{ form.errors.email }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-300">Password</label>
                        <div class="relative">
                            <input
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
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
                        <p v-if="form.errors.password" class="text-red-400 text-sm mt-1">{{ form.errors.password }}</p>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center cursor-pointer group">
                            <input
                                v-model="form.remember"
                                type="checkbox"
                                class="w-4 h-4 rounded border-gray-700 bg-gray-800 text-copper focus:ring-copper focus:ring-offset-0"
                            />
                            <span class="ml-2 text-sm text-gray-300 group-hover:text-white transition-colors">Remember me</span>
                        </label>
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-sm text-copper hover:text-copper-light transition-colors"
                        >
                            Forgot password?
                        </Link>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="btn-primary w-full h-12 flex items-center justify-center"
                    >
                        <span v-if="form.processing" class="flex items-center justify-center">
                            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                        </span>
                        <span v-else>Sign In</span>
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <Link href="/" class="text-sm text-gray-400 hover:text-copper transition-colors inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Home
                    </Link>
                </div>
            </div>

            <div class="mt-6 text-center">
                <p class="text-xs text-gray-500">Protected by advanced security measures</p>
            </div>
        </div>
    </div>
</template>
