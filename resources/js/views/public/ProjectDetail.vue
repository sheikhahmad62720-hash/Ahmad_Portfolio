<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '@/utils/api';
import LoadingSpinner from '@/components/LoadingSpinner.vue';
import Modal from '@/components/Modal.vue';
import {
    ArrowLeftIcon,
    EyeIcon,
    CalendarIcon,
    GlobeAltIcon,
    CodeBracketIcon,
} from '@heroicons/vue/24/outline';
import type { Project } from '@/types';
import dayjs from 'dayjs';

const route = useRoute();
const router = useRouter();
const loading = ref(true);
const project = ref<Project | null>(null);
const imageModalOpen = ref(false);
const selectedImage = ref('');

function formatDate(date: string) {
    return dayjs(date).format('MMMM D, YYYY');
}

function openImageModal(imageUrl: string) {
    selectedImage.value = imageUrl;
    imageModalOpen.value = true;
}

async function fetchProject() {
    loading.value = true;

    try {
        const response = await api.get(`/portfolio/projects/${route.params.slug}`);

        if (response.data.success) {
            project.value = response.data.data.project;
        }
    } catch (error: any) {
        if (error.response?.status === 404) {
            project.value = null;
        }
        console.error('Error fetching project:', error);
    } finally {
        loading.value = false;
    }
}

onMounted(() => {
    fetchProject();
});
</script>

<template>
    <div class="py-12 sm:py-20 px-3 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto px-2 sm:px-0">
            <LoadingSpinner v-if="loading" full-screen />

            <div v-else-if="project" class="animate-fade-in">
                <!-- Back Button -->
                <button @click="router.back()"
                    class="flex items-center text-sm sm:text-base text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 mb-4 sm:mb-6 transition-colors pt-4">
                    <ArrowLeftIcon class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2 flex-shrink-0" />
                    Back to Portfolio
                </button>

                <!-- Header -->
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <span v-if="project.is_featured" class="badge-primary">⭐ Featured</span>
                        <span :class="[
                            'badge',
                            project.is_published ? 'badge-success' : 'badge-warning'
                        ]">
                            {{ project.is_published ? 'Published' : 'Draft' }}
                        </span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4">{{ project.title }}</h1>
                    <p class="text-base sm:text-lg md:text-xl text-gray-600 dark:text-gray-400 mb-6">{{ project.description }}</p>

                    <!-- Meta Info -->
                    <div class="flex flex-wrap gap-4 sm:gap-6 text-sm text-gray-600 dark:text-gray-400">
                        <span class="flex items-center">
                            <EyeIcon class="w-5 h-5 mr-2" />
                            {{ project.views_count }} views
                        </span>
                        <span class="flex items-center">
                            <CalendarIcon class="w-5 h-5 mr-2" />
                            {{ formatDate(project.created_at) }}
                        </span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-3 sm:gap-4 mt-6">
                        <a v-if="project.demo_url" :href="project.demo_url" target="_blank"
                            class="btn-primary flex items-center">
                            <GlobeAltIcon class="w-5 h-5 mr-2" />
                            Live Demo
                        </a>
                        <a v-if="project.github_url" :href="project.github_url" target="_blank"
                            class="btn-outline flex items-center">
                            <CodeBracketIcon class="w-5 h-5 mr-2" />
                            View Code
                        </a>
                    </div>
                </div>

                <!-- Featured Image -->
                <div v-if="project.thumbnail" class="aspect-video mb-8 rounded-2xl overflow-hidden shadow-xl">
                    <img :src="project.thumbnail" :alt="project.title" class="w-full h-full object-cover" />
                </div>

                <!-- Technologies Used -->
                <div v-if="project.technologies && project.technologies.length > 0" class="mb-8">
                    <h2 class="text-2xl font-bold mb-4">Technologies Used</h2>
                    <div class="flex flex-wrap gap-3">
                        <span v-for="tech in project.technologies" :key="tech.id" class="badge-primary">
                            {{ tech.icon }} {{ tech.name }}
                        </span>
                    </div>
                </div>

                <!-- Content -->
                <div v-if="project.content" class="prose dark:prose-invert max-w-none mb-8 overflow-x-auto">
                    <div v-html="project.content"></div>
                </div>

                <!-- Project Images Gallery -->
                <div v-if="project.images && project.images.length > 0" class="mb-8">
                    <h2 class="text-2xl font-bold mb-4">Project Gallery</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div v-for="image in project.images" :key="image.id"
                            class="rounded-lg overflow-hidden shadow-lg cursor-pointer hover:shadow-2xl transition-shadow"
                            @click="openImageModal(image.image_url)">
                            <img :src="image.image_url" :alt="image.caption || project.title"
                                class="w-full aspect-video object-cover hover:scale-105 transition-transform duration-300" />
                            <p v-if="image.caption"
                                class="p-3 bg-gray-50 dark:bg-gray-800 text-sm text-gray-600 dark:text-gray-400">
                                {{ image.caption }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Video -->
                <div v-if="project.video_url" class="mb-8">
                    <h2 class="text-2xl font-bold mb-4">Project Video</h2>
                    <div class="aspect-video rounded-2xl overflow-hidden shadow-xl">
                        <iframe :src="project.video_url" frameborder="0" allowfullscreen class="w-full h-full"></iframe>
                    </div>
                </div>

                <!-- Share Section -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6 sm:pt-8">
                    <h3 class="text-lg sm:text-xl font-semibold mb-4">Share this project</h3>
                    <div class="flex flex-wrap gap-3 sm:gap-4">
                        <a href="https://x.com" target="_blank" rel="noopener noreferrer"
                            class="btn-outline text-sm sm:text-base px-4 sm:px-6 py-2 sm:py-2.5">Twitter</a>
                        <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer"
                            class="btn-outline text-sm sm:text-base px-4 sm:px-6 py-2 sm:py-2.5">LinkedIn</a>
                        <a href="https://facebook.com" target="_blank" rel="noopener noreferrer"
                            class="btn-outline text-sm sm:text-base px-4 sm:px-6 py-2 sm:py-2.5">Facebook</a>
                    </div>
                </div>
            </div>

            <!-- Not Found -->
            <div v-else class="text-center py-12">
                <div class="text-6xl mb-4">😕</div>
                <h2 class="text-3xl font-bold mb-2">Project Not Found</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-6">The project you're looking for doesn't exist.</p>
                <router-link to="/portfolio" class="btn-primary">
                    Back to Portfolio
                </router-link>
            </div>
        </div>

        <!-- Image Modal -->
        <Modal v-model="imageModalOpen" :close-on-click-outside="true">
            <template #title>Project Image</template>
            <img :src="selectedImage" :alt="project?.title || 'Project image'" class="w-full h-auto rounded-lg" />
        </Modal>
    </div>
</template>
