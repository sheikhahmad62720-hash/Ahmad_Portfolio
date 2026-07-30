<script setup lang="ts">
import { ref } from 'vue';
import { useThemeStore } from '@/stores/theme';
import { Bars3Icon, XMarkIcon, SunIcon, MoonIcon } from '@heroicons/vue/24/outline';

const themeStore = useThemeStore();
const mobileMenuOpen = ref(false);

const menuItems = [
  { name: 'Home', path: '/' },
  { name: 'About', path: '/about' },
  { name: 'Portfolio', path: '/portfolio' },
  { name: 'Blog', path: '/blog' },
  { name: 'Contact', path: '/contact' },
];
</script>

<template>
  <div class="min-h-screen bg-white dark:bg-navy">
    <!-- Same Navbar as PublicLayout -->
    <nav class="fixed top-0 w-full z-50 bg-navy/95 dark:bg-navy/95 backdrop-blur-xl border-b border-gray-800/50">
      <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        <div class="flex justify-between items-center h-20">
          <!-- Logo -->
          <router-link to="/" class="flex items-center space-x-3 group">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-copper to-copper-light flex items-center justify-center text-white font-display font-bold text-base group-hover:scale-110 transition-transform shadow-lg">
              MA
            </div>
            <span class="text-sm sm:text-xl font-display font-bold text-white truncate max-w-[120px] sm:max-w-none">Muhammad Ahmad</span>
          </router-link>

          <!-- Desktop Menu -->
          <div class="hidden md:flex items-center space-x-2">
            <router-link
              v-for="item in menuItems"
              :key="item.path"
              :to="item.path"
              exact
              class="px-5 py-2.5 rounded-xl font-medium text-gray-300 transition-all hover:bg-white/10 hover:text-white"
              exact-active-class="bg-copper/20 text-copper font-semibold"
            >
              {{ item.name }}
            </router-link>

            <!-- Theme Toggle -->
            <button
              @click="themeStore.toggle()"
              class="ml-3 p-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white transition-all"
              aria-label="Toggle theme"
            >
              <component :is="themeStore.isDark ? SunIcon : MoonIcon" class="w-5 h-5" />
            </button>
          </div>

          <!-- Mobile Menu Button -->
          <button
            @click="mobileMenuOpen = !mobileMenuOpen"
            class="md:hidden p-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white transition-all"
          >
            <component :is="mobileMenuOpen ? XMarkIcon : Bars3Icon" class="w-6 h-6" />
          </button>
        </div>
      </div>

      <!-- Mobile Menu -->
      <div v-if="mobileMenuOpen" class="md:hidden border-t border-gray-800/50 bg-navy/98 backdrop-blur-xl">
        <div class="px-4 py-4 space-y-2">
          <router-link
            v-for="item in menuItems"
            :key="item.path"
            :to="item.path"
            @click="mobileMenuOpen = false"
            class="block px-5 py-3 rounded-xl text-gray-300 hover:bg-white/10 hover:text-white transition-all font-medium"
          >
            {{ item.name }}
          </router-link>
        </div>
      </div>
    </nav>

    <!-- Page Content -->
    <div class="pt-20 min-h-screen flex items-center justify-center p-4 sm:p-6">
      <router-view />
    </div>
  </div>
</template>
