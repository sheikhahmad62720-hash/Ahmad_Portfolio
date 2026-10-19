<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { useThemeStore } from '@/stores/theme';
import {
  Bars3Icon,
  XMarkIcon,
  SunIcon,
  MoonIcon,
  HomeIcon,
  FolderIcon,
  DocumentTextIcon,
  AcademicCapIcon,
  BriefcaseIcon,
  ChatBubbleLeftRightIcon,
  EnvelopeIcon,
  ChartBarIcon,
  Cog6ToothIcon,
} from '@heroicons/vue/24/outline';

const router = useRouter();
const authStore = useAuthStore();
const themeStore = useThemeStore();
const sidebarOpen = ref(false);

const menuItems = [
  { name: 'Dashboard', path: '/admin', icon: HomeIcon },
  { name: 'Projects', path: '/admin/projects', icon: FolderIcon },
  { name: 'Blogs', path: '/admin/blogs', icon: DocumentTextIcon },
  { name: 'Skills', path: '/admin/skills', icon: AcademicCapIcon },
  { name: 'Experience', path: '/admin/experience', icon: BriefcaseIcon },
  { name: 'Education', path: '/admin/education', icon: AcademicCapIcon },
  { name: 'Testimonials', path: '/admin/testimonials', icon: ChatBubbleLeftRightIcon },
  { name: 'Messages', path: '/admin/messages', icon: EnvelopeIcon },
  { name: 'Analytics', path: '/admin/analytics', icon: ChartBarIcon },
  { name: 'Settings', path: '/admin/settings', icon: Cog6ToothIcon },
];

async function handleLogout() {
  await authStore.logout();
  router.push({ name: 'login' });
}
</script>

<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Sidebar -->
    <aside 
      :class="[
        'fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transform transition-transform duration-200 ease-in-out',
        sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'
      ]"
    >
      <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200 dark:border-gray-700">
          <span class="text-xl font-bold text-gradient">Admin Panel</span>
          <button @click="sidebarOpen = false" class="md:hidden" aria-label="Close sidebar">
            <XMarkIcon class="w-6 h-6" />
          </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
          <router-link
            v-for="item in menuItems"
            :key="item.path"
            :to="item.path"
            class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
            active-class="bg-copper/10 dark:bg-copper/20 text-copper"
          >
            <component :is="item.icon" class="w-5 h-5" />
            <span>{{ item.name }}</span>
          </router-link>
        </nav>

        <!-- User Info -->
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
              <span class="text-primary-600 dark:text-primary-400 font-semibold">
                {{ authStore.user?.name?.charAt(0) }}
              </span>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium truncate">{{ authStore.user?.name }}</p>
              <p class="text-xs text-gray-500 truncate">{{ authStore.user?.email }}</p>
            </div>
          </div>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="md:pl-64">
      <!-- Top Navigation -->
      <header class="sticky top-0 z-40 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between h-16 px-6">
          <button @click="sidebarOpen = true" class="md:hidden" aria-label="Open sidebar">
            <Bars3Icon class="w-6 h-6" />
          </button>

          <div class="flex-1"></div>

          <div class="flex items-center space-x-4">
            <!-- Theme Toggle -->
            <button 
              @click="themeStore.toggle()"
              class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
              aria-label="Toggle theme"
            >
              <component :is="themeStore.isDark ? SunIcon : MoonIcon" class="w-5 h-5" />
            </button>

            <!-- Logout -->
            <button 
              @click="handleLogout"
              class="px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
            >
              Logout
            </button>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="p-3 sm:p-6">
        <router-view />
      </main>
    </div>

    <!-- Mobile Overlay -->
    <div 
      v-if="sidebarOpen" 
      @click="sidebarOpen = false"
      class="fixed inset-0 z-40 bg-black/50 md:hidden"
    ></div>
  </div>
</template>
