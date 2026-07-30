<script setup lang="ts">
import { ref } from 'vue';
import api from '@/utils/api';
import LoadingSpinner from '@/components/LoadingSpinner.vue';

const loading = ref(false);
const successMessage = ref('');

const form = ref({
    name: '',
    email: '',
    phone: '',
    subject: '',
    message: '',
});

const errors = ref<Record<string, string>>({});

async function submitForm() {
    loading.value = true;
    successMessage.value = '';
    errors.value = {};

    try {
        const response = await api.post('/contact', form.value);

        if (response.data.success) {
            successMessage.value = 'Thank you! Your message has been sent successfully. I\'ll get back to you soon.';

            // Reset form
            form.value = {
                name: '',
                email: '',
                phone: '',
                subject: '',
                message: '',
            };
        }
    } catch (error: any) {
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors;
        }
    } finally {
        loading.value = false;
    }
}
</script>

<template>
    <div class="min-h-screen">
        <!-- Header -->
        <section class="section bg-gradient-to-b from-gray-50 to-white dark:from-graphite dark:to-navy">
            <div class="container-custom text-center animate-fade-in mt-8">
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-copper/10 border border-copper/20 mb-6">
                    <span class="text-sm font-mono text-copper">Get In Touch</span>
                </div>
                <h1 class="heading-lg mb-6">
                    Let's work <span class="text-gradient">together</span>
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                    Have a project in mind? Let's discuss how I can help bring your ideas to life
                </p>
            </div>
        </section>

        <!-- Main Content -->
        <section class="section">
            <div class="container-custom">
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 lg:gap-12">
                    <!-- Contact Form -->
                    <div class="lg:col-span-3">
                        <div class="card-hover p-8">
                            <h2 class="font-display font-semibold text-2xl mb-6">Send Me a Message</h2>

                            <form @submit.prevent="submitForm" class="space-y-6">
                                <!-- Name -->
                                <div>
                                    <label class="block text-sm font-medium font-mono mb-2">Full Name *</label>
                                    <input v-model="form.name" type="text" required class="input"
                                        placeholder="John Doe" />
                                    <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</p>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="block text-sm font-medium font-mono mb-2">Email Address *</label>
                                    <input v-model="form.email" type="email" required class="input"
                                        placeholder="john@example.com" />
                                    <p v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email }}</p>
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label class="block text-sm font-medium font-mono mb-2">Phone Number</label>
                                    <input v-model="form.phone" type="tel" class="input"
                                        placeholder="+92 331 6918426" />
                                </div>

                                <!-- Subject -->
                                <div>
                                    <label class="block text-sm font-medium font-mono mb-2">Subject</label>
                                    <input v-model="form.subject" type="text" class="input"
                                        placeholder="Project Inquiry" />
                                </div>

                                <!-- Message -->
                                <div>
                                    <label class="block text-sm font-medium font-mono mb-2">Message *</label>
                                    <textarea v-model="form.message" required rows="6" class="input"
                                        placeholder="Tell me about your project..."></textarea>
                                    <p v-if="errors.message" class="text-red-500 text-sm mt-1">{{ errors.message }}</p>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" :disabled="loading" class="btn-primary w-full">
                                    <LoadingSpinner v-if="loading" size="sm" />
                                    <span v-else>Send Message</span>
                                </button>

                                <!-- Success Message -->
                                <div v-if="successMessage"
                                    class="p-4 bg-green-50 dark:bg-green-900/20 border-2 border-green-500 rounded-xl">
                                    <p class="text-green-700 dark:text-green-300 font-medium">{{ successMessage }}</p>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Contact Info Card -->
                        <div class="card-hover p-4 sm:p-6">
                            <h3 class="font-display font-semibold text-lg sm:text-xl mb-4 sm:mb-6">Contact Information
                            </h3>

                            <div class="space-y-4 sm:space-y-6">
                                <div class="flex items-start gap-3 sm:gap-4">
                                    <div
                                        class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-copper/10 flex items-center justify-center flex-shrink-0">
                                        <span class="text-lg sm:text-2xl">📧</span>
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="font-semibold font-mono text-xs sm:text-sm text-gray-500 mb-1">Email
                                        </h4>
                                        <a href="mailto:sheikhahmad62720@gmail.com"
                                            class="link text-sm sm:text-lg break-all">
                                            sheikhahmad62720@gmail.com
                                        </a>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3 sm:gap-4">
                                    <div
                                        class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-electric/10 flex items-center justify-center flex-shrink-0">
                                        <span class="text-lg sm:text-2xl">📱</span>
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="font-semibold font-mono text-xs sm:text-sm text-gray-500 mb-1">Phone
                                        </h4>
                                        <a href="tel:+923316918426" class="link text-sm sm:text-lg">
                                            +92 331 6918426
                                        </a>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3 sm:gap-4">
                                    <div
                                        class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-copper/10 flex items-center justify-center flex-shrink-0">
                                        <span class="text-lg sm:text-2xl">📍</span>
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="font-semibold font-mono text-xs sm:text-sm text-gray-500 mb-1">
                                            Location</h4>
                                        <p class="text-sm sm:text-lg">Haroonabad, Pakistan</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Social Links -->
                        <div class="card-hover p-6">
                            <h3 class="font-display font-semibold text-xl mb-6">Follow Me</h3>
                            <div class="grid grid-cols-2 gap-3">
                                <a href="https://github.com/sheikhahmad62720-hash" target="_blank"
                                    class="btn-outline text-center">
                                    GitHub
                                </a>
                                <a href="https://www.linkedin.com/in/muhammad-ahmad-85135a28b?utm_source=share_via&utm_content=profile&utm_medium=member_android"
                                    target="_blank" class="btn-outline text-center">
                                    LinkedIn
                                </a>
                                <a href="https://x.com/ahmaddkhan628" target="_blank" class="btn-outline text-center">
                                    Twitter
                                </a>
                                <a href="https://www.instagram.com/ahmad_sheikh_79?igsh=dnVhM3I2enpxaXNh"
                                    target="_blank" class="btn-outline text-center">
                                    Instagram
                                </a>
                            </div>
                        </div>

                        <!-- Availability -->
                        <div class="glass-card p-6 border-2 border-copper/20">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                <h4 class="font-display font-semibold">Available for Freelance</h4>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                                I'm currently available for freelance projects and consulting opportunities.
                                Let's build something amazing together!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
