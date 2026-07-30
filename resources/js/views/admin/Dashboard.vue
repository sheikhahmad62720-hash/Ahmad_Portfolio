<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import api from '@/utils/api';
import LoadingSpinner from '@/components/LoadingSpinner.vue';
import type { Project, Message } from '@/types';

const router = useRouter();
const authStore = useAuthStore();
const loading = ref(true);

const currentDate = computed(() => {
  return new Date().toLocaleDateString('en-US', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
});

const stats = ref({
  projects: 0,
  projects_this_month: 0,
  blogs: 0,
  blogs_this_month: 0,
  messages: 0,
  unread_messages: 0,
  total_views: 0,
  views_this_month: 0,
});

const recentMessages = ref<Message[]>([]);
const popularProjects = ref<Project[]>([]);
const recentBlogs = ref<any[]>([]);

function formatNumber(num: number) {
  return new Intl.NumberFormat().format(num);
}

function formatDate(date: string) {
  const d = new Date(date);
  return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}

async function fetchStats() {
  try {
    const response = await api.get('/admin/dashboard');
    if (response.data.success) {
      const data = response.data.data;
      const s = data.stats;
      stats.value = {
        projects: s.projects?.total || 0,
        projects_this_month: s.projects?.this_month || 0,
        blogs: s.blogs?.total || 0,
        blogs_this_month: s.blogs?.this_month || 0,
        messages: s.messages?.total || 0,
        unread_messages: s.messages?.unread || 0,
        total_views: s.page_views?.total || 0,
        views_this_month: s.page_views?.today || 0,
      };
      // Recent data from same API call
      recentMessages.value = data.recent_messages?.slice(0, 5) || [];
      popularProjects.value = data.recent_projects?.slice(0, 5) || [];
      recentBlogs.value = data.recent_blogs?.slice(0, 5) || [];
    }
  } catch (error) {
    console.error('Error fetching stats:', error);
  }
}

async function fetchRecentMessages() {
  // Already loaded in fetchStats
}

async function fetchPopularProjects() {
  // Already loaded in fetchStats
}

onMounted(async () => {
  loading.value = true;
  await fetchStats();
  loading.value = false;
});
</script>

<template>
  <div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
      <div>
        <h1 class="text-4xl font-display font-bold text-gradient mb-2">Dashboard</h1>
        <p class="text-gray-600 dark:text-gray-400">Welcome back, <span class="font-semibold text-copper">{{ authStore.user?.name }}</span>!</p>
      </div>
      <div class="mt-4 md:mt-0">
        <div class="text-sm text-gray-500">{{ currentDate }}</div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="card-hover p-6 bg-gradient-to-br from-copper/10 to-copper/5 border-copper/20">
        <div class="flex items-center justify-between mb-4">
          <div class="w-12 h-12 rounded-xl bg-copper/20 flex items-center justify-center text-2xl">
            📁
          </div>
          <div class="px-3 py-1 rounded-full bg-green-500/10 text-green-600 text-xs font-semibold">
            +{{ stats.projects_this_month }}
          </div>
        </div>
        <h3 class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">Total Projects</h3>
        <p class="text-3xl font-bold text-copper">{{ stats.projects }}</p>
        <p class="text-xs text-gray-500 mt-2">{{ stats.projects_this_month }} added this month</p>
      </div>

      <div class="card-hover p-6 bg-gradient-to-br from-electric/10 to-electric/5 border-electric/20">
        <div class="flex items-center justify-between mb-4">
          <div class="w-12 h-12 rounded-xl bg-electric/20 flex items-center justify-center text-2xl">
            📝
          </div>
          <div class="px-3 py-1 rounded-full bg-green-500/10 text-green-600 text-xs font-semibold">
            +{{ stats.blogs_this_month }}
          </div>
        </div>
        <h3 class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">Blog Posts</h3>
        <p class="text-3xl font-bold text-electric">{{ stats.blogs }}</p>
        <p class="text-xs text-gray-500 mt-2">{{ stats.blogs_this_month }} published this month</p>
      </div>

      <div class="card-hover p-6 bg-gradient-to-br from-purple-500/10 to-purple-500/5 border-purple-500/20">
        <div class="flex items-center justify-between mb-4">
          <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center text-2xl">
            💬
          </div>
          <div class="px-3 py-1 rounded-full bg-yellow-500/10 text-yellow-600 text-xs font-semibold">
            {{ stats.unread_messages }} new
          </div>
        </div>
        <h3 class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">Messages</h3>
        <p class="text-3xl font-bold text-purple-600">{{ stats.messages }}</p>
        <p class="text-xs text-gray-500 mt-2">{{ stats.unread_messages }} unread messages</p>
      </div>

      <div class="card-hover p-6 bg-gradient-to-br from-green-500/10 to-green-500/5 border-green-500/20">
        <div class="flex items-center justify-between mb-4">
          <div class="w-12 h-12 rounded-xl bg-green-500/20 flex items-center justify-center text-2xl">
            👁️
          </div>
          <div class="px-3 py-1 rounded-full bg-green-500/10 text-green-600 text-xs font-semibold">
            +{{ formatNumber(stats.views_this_month) }}
          </div>
        </div>
        <h3 class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">Total Views</h3>
        <p class="text-3xl font-bold text-green-600">{{ formatNumber(stats.total_views) }}</p>
        <p class="text-xs text-gray-500 mt-2">{{ formatNumber(stats.views_this_month) }} views this month</p>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
        <router-link to="/admin/projects/create" class="card-hover p-3 sm:p-4 text-center group">
        <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-copper/10 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
          ➕
        </div>
        <h4 class="font-semibold text-sm">New Project</h4>
      </router-link>

      <router-link to="/admin/blogs/create" class="card-hover p-3 sm:p-4 text-center group">
        <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-electric/10 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
          ✍️
        </div>
        <h4 class="font-semibold text-sm">Write Post</h4>
      </router-link>

      <router-link to="/admin/messages" class="card-hover p-3 sm:p-4 text-center group">
        <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-purple-500/10 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
          📧
        </div>
        <h4 class="font-semibold text-sm">Messages</h4>
      </router-link>

      <router-link to="/admin/settings" class="card-hover p-3 sm:p-4 text-center group">
        <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-gray-500/10 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
          ⚙️
        </div>
        <h4 class="font-semibold text-sm">Settings</h4>
      </router-link>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Recent Messages -->
      <div class="card-hover">
        <div class="p-6 border-b border-gray-200 dark:border-gray-800">
          <div class="flex items-center justify-between">
            <h3 class="text-xl font-display font-bold">Recent Messages</h3>
            <router-link to="/admin/messages" class="text-copper hover:text-copper-light text-sm font-medium">
              View All →
            </router-link>
          </div>
        </div>

        <div v-if="loading" class="p-8">
          <LoadingSpinner />
        </div>

        <div v-else-if="recentMessages.length > 0" class="divide-y divide-gray-200 dark:divide-gray-800">
          <div
            v-for="message in recentMessages"
            :key="message.id"
            class="p-6 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors cursor-pointer"
            @click="router.push({ name: 'admin-messages' })"
          >
            <div class="flex items-start justify-between mb-2">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-copper to-electric flex items-center justify-center text-white font-bold text-sm">
                  {{ message.name.charAt(0) }}
                </div>
                <div>
                  <h4 class="font-semibold">{{ message.name }}</h4>
                  <p class="text-xs text-gray-500">{{ message.email }}</p>
                </div>
              </div>
              <span :class="[
                'px-2 py-1 rounded-full text-xs font-medium',
                message.status === 'unread' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700'
              ]">
                {{ message.status }}
              </span>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-2">
              {{ message.message }}
            </p>
            <p class="text-xs text-gray-500">{{ formatDate(message.created_at) }}</p>
          </div>
        </div>

        <div v-else class="p-12 text-center text-gray-500">
          <div class="text-5xl mb-4">📭</div>
          <p>No messages yet</p>
        </div>
      </div>

      <!-- Popular Projects -->
      <div class="card-hover">
        <div class="p-6 border-b border-gray-200 dark:border-gray-800">
          <div class="flex items-center justify-between">
            <h3 class="text-xl font-display font-bold">Popular Projects</h3>
            <router-link to="/admin/projects" class="text-copper hover:text-copper-light text-sm font-medium">
              View All →
            </router-link>
          </div>
        </div>

        <div v-if="loading" class="p-8">
          <LoadingSpinner />
        </div>

        <div v-else-if="popularProjects.length > 0" class="divide-y divide-gray-200 dark:divide-gray-800">
          <div
            v-for="project in popularProjects"
            :key="project.id"
            class="p-6 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors"
          >
            <div class="flex items-center justify-between">
              <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                  <h4 class="font-semibold">{{ project.title }}</h4>
                  <span v-if="project.is_featured" class="px-2 py-0.5 rounded bg-copper/10 text-copper text-xs font-medium">
                    ⭐ Featured
                  </span>
                </div>
                <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                  <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    {{ project.views_count || 0 }}
                  </span>
                  <span>{{ formatDate(project.created_at) }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div v-else class="p-12 text-center text-gray-500">
          <div class="text-5xl mb-4">📂</div>
          <p>No projects yet</p>
        </div>
      </div>

      <!-- Recent Blogs -->
      <div class="card-hover">
        <div class="p-6 border-b border-gray-200 dark:border-gray-800">
          <div class="flex items-center justify-between">
            <h3 class="text-xl font-display font-bold">Recent Blogs</h3>
            <router-link to="/admin/blogs" class="text-copper hover:text-copper-light text-sm font-medium">
              View All →
            </router-link>
          </div>
        </div>

        <div v-if="loading" class="p-8">
          <LoadingSpinner />
        </div>

        <div v-else-if="recentBlogs.length > 0" class="divide-y divide-gray-200 dark:divide-gray-800">
          <div
            v-for="blog in recentBlogs"
            :key="blog.id"
            class="p-6 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors"
          >
            <div class="flex items-center justify-between">
              <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                  <h4 class="font-semibold">{{ blog.title }}</h4>
                  <span v-if="blog.is_featured" class="px-2 py-0.5 rounded bg-copper/10 text-copper text-xs font-medium">
                    ⭐ Featured
                  </span>
                </div>
                <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                  <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    {{ blog.views_count || 0 }}
                  </span>
                  <span>{{ formatDate(blog.created_at) }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div v-else class="p-12 text-center text-gray-500">
          <div class="text-5xl mb-4">📝</div>
          <p>No blogs yet</p>
        </div>
      </div>
    </div>
  </div>
</template>
