<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '@/utils/api';
import LoadingSpinner from '@/components/LoadingSpinner.vue';
import type { BlogPost, Category, PaginationMeta } from '@/types';

const router = useRouter();
const loading = ref(true);
const blogs = ref<BlogPost[]>([]);
const categories = ref<Category[]>([]);
const searchQuery = ref('');
const selectedCategory = ref<number | null>(null);

const pagination = ref<PaginationMeta>({
    current_page: 1,
    from: 1,
    to: 1,
    per_page: 9,
    total: 0,
    last_page: 1,
});

const paginationPages = computed(() => {
    const pages: (number | string)[] = [];
    const current = pagination.value.current_page;
    const last = pagination.value.last_page;

    if (last <= 7) {
        for (let i = 1; i <= last; i++) pages.push(i);
    } else {
        if (current <= 3) {
            for (let i = 1; i <= 5; i++) pages.push(i);
            pages.push('...');
            pages.push(last);
        } else if (current >= last - 2) {
            pages.push(1);
            pages.push('...');
            for (let i = last - 4; i <= last; i++) pages.push(i);
        } else {
            pages.push(1);
            pages.push('...');
            for (let i = current - 1; i <= current + 1; i++) pages.push(i);
            pages.push('...');
            pages.push(last);
        }
    }
    return pages;
});

async function fetchCategories() {
    try {
        const response = await api.get('/blogs/categories');
        if (response.data.success) {
            categories.value = response.data.data;
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function fetchBlogs() {
    loading.value = true;
    try {
        const params: any = {
            page: pagination.value.current_page,
            per_page: pagination.value.per_page,
        };
        if (searchQuery.value) params.search = searchQuery.value;
        if (selectedCategory.value) params.category_id = selectedCategory.value;

        const response = await api.get('/blogs', { params });
        if (response.data.success) {
            blogs.value = response.data.data;
            if (response.data.meta) {
                pagination.value = response.data.meta;
            }
        }
    } catch (error) {
        console.error('Error:', error);
    } finally {
        loading.value = false;
    }
}

function filterByCategory(categoryId: number | null) {
    selectedCategory.value = categoryId;
    pagination.value.current_page = 1;
    fetchBlogs();
}

let searchTimeout: ReturnType<typeof setTimeout>;
function handleSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        pagination.value.current_page = 1;
        fetchBlogs();
    }, 300);
}

function changePage(page: number) {
    if (page >= 1 && page <= pagination.value.last_page) {
        pagination.value.current_page = page;
        fetchBlogs();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

function resetFilters() {
    searchQuery.value = '';
    selectedCategory.value = null;
    pagination.value.current_page = 1;
    fetchBlogs();
}

function navigateToBlog(slug: string) {
    router.push({ name: 'blog-detail', params: { slug } });
}

function formatDate(date: string) {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

onMounted(() => {
    fetchCategories();
    fetchBlogs();
});
</script>

<template>
    <div class="min-h-screen">
        <!-- Header -->
        <section class="section bg-gradient-to-b from-gray-50 to-white dark:from-graphite dark:to-navy">
            <div class="container-custom text-center animate-fade-in mt-10">
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-electric/10 border border-electric/20 mb-6">
                    <span class="text-sm font-mono text-electric">Blog</span>
                </div>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-display font-bold mb-6">
                    Latest <span class="text-gradient">Articles</span>
                </h1>
                <p class="text-base sm:text-lg md:text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                    Thoughts, tutorials, and insights on web development and technology
                </p>
            </div>
        </section>

        <!-- Filters -->
        <section class="section pt-8">
            <div class="container-custom">
                <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                    <!-- Category Filters -->
                    <div class="flex flex-wrap gap-1.5 sm:gap-2">
                        <button @click="filterByCategory(null)" :class="[
                            'px-3 sm:px-4 py-1.5 sm:py-2 rounded-xl text-sm sm:text-base font-medium transition-all',
                            !selectedCategory
                                ? 'bg-electric text-white shadow-lg'
                                : 'bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700'
                        ]">
                            All Posts
                        </button>
                        <button v-for="cat in categories" :key="cat.id" @click="filterByCategory(cat.id)" :class="[
                            'px-3 sm:px-4 py-1.5 sm:py-2 rounded-xl text-sm sm:text-base font-medium transition-all',
                            selectedCategory === cat.id
                                ? 'bg-electric text-white shadow-lg'
                                : 'bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700'
                        ]">
                            {{ cat.name }}
                        </button>
                    </div>

                    <!-- Search -->
                    <div class="relative w-full md:w-auto">
                        <input v-model="searchQuery" type="text" placeholder="Search articles..."
                            class="input pl-12 w-full md:w-80" @input="handleSearch" />
                        <svg class="w-5 h-5 absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </section>

        <!-- Loading State -->
        <LoadingSpinner v-if="loading" />

        <!-- Blog Posts -->
        <section v-else-if="blogs.length > 0" class="section pt-8">
            <div class="container-custom">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                    <div v-for="(blog, index) in blogs" :key="blog.id" class="card-hover group cursor-pointer"
                        :style="{ animationDelay: `${index * 100}ms` }" @click="navigateToBlog(blog.slug)">
                        <div
                            class="aspect-video relative overflow-hidden bg-gradient-to-br from-copper/20 to-electric/20 rounded-xl mb-4">
                            <img v-if="blog.featured_image" :src="blog.featured_image" :alt="blog.title"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="badge-electric text-xs">{{ blog.category?.name }}</span>
                                <span class="text-xs font-mono text-gray-500">{{ formatDate(blog.published_at) }}</span>
                            </div>
                            <h3
                                class="font-display font-semibold text-xl mb-2 group-hover:text-electric transition-colors line-clamp-2">
                                {{ blog.title }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">
                                {{ blog.excerpt }}
                            </p>
                            <div
                                class="flex items-center justify-between text-sm text-gray-500 border-t border-gray-200 dark:border-gray-800 pt-4">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    {{ blog.views_count }}
                                </span>
                                <span>{{ blog.reading_time }} min read</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="pagination.last_page > 1"
                    class="flex flex-wrap justify-center items-center gap-1.5 sm:gap-2 mt-8 sm:mt-12">
                    <button @click="changePage(pagination.current_page - 1)" :disabled="pagination.current_page === 1"
                        class="p-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 disabled:opacity-30 disabled:cursor-not-allowed transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <template v-for="page in paginationPages" :key="page">
                        <button v-if="page !== '...'" @click="changePage(page as number)" :class="[
                            'px-4 py-2 rounded-xl font-medium transition-all',
                            page === pagination.current_page
                                ? 'bg-electric text-white shadow-lg scale-110'
                                : 'hover:bg-gray-100 dark:hover:bg-gray-800'
                        ]">
                            {{ page }}
                        </button>
                        <span v-else class="px-2">...</span>
                    </template>

                    <button @click="changePage(pagination.current_page + 1)"
                        :disabled="pagination.current_page === pagination.last_page"
                        class="p-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 disabled:opacity-30 disabled:cursor-not-allowed transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </section>

        <!-- Empty State -->
        <section v-else class="section">
            <div class="container-custom text-center py-20">
                <div class="text-6xl mb-4">📝</div>
                <h2 class="text-3xl font-display font-bold mb-4">No articles yet</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-8">Check back soon for new content!</p>
            </div>
        </section>
    </div>
</template>
