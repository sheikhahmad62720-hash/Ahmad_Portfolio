<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '@/utils/api';
import LoadingSpinner from '@/components/LoadingSpinner.vue';
import { ArrowLeftIcon, ClockIcon, EyeIcon } from '@heroicons/vue/24/outline';

const route = useRoute();
const router = useRouter();
const loading = ref(true);
const blog = ref<any>(null);

function formatDate(date: string | undefined) {
    if (!date) return '';
    return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
}

async function fetchBlog() {
    loading.value = true;

    try {
        const response = await api.get(`/blogs/${route.params.slug}`);

        if (response.data.success) {
            // API returns { blog: {...}, related: [...] }
            blog.value = response.data.data.blog || response.data.data;
        }
    } catch (error: any) {
        if (error.response?.status === 404) {
            blog.value = null;
        }
        console.error('Error fetching blog:', error);
    } finally {
        loading.value = false;
    }
}

onMounted(() => {
    fetchBlog();
});
</script>

<template>
    <div class="py-12 sm:py-20 px-3 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto px-2 sm:px-0">
            <LoadingSpinner v-if="loading" full-screen />

            <article v-else-if="blog" class="animate-fade-in">
                <!-- Back Button -->
                <button @click="router.back()"
                    class="flex items-center text-sm sm:text-base text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 mb-2 pt-4 sm:pt-6 transition-colors">
                    <ArrowLeftIcon class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2 flex-shrink-0" />
                    Back to Blog
                </button>

                <!-- Header -->
                <header class="mb-8">
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span v-for="cat in blog.categories" :key="cat.id" class="badge-primary">
                            {{ cat.name }}
                        </span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4">{{ blog.title }}</h1>

                    <div class="flex flex-wrap items-center gap-4 sm:gap-6 text-sm sm:text-base text-gray-600 dark:text-gray-400">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center mr-3">
                                <span class="text-primary-600 dark:text-primary-400 font-semibold">
                                    {{ blog.user?.name?.charAt(0) || 'A' }}
                                </span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ blog.user?.name || 'Muhammad Ahmad' }}</p>
                                <p class="text-sm">{{ formatDate(blog.published_at) }}</p>
                            </div>
                        </div>

                        <span class="flex items-center">
                            <ClockIcon class="w-5 h-5 mr-2" />
                            {{ blog.reading_time }} min read
                        </span>

                        <span class="flex items-center">
                            <EyeIcon class="w-5 h-5 mr-2" />
                            {{ blog.views_count }} views
                        </span>
                    </div>
                </header>

                <!-- Featured Image -->
                <div v-if="blog.featured_image" class="aspect-video mb-8 rounded-2xl overflow-hidden shadow-xl">
                    <img :src="blog.featured_image" :alt="blog.title" class="w-full h-full object-cover" />
                </div>

                <!-- Content -->
                <div class="prose dark:prose-invert max-w-none mb-8 overflow-x-auto">
                    <div v-html="blog.content"></div>
                </div>

                <!-- Tags -->
                <div v-if="blog.tags && blog.tags.length > 0" class="mb-8">
                    <h3 class="text-lg font-semibold mb-3">Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        <span v-for="tag in blog.tags" :key="tag.id" class="badge">
                            #{{ tag.name }}
                        </span>
                    </div>
                </div>

                <!-- Share Section -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6 sm:pt-8">
                    <h3 class="text-lg sm:text-xl font-semibold mb-4">Share this article</h3>
                    <div class="flex flex-wrap gap-3 sm:gap-4">
                        <a href="https://x.com" target="_blank" rel="noopener noreferrer" class="btn-outline text-sm sm:text-base">Twitter</a>
                        <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" class="btn-outline text-sm sm:text-base">LinkedIn</a>
                        <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="btn-outline text-sm sm:text-base">Facebook</a>
                    </div>
                </div>
            </article>

            <!-- Not Found -->
            <div v-else class="text-center py-12">
                <div class="text-6xl mb-4">😕</div>
                <h2 class="text-3xl font-bold mb-2">Post Not Found</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-6">The blog post you're looking for doesn't exist.</p>
                <router-link to="/blog" class="btn-primary">
                    Back to Blog
                </router-link>
            </div>
        </div>
    </div>
</template>
